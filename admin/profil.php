<?php
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});
require_once ("admin_functions.php");
$log = new log();
$bdd = new tapdo();
$message = "";
if (!$log->is_logued()) {
	header('Location: login.php?case=disconnect');
}else{
	$cookie = unserialize($_COOKIE['admin_session_toulouse_acoustics']);
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
		$cookie = unserialize($_COOKIE['admin_session_toulouse_acoustics']);
		$name = $cookie['user'];
		$profil = $bdd->get_one_user('ta_login', $name);
		$message .= "<div class='alert alert-danger'>Mot de passe incorrect!</div>";
	}
}
	html_header("Profil");

 ?>
<body>
	<?php 
$alls = $bdd->get_all_names_id();
$rights = $user['rights'];

 ?>
<div id="conteneur-menu2">
	<ul>
		<li><a href="index.php">Admin</a></li>
		<li><a href="profil.php">Editer le profil</a></li>
		<li><a href="add_profil.php">gestion admins</a></li>
		<li><a href="../components/piwik/piwik/index.php">Stats site</a></li>
		<li><a href="new_quartier.php">Nouveau quartier</a></li>
		<li><a href="new_post.php">Nouveau post</a></li>
		<li><a href="index.php?session=leave">Se déconnecter</a></li>
		<li><a href="../index.php">Voir le site</a></li>
	</ul>
</div>
<div id="sidebar">
		<h3>Raccourcis</h3>
		<ul>
			<li><a href="https://www.facebook.com/toulouseacoustics">Facebook Page</a></li>
			<li><a href="https://www.facebook.com/groups/495231230518643/">Facebook Groupe</a></li>
			<li><a href="https://trello.com/#">Trello</a></li>
			<li><a href="https://soundcloud.com/toulouse-acoustics">Souncloud</a></li>
			<li><a href="edit_texts.php">Editer les textes</a></li>
			<li><a href="styles.php">Ajouter un style</a></li>
			<li><a href="partners.php">Partenaires</a></li>
			<li><a href="pics.php?show=team">Galerie équipe</a></li>
			<li><a href="pics.php?show=off">Galerie 'off'</a></li>
		</ul>
		<hr>
		<h3>Edition rapide</h3>
		<div class="dropdown">
			<a  class='btn btn-default btn-md' data-toggle="dropdown" href="#">artistes</a>
  			<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
  			<?php foreach ($alls['artistes'] as $artiste) {
  				echo "<li><a  href='edit.php?type=artiste&id=".$artiste['id']."'>".$artiste['name']."</a></li>";
  			} ?>
  			</ul>
		</div>
		<div class="dropdown">
			<a  class='btn btn-default btn-md'  data-toggle="dropdown" href="#">quartiers</a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
			<?php foreach ($alls['quartiers'] as $quartier) {
  				echo "<li><a href='edit.php?type=quartier&id=".$quartier['id']."'>".$quartier['name']."</a></li>";
  			} ?>
  			</ul>
		</div>
		<div class="dropdown">
			<a   class='btn btn-default btn-md' data-toggle="dropdown" href="#">videos</a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
			<?php foreach ($alls['videos'] as $video) {
  				echo "<li><a  href='edit.php?type=video&id=".$video['id']."'>".$video['name']."</a></li>";
  			} ?>
  			</ul>
		</div>
		<hr>
		<div>
			<button class="btn btn-default draft">Notes publiques</button>
		</div>
		<hr>
		<h3>Dev</h3>
		<div class="dropdown">
			<a  class='btn btn-default btn-md'  data-toggle="dropdown" href="#">Dev links</a>
			<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				<li><a href="http://localhost/phpmyadmin/"> Local PhpMyadmin</a></li>
  				<li><a href="https://vmheb62064.ikoula.com:8443/domains/databases/phpMyAdmin/import.php">Dk phpMyAdmin</a></li>
  				<li><a href="../ta.sql">Dump SQL install</a></li>
  				<?php if($rights == 0){ ?><li><a href="../t_q.php" class="btn btn-danger">(danger) Run get_.sh.</a></li><?php } ?>
  			</ul>
		</div>
		<a href="docTA-Admin.odt">Documentation admin.</a>
</div>
<div id="drafts" style="display:none;">
	<button class="btn btn-default btn-xs add_draft">Ajouter une note</button>
	<button class="btn btn-warning btn-xs hide_drafts">Fermer (Esc)</button>
	<div id="form_draft">
		<form method="post" action="./drafts.php" id="send_draft" style="width:50%; min-width: 350px;">
			<input type="text" name="draft_name" placeholder="nom">
			<textarea id="ck_b" name="draft" rows="5" cols="30"></textarea>
			<script>CKEDITOR.replace( 'ck_b' );</script>
			<div id="sub_form">
				<button class="btn btn-success valid" type="submit" name="new_partner">Add it</button>
			</div>
		</form>
	</div>
	<div id="drafts_list">
	</div>
</div>
<script src="../components/jquery.js"></script>
<script src="../css/bootstrap/js/bootstrap.min.js"></script>
 <script src="http://malsup.github.com/jquery.form.js"></script> 
<script type="text/javascript">
	$( document ).ready(function(){
		$("#drafts").hide(0);

		

		$get_drafts = function(){
			$.post('drafts.php', {get: "drafts"}).done(function(data){
			$drafts  = jQuery.parseJSON(data);
			console.log($drafts);
			}).then(function(){
				for (var i = $drafts.length - 1; i >= 0; i--) {
					$("#drafts_list").append("<div class='one_draft'> <button class='btn btn-warning btn-xs suppr_draft' onclick='$del_draft(" + $drafts[i].id + ")' >Supprimer</button> <h2>" + $drafts[i].name + "</h2><hr><h4> By : " + $drafts[i].user + "</h4><span class='date'>Le " + $drafts[i].date_creation + "</span><hr><div class='text_draft'> "+ $drafts[i].txt + "</div></div>");
				}
			});
		}

		$(".draft").click(function(){
			$get_drafts();
			$("#form_draft").hide(0);
			$("#drafts").show("slow");
			$('html, body').animate({
				scrollTop: $("#drafts").offset().top
			}, 500);
		});

		$(document).keyup(function(e) {
			if (e.keyCode == 27) {
				$('#drafts').hide("slow");
			}
		});

		$('.add_draft').click(function(){
			if ($("#form_draft").is(":visible")) {
				$('.add_draft').html("Nouvelle note");
				$("#form_draft").hide(0);
			}else{
				$('.add_draft').html("cacher");
				$("#form_draft").show("slow");
			}
		});
		$('#send_draft').submit(function( event ) { 
			event.preventDefault();
			$name = $('input').val();
			$txt = $('textarea').val();
			$txt = $('textarea').val();
			$txt = $('textarea').val();
			$.post('drafts.php', {name: $name, txt: $txt}).done(function(data){
				$("#drafts_list").html("");
				$get_drafts();
				$('.add_draft').html("Nouvelle note");
				$("#form_draft").hide(0);
			});
		}); 

		$('.hide_drafts').click(function(){
			$('#drafts').hide("slow");
		});

		$del_draft = function(id){
			console.log("draft id " + id + " need to be removed.");
			$.post('drafts.php', {del: id}).done(function(){
				$("#drafts_list").html("");
				$get_drafts();
				$('.add_draft').html("Nouvelle note");
				$("#form_draft").hide(0);
			});
		}

		$('.run_script').click(function(){
			$.post('ajax.php', {run: "script"}).done(function(data){
				console.log(data);
			});
		});
	});
</script>
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