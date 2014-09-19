<?php 
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});
//constantes.

$mainDir = __DIR__."/../data";
$pathpicQ = "../portfolio/quartiers/";
$pathpicA = "../portfolio/artistes/";

$ext_ok = array("jpg", "png", "gif", "jpeg");

ini_set('upload_max_filesize', '15M');
ini_set('post_max_size', '15M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);

function save_file($file, $ext, $dest, $v = null){
	if (!in_array(strtolower($ext), $GLOBALS["ext_ok"]))
		return false;

	if (!is_dir($dest."/min")) {
		mkdir($dest."/min");
	}
	$img = new SimpleImage();
	$img->load($file['tmp_name']);
	if ($v !== null)
		$img->resize(220, 220);
	else
		$img->best_fit(1000, 1000);
	$img->save($dest."/".$file['name']);
	$img->fit_to_width(320);
	$img->save($dest."/min/".$file['name']);
	// if (!move_uploaded_file($file['tmp_name'], $dest."/".$file['name']))
	//  	return false;
	// chmod($dest."/".$file['name'], 0777);
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
	if (!is_dir($path))
			mkdir($path);
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
				echo "<h4> An error has occur saving the file '".$files[$key]["name"]."'. Contact admin please ". $url ." </h4>";
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
		return save_file($pic, $ext, $path, "yes");
		
	}
	return false;
}

function html_edit($entry, $id, $type) {

	$bdd = new tapdo();
	$styles = $bdd->get_all_styles();
	$quartiers = $bdd->get_all_quartiers_name();
	$stylehtml = "";

	if (isset($entry['id_style'])) {
		foreach ($styles as $style) {
			if ($style['id'] == $entry['id_style']) {
				$stylehtml .= "<button value='" .  $style['id'] . "' class='btn btn-success style_choix'>" . $style['name'] . "</button>";
			}else
				$stylehtml .= "<button value='" .  $style['id'] . "' class='btn btn-default style_choix'>" . $style['name'] . "</button>";
		}
		echo '<div class="btn-group change-style">';
		echo "<a href='styles.php' class='btn btn-warning new_style_btn'>nouveau</a>";
		echo $stylehtml;
		echo "</div>";
		$hidden_id = "style_id";
		$hidden_name = "id_style";
		$hidden_val = $entry['id_style'];
	}

	if (isset($entry["id_quartier"])) {
		$html = "";
		foreach ($quartiers as $q) {
			if ($q['id'] == $entry['id_quartier']) {
				$html .= "<button value='" .  $q['id'] . "' class='quartier_choix btn btn-success'>" . $q['name'] . "</button>";
			}else
				$html .= "<button value='" .  $q['id'] . "' class='quartier_choix btn btn-default'  style='display:none'>" . $q['name'] . "</button>";
		}
		echo "<input type='text' class='input-large' name='search' placeholder='rechercher un quartier' id='s_q'>";
		echo "<input type='submit' value='ok' id='sub_q'><hr>";
		echo "<div class='btn-group change-quartier'>";
		echo "	<a class='btn btn-warning' href='new_quartier.php'>nouveau</a>";
		echo $html;
		echo "	</div>";
		$hidden_id = "quartier_id";
		$hidden_name = "id_quartier";
		$hidden_val = $entry['id_quartier'];
	}

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
				<input type='hidden' name='MAX_FILE_SIZE' value='104857600' />
				<a class='btn_pics btn btn-default btn-lg'>Voir les photos.</a>
				<div class='gal_pic'></div><hr style='clear:both;'>
				<h3>Ajouter des photos</h3>
				<input type='file' multiple='multiple' name='pics[]' id='pics'> <br>
				<h3>Changer la vignette  : 220px / 220px</h3>
				<input type='file' name='vignette' id='vignette'> <br>
			</div>";
			if (isset($hidden_name)) {
				echo "<input type='hidden' class='input-large' name='$hidden_name' value='$hidden_val' id='$hidden_id'>";
			}
	}else{
		echo "<input type='hidden' class='input-large' name='$hidden_name' value='$hidden_val' id='$hidden_id'>";
	}
	echo "<br><input type='submit' value='valider'>";
	echo "</form>";
}


function handler_new_quartier($bdd, $post, $files)
{
	$id = $bdd->new_quartier($post['quartier_name'], "temp", $post['quartier_desc'], $post['quartier_url'], "temp");
	$path =$GLOBALS['pathpicQ'].$id;
	if (isset($files['vignette']) && $files['vignette']['name'] != '') {
		$ext = explode(".", $files['vignette']["name"]);
		$ext = strtolower($ext[1]);
		$path_vignette = "img/uniques/quartier/".$id.".".$ext;
	}else{
		$path_vignette = "";
	}
	$q = $bdd->get_one_quartier('id', $id);
	$q['path_pics'] = $path;
	$q['path_vignette'] = $path_vignette;
	$bdd->update_one('quartier', 'id', $id, $q);
	pics_handler($files, $path, $id);
}


function handler_new_entry($bdd, $post, $files) {
	foreach ($post as $key => $value) {
		if (strpos($key, "desc") === false) {
			$post[$key] = htmlspecialchars($value, ENT_QUOTES);
		}
	}
	$message = "";
	if (isset($post['new_quartier'])) {
		handler_new_quartier($bdd, $post, $files);
		$message .= "<div class='alert alert-success'>Quartier \"".$post['quartier_name']."\" sauvergardé!</div>";
	}
	elseif (isset($post['new_post'])) {
		$id_a = $bdd->new_artiste($post['artiste_name'], "temp", $post['artiste_desc'], $post['artiste_url'], $post['itw'], "temp", $post['style_id']);
		$path =$GLOBALS['pathpicA'].$id_a;
		if (isset($files['vignette']) && $files['vignette']['name'] != '') {
			$ext = explode(".", $files['vignette']["name"]);
			$ext = strtolower($ext[1]);
			$path_vignette = "img/uniques/artiste/".$id_a.".".$ext;
		}else{
			$path_vignette = "";
		}
		$q = get_one_artiste('id', $id_a);
		$q['path_pics'] = $path;
		$q['path_vignette'] = $path_vignette;
		$bdd->update_one('artiste', 'id', $id_a, $q);
	 	pics_handler($files, $path, $id_a);
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

function handler_new_partner($post, $files, $bdd) {
	foreach ($post as $key => $value) {
		if (strpos($key, "desc") === false) {
			$post[$key] = htmlspecialchars($value, ENT_QUOTES);
		}
	}

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
	if (isset($_COOKIE['admin_session_toulouse_acoustics'])) {
		$cookie = unserialize($_COOKIE['admin_session_toulouse_acoustics']);
		$name = $cookie['user'];
		$user = $bdd->get_one_user('ta_login', $name);
		$rights = $user['rights'];
	}else
		$rights = 999;
	return $rights;
}

function html_header($title){
	echo '	<!DOCTYPE html>';
	echo '<html>';
	echo '<head>';
	echo '	<title>Admin | '.$title.'</title>';
	echo '	<meta charset="utf-8">';
	echo '	<script src="../components/ckeditor/ckeditor.js"></script>';
	echo '	<script src="../components/purl.js"></script>';
	echo '	<script src="../components/jquery.js"></script>';
	echo '	<script src="adminjs.js"></script>';
	echo '  	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">';
	echo '	<link href="http://fonts.googleapis.com/css?family=Abel" rel="stylesheet" type="text/css">';
	echo '	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>';
	echo '</head>';
}

?>