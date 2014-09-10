<?php
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});
$log = new log();

if (!$log->is_logued()) {
	header('Location: login.php?case=disconnect');
}else{
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Profil</title>
	<meta charset="utf-8">
	<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
  	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
	<script src="../components/jquery.js"></script>
</head>
<body>
	<?php include('menu.php'); ?>

	<iframe src="../components/piwik/piwik/index.php" id="stats_frame"></iframe>
</body>
</html>
<?php } ?>
