<?php 

/**
* New class for ta.. again!
* 
*/
class tapdo
{
	private $_conf;
	private $_con;
	private $_querys;
	private $_quartiers_cols = array("id", "date_creation", "date_update", "name", "path_pics", "text", "nb_videos", "url");
	private $_artistes_cols = array("id", "date_creation", "date_update", "name", "path_pics", "text", "url");

	function __construct()
	{
		$this->set_conf();
		$str = "mysql:host=" . $this->_conf->server . ";dbname=" . $this->_conf->dbname;
		try {
			$this->_con = new PDO($str, $this->_conf->user, $this->_conf->password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		} catch (PDOException $e) {
			echo ("<bold>Connection failed. Message: " . $e->getMessage() . "<br></bold>");
			return false;
		}
		return true;
	}

	private function set_conf()
	{
		$file = __DIR__ . "/../admin/conf/bdd_conf.json";
		$conf = json_decode(file_get_contents($file));
		$this->_conf = $conf->init;
		$this->_querys = $conf->querys;
	}

	public function new_quartier($name, $path, $text, $url = "", $path_vignette)
	{
		$verif_q = "SELECT `id` FROM `quartier` WHERE `name`='$name'";
		$prep = $this->_con->prepare($verif_q);
		$prep->execute();
		$result = $prep->fetch(PDO::FETCH_ASSOC);
		if (!empty($result)) {
			return $result['id'];
		}
		$q = $this->_querys->new->quartier;
		$prep = $this->_con->prepare($q);
		$vars = array(
			"name" => $name
			,"path" => $path
			,"text" => $text
			,"url" => $url
			,"path_vignette" => $path_vignette);
		$prep->execute($vars);
		return $this->_con->lastInsertId();
	}

	public function new_artiste($name, $path, $text, $url = "", $itw, $path_vignette, $id_style)
	{
		$verif_q = "SELECT `id` FROM `artiste` WHERE `name`='$name'";
		$prep = $this->_con->prepare($verif_q);
		$prep->execute();
		$result = $prep->fetch(PDO::FETCH_ASSOC);
		if (!empty($result)) {
			return $result['id'];
		}
		$q = $this->_querys->new->artiste;
		$prep = $this->_con->prepare($q);
		$vars = array(
			"name" => $name
			,"path" => $path
			,"text" => $text
			,"url" => $url
			,"itw" => $itw
			,"path_vignette" => $path_vignette
			,"id_style" => $id_style);
		$prep->execute($vars);
		$id_artiste = $this->_con->lastInsertId();
		//self::update_one("style", "nb_artistes");
		return $id_artiste;
	}

	public function new_video($name, $text, $url, $id_artiste, $id_quartier, $weekly, $cat)
	{
		$this->_con->beginTransaction();

		$verif_q = "SELECT `id` FROM `video` WHERE `name`='$name'";
		$prep = $this->_con->prepare($verif_q);
		$prep->execute();
		$result = $prep->fetch(PDO::FETCH_ASSOC);
		if (!empty($result)) {
			return $result['id'];
		}

		if($weekly == 1)
		{
			$qu = $this->_querys->update->video_week;
			$p = $this->_con->prepare($qu);
			$p->execute();
		}

		$q = $this->_querys->new->video;
		$prep = $this->_con->prepare($q);
		$vars = array(
			"name" => $name
			,"url" => $url
			,"id_artiste" => $id_artiste
			,"id_quartier" => $id_quartier
			,"text" => $text
			,"weekly"=> $weekly
			,"category" => $cat);
		$prep->execute($vars);

		$q = $this->_querys->get->nb_videos;
		$vars = array("id_quartier" => $id_quartier);
		$prep = $this->_con->prepare($q);
		$prep->execute($vars);
		$result = $prep->fetch(PDO::FETCH_ASSOC);
		$nb = $result['nb_videos'] + 1;

		$q = $this->_querys->update->quartier_nb;
		$vars = array("new_nb" => $nb, "id_quartier" => $id_quartier);
		$prep = $this->_con->prepare($q);
		$prep->execute($vars);

		$this->_con->commit();
	}

	public function new_style($name)
	{
		$this->_con->beginTransaction();
		$exist = $this->fetch_res($this->run_q($this->_querys->get->style_by_name, array($name)));
		if (isset($exist[0])) {
			$this->_con->commit();
			return ;
		}
		$this->run_q($this->_querys->new->style, array($name));
		$this->_con->commit();
	}

	public function new_partner($kwarg)
	{
		$this->_con->beginTransaction();
		$exist = $this->fetch_res($this->run_q($this->_querys->get->partner_by_name, array($kwarg['partner_name'])));
		if (isset($exist[0])) {
			$this->_con->commit();
			return ;
		}
		$this->run_q($this->_querys->new->partner, array(
			$kwarg['partner_name']
			,$kwarg['partner_desc']
			,$kwarg['partner_url']
			,$kwarg['logo_path']));
		$this->_con->commit();
	}

	public function new_user($kwarg)
	{
		$this->_con->beginTransaction(); 
		$q = $this->_querys->new->user;
		$prep=$this->_con->prepare($q);
		$prep->execute($kwarg);
		$this->_con->commit();
	}

	public function new_draft($kwarg)
	{
		$this->_con->beginTransaction(); 
		$this->run_q($this->_querys->new->draft, $kwarg);
		$this->_con->commit();
	}

	public function get_all_quartiers_name()
	{
		$q = $this->_querys->get->all_quartiers_name;
		$prep = $this->_con->prepare($q);
		$prep->execute();
		return $prep->fetchAll();
	}

	public function get_all_post()
	{
		$post = array();
		$this->_con->beginTransaction();

		$q = $this->_querys->get->all_videos;
		$prep = $this->_con->prepare($q);
		$prep->execute();
		$videos = $prep->fetchAll();
		foreach ($videos as $video) {
			$q_id = $video['id_quartier'];
			$a_id = $video['id_artiste'];
			$q_q = $this->_querys->get->one_quartier_id;
			$q_a = $this->_querys->get->one_artiste_id;
			$prep_q = $this->_con->prepare($q_q);
			$prep_a = $this->_con->prepare($q_a);
			$prep_q->execute(array($q_id));
			$prep_a->execute(array($a_id));
			$post[] = $this->build_fetch($video, $prep_a->fetchAll(), $prep_q->fetchAll());
		}
		$this->_con->commit();
		return $post;
	}

	private function build_fetch($video, $artiste, $quartier)
	{
		$artiste = $artiste[0];
		$quartier = $quartier[0];
		$post = array();
		$post['video'] = array(
			"id" => $video['id']
			,"name" => $video['name']
			,"text" =>$video['text']
			,"url" => $video['url']
			,"date" => $video['date_creation']
			,"weekly" => $video['weekly']
			,"category" => $video['category']);
		$post['artiste'] = array(
			"id" => $artiste['id']
			,"path_pics" => $artiste['path_pics']
			,"name" => $artiste['name']
			,"text" =>$artiste['text']
			,"url" => $artiste['url']
			,"path_vignette" => $artiste['path_vignette']
			,"date" => $artiste['date_creation']);
		$post['quartier'] = array(
			"id" => $quartier['id']
			,"path_pics" => $quartier['path_pics']
			,"name" => $quartier['name']
			,"text" =>$quartier['text']
			,"path_vignette" => $quartier['path_vignette']
			,"url" => $quartier['url']
			,"date" => $quartier['date_creation']);
		return $post;
	}

	private function related_to_video($id_artiste, $id_quartier)
	{
		$this->_con->beginTransaction();

		$q_a = $this->_querys->get->artiste_id;
		$q_q = $this->_querys->get->quartier_id;
		$prep_q = $this->_con->prepare($q_q);
		$prep_a = $this->_con->prepare($q_a);
		$prep_a->execute(array("id_artiste" => $id_artiste));
		$prep_q->execute(array("id_quartier" => $id_quartier));
		$res_a = $prep_a->fetchAll();
		$res_q = $prep_q->fetchAll();

		$this->_con->commit();
		return (array("artiste" => $res_a, "quartier" => $res_q));
	}

	public function get_weekly_post()
	{
		$q = $this->_querys->get->all_videos_weekly;
		$prep = $this->_con->prepare($q);
		$prep->execute();
		$res = $prep->fetchAll();
		$video = $res[0];
		$related = $this->related_to_video($video['id_artiste'], $video['id_quartier']);
		$post = $this->build_fetch($video, $related['artiste'], $related['quartier']);
		return $post;
	}

	public function get_quartiers($param = '')
	{
		if ($param != ''){
			if (!in_array($param, $this->_quartiers_cols))
				die("wrong parameter.");
			$q = "SELECT `$param` FROM `quartier` WHERE 1";
		}else{
			$q = "SELECT * FROM `quartier` WHERE 1";
		}
		$prep = $this->_con->prepare($q);
		$prep->execute();
		$ret = array();
		while ($res = $prep->fetch(PDO::FETCH_ASSOC))
		{
			$ret[] = $res;
		}
		return $ret;
	}

	public function get_artistes($param = '')
	{
		$this->_con->beginTransaction();
		if ($param != ''){
			if (!in_array($param, $this->_artistes_cols))
				die("wrong parameter.");
			$q = "SELECT `$param` FROM `artiste` WHERE 1";
		}else{
			$q = "SELECT * FROM `artiste` WHERE 1";
		}
		$prep = $this->_con->prepare($q);
		$prep->execute();
		$ret = array();
		while ($res = $prep->fetch(PDO::FETCH_ASSOC))
			$ret[] = $res;
		$this->_con->commit();
		return $ret;
	}

	public function get_one_artiste($col, $val)
	{
		$this->_con->beginTransaction();
		$t = "one_artiste_".$col;
		$q = $this->_querys->get->$t;
		$prep = $this->_con->prepare($q);
		$prep->execute(array($val));
		$ret = array();
		while ($res = $prep->fetch(PDO::FETCH_ASSOC))
			$ret[] = $res;
		$this->_con->commit();
		return($ret[0]);
	}

	public function get_one_quartier($col, $val)
	{
		$this->_con->beginTransaction();
		$t = "one_quartier_".$col;
		$q = $this->_querys->get->$t;
		$prep = $this->_con->prepare($q);
		$prep->execute(array($val));
		$ret = array();
		while ($res = $prep->fetch(PDO::FETCH_ASSOC))
			$ret[] = $res;
		$this->_con->commit();
		return($ret[0]);
	}

	public function get_one_video($col, $val)
	{
		$this->_con->beginTransaction();
		$t = "one_video_".$col;
		$q = $this->_querys->get->$t;
		$prep = $this->_con->prepare($q);
		$prep->execute(array($val));
		$ret = array();
		while ($res = $prep->fetch(PDO::FETCH_ASSOC))
			$ret[] = $res;
		$this->_con->commit();
		if (isset($ret[0])) {
			return($ret[0]);
		}
		return false;
	}

	public function get_one_user($col, $val)
	{
		$this->_con->beginTransaction();
		$t = "one_user_".$col;
		$q = $this->_querys->get->$t;
		$prep = $this->_con->prepare($q);
		$prep->execute(array($val));
		$ret = array();
		while ($res = $prep->fetch(PDO::FETCH_ASSOC))
			$ret[] = $res;
		$this->_con->commit();
		return($ret[0]);
	}

	public function get_videos_related($col, $val)
	{
		$this->_con->beginTransaction();
		$t = "all_videos_from_".$col;
		$q = $this->_querys->get->$t;
		$prep = $this->_con->prepare($q);
		$prep->execute(array($val));
		$ret = array();
		while ($res = $prep->fetch(PDO::FETCH_ASSOC))
			$ret[] = $res;
		$this->_con->commit();
		return($ret);
	}

	public function user_exist($name, $password)
	{
		$this->_con->beginTransaction();
		$q = $this->_querys->user->user_exist;
		$prep = $this->_con->prepare($q);
		$prep->execute(array("name" => $name, "hash" => $password));
		$nb = $prep->rowCount();
		$this->_con->commit();
		if($nb != 0)
			return true;
		return false;
	}

	public function update_one($table, $col, $val, $entry)
	{
		$this->_con->beginTransaction(); 

		if ($table == 'video' && $entry['weekly'] == 1) {
			$qu = $this->_querys->update->video_week;
			$p = $this->_con->prepare($qu);
			$p->execute();
		}

		$q = "one_".$table."_by_".$col;
		$q = $this->_querys->update->$q;
		$prep = $this->_con->prepare($q);
		$entry['id_where'] = $entry['id'];
		$prep->execute($entry);

		$this->_con->commit();
	}

	public function up_user_visit($login)
	{
		$this->_con->beginTransaction(); 
		
		$q = $this->_querys->get->nb_visits_user_by_name;
		$prep=$this->_con->prepare($q);
		$prep->execute(array($login));
		$res = $prep->fetch(PDO::FETCH_ASSOC);
		$nb = $res['nb_visits'] + 1;
		$date = date("Y-m-d H:i:s");
		$q = $this->_querys->update->user_nb_visits;
		$prep=$this->_con->prepare($q);
		$prep->execute(array($nb, $date, $login));
		
		$this->_con->commit();
		return $res['id'];
	}

	public function update_one_user($vars)
	{
		$this->_con->beginTransaction(); 
		
		$q = $this->_querys->update->one_user_by_id;
		$prep=$this->_con->prepare($q);
		$vars['id_where'] = $vars['id'];
		$prep->execute($vars);

		$this->_con->commit();
	}

	public function get_all_users()
	{
		$this->_con->beginTransaction(); 
		$q = $this->_querys->get->all_users;
		$prep=$this->_con->prepare($q);
		$prep->execute();
		$ret = array();
		while ($res = $prep->fetch(PDO::FETCH_ASSOC))
			$ret[] = $res;
		$this->_con->commit();
		return $ret;
	}

	public function get_all_names_id()
	{
		$this->_con->beginTransaction(); 
		$qa = $this->_querys->get->all_artistes_name;
		$qq = $this->_querys->get->all_quartiers_name;
		$qv = $this->_querys->get->all_videos_name;
		$prepa=$this->_con->prepare($qa);
		$prepa->execute();
		$res['artistes'] = $this->fetch_res($prepa);
		$prepq=$this->_con->prepare($qq);
		$prepq->execute();
		$res['quartiers'] = $this->fetch_res($prepq);
		$prepv=$this->_con->prepare($qv);
		$prepv->execute();
		$res['videos'] = $this->fetch_res($prepv);
		$this->_con->commit();
		return $res;
	}

	public function get_rights_user($id)
	{
		$this->_con->beginTransaction(); 
		$q= $this->_querys->get->rights_user_id;
		$prep=$this->_con->prepare($q);
		$prep->execute($id);
		$res = $this->fetch_res($prep);
		$this->_con->commit();
		return $res[0]['rights'];
	}

	public function update_one_text($post, $name)
	{
		$this->_con->beginTransaction(); 
		if (isset($post['about'])) {
			$q = $this->_querys->update->about;
			$var = array($post['about'], $name);
		}elseif (isset($post['team'])) {
			$q = $this->_querys->update->team;
			$var = array($post['team'], $name);
		}elseif (isset($post['contact'])) {
			$q = $this->_querys->update->contact;
			$var = array($post['contact'], $name);
		}elseif (isset($post['short_about'])) {
			$q = $this->_querys->update->short_about;
			$var = array($post['short_about'], $name);
		}else{
			return false;
		}
		$prep = $this->_con->prepare($q);
		$prep->execute($var);
		$this->_con->commit();
	}

	public function get_about()
	{
		$this->_con->beginTransaction(); 
		$q = $this->_querys->get->about;
		$prep = $this->_con->prepare($q);
		$prep->execute();
		$res = $this->fetch_res($prep);
		$this->_con->commit();
		return $res[0];
	}

	public function get_team()
	{
		$this->_con->beginTransaction(); 
		$q = $this->_querys->get->team;
		$prep = $this->_con->prepare($q);
		$prep->execute();
		$res = $this->fetch_res($prep);
		$this->_con->commit();
		return $res[0];
	}

	public function get_short_about()
	{
		$this->_con->beginTransaction(); 
		$q = $this->_querys->get->short_about;
		$prep = $this->_con->prepare($q);
		$prep->execute();
		$res = $this->fetch_res($prep);
		$this->_con->commit();
		return $res[0];
	}

	public function get_contact()
	{
		$this->_con->beginTransaction(); 
		$q = $this->_querys->get->contact;
		$prep = $this->_con->prepare($q);
		$prep->execute();
		$res = $this->fetch_res($prep);
		$this->_con->commit();
		return $res[0];
	}

	public function get_category($cat)
	{
		$this->_con->beginTransaction(); 
		$q = $this->_querys->get->videos_id_artistes_cat;
		$prep = $this->_con->prepare($q);
		$prep->execute(array($cat));
		$res = $this->fetch_res($prep);
		$this->_con->commit();
		$arts = array();
		foreach ($res as $id) {
			$artiste = self::get_one_artiste("id", $id['id_artiste']);
			$style = self::get_one_style("id", $artiste['id_style']);
			$arts[] = array("artiste" => $artiste, "style" => $style[0] );
		}
		return $arts;
	}

	public function get_one_style($col, $val)
	{
		$this->_con->beginTransaction(); 
		$q = "one_style_by_".$col;
		$q = $this->_querys->get->$q;
		$p = $this->run_q($q, array($val));
		$res = $this->fetch_res($p);
		$this->_con->commit();
		return $res;
	}

	public function get_all_styles()
	{
		$this->_con->beginTransaction(); 	
		$res = $this->fetch_res($this->run_q($this->_querys->get->all_styles));
		$this->_con->commit();
		return $res;
	}

	public function get_artistes_by_style()
	{
		$styles = self::get_all_styles();
		$arts = array();
		foreach ($styles as $style) {
			$arts[] =array("name" => $style['name'], "artistes" =>self::get_all_artistes("style", $style['id']));
		}
		return $arts;
	}

	public function get_all_artistes($col, $val)
	{
		$q = "all_artistes_by_".$col;
		$q = $this->_querys->get->$q;
		$p = $this->run_q($q, array($val));
		$res = $this->fetch_res($p);
		return $res;
	}

	public function get_all_partners()
	{
		$this->_con->beginTransaction(); 	
		$res = $this->fetch_res($this->run_q($this->_querys->get->all_partners));
		$this->_con->commit();
		return $res;
	}

	/*	DELETE PART 	*/

	public function suppr($table, $col, $val)
	{
		if (!is_array($val))
			$val = array($val);
		$this->_con->beginTransaction();
		$q = $table."_by_".$col;
		$this->run_q($this->_querys->delete->$q, $val);
		$this->_con->commit();
	}

	public function get_all($table)
	{
		$q = "all_".$table."s";
		$this->_con->beginTransaction();
		$res = $this->fetch_res($this->run_q($this->_querys->get->$q));
		$this->_con->commit();
		return $res;
	}

	public function update_vign_name($path, $name)
	{
		echo "<pre>";
		print_r($path);
		print_r($name);
		echo "</pre>";
		$this->_con->beginTransaction();
		$this->run_q($this->_querys->update->path_vign, array("path" => $path, "name" => $name));	
		$this->_con->commit();
	}

	/*	PRIVATE FUNCTIONS 	*/

	private function fetch_res($prep)
	{
		$ret = array();
		while ($res = $prep->fetch(PDO::FETCH_ASSOC))
			$ret[] = $res;
		return $ret;
	}

	private function run_q($q, $vars = null)
	{
		$prep = $this->_con->prepare($q);
		$prep->execute($vars);
		return $prep;
	}

	public function count_videos()
	{
		$q = "SELECT `id` FROM `video` WHERE 1";
		$p = $this->run_q($q);
		return $this->fetch_res($p);
	}
}

?>