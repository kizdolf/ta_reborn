<?php
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});

function get_from_dir($handle, $path){
	$files = array();
	while ($entry = readdir($handle)){
		if($entry!= "." && $entry != "..")
			$files[] = $path ."/" . $entry;
	}
	return $files;
}
$log = new log();

if (!$log->is_logued()) {
	header('Location: login.php?case=disconnect');
}else{

	$bdd = new tapdo();

	if (isset($_POST['id'])) {
		$artiste = $bdd->get_one_artiste("id", $_POST['id']);
		$path = $artiste['path_pics'];
		print_r(json_encode(get_from_dir(opendir($path), $path)));
	}elseif (isset($_POST['del'])) {
		echo unlink($_POST['src']);
	}elseif(isset($_POST['galerie'])){
		$path = $_POST['galerie'];
		print_r(json_encode(get_from_dir(opendir($path), $path)));
	}
}
?>