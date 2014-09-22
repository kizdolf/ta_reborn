<?php 
if(!isset($bdd)){$bdd = new tapdo();}
$alls = $bdd->get_all_names_id();
$rights = rights($bdd);

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
  				<li><button class="get_dump">Dump SQL install</button></li>
  				<?php if($rights == 0){ ?><li><button class="btn btn-danger run_s">(danger) Run get_.sh.</button></li><?php } ?>
  				<li><a href="test_admin_dev.php">tests.</a></li>
  			</ul>
		</div>
		<div id="confirm" style="display:none;" class="alert alert-danger">
			<h4>Attention! lancer ce script vas supprimer puis générer la totalité de la base de données. Voulez vous continuez?</h4>
			<button class="btn btn-success btn-xs cancel">Annuler</button>
			<a class="btn btn-warning btn-xs" href="../t_q.php">Shut UP and do it!</a>
		</div>
		<div id="links_ready" style="display:none;" class="alert alert-info"></div>
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
				<button class="btn btn-success valid" type="submit" name="new_draft">Add it</button>
			</div>
		</form>
	</div>
	<div id="drafts_list">
	</div>
</div>
<script src="../components/jquery.js"></script>
<script src="../css/bootstrap/js/bootstrap.min.js"></script>
 <script src="../components/jquery.form.js"></script> 
<script type="text/javascript">
	
	$( document ).ready(function(){

		$('.get_dump').click(function(){
			$('#links_ready').show();
			$('#links_ready').html('Génération en cours... <img src="img/miniloader.gif"> ');
			$.post('ajax.php', {dump: 'all'})
			.done(function(data){
				console.log(data);
				$('#links_ready').html('<a class="btn btn-success" href="dump_data.json">Télécharger le dump en JSON</a>');
			});
		});

		$('.run_s').on('click', function(){
			console.log("qui c'est qui vas tout péter?");
			$('#confirm').show(0);
		});

		$('.cancel').on('click', function(){
			$('#confirm').hide(0);
		});

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

		$('#send_draft').on('submit', function( event ) { 
			$name = $('input').val();
			$txt = $('textarea').val();
			$.post('drafts.php', {name: $name, txt: $txt}).done(function(data){
				$("#drafts_list").html("");
				$get_drafts();
				$('.add_draft').html("Nouvelle note");
				$("#form_draft").hide(0);
			});
			event.preventDefault();
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