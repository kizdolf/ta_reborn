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
if (isset($_GET['id'])) {
	$profil = $bdd->get_one_user('id', $_GET['id']);
}elseif (!isset($_POST['up_profil'])) {
	$profil = $user;
}elseif(isset($_POST['up_profil'])) {
	if ($user['rights'] > 1 && $_POST['id'] != $user['id']) {
		$message .= "<div class='alert alert-danger'>Faut pas tricher avec le système!!!</div>";
		$profil = $user;
	}elseif (hash('whirlpool', $_POST['current_password']) == $cookie['hash']) {
		$vars = array();
		$profil = $bdd->get_one_user('id', $_POST['id']);
		$vars = $_POST;
		if ($_POST['new_password'] != '' && $_POST['new_password_verif'] != '') {
			if ($_POST['new_password_verif'] == $_POST['new_password']) {
				$vars['ta_password'] = hash('whirlpool', $_POST['new_password']);
			}
			else{
				$message .= "Mots de passe non identiques.";
			}
		}
		foreach ($profil as $key => $value) {
			if (isset($vars[$key]) && $vars[$key] != '') {
				$profil[$key] = $vars[$key];
			}
		}
		$profil['date_update'] = date("Y-m-d H:i:s");
		$bdd->update_one_user($profil);
		if(isset($vars['ta_password']) || isset($vars['ta_login']))
			$message .= "<div class='alert alert-info'>Veuillez vous reconnecter pour valider les changements.<br><a class='btn btn-success btn-lg' href='login.php?case=change'>Se reconnecter</a>(inutile si vous ne modifiez pas votre profil...)</div>";
		else
			$message .= "<div class='alert alert-info'>Changements effectués!</div>";
	}
	else
	{
		$cookie = unserialize($_COOKIE['session']);
		$name = $cookie['user'];
		$profil = $bdd->get_one_user('ta_login', $name);
		$message .= "<div class='alert alert-danger'>Mot de passe incorrect!</div>";
	}
}
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
<div id="wrapper">
	<div id="message"><?php if(isset($message)){echo "<h4>$message</h4>";} ?></div>
	<div class="jumbotron">
		<h3>Apercu du profil de: "<?php echo $profil['ta_login']; ?>"</h3>
		<p>Compte créer le: <?php echo $profil['date_creation']; ?></p>
		<p>Nombre de visites sur l'admin toulouse Acoustics: <?php echo $profil['nb_visits'] ?></p>
		<p>Mail de contact : <?php echo $profil['mail'] ?></p>
	</div>
	<div>
	<form method="post" action="./profil.php">
		<div id="float_form" class="input-group input-group-lg">
			<input type="hidden" value="<?php echo $profil['id']; ?>" name="id">
			<input type="text" class="form-control" name="ta_login" placeholder="Login">
			<input type="text" class="form-control" name="mail" placeholder="mail">
			<input type="password" class="form-control" name="new_password" placeholder="password">
			<input type="password" class="form-control" name="new_password_verif" placeholder="password_verif">
		<?php  if ($user['rights'] <= 1) {?>
			<select class="form-control" name='rights'>
			<?php if($user['rights'] == 0){ ?>
			  <option value="0">Full admin</option>
			<?php } ?>
			  <option value="1">Admin classique (publication/edition/supression mais pas de création ou supression de compte)</option>
			  <option value="2">Modificateur (ne peux que modifier les posts)</option>
			  <option value="3">Voyeur. (ne peux rien faire.)</option>
			</select>
		<?php 	}?>
		</div>
		<input type="password" class="form-control" name="current_password" placeholder="password actuel">
		<button class="btn btn-lg btn-success " type="submit" name="up_profil">Valider les modifications</button>
	</form>	
	</div>
<hr>
	<?php  if ($profil['rights'] <= 1) {
			?>
			<form method="post" action="./profil.php">
				<input type="hidden" value="<?php echo $profil['id']; ?>">
				<input type='submit' class="btn btn-xs btn-danger" name="suppr_profil" value="supprimer le profil (irréversible)">
				</form>
			</form>
			<?php 
		}?>
</div>
</body>
</html>
<?php } ?>