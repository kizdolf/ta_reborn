<?php 


/**
* Tables de transition bool/int => char/string
*/
class tables
{
	static private $_rights = array(
		0 => "Full Admin"
		,1 => "Admin Classique"
		,2 => "Modificateur"
		,3 => "Voyeur"	);

	static private $_erros = array(
		0 => "Rank integer does not exist. Error code : tables0");

	static private $_alerts = array(
		 0 => "<div class='alert alert-danger'>"
		,1 => "<div class='alert alert-success'>"
		,2 => "<div class='alert alert-info'>");

	public function int_to_rank($int)
	{
		if (!array_key_exists($int, self::$_rights))
			return self::$_erros[0];
		return self::$_rights[$int];
	}

	public function get_to_message($get){
		$message = "";
		if (isset($get['wrong'])) {
			$message .= self::$_alerts[0]."ERREUR!! Le programme recontre une erreur lors de : <p style='font-weight: bold'>".$get['wrong']."</p></div>";
		}
		if (isset($get['no'])) {
			$message .= self::$_alerts[0]."Vous n'avez pas les droits pour ça.</div>";
		}
		if (isset($get['done'])){
			$message .= self::$_alerts[1].$get['done'] . " effectué! </div>";
		}
		if (isset($get['add'])) {
			$message .= self::$_alerts[1].$get['add'] . " ". $get['n'] ." ajouté! </div>";
		}
		if (isset($get['del'])) {
			$message .= self::$_alerts[2].$get['del'] . " ". $get['n'] ." supprimé </div>";
		}
		return $message;
	}

}

?>