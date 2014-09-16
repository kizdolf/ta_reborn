<?php 
spl_autoload_register(function ($class) {
	include __DIR__.'/classes/' . $class . '_class.php';
});
$html = "\n\n début script php 't_q.php' ---------------- \n\n";

$file_log = $argv[1];

if (isset($argv[2])) {
	$limit = $argv[2];
}else
	$limit = 0;

if (isset($argv[3]) && $argv[3] == "del"){
	$supr = true;
	$html .= "effacement de la base de donées passé en paramètres.";
}
else
	$supr = false;


$times = array();
$start_time = microtime(true);


$ol = file_get_contents("res_html");

echo "Découpage des pages web : ";
$html.= "Découpage des pages web : ";
$lis = explode("</li>", $ol);
echo "... Fait! \n";
$html.= "... Fait! \n";
$a = $lis[0];

$url = array();
$post = array();
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
	if (isset($matches[2]) && !isset($matches[3])) {
		$title = explode("-", $matches[2]);
		if (isset($title[1]) && !isset($title[2]) && ($i < $limit || $limit == 0)) {
			echo "\nVidéo trouvée! num: ".$i++;
			$html.= "\nVidéo trouvée! num: ".$i;
			$name = explode("(", $title[1]);
			$item["url"] = "http://vimeo.com".$matches[1];
			$item["artiste"] =  strtolower(trim($title[0]));
			$item["titre_video"] = strtolower(trim($name[0]));
			echo "\nArtiste ".$item['artiste'];
			$html.= "\nArtiste ".$item['artiste'];
			if (true) {
				echo "\nDownloading video page : ".$item['url']."\n\r";
				$html.= "\nDownloading video page : ".$item['url']."\n\r";
				$t = microtime(true);
				echo "Nom de la vidéo: ".$item['titre_video'];
				$html.= "Nom de la vidéo: ".$item['titre_video'];
				exec("wget -q -O page.tmp ".$item['url']);
				$t = microtime(true) - $t;
				echo "\nDownload fini en ".$t. "secondes.\n";
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
					$pic["name"] = $item['artiste'];
					echo "Downloading image ".$matches_img[2]. " ...\n";
					$html.= "Downloading image ".$matches_img[2]. " ...\n";
					$t = microtime(true);
					$pic['img'] = file_get_contents($matches_img[2]);
					$t = microtime(true) - $t;
					echo "Download fini en ".$t. "secondes.\n";
					$html.= "Download fini en ".$t. "secondes.\n";
					if (!is_dir("portfolio/artistes/".$pic['name'])) {
						mkdir("portfolio/artistes/".$pic['name']);
					}
					if (!is_dir("portfolio/artistes/".$pic['name']."/"."min")) {
						mkdir("portfolio/artistes/".$pic['name']."/"."min");
					}
					file_put_contents("portfolio/artistes/".$pic['name']."/"."min/".$pic['name'].".jpg", $pic['img']);
					$item['path_vignette'] = "portfolio/artistes/".$pic['name']."/"."min/".$pic['name'].".jpg";
					echo "Image sauvegardé avec succée.\n\n\n";
					$html.= "Image sauvegardé avec succée.\n\n\n";
				}
			}
			$post[] = $item;
		}
	}
}

unlink("page.tmp");

$end_dl = microtime(true);
$sql_start = microtime(true);

$bdd = new tapdo();

$q_a = 	"INSERT INTO `artiste`(`name`, `path_pics`, `text`, `path_vignette`, `id_style`) VALUES (?, ?, ?, ?, ?)";
$q_v = 	"INSERT INTO `video`(`category`, `name`,`url`,`id_artiste`, `id_quartier`) VALUES (?, ?, ?, ?, ?)";
$q_q = 	"INSERT INTO `quartier`(`name`, `path_pics`, `nb_videos`) VALUES (?, ?, ?)";
$del = "DELETE FROM `artiste` WHERE 1";
$del1 = "DELETE FROM `video` WHERE 1";
$del2 =  "DELETE FROM `quartier` WHERE 1";
$id_style = "SELECT `id` FROM `style` WHERE 1 LIMIT 1";
$s = $bdd->prep($id_style);
$s->execute();
$ret = array();
while ($res = $s->fetch(PDO::FETCH_ASSOC))
	$ret[] = $res;
$id_style = $ret[0]['id'];
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

foreach ($post as $one) {
	if (isset($one['artiste']) && isset($one['path_vignette']) && isset($one['lieu']) && isset($one['titre_video']) && isset($one['url'])) {
		$a = array($one['artiste']
				, "../portfolio/artistes/".$one['artiste']
				,"Toulouse Acoustics vous présente ".$one['artiste']." !! :)"
				,$one['path_vignette']
				,$id_style);
		$p = $bdd->prep($q_a);
		$p->execute($a);
		$id_a = $bdd->id();
		$q = array($one['lieu']
					, "../portfolio/quartiers/".$one['lieu']
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
}
$q = "UPDATE `video` SET `weekly` = '1' WHERE `id` = ".$last_id;
$p = $bdd->prep($q);
$p->execute();
$bdd->commit();
$end = microtime(true);
$times['preparation'] = $start_dl - $start_time;
$times['download'] = $end_dl - $start_dl;
$times['bdd'] = $end - $sql_start;
$times['full'] = $end - $start_time;
echo "-- Temps Total --\n";
$html.= "-- Temps Total --\n";
echo "-- ".$times['full']."\n";
$html.= "-- ".$times['full']."\n";
echo "\n-- preparation : ".$times['preparation'];
$html.= "\n-- preparation : ".$times['preparation'];
echo "\n-- download : ".$times['download'];
$html.= "\n-- download : ".$times['download'];
echo "\n-- mise en bdd : ".$times['bdd'];
$html.= "\n-- mise en bdd : ".$times['bdd']."\n\n";

file_put_contents($file_log, $html, FILE_APPEND);

?>