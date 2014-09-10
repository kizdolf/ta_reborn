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

/*
	NEW ENTRY:
*/
$message .= handler_new_entry($bdd, $_POST, $_FILES);

/*
	HANDLER MESSAGES
*/
$message .= handler_message($_GET);

/*
	USER
*/
if (isset($_COOKIE['session'])) {
	$cookie = unserialize($_COOKIE['session']);
	$name = $cookie['user'];
	$user = $bdd->get_one_user('ta_login', $name);
	$rights = $user['rights'];
}else{
	$rights = $bdd->get_rights_user(array($id_admin));
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>admin</title>
	<meta charset="utf-8">
	<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
  	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
	<script src="../components/ckeditor/ckeditor.js"></script>
	<script src="../components/jquery.js"></script>
</head>
<body>
	<?php include('menu.php'); ?>	
	<div id="wrapper">
	<div id="messages"><?php echo $message; ?></div>
		<?php 
			foreach ($post as $p) {
				echo "<div id='post' >";
				if ($p['video']['weekly'] == 1) {
					echo "<span class='weekly'><img src='img/weekly.png'></span>";
				}
				if ($p['video']['category'] == 1) {
					echo "<span class='visit'><img src='img/visiteur.jpg'><p>Visiteur</p></span>";
				}
				else{
					echo "<span class='visit'><img src='img/local.jpg'><p>Local</p></span>";
				}
				echo "<div class='page-header'>";
   				echo "<h3>".$p['artiste']['name'];
				echo "<br><small>".$p['video']['name']."</small>";
				echo "</h3>".$p['video']['date']."</div>";
				echo "Quartier : ".$p['quartier']['name'];
				echo "<button class='btn btn-default btn-xs get_vid'>Récupérer la vidéo</button><div class='frame'>".$p['video']['url']."</div>";
				if ($rights < 3) {
					echo "<br><a id='edit_btn' href='edit.php?type=video&id=".$p['video']['id']."' class='btn btn-info'><span class='glyphicon glyphicon-th'></span>Modifier la vidéo</a>";
					echo "<a id='edit_btn' href='edit.php?type=artiste&id=".$p['artiste']['id']."'class='btn btn-info'><span class='glyphicon glyphicon-th'></span>Editer l'artiste</a>";
					echo "<a id='edit_btn' href='edit.php?type=quartier&id=".$p['quartier']['id']."'class='btn btn-info'><span class='glyphicon glyphicon-th'></span>Editer le quartier</a>";
				}
				echo "</div><hr>";
			}
	 	?>
	</div>
	<script type="text/javascript">
		if ($('#messages').html() == "") {
			$('#messages').hide();
		}
		$('.get_vid').click(function(){
			$(this).hide();
			$frame = $(this).next('div');
			var url = $frame.html();
			if (url.indexOf('youtube') != -1) {
				var token = url.split("watch?v=");
				token = token[1].split("&");
				token = token[0];
				var frame = "<iframe src='//www.youtube.com/embed/"+token+"?feature=player_detailpage' frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
			}
			else if (url.indexOf('vimeo') != -1) {
				var token = url.split("/");
				token = token[3];
				var frame = "<iframe src='//player.vimeo.com/video/"+ token+ "' frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
			}
			$frame.html(frame);
		});
	</script>
</body>
</html>
