<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Cache-control" content="public">
	<title>Toulouse Acoustics </title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap/css/bootstrap.min.css">

</head>
<body>
<?php 
	spl_autoload_register(function ($class) {
		include __DIR__.'/classes/' . $class . '_class.php';
	});
	require_once('admin/admin_functions.php');
	$log = new log();
	$bdd = new tapdo();
	$rights = rights($bdd);
	if (!$log->is_logued() || $rights != 0) {
		echo "<br><h1> C'est pas pour toi ici...</h1>";
		echo "<p>Mâ je suis gentil, voici de la musique. Et un gros bouton pour retourner sur le site ;) </p>";
		echo "<iframe width=100% height=450 scrolling=no frameborder=no src=https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/4736449&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true></iframe>";
		echo "<a class='btn btn-lg btn-default' href='index.php'>Get back on the website please :) </a>";
	} else {
		echo "RIGHTS = ".$rights;
	?>
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
		exec("rm page*");
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
		if (file_exists($file_out))
			unlink($file_out);
		exec("cat page* >> $file_out");

		$ol = file_get_contents($file_out);

		echo "<br>Découpage des pages web : ";
		$html.= "Découpage des pages web : ";
		$lis = explode("</li>", $ol);
		echo "<br>... Fait! \n";
		$html.= "... Fait! \n";
		$a = $lis[0];

		ob_flush();
		flush();

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
		$q_q = 	"INSERT INTO `quartier`(`name`, `path_pics`, `nb_videos`) VALUES (?, ?, ?)";
		$del = "DELETE FROM `artiste` WHERE 1";
		$del1 = "DELETE FROM `video` WHERE 1";
		$del2 =  "DELETE FROM `quartier` WHERE 1";
		$id_style = "SELECT `id` FROM `style` WHERE 1 LIMIT 3";
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
		$id = 1;
		foreach ($post as $one) {
			if (isset($one['artiste']) && isset($one['path_vignette']) && isset($one['lieu']) && isset($one['titre_video']) && isset($one['url'])) {
				$a = array($one['artiste']
						, "../portfolio/artistes/".$id
						,"Toulouse Acoustics vous présente ".$one['artiste']." !! :)"
						,$one['path_vignette']
						,$id_style);
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
//spl_autoload_register(function($b){include __DIR__.'/classes/'.$b.'_class.php';});require_once('admin/admin_functions.php');$c=new log();$d=new tapdo();$e=rights($d);if(!$c->is_logued()||$e!=0){echo "<br><h1> C'est pas pour toi ici...</h1>";echo "<p>Mâ je suis gentil, voici de la musique. Et un gros bouton pour retourner sur le site ;) </p>";echo "<iframe width=100% height=450 scrolling=no frameborder=no src=https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/4736449&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true></iframe>";echo "<a class='btn btn-lg btn-default' href='index.php'>Get back on the website please :) </a>";}else{echo "RIGHTS = ".$e;
//ob_flush();flush();ob_flush();flush();ob_flush();flush();$f="\n\n début script php 't_q.php' ---------------- \n\n";if(isset($argv[1]))$g=$argv[1];else $g="log_vimeo.log";if(isset($argv[2]))$h=$argv[2];else $h=0;$j=true;$k="http://vimeo.com/toulouseacoustics/videos/page:";$l="res_html";$g="log_vimeo.log";$m=array();$n=microtime(true);$o=6;$r=1;while($r<=$o){$u=$k.$r;exec("wget -q ".$u);echo"<br>page $u retrieved.<br>";$r++;ob_flush();flush();ob_flush();flush();ob_flush();flush();ob_flush();flush();}exec("cat page* >> $l");$w=file_get_contents($l);echo "<br>Découpage des pages web : ";$f.="Découpage des pages web : ";$x=explode("</li>",$w);echo "<br>... Fait! \n";$f.="... Fait! \n";$y=$x[0];ob_flush();flush();$z=array();$aa=array();$bb='#<a href="(.*)".*title="(.*)">#';$cc='#<img src=(.*)srcset="(.*) 2x" alt=""#';$dd='#LIEU TOURNAGE :(.*)</p>#';$ee='#<time datetime="(.*)" title#';$ff=array();$r=1;$gg=microtime(true);foreach($x as $hh){$ii=array();$jj=array();$kk=array();$ll=array();$mm=array();$nn=preg_match($bb,$hh,$mm);$nn=preg_match($cc,$hh,$jj);if(isset($mm[2])&&!isset($mm[3])){$oo=explode("-",$mm[2]);if(isset($oo[1])&&!isset($oo[2])&&($r<$h||$h==0)){echo "<br><hr><br><br>\nVidéo trouvée! num: ".$r++;ob_flush();flush();$f.="\nVidéo trouvée! num: ".$r;$pp=explode("(",$oo[1]);$ii["url"]="http://vimeo.com".$mm[1];$ii["artiste"]=strtolower(trim($oo[0]));$ii["titre_video"]=strtolower(trim($pp[0]));echo "<br>\nArtiste: ".$ii['artiste'];ob_flush();flush();$f.="\nArtiste ".$ii['artiste'];if(true){echo "<br>\nDownloading video page : ".$ii['url']."\n\r";ob_flush();flush();$f.="\nDownloading video page : ".$ii['url']."\n\r";$qq=microtime(true);echo "<br>Nom de la vidéo: ".$ii['titre_video'];$f.="Nom de la vidéo: ".$ii['titre_video'];ob_flush();flush();exec("wget -q -O page.tmp ".$ii['url']);$qq=microtime(true)-$qq;echo "<br>\nDownload fini en ".$qq."secondes.\n";ob_flush();flush();$f.="\nDownload fini en ".$qq."secondes.\n";$rr=file_get_contents("page.tmp");$nn=preg_match($dd,$rr,$kk);$nn=preg_match($ee,$rr,$ll);if(isset($kk[1])&&!isset($kk[2])){$kk=explode("(",$kk[1]);$kk=strtolower(trim($kk[0]));$ii["lieu"]=$kk;}if(isset($ll[0])){$ii['date']=$ll[0];}if(isset($jj[2])){$ss=array();$ss["name"]=$ii['artiste'];echo "<br>Downloading image ".$jj[2]." ...\n";$f.="Downloading image ".$jj[2]." ...\n";$qq=microtime(true);$ss['img']=file_get_contents($jj[2]);$qq=microtime(true)-$qq;echo "<br>Download fini en ".$qq."secondes.\n";$f.="Download fini en ".$qq."secondes.\n";if(!is_dir("portfolio/artistes/".$ss['name'])){mkdir("portfolio/artistes/".$ss['name']);}if(!is_dir("portfolio/artistes/".$ss['name']."/"."min")){mkdir("portfolio/artistes/".$ss['name']."/"."min");}file_put_contents("portfolio/artistes/".$ss['name']."/"."min/".$ss['name'].".jpg",$ss['img']);$ii['path_vignette']="portfolio/artistes/".$ss['name']."/"."min/".$ss['name'].".jpg";echo "<br>Image sauvegardé avec succée.\n\n\n";$f.="Image sauvegardé avec succée.\n\n\n";}}$aa[]=$ii;ob_flush();flush();}}}unlink("page.tmp");$tt=microtime(true);$uu=microtime(true);$vv="INSERT INTO `artiste`(`name`, `path_pics`, `text`, `path_vignette`, `id_style`) VALUES (?, ?, ?, ?, ?)";$ww="INSERT INTO `video`(`category`, `name`,`url`,`id_artiste`, `id_quartier`) VALUES (?, ?, ?, ?, ?)";$xx="INSERT INTO `quartier`(`name`, `path_pics`, `nb_videos`) VALUES (?, ?, ?)";$yy="DELETE FROM `artiste` WHERE 1";$zz="DELETE FROM `video` WHERE 1";$aaa="DELETE FROM `quartier` WHERE 1";$bbb="SELECT `id` FROM `style` WHERE 1 LIMIT 1";$ccc=$d->prep($bbb);$ccc->execute();$ddd=array();while($eee=$ccc->fetch(PDO::FETCH_ASSOC))$ddd[]=$eee;$bbb=$ddd[0]['id'];$d->begin();if($j){$fff=$d->prep($yy);$fff->execute();$ggg=$d->prep($zz);$ggg->execute();$hhh=$d->prep($aaa);$hhh->execute();echo "base de donées supprimée\n";}foreach($aa as $iii){if(isset($iii['artiste'])&&isset($iii['path_vignette'])&&isset($iii['lieu'])&&isset($iii['titre_video'])&&isset($iii['url'])){$y=array($iii['artiste'],"../portfolio/artistes/".$iii['artiste'],"Toulouse Acoustics vous présente ".$iii['artiste']." !! :)",$iii['path_vignette'],$bbb);$fff=$d->prep($vv);$fff->execute($y);$jjj=$d->id();$kkk=array($iii['lieu'],"../portfolio/quartiers/".$iii['lieu'],1);$lll="SELECT `id` FROM `quartier` WHERE `name`='".$iii['lieu']."'";$mmm=$d->prep($lll);$mmm->execute();$nn=$mmm->fetch(PDO::FETCH_ASSOC);if(!empty($nn)){$nnn=$nn['id'];}else{$fff=$d->prep($xx);$fff->execute($kkk);$nnn=$d->id();}$ooo=array(0,$iii['titre_video'],$iii['url'],$jjj,$nnn);$fff=$d->prep($ww);$fff->execute($ooo);$ppp=$d->id();}}$kkk="UPDATE `video` SET `weekly` = '1' WHERE `id` = ".$ppp;$fff=$d->prep($kkk);$fff->execute();$d->commit();exec("rm page*");exec("rm $l");$qqq=microtime(true);$m['preparation']=$gg-$n;$m['download']=$tt-$gg;$m['bdd']=$qqq-$uu;$m['full']=$qqq-$n;echo "<br><br>-- Temps Total --\n";$f.="-- Temps Total --\n";echo "<br>-- ".$m['full']."\n";$f.="-- ".$m['full']."\n";echo "<br>\n-- preparation : ".$m['preparation'];$f.="\n-- preparation : ".$m['preparation'];echo "<br>\n-- download : ".$m['download'];$f.="\n-- download : ".$m['download'];echo "<br>\n-- mise en bdd : ".$m['bdd'];$f.="\n-- mise en bdd : ".$m['bdd']."\n\n";file_put_contents($g,$f,FILE_APPEND);}*
	?>
</body>
</html>
