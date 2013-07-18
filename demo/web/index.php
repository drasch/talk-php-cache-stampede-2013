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

$app = new Silex\Application(); 

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/../views',
));

$app->get('/v1', function() use($app) { 
  return $app['twig']->render('data.twig', array(
    'data' => query("select code, count(*) as ct from wikimedia_hits group by 1 order by 2 desc limit 10")
  ));
}); 

$app->run(); 


