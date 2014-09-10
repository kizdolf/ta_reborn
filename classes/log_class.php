<?php 
spl_autoload_register(function ($class) {
	include __DIR__.'/' . $class . '_class.php';
});

/**
* Class pour gérer le login sur l'admin.
*/
class log
{
	private $_bdd; /* ctapdo class, gère la bdd.*/

	function __construct()
	{
		$this->_bdd = new tapdo();
	}

	public function is_logued()
	{
		if (isset($_COOKIE['session']))
		{
			$to_verif = unserialize($_COOKIE['session']);
			if ($to_verif['ip'] != $_SERVER["REMOTE_ADDR"])
				return false;
			if ($this->_bdd->user_exist($to_verif['user'], $to_verif['hash'])) {
				return true;
			}
		}
		return false;
	}

	public function is_user($login, $password)
	{
		if ($this->_bdd->user_exist($login, hash('whirlpool', $password))) {
				return true;
		}
		return false;
	}

	public function set_session($login, $password, $trust)
	{
		$to_save = array("ip" => $_SERVER["REMOTE_ADDR"]
				,"user" => $login
				,"hash" => hash('whirlpool', $password));
		$to_save = serialize($to_save);
		if ($trust) {
			setcookie("session", $to_save, time()+(3600 * 24 * 7));
		}
		else
			setcookie("session", $to_save, time()+3600);
		return $this->_bdd->up_user_visit($login);
	}

	public function unset_session()
	{
		setcookie("session", "", time()-3600);
	}

}

?>