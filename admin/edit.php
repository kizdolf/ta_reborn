<?php 
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});
require_once('admin_functions.php');
$log = new log();
if (!$log->is_logued()) {
	header('Location: login.php?case=disconnect');
}else{
if (!isset($_GET['type']) || !isset($_GET['id'])) {
	header('Location: index.php?wrong=edition');
}
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
ini_set('max_input_time', 3000);
ini_set('max_execution_time', 3000);
$bdd = new tapdo();
$type = $_GET['type'];
$id = $_GET['id'];
$cookie = unserialize($_COOKIE['admin_session_toulouse_acoustics']);
$name = $cookie['user'];
$user = $bdd->get_one_user('ta_login', $name);
$rights = $user['rights'];
if ($rights > 2) {
	header('Location: index.php?no=rights');
}
if ($_GET['type'] == "valid_edit") {
	$get = "get_one_".$_GET['table'];
	$entry = $bdd->$get('id', $id);
	if(isset($entry['path_pics'])){
		if (!is_dir($entry['path_pics']))
			mkdir($entry['path_pics']);
		$test = pics_handler($_FILES, $entry['path_pics'], $entry['name']);
		if (isset($_FILES['vignette']) && $_FILES['vignette']['name'] != '') {
			$ext = explode(".", $_FILES['vignette']["name"]);
			$ext = strtolower($ext[1]);
			$entry['path_vignette'] = "img/uniques/artiste/".$entry['name'].".".$ext;
		}
	}
	foreach ($_POST as $key => $value) {
		if (isset($entry[$key])) {
			$entry[$key] = $value;
		}
	}
	if (isset($entry['weekly']) && !isset($_POST['weekly'])) {
		$entry['weekly'] = 0;
	}
	if (isset($entry['category']) && !isset($_POST['category'])) {
		$entry['category'] = 0;
	}
	$update = "update_one";
	$entry['date_update'] = date("Y-m-d H:i:s");
	$bdd->$update($_GET['table'], 'id', $id, $entry);
	header('Location: index.php?done=edit');
}else{
	html_header("Edition ".$type);

?>
<body>
 	<div id="img_div"></div>
	<?php include('menu.php'); ?>
	<div id="wrapper">
<?php

	$get = "get_one_".$type;
	html_edit($bdd->$get('id', $id), $id, $type);

 ?>
</div>
	<script src="../components/jquery.js"></script>
	<script src="../components/purl.js"></script>
	<script src="adminjs.js" type="text/javascript"></script>
</body>
</html>
<?php  } } ?>
