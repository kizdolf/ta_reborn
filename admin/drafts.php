<?php
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});
require_once('admin_functions.php');
$log = new log();
if (!$log->is_logued()) {
	header('Location: login.php?case=disconnect');
}else{
	$bdd = new tapdo();
	$rights = rights($bdd);
	if (isset($_POST['get']) && $_POST['get'] ="drafts") {
		$drafts = $bdd->get_all('draft');
		print_r(json_encode($drafts));
	}elseif(isset($_POST['del'])){
		if ($rights > 2)
			header('Location: index.php?no=no');
		else
			$bdd->suppr('draft', 'id', $_POST['del']);
	}elseif (!isset($_POST['name']) || !isset($_POST['txt'])) {
		header('Location: index.php?wrong=params drafts ajax');
	}else{
		$cookie = unserialize($_COOKIE['session']);
		$name = $cookie['user'];
		$kwarg = array($name, $_POST['name'], $_POST['txt']);
		$bdd->new_draft($kwarg);
	}
?>

<?php } ?>