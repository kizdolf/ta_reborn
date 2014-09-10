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
$message = "";
$rights = rights($bdd);
if (isset($_POST['submit_text']) ) {
	if ($rights > 2) {
		header('Location: index.php?no=fail');
		exit();
	}
	$bdd->update_one_text($_POST, $user['ta_login']);
	$message .= "<div class='alert alert-success'>Texte bien modifié! </div>";
}
if ($rights > 2) {
	$message .= "<div class='alert alert-danger'>Vous n'avez pas les droits pour modifier ces textes.</div>";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit</title>
	<meta charset="utf-8">
	<script src="../components/ckeditor/ckeditor.js"></script>
  	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
	<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
</head>
<body>
	<?php include('menu.php'); ?>
	<div id="wrapper">
	<div id="message"><?php if(isset($message)){echo "<h4>$message</h4>";} ?></div>
	<h1>Modifications des textes:</h1>
	<?php if($rights <= 2){ ?>
	<div class="panel-group" id="accordion">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						"A Propos"
					</a>
				</h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in">
				<div class="panel-body">
					<form method="post" action="edit_texts.php">
						<textarea id="ck_a" name="about" rows="5" cols="30" class="form-control"></textarea>
							<script>CKEDITOR.replace( 'ck_a' );</script>
							<input type="submit" name="submit_text" class="btn btn-success" value="Valider">
					</form>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
						"L'équipe"
					</a>
				</h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse">
				<div class="panel-body">
					<form method="post" action="edit_texts.php">
						<textarea id="ck_b" name="team" rows="5" cols="30" class="form-control"></textarea>
							<script>CKEDITOR.replace( 'ck_b' );</script>
							<input type="submit" name="submit_text" class="btn btn-success" value="Valider">
					</form>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" >
						"Contact"
					</a>
				</h4>
			</div>
			<div id="collapseThree" class="panel-collapse collapse">
				<div class="panel-body">
					<form method="post" action="edit_texts.php">
						<textarea id="ck_c" name="contact" rows="5" cols="30" class="form-control"></textarea>
							<script>CKEDITOR.replace( 'ck_c' );</script>
							<input type="submit" name="submit_text" class="btn btn-success" value="Valider">
					</form>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
						"A Propos version courte"
					</a>
				</h4>
			</div>
			<div id="collapseFour" class="panel-collapse collapse">
				<div class="panel-body">
					<form method="post" action="edit_texts.php">
						<textarea id="ck_d" name="short_about" rows="5" cols="30" class="form-control"></textarea>
							<script>CKEDITOR.replace( 'ck_d' );</script>
							<input type="submit" name="submit_text" class="btn btn-success" value="Valider">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<?php } } ?>