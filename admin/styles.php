<?php 
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});
$log = new log();
$bdd = new tapdo();
$message = "";

if (!$log->is_logued()) {
	header('Location: login.php?case=disconnect');
}else{
	$cookie = unserialize($_COOKIE['session']);
	$name = $cookie['user'];
	$user = $bdd->get_one_user('ta_login', $name);
	$rights = $user['rights'];
	$styles = $bdd->get_all_styles();
	$html = "";

	foreach ($styles as $style) {
		$html .= "<div class='dropdown pull-left'>";
		$html .= "	<a href='#' data-toggle='dropdown' class='btn btn-default'>" . $style['name'] . "</a>";
		$html .= "	<ul class='dropdown-menu' role='menu' aria-labelledby='dLabel'>";
		$html .= "		<li><a href='styles.php?del=" .  $style['id'] . "&n=" . $style['name'] ."'>Supprimer</a></li>";
		$html .= "	</ul>";
		$html .= "</div>";
	}
	if (isset($_POST['new_style']) && isset($_POST['name']) && $_POST['name'] != '') {
		if ($rights > 2) {
			header('Location: index.php?no=fail');
			exit();
		}
		$bdd->new_style($_POST['name']);
		if (isset($_GET['from'])) {
			header('Location: '.$_GET['from'].".php");
		}else{
			header('Location: index.php?add=style&n='.$_POST['name']);
		}
	}elseif(isset($_GET['del']) && isset($_GET['n'])){
		if ($rights > 2) {
			header('Location: index.php?no=fail');
			exit();
		}else{
			$bdd->suppr("style", "id", $_GET['del']);
			header('Location: index.php?del=style&n='.$_GET['n']);
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin - Styles Musicaux</title>
	<meta charset="utf-8">
	<script src="../components/ckeditor/ckeditor.js"></script>
  	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
	<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
</head>
<body>
	<?php include('menu.php'); ?>
	<div id="wrapper">
		<?php echo $html; ?>
		<form action="styles.php" method="post">
			<div id="float_form" class="input-group input-group-lg">
			<div class='page-header'>
				<h2>Style</h2>
			</div>
			<input type="text" class="form-control" name="name" placeholder="Nom du style">
			<hr>
			<button class="btn btn-lg btn-success valid" type="submit" name="new_style">Add it</button>
		</form>
	</div>

</body>

</html>
<?php } ?>