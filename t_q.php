<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Toulouse Acoustics </title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap/css/bootstrap.min.css">
</head>
<body>
<script src="components/jquery.js"></script>
<script>
			setInterval(function(){
				var t = $('#wrapper').height();
				console.log(t);
				$('body').animate({
					scrollTop: t
				});
			}, 3000);
</script>
<?php 
	spl_autoload_register(function ($class) {
		include __DIR__.'/classes/' . $class . '_class.php';
	});
	require_once('admin/admin_functions.php');
	$log = new log();
	$bdd = new tapdo();
	$rights = rights($bdd);

	/*controle d'identité.*/
	if (!$log->is_logued() || $rights != 0) {
		echo "<br><h1> C'est pas pour toi ici...</h1>";
		echo "<p>Mâ je suis gentil, voici de la musique. Et un gros bouton pour retourner sur le site ;) </p>";
		echo "<iframe width=100% height=450 scrolling=no frameborder=no src=https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/4736449&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true></iframe>";
		echo "<a class='btn btn-lg btn-default' href='index.php'>Get back on the website please :) </a>";
	} else {
		$id_style = "SELECT `id` FROM `style` WHERE 1";
		$s = $bdd->prep($id_style);
		$s->execute();
		$ret = array();
		$id_style = array();
		while ($res = $s->fetch(PDO::FETCH_ASSOC))
			$ret[] = $res;
		if (empty($ret)) {
			echo "<h1> WAAAAIIITTTTT. Problem has been seen. Il n'y a aucun style d'enregistré. Allez en enregistré au moins 2 (deux) et relancez le script. Grazie. </h1>";
		}else{

	?>
	<div id="wrapper">
		<a class="btn btn-danger btn-xs" href="admin/index.php" style="position: fixed; top: 15px; right: 15px;">Retourner sur l'admin.</a>
		<h1>SCRIPT HAS STARTED. SCROLL DOWN TO SEE WHAT HAPPEN.</h1>
		<h2>And don't quit the page...</h2>
		<h2>Si rien ne s'affiche c'est normal au début. Just wait.</h2>
		<iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/4736449&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>

	<?php
		ob_flush();
		flush();
		ob_flush();
		flush();
		ob_flush();
		flush();
		$html = "\n\n début script php 't_q.php' ---------------- \n\n";

		/* initialisation des variables */

		if (isset($argv[1]))
			$file_log = $argv[1];
		else
			$file_log = "log_vimeo.log";

		if (isset($argv[2]))
			$limit = $argv[2];
		else
			$limit = 0;

		$supr = true;
		$url_base="http://vimeo.com/toulouseacoustics/videos/page:";
		$file_out="res_html";
		$file_log="log_vimeo.log";

		$times = array();
		$start_time = microtime(true);
		$nb_page = 6;
		$i = 1;
		
		/* suppression de fichiers présents si le script s'est fait avorter. */
		exec("rm page*");
		if (file_exists($file_out))
			unlink($file_out);
		
		/* passage par le shell pour aller chercher les pages nécéssaires.  */
		while ($i <= $nb_page)
		{
			$currenturl = $url_base.$i;
			exec("wget -q ".$currenturl);
			echo "<br>page $currenturl retrieved.<br>";
			$i++;
			ob_flush();
			flush();
			ob_flush();
			flush();
			ob_flush();
			flush();
			ob_flush();
			flush();
		}

		/* concaténation des pages en une seule. */		
		exec("cat page* >> $file_out");

		$ol = file_get_contents($file_out);

		/* split à l'arrache de l'html récupéré...*/
		echo "<br>Découpage des pages web : ";
		$html.= "Découpage des pages web : ";
		$lis = explode("</li>", $ol);
		echo "<br>... Fait! \n";
		$html.= "... Fait! \n";

		ob_flush();
		flush();

		$url = array();
		$post = array();

		/* REGEX pour spliter précisément chaque grossiere partie de l'html. */
		$pattern = '#<a href="(.*)".*title="(.*)">#';
		$pattern_img = '#<img src=(.*)srcset="(.*) 2x" alt=""#';
		$pattern_lieu = '#LIEU TOURNAGE :(.*)</p>#';
		$pattern_date = '#<time datetime="(.*)" title#';
		$urls_img = array();
		$i  = 1;
		$start_dl = microtime(true);
		foreach ($lis as $li) {
			$item = array();
			$matches_img = array();
			$lieu = array();
			$date = array();
			$matches = array();
			$result = preg_match ($pattern, $li, $matches);
			$result = preg_match ($pattern_img, $li, $matches_img);
			//controle que l'on ai bien sur une vidéo
			if (isset($matches[2]) && !isset($matches[3])) {
				$title = explode("-", $matches[2]);
				if (isset($title[1]) && !isset($title[2]) && ($i < $limit || $limit == 0)) {
					echo "<br><hr><br><br>\nVidéo trouvée! num: ".$i;
					ob_flush();
					flush();
					$html.= "\nVidéo trouvée! num: ".$i;
					$name = explode("(", $title[1]);
					$item["url"] = "http://vimeo.com".$matches[1];
					$item["artiste"] =  strtolower(trim($title[0]));
					$item["titre_video"] = strtolower(trim($name[0]));
					echo "<br>\nArtiste: ".$item['artiste'];
					ob_flush();
					flush();
					$html.= "\nArtiste ".$item['artiste'];
					if (true) {
						echo "<br>\nDownloading video page : ".$item['url']."\n\r";
						ob_flush();
						flush();
						$html.= "\nDownloading video page : ".$item['url']."\n\r";
						$t = microtime(true);
						echo "<br>Nom de la vidéo: ".$item['titre_video'];
						$html.= "Nom de la vidéo: ".$item['titre_video'];
						ob_flush();
						flush();
						exec("wget -q -O page.tmp ".$item['url']);
						$t = microtime(true) - $t;
						echo "<br>\nDownload fini en ".$t. "secondes.\n";
						ob_flush();
						flush();
						$html.= "\nDownload fini en ".$t. "secondes.\n";
						$page = file_get_contents("page.tmp");
						$result = preg_match($pattern_lieu, $page, $lieu);
						$result = preg_match($pattern_date, $page, $date);
						if (isset($lieu[1]) && ! isset($lieu[2])) {
							$lieu = explode("(", $lieu[1]);
							$lieu = strtolower(trim($lieu[0]));
							$item["lieu"] = $lieu;
						}
						if (isset($date[0])) {
							$item['date'] = $date[0];
						}
						if (isset($matches_img[2])) {
							$pic = array();
							$pic["name"] = $i;
							echo "<br>Downloading image ".$matches_img[2]. " ...\n";
							$html.= "Downloading image ".$matches_img[2]. " ...\n";
							$t = microtime(true);
							$pic['img'] = file_get_contents($matches_img[2]);
							$t = microtime(true) - $t;
							echo "<br>Download fini en ".$t. "secondes.\n";
							$html.= "Download fini en ".$t. "secondes.\n";
							if (!is_dir("portfolio/artistes/".$pic['name'])) {
								mkdir("portfolio/artistes/".$pic['name']);
							}
							if (!is_dir("portfolio/artistes/".$pic['name']."/"."min")) {
								mkdir("portfolio/artistes/".$pic['name']."/"."min");
							}
							file_put_contents("portfolio/artistes/".$pic['name']."/"."min/".$pic['name'].".jpg", $pic['img']);
							$item['path_vignette'] = "portfolio/artistes/".$pic['name']."/"."min/".$pic['name'].".jpg";
							echo "<br>Image sauvegardé avec succée.\n\n\n";
							$html.= "Image sauvegardé avec succée.\n\n\n";
						}
					}
					$post[] = $item;
					ob_flush();
					flush();
					$i++;
				}
			}
		}

		unlink("page.tmp");

		$end_dl = microtime(true);
		$sql_start = microtime(true);


		$q_a = 	"INSERT INTO `artiste`(`name`, `path_pics`, `text`, `path_vignette`, `id_style`) VALUES (?, ?, ?, ?, ?)";
		$q_v = 	"INSERT INTO `video`(`category`, `name`,`url`,`id_artiste`, `id_quartier`) VALUES (?, ?, ?, ?, ?)";
		$q_q = "INSERT INTO `quartier`(`name`, `path_pics`, `nb_videos`) VALUES (?, ?, ?)";
		$del = "DELETE FROM `artiste` WHERE 1";
		$del1 = "DELETE FROM `video` WHERE 1";
		$del2 =  "DELETE FROM `quartier` WHERE 1";
		
			$id_style[0] = $ret[0]['id'];
			$id_style[1] = $ret[1]['id'];
			echo "<pre>";
			print_r($id_style);
			echo "</pre>";
			$bdd->begin();
			if($supr){
				$p = $bdd->prep($del);
				$p->execute();
				$p1 = $bdd->prep($del1);
				$p1->execute();
				$p2 = $bdd->prep($del2);
				$p2->execute();
				echo "base de donées supprimée\n";
			}
			$id = 1;
			foreach ($post as $one) {
				if (isset($one['artiste']) && isset($one['path_vignette']) && isset($one['lieu']) && isset($one['titre_video']) && isset($one['url'])) {
					$a = array($one['artiste']
							, "../portfolio/artistes/".$id
							,"Toulouse Acoustics vous présente ".$one['artiste']." !! :)"
							,$one['path_vignette']
							,(($id % 2 == 0) ? $id_style[0] : $id_style[1]));
					$p = $bdd->prep($q_a);
					$p->execute($a);
					$id_a = $bdd->id();
					$q = array($one['lieu']
								, "../portfolio/quartiers/".$id
								,1);
					$verif_q = "SELECT `id` FROM `quartier` WHERE `name`='" . $one['lieu'] . "'";
					$prep = $bdd->prep($verif_q);
					$prep->execute();
					$result = $prep->fetch(PDO::FETCH_ASSOC);
					if (!empty($result)) {
						$id_q =  $result['id'];
					}else{
						$p = $bdd->prep($q_q);
						$p->execute($q);
						$id_q = $bdd->id();
					}
					$v = array(0
								,$one['titre_video']
								,$one['url']
								,$id_a
								,$id_q);
					$p = $bdd->prep($q_v);
					$p->execute($v);
					$last_id = $bdd->id();
				}
				$id++;
			}
			$q = "UPDATE `video` SET `weekly` = '1' WHERE `id` = ".$last_id;
			$p = $bdd->prep($q);
			$p->execute();
			$bdd->commit();
			exec("rm page*");
			exec("rm $file_out");
			$end = microtime(true);
			$times['preparation'] = $start_dl - $start_time;
			$times['download'] = $end_dl - $start_dl;
			$times['bdd'] = $end - $sql_start;
			$times['full'] = $end - $start_time;
			echo "<br><br>-- Temps Total --\n";
			$html.= "-- Temps Total --\n";
			echo "<br>-- ".$times['full']."\n";
			$html.= "-- ".$times['full']."\n";
			echo "<br>\n-- preparation : ".$times['preparation'];
			$html.= "\n-- preparation : ".$times['preparation'];
			echo "<br>\n-- download : ".$times['download'];
			$html.= "\n-- download : ".$times['download'];
			echo "<br>\n-- mise en bdd : ".$times['bdd'];
			$html.= "\n-- mise en bdd : ".$times['bdd']."\n\n";

			file_put_contents($file_log, $html, FILE_APPEND);

		}
	}
	?>
	<a href="index.php" class="btn btn-success btn-lg">Aller sur le site</a>
	<a href="admin/index.php" class="btn btn-success btn-lg">Aller sur l'admin</a>
	</div>
</body>
</html>
