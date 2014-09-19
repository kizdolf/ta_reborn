<?php 
/*		AUTO LOADER DE CLASSES */
spl_autoload_register(function ($class) {
	include __DIR__.'/../classes/' . $class . '_class.php';
});

require_once('admin_functions.php');

/* Génération de toutes les classes que c'est moi qui les ai faites.*/

$bdd = new tapdo();
$log = new log();
// $tables = new tables();

echo "<pre>";
print_r(tables::int_to_rank(2));
echo "</pre>";
?>