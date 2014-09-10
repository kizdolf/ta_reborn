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
	$cookie = unserialize($_COOKIE['session']);
	$name = $cookie['user'];
	$profil = $bdd->get_one_user('ta_login', $name);
	$rights = $profil['rights'];
	$partners = $bdd->get_all_partners();
	if ($rights > 2) {
		$message = handler_message(array('no'));
	}
if (isset($_POST['new_partner'])) {
	if (rights($bdd) >= 2) {
		header('Location: index.php?no=fail');
		exit();
	}
	handler_new_partner($_POST, $_FILES, $bdd);
	header('Location: index.php?add=partenaire&n='.$_POST['partner_name']);
}elseif (isset($_GET['suppr'])) {
	if ($rights > 1) {
		header('Location: index.php?no=fail');
		exit();
	}else{
		$bdd->suppr("partner", "id", $_GET['suppr']);
		header('Location: index.php?del=partenaire&n='.$_GET['name']);
	}
}

 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>Partenaires - Admin</title>
 	<meta charset="utf-8">
	<script src="../css/bootstrap/js/bootstrap.min.js"></script>
	<script src="../components/ckeditor/ckeditor.js"></script>
  	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
	<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
	<script src="../components/jquery.js"></script>
 </head>
 <body>
 <?php include('menu.php'); ?>
	<div id="wrapper">
	<?php if (isset($message)) { echo $message;	} ?>
	<div id="block_edit">
		<h4>Partenaires : </h4>
		<ul>
		<?php foreach ($partners as $p) { ?>
				<li>
				<h5><?php echo $p['name'] ?></h5>
					<a  class='btn btn-danger btn-xs' href='partners.php?suppr=<?php echo $p['id'] ?>&name=<?php echo $p['name'] ?>'>Supprimer</a>
					<btn  class='btn btn-default btn-xs edit_p' >Editer</btn>
					<div class="edit_partner">
						
						<form method="post" action="./partners.php" enctype="multipart/form-data" class="form_hide">
							<input type="text" class="form-control" name="partner_name" placeholder="nom" value="<?php echo $p['name']; ?>">
							<div id="float_form" class="input-group input-group-lg">
								<span class="input-group-addon">@</span>
								<input type="text" class="inputc form-control" name="partner_url" placeholder="url du site" value="<?php echo $p['url']; ?>">
							</div>			<textarea id="ck_<?php echo $p['name']; ?>" name="partner_desc" rows="5" cols="30" class="form-control"><?php echo $p['desc']; ?></textarea>
							<script>CKEDITOR.replace( 'ck_<?php echo $p['name']; ?>' );</script>
							<div id="upload" class="jumbotron">
								<h3>Logo partenaire : </h3>
								<input type="file" name="partner_logo" id="partner_logo"> <br>
							</div>
							<div id="sub_form">
								<button class="btn btn-lg btn-success valid" type="submit" name="new_partner">Valider</button>
							</div>	
						</form>
					</div>
				</li>
		<?php }?>
		</ul>
	</div>
	<div id="block_new_partner"><?php if($rights < 2){ ?>
		<hr style='clear : both;'>
		<h2>Nouveau Partenaire</h2>
		<form method="post" action="./partners.php" enctype="multipart/form-data">
			<input type="text" class="form-control" name="partner_name" placeholder="nom">
			<div id="float_form" class="input-group input-group-lg">
				<span class="input-group-addon">@</span>
				<input type="text" class="inputc form-control" name="partner_url" placeholder="url du site">
			</div>
			<textarea id="ck_b" name="partner_desc" rows="5" cols="30" class="form-control"></textarea>
			<script>CKEDITOR.replace( 'ck_b' );</script>
			<div id="upload" class="jumbotron">
				<h3>Logo partenaire : </h3>
				<input type="file" name="partner_logo" id="partner_logo"> <br>
			</div>
			<div id="sub_form">
				<button class="btn btn-lg btn-success valid" type="submit" name="new_partner">Add it</button>
			</div>	
		</form>
		<?php } ?>
	</div>
	</div>
	<script type="text/javascript">
		$(document).on('load', function(){
			console.log("charged");
			$('.edit_partner').hide();
		});
		$('.edit_partner').hide();

		$(document).on('click', ".edit_p", function(){
			if ($(this).html() != "cacher") {
				$(this).next('.edit_partner').show('slow');
				$(this).html("cacher");
			}else{
				$(this).next('.edit_partner').hide('fast');
				$(this).html("Editer");
			}
		});
	</script>
 </body>
 </html>
 <?php } ?>