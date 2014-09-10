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
	$profil = $bdd->get_one_user('ta_login', $name);
	$users = $bdd->get_all_users();
	if ($profil['rights'] != 0) {
		$message .= "<div class='alert alert-warning'>Vous ne disposez pas des droits pour ajouter ou modifier les admins.</div>";
	}
	if (isset($_POST['new_profil'])) {
		$password = hash('whirlpool', $_POST['password']);
		$bdd->new_user(array($_POST["ta_login"], $password, $_POST["mail"], $_POST["rights"]));
	}
?><!DOCTYPE html>
<html>
<head>
	<title>Nouvel admin</title>
	<meta charset="utf-8">
	<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
  	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
	<script src="../components/jquery.js"></script>
</head>
<body>
	<?php include('menu.php'); ?>
	<div id="wrapper">
	<div id="message"><?php if(isset($message)){echo "<h4>$message</h4>";} ?></div>
	<div class="container" id="block_users">
	<h2>Admins présents:</h2>
<?php 

foreach ($users as $user) {
 	echo "<div id='profil' class='container container-fluid'>";
 	echo "Login: ".$user['ta_login']."<br>";
 	if ($user['ta_login'] == $profil['ta_login']) {
 		echo "(C'est vous...)<br>";
 	}
 	echo "mail : ".$user['mail']."<br>";
 	echo "nombre de visites sur l'admin:".$user['nb_visits']."<br>";
 	echo "Dernière connexion: ".$user['date_last_visit']."<br>";
	echo "Type de compte: ";
	switch ($user['rights']) {
		case '0':
			echo "Full Admin";
			break;
		case '1':
			echo "Admin Classique";
			break;
		case '2':
			echo "Modificateur";
			break;
		case '3':
			echo "Voyeur(useless)";
			break;
		default:
			echo "Bug";
			break;
	}
	if($profil['rights'] == 0){
		echo "<br><a href='profil.php?id=".$user['id']."'>Modifier le profil</a>";
	}
	echo "</div><hr>";
 }
 echo "</div>";
	if($profil['rights'] == 0){
 ?>
 <div class="container" id="block_new_admin">
 <h2>Nouvel Admin: </h2>
<form method="post" action="./index.php">
	<div id="float_form" class="input-group input-group-lg">
		<input type="text" class="form-control" name="ta_login" placeholder="Login">
		<input type="text" class="form-control" name="mail" placeholder="mail">
		<input type="password" class="form-control" name="password" placeholder="password">
		<select class="form-control" name='rights'>
		  <option value="0">Full admin</option>
		  <option value="1">Admin classique (publication/edition/supression mais pas de création ou supression de compte)</option>
		  <option value="2">Modificateur (ne peux que modifier les posts)</option>
		  <option value="3">Voyeur. (ne peux rien faire.)</option>
		</select>
	</div>
	<input type="password" class="form-control" name="current_password" placeholder="password actuel">
	<button class="btn btn-lg btn-success " type="submit" name="new_profil">Créer le profil</button>
</form>
</div>
</div>
</body>
</html>
<?php }} ?>
