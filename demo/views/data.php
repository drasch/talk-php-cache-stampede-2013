<!DOCTYPE html>
<html lang="en">
  <head><title>PHP/MySQL Cache Storm App</title>
  <!-- http://www.bootstrapcdn.com/?v=01282013154951#tab_quickstart -->
  <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
  <style>
    /* Custom container */
    .container-narrow {
      margin: 0 auto;
      max-width: 500px;
    }
    .hr-blue {
      height: 4px;
      background-color: blue;
    }
  </style>
</head>
<body>
  <div class="container-narrow">
    <div class="row-fluid">
      <h1>Top 10 Pages</h1>
      <hr class="hr-blue">
      <table class="table table-bordered table-hover">
        <tr><th>Code</th><th>Hits</th></tr>
		  <? foreach($data as $r): ?>
		  <tr><td><?= $r['code'] ?></td><td><?= number_format($r["ct"]) ?></td></tr>
		<? endforeach; ?>
      </table>
    </div>
  </div>
  <script src="//code.jquery.com/jquery-latest.js"></script>
  <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script> 
</body>
</html>
