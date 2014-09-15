<?php 

$ol = file_get_contents("res_html");

$lis = explode("</li>", $ol);
$a = $lis[0];

$url = array();
$post = array();
$pattern = '#<a href="(.*)".*title="(.*)">#';
$pattern_img = '#<img src=(.*)srcset="(.*) 2x" alt=""#';
$pattern_lieu = '#LIEU TOURNAGE :(.*)</p>#';
$pattern_date = '#<time datetime="(.*)" title#';
$urls_img = array();
$i = 2;
foreach ($lis as $li) {
	if ($i < 4) {
		$item = array();
		$matches_img = array();
		$lieu = array();
		$date = array();
		$matches = array();
		$result = preg_match ($pattern, $li, $matches);
		$result = preg_match ($pattern_img, $li, $matches_img);
		if (isset($matches[2]) && !isset($matches[3])) {
			$title = explode("-", $matches[2]);
			if (isset($title[1]) && !isset($title[2])) {
				$name = explode("(", $title[1]);
				$item["url"] = "http://vimeo.com".$matches[1];
				$item["artiste"] =  htmlspecialchars_decode(html_entity_decode(strtolower(trim($title[0]))));
				$item["titre_video"] = htmlspecialchars_decode(html_entity_decode(strtolower(trim($name[0]))));
				echo "Downloading video page : ".$item['url']."\n\r";
				exec("wget -q -O page.tmp ".$item['url']);
				$page = file_get_contents("page.tmp");
				$result = preg_match($pattern_lieu, $page, $lieu);
				$result = preg_match($pattern_date, $page, $date);
				if (isset($lieu[1]) && ! isset($lieu[2])) {
					$lieu = explode("(", $lieu[1]);
					$lieu = htmlspecialchars_decode(html_entity_decode(strtolower(trim($lieu[0]))));
					$item["lieu"] = $lieu;
				}
				if (isset($date[0])) {
					$item['date'] = $date[0];
				}
				if (isset($matches_img[2])) {
					$pic = array();
					$pic["name"] = $item['artiste'];
					echo "Downloading file ".$matches_img[2]. " ...\n\r".PHP_EOL;
					$pic['img'] = file_get_contents($matches_img[2]);
					mkdir($pic['name']);
					mkdir($pic['name']."/"."min");
					file_put_contents($pic['name']."/"."min/".$pic['name'].".jpg", $pic['img']);
					$item['path_vignette'] = "portfolio/artistes/".$pic['name']."/"."min/".$pic['name'].".jpg";
				}
				$post[] = $item;
				$i++;
			}
		}
	}
}
/*"INSERT INTO `quartier`(`name`, `path_pics`, `text`, `nb_videos`, `url`, `path_vignette`) VALUES (:name, :path, :text, 0, :url, :path_vignette) "
"INSERT INTO `video`(`name`, `url`, `id_artiste`, `id_quartier`, `text`, `weekly`, `category`) VALUES (:name, :url, :id_artiste, :id_quartier, :text, :weekly, :category)"

print_r($post);
																								    [1] => Array
																								        (
																								            [url] => http://vimeo.com/98276574
																								            [artiste] => les trash croutes
																								            [titre_video] => totale �clipse
																								            [lieu] => parc pinel
																								            [date] => <time datetime="2014-06-15T15:18:04-04:00" title
																								            [path_vignette] => portfolio/artistes/les trash croutes/min/les trash croutes.jpg
																								        )
$q_a = "INSERT INTO `artiste`(`name`, `path_pics`, `text`, `url`, `itw`, `path_vignette`, `id_style`) VALUES (:artiste, :path, :text, :url, :itw, :path_vignette, :id_style)"*/

?>