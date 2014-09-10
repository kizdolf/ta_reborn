<?php 
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});
require_once 'admin_functions.php';
$log = new log();
$bdd = new tapdo();

if (!$log->is_logued()) {
	header('Location: login.php?case=disconnect');
}else{ 
	if (rights($bdd) >= 2) {
		header('Location: index.php?no=fail');
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ajouter un quartier</title>
	<script src="../components/ckeditor/ckeditor.js"></script>
  	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
	<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
</head>
<body>
	<?php include('menu.php'); ?>
	<div id="wrapper">
	<h2>Nouveau quartier</h2>
	<form method="post" action="./index.php"  enctype="multipart/form-data">
		<div id="float_form" class="input-group input-group-lg">
			<input type="text" class="form-control" name="quartier_name" placeholder='Nom'>
		</div>
		<hr>
		<div>
			<p>Texte : </p>
			<textarea name="quartier_desc" rows="5" cols="30" class="input-large" id="ck"></textarea>
			<script> CKEDITOR.replace( 'ck' ); </script>
		</div>
		<hr>
		<div id="float_form" class="input-group input-group-lg">
			<span class="input-group-addon">@</span>
			<input type="text" class="form-control" name="quartier_url" placeholder='Site Web '>
		</div>
		<div id="upload" class="jumbotron">
			<h3>Photos</h3>
			<input type="file" multiple="multiple" name="pics[]" id="pics"> <br>
			<h3>Vignette Quartier : 220px / 220px</h3>
			<input type="file" name="vignette" id="vignette"> <br>
		</div>
		<div id="sub_form">
			<button class="btn btn-lg btn-success valid" type="submit" name="new_quartier">Add it</button>
		</div>	
	</form>
	</div>
</body>
</html>
<?php } ?>
