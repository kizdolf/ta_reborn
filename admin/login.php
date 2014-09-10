<?php 
$html = "";
if (isset($_GET['case'])) {
	switch ($_GET['case']) {
		case 'logs':
			$html = "<div class='alert alert-danger'>Mauvais login ou password.";
			break;
		case 'disconnect':
			$html = "<div class='alert alert-info'>Vous n'êtes pas connecté";
			break;
		case 'leave':
			$html = "<div class='alert alert-success'>Vous êtes bien déconnecté";
			break;
		case 'change':
			$html = '<div class="alert alert-warning">Veuillez vous reconnecter avec vos nouveaux identifiants.';
			break;
		default:
			$html = "";
			break;
		$html .= "</div>";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Login admin TA</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
  	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
	<meta charset="utf-8">
</head>
<body id="body_login">
<div id="form_login" class="container container-fluid">
<div>
	<h3><?php echo $html; ?></h3>
</div>
<?php 
// echo hash('whirlpool', '123');
 ?>
<form action="index.php" method="post">
	<p>Login : </p>
	<input	type="text" name="login">
	<p>Pasword : </p>
	<input type="password" name="password">
	<br><input type="checkbox" name="trust">Se souvenir de moi pendant une semaine (sur les postes de confiances...)
	<br><input type="submit" name="login_sub" value="Se connecter">
</form>
<a href="../index.php">Retourner sur le site.</a>
</div>
</body>
</html>