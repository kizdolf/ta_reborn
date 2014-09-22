<?php
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});

/* founded here : http://stackoverflow.com/questions/6054033/pretty-printing-json-with-php */
function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}


function get_all_data_bdd($bdd){

	$q = "SELECT * FROM `partner` WHERE 1";
	$q1 = "SELECT * FROM `artiste` WHERE 1";
	$q2 = "SELECT * FROM `draft` WHERE 1";
	$q3 = "SELECT * FROM `quartier` WHERE 1";
	$q4 = "SELECT * FROM `style` WHERE 1";
	$q5 = "SELECT * FROM `text` WHERE 1";
	$q6 = "SELECT * FROM `user` WHERE 1";
	$q7 = "SELECT * FROM `video`  WHERE 1";
	$final = array();
	$bdd->begin();
	$s = $bdd->prep($q);
	$s->execute();
	$ret = array();
	while ($res = $s->fetch(PDO::FETCH_ASSOC))
		$ret[] = $res;
	$final["partner"] = $ret;

	$s = $bdd->prep($q1);
	$s->execute();
	$ret = array();
	while ($res = $s->fetch(PDO::FETCH_ASSOC))
		$ret[] = $res;
	$final["artiste"] = $ret;

	$s = $bdd->prep($q2);
	$s->execute();
	$ret = array();
	while ($res = $s->fetch(PDO::FETCH_ASSOC))
		$ret[] = $res;
	$final["draft"] = $ret;

	$s = $bdd->prep($q3);
	$s->execute();
	$ret = array();
	while ($res = $s->fetch(PDO::FETCH_ASSOC))
		$ret[] = $res;
	$final["quartier"] = $ret;

	$s = $bdd->prep($q4);
	$s->execute();
	$ret = array();
	while ($res = $s->fetch(PDO::FETCH_ASSOC))
		$ret[] = $res;
	$final["style"] = $ret;

	$s = $bdd->prep($q5);
	$s->execute();
	$ret = array();
	while ($res = $s->fetch(PDO::FETCH_ASSOC))
		$ret[] = $res;
	$final["text"] = $ret;

	$s = $bdd->prep($q6);
	$s->execute();
	$ret = array();
	while ($res = $s->fetch(PDO::FETCH_ASSOC))
		$ret[] = $res;
	$final["user"] = $ret;

	$s = $bdd->prep($q7);
	$s->execute();
	$ret = array();
	while ($res = $s->fetch(PDO::FETCH_ASSOC))
		$ret[] = $res;
	$final["video"] = $ret;

	$bdd->commit();
	
	$json = prettyPrint(json_encode($final));
	echo "ICI";
	
	if (file_exists("dump_data.json")) {
		unlink("dump_data.json");
	}
	file_put_contents("dump_data.json", $json);
	chmod("dump_data.json", 0777);
	return true;
}


function get_from_dir($handle, $path){
	$files = array();
	while ($entry = readdir($handle)){
		if($entry!= "." && $entry != ".." && strpos($entry, "min") === false)
			$files[] = $path ."/" . $entry;
	}
	return $files;
}
$log = new log();

if (!$log->is_logued()) {
	header('Location: login.php?case=disconnect');
}else{

	$bdd = new tapdo();

	if (isset($_POST['id'])) {
		$artiste = $bdd->get_one_artiste("id", $_POST['id']);
		$path = $artiste['path_pics'];
		print_r(json_encode(get_from_dir(opendir($path), $path)));
	}elseif (isset($_POST['del'])) {
		echo unlink($_POST['src']);
	}elseif(isset($_POST['galerie'])){
		$path = $_POST['galerie'];
		print_r(json_encode(get_from_dir(opendir($path), $path)));
	}elseif(isset($_POST['dump'])){
		get_all_data_bdd($bdd);
		return true;
	}
}
?>