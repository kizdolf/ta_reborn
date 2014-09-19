<?php 
/*
	INIT
*/
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});
require_once(__DIR__."/admin_functions.php");
$bdd = new tapdo();
$log = new log();
$message = "";
$post = $bdd->get_all_post();
$post = array_reverse($post);


/*
	LOGIN:
*/
if (isset($_POST['login_sub']) && isset($_POST['login']) && isset($_POST['password'])){
	if (!$log->is_user($_POST['login'], $_POST['password'])) {
	 	header('Location: login.php?case=logs');
	}else{
		$message.= "<h2>Welcome ".$_POST['login']."</h2><br>";
		$trust = (isset($_POST['trust']))? true : false;
		$id_admin = $log->set_session($_POST['login'], $_POST['password'], $trust);
	}
}
elseif (!$log->is_logued()) {
	header('Location: login.php?case=disconnect');
}
if (isset($_GET['session']) && $_GET['session'] == "leave") {
	$log->unset_session();
	header('Location: login.php?case=leave');
}


$message .= handler_new_entry($bdd, $_POST, $_FILES).tables::get_to_message($_GET);

/*
	USER
*/

$rights = (isset($_COOKIE['admin_session_toulouse_acoustics'])) ? rights($bdd) : $bdd->get_rights_user(array($id_admin));

	html_header("Home");

?>
<body>
	<?php include('menu.php'); ?>	
	<div id="wrapper">
	<div id="messages"><?php echo $message; ?></div>
		<?php 
			foreach ($post as $p) {
				echo "<div id='post' >";

				if ($p['video']['weekly'] == 1)
					echo "<span class='weekly'><img src='img/weekly.png'></span>";
				if ($p['video']['category'] == 1)
					echo "<span class='visit'><img src='img/visiteur.jpg'><p>Visiteur</p></span>";
				else
					echo "<span class='visit'><img src='img/local.jpg'><p>Local</p></span>";

				echo "<div class='page-header'>";
   				echo "<h3>".$p['artiste']['name']."</h3>";
   				echo "<div class='art-text min-text'><button style='display:none;' class='btn btn-info btn-xs hide-txt'>réduire</button><h5>Texte Artiste:</h5>".$p['artiste']['text']."</div>";
				echo "<br>".$p['video']['name']."<br>";
				echo "<small>".$p['video']['date']."</small></div>";
   				echo "<div class='vid-text min-text'><button style='display:none;' class='btn btn-info btn-xs hide-txt'>réduire</button><h5>Texte Vidéo:</h5>".$p['video']['text']."</div>";
				echo "<h4>Quartier : ".$p['quartier']['name']."</h4>";
				if ($rights < 3) {
					echo "<br><a id='edit_btn' href='edit.php?type=video&id=".$p['video']['id']."' class='btn btn-info'><span class='glyphicon glyphicon-th'></span>Modifier la vidéo</a>";
					echo "<a id='edit_btn' href='edit.php?type=artiste&id=".$p['artiste']['id']."'class='btn btn-info'><span class='glyphicon glyphicon-th'></span>Editer l'artiste</a>";
					echo "<a id='edit_btn' href='edit.php?type=quartier&id=".$p['quartier']['id']."'class='btn btn-info'><span class='glyphicon glyphicon-th'></span>Editer le quartier</a>";
				}
				echo "<button class='btn btn-default btn-xs get_vid'>Récupérer la vidéo</button><div class='frame'>".$p['video']['url']."</div>";
				echo "</div><hr>";
			}
	 	?>
	</div>
	<script src="../components/ckeditor/ckeditor.js"></script>
	<script src="../components/jquery.js"></script>
	<script src="../components/purl.js"></script>
	<script src="adminjs.js"></script>
</body>
</html>
