<?php 
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});
//constantes.
$mainDir = __DIR__."/../data";
$ext_ok = array("jpg", "png", "gif");

function save_file($file, $ext, $dest){
	if (!in_array($ext, $GLOBALS["ext_ok"]))
		return false;
	if (!move_uploaded_file($file['tmp_name'], $dest."/".$file['name']))
	 	return false;
	chmod($dest."/".$file['name'], 0777);
	return $dest."/".$file['name'];
}

function reArrayFiles(&$file_post) {

	$file_ary = array();
	$file_count = count($file_post['name']);
	$file_keys = array_keys($file_post);

	for ($i=0; $i<$file_count; $i++) {
		foreach ($file_keys as $key) {
			$file_ary[$i][$key] = $file_post[$key][$i];
 		}
	}
	return $file_ary;

}

 function pics_handler($files, $path, $name_pic){
	$vign = $files;
	$files = reArrayFiles($files["pics"]);
	$i = 1;
	foreach ($files as $key => $value) {
		if ($value["name"] != '') {
			$ext = explode(".", $value["name"]);
			$ext = strtolower($ext[1]);
			while (file_exists($path."/".$name_pic."_$i.".$ext))
				$i++;
			$files[$key]["name"] = $name_pic."_$i.".$ext;
			$i++;
			if(!($url = save_file($files[$key], $ext, $path))) {
				echo "<h4> An error has occur saving the file '".$files[$key]["name"]."'. Contact admin please </h4>";
			}
		}
	}
	if (isset($vign['vignette']) && $vign['vignette']['name'] != '') {
		$pic = $vign['vignette'];
		if (strstr($path, 'quartier')) {
			$path = "../img/uniques/quartier";
		}else{
			$path = "../img/uniques/artiste";
		}
		$ext = explode(".", $pic["name"]);
		$ext = strtolower($ext[1]);
		$pic['name'] = $name_pic.".".$ext;
		return save_file($pic, $ext, $path);
		
	}
	return false;
}
function html_edit($entry, $id, $type) {
	echo "<form action='edit.php?type=valid_edit&id=$id&table=$type' method='post' enctype='multipart/form-data'>";
	foreach ($entry as $col => $val) {
		if(!strstr($col, "id") && !strstr($col, "date") && !strstr($col, "path")) {
			echo "<p>$col : </p>";
			switch ($col) {
				case 'text':
					echo "<textarea name='text' rows='5' cols='30' class='input-large' id='$id'>$val</textarea>";
					echo "<script>CKEDITOR.replace( '$id' );</script>";
					break;
				case 'category':
					echo "<input type='checkbox' name='category' value='1' >Visiteur ? ";
					break;
				case 'weekly':
					echo "<input type='checkbox' name='weekly' value='1' >Vidéo de la semaine ";
					break;
				default:
					echo "<input type='text' class='input-large' name='$col' value='$val'>";
					break;
			}
		}
	}
	if (isset($type) && !strstr($type, 'video')) {
		echo "<div id='upload' class='jumbotron'>
				<a class='btn_pics btn btn-default btn-lg'>Voir les photos.</a>
				<div class='gal_pic'></div><hr style='clear:both;'>
				<h3>Ajouter des photos</h3>
				<input type='file' multiple='multiple' name='pics[]' id='pics'> <br>
				<h3>Changer la vignette  : 220px / 220px</h3>
				<input type='file' name='vignette' id='vignette'> <br>
			</div>";
	}
	echo "<br><input type='submit' value='valider'>";
	echo "</form>";
}

function handler_new_entry($bdd, $post, $files)
{
	$message = "";
	if (isset($post['new_quartier'])) {
		$path = "../portfolio/quartiers/".$post['quartier_name'];
		if (!is_dir($path))
			mkdir($path);
		if (isset($files['vignette']) && $files['vignette']['name'] != '') {
			$ext = explode(".", $files['vignette']["name"]);
			$ext = strtolower($ext[1]);
			$path_vignette = "img/uniques/quartier/".$post['quartier_name'].".".$ext;
		}else{
			$path_vignette = "";
		}
	 	pics_handler($files, $path, $post['quartier_name']);
		$id = $bdd->new_quartier($post['quartier_name'], $path, $post['quartier_desc'], $post['quartier_url'], $path_vignette);
		$message .= "<div class='alert alert-success'>Quartier \"".$post['quartier_name']."\" sauvergardé!</div>";
	}
	elseif (isset($post['new_post'])) {
		$path = "../portfolio/artistes/" . $post['artiste_name'];
		if (!is_dir($path))
			mkdir($path);
		if (isset($files['vignette']) && $files['vignette']['name'] != '') {
			$ext = explode(".", $files['vignette']["name"]);
			$ext = strtolower($ext[1]);
			$path_vignette = "img/uniques/artiste/".$post['artiste_name'].".".$ext;
		}else{
			$path_vignette = "";
		}
	 	pics_handler($files, $path, $post['artiste_name']);
		$id_a = $bdd->new_artiste($post['artiste_name'], $path, $post['artiste_desc'], $post['artiste_url'], $post['itw'], $path_vignette, $post['style_id']);
		$weekly = (isset($post['weekly']) ? 1 : 0);
		$category = (isset($post['visiteur']) ? 1 : 0);
		$bdd->new_video($post['video_name'], $post['video_desc'], $post['video_url'], $id_a, $post['quartier_id'], $weekly, $category);
		$message .= "<div class='alert alert-success'><a href='index.php'>Sauvegarde effectuée. Cliquez ici pour actualiser et voir le post apparaitre.</a></div>";
	}
	elseif (isset($post['new_profil'])) {
			$password = hash('whirlpool', $post['password']);
			$bdd->new_user(array($post["ta_login"], $password, $post["mail"], $post["rights"]));
			header('Location: add_profil.php');
		}
	return $message;
}

function handler_message($get)
{
	$message = "";
	if (isset($get['wrong'])) {
		$message .= "<div class='alert alert-danger'>ERREUR!! Le programme recontre une erreur lors de : <p style='font-weight: bold'>".$get['wrong']."</p></div>";
	}
	if (isset($get['no'])) {
		$message .= "<div class='alert alert-danger'>Vous n'avez pas les droits pour ça.</div>";
	}
	if (isset($get['done'])){
		$message .= "<div class='alert alert-success'>".$get['done'] . " effectué! </div>";
	}
	if (isset($get['add'])) {
		$message .= "<div class='alert alert-success'>".$get['add'] . " ". $get['n'] ." ajouté! </div>";
	}
	if (isset($get['del'])) {
		$message .= "<div class='alert alert-info'>".$get['del'] . " ". $get['n'] ." supprimé </div>";
	}
	return $message;
}

function handler_new_partner($post, $files, $bdd)
{
	if (isset($files['partner_logo']) && $files['partner_logo']['name'] != '') {
		$ext = explode(".", $files['partner_logo']["name"]);
		$ext = strtolower($ext[1]);
		$path_logo = "img/uniques/logo/".$post['partner_name'].".".$ext;
		$files['partner_logo']['name'] = $post['partner_name'].".".$ext;
		save_file($files['partner_logo'], $ext, "../img/uniques/logo");
		$post['logo_path'] = $path_logo;
	}else{
		$post['logo_path'] = "";
	}
	$bdd->new_partner($post);
}

function rights($bdd){
	$cookie = unserialize($_COOKIE['session']);
	$name = $cookie['user'];
	$profil = $bdd->get_one_user('ta_login', $name);
	return $profil['rights'];
}

?>