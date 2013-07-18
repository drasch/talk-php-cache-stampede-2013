<?php

require_once __DIR__.'/../vendor/autoload.php'; 

$db =  mysqli_connect("localhost","root","","test");

function query($query) {
  global $db;
  $rows = array();
  $result = mysqli_query($db, $query);
  while ($row = mysqli_fetch_assoc($result)) { $rows[] = $row; }
  return $rows;
};

$cache = new Memcache("localhost");
$cache->connect('localhost', 11211);

$app = new Silex\Application(); 

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/../views',
));

function render($app, $data) {
  return $app['twig']->render('data.twig', array(
    'data' => $data
  ));
}

$app->get('/v1', function () use ($app) {
  $data = query("select code, count(*) as ct from wikimedia_hits group by 1 order by 2 desc limit 10");
  return render($app, $data);
});

$app->get('/v2', function () use ($app) {
  $data = query("select code, count(*) as ct from wikimedia_hits group by 1 order by 2 desc limit 10");
  query("update wikimedia_hits set hits= hits +1 where id = 1");
  return render($app, $data);
});

$app->get('/v3', function () use ($app, $cache) {
  $data = query("select code, count(*) as ct from wikimedia_hits group by 1 order by 2 desc limit 10");
  $current_value = $cache->increment('counter', 1);

  $cache->add("counter", 1);
  if ($cache->increment("counter") % 50 == 0) {
    query("update wikimedia_hits set hits= hits + 50 where id = 1");
  }
  return render($app, $data);
});

$app->get('/v4', function () use ($app, $cache) {
  $data = $cache->get("data");
  
  if (!$data) {
    $data = query("select code, count(*) as ct from wikimedia_hits group by 1 order by 2 desc limit 10");
    $cache->set("data", $data, 0, 10);
  }
  
  query("update wikimedia_hits set hits= hits + 1 where id = 1");
  return render($app, $data);
});


$app->get('/v5', function () use ($app, $cache) {
  $key = "datav5";
  $ttl = 10;
  $result = $cache->get($key);
  if (is_array($result)) list($data, $expire) = $result;

  if (!$result || time() > $expire) {
    if ($cache->add("__lock_$key", 1, 0, 30)) {
      $data = query("select code, count(*) as ct from wikimedia_hits group by 1 order by 2 desc limit 10");
      $cache->set($key, array($data, time()+$ttl), 0, $ttl+60);
      $cache->delete("__lock_$key");
    } else {
      while (true) {
        if ($result) {
          $data = $result[0];
          break;
        }
        sleep(0.2);
        $result = $cache->get($key);
      }
    }
  }
  
  query("update wikimedia_hits set hits = hits + 1 where id = 1");
  return render($app, $data);
});

$app->run(); 


