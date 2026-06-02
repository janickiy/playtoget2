<?php
defined('PLAYTOGET') || exit('Playtoget: access denied!');

Error_Reporting(0); // set error reporting level
define("DEBUG", 1); 

$cmspaths = array(
    'core' => 'modules/core',
    'engine' => 'modules/engine', // Engines AUTOLOAD folder
    'config' => 'config', // Config
	'templates' => 'templates', // templates
    'libs' => 'libraries', // libraries
    'controllers' => 'engines/controllers', // controllers
    'models' => 'engines/models',
    'views' => 'engines/views'
);

require_once SYS_ROOT . $cmspaths['config'] . '/config_db.php';
require_once SYS_ROOT . $cmspaths['config'] . '/settings.php';
require_once SYS_ROOT . $cmspaths['core'] . '/core.php';
core::init($cmspaths);
core::setTemplate("html/");
core::$main = new DocumentParser();
core::$db = new DBParser($ConfigDB);
core::$user = new User();

// get language
$lang_file = core::pathTo($cmspaths['templates'], 'language/');
// $lang_file .= ((core::getSetting("language")) ? core::getSetting("language") . ".php" : "ru.php");

$lang_file .= "ru.php";

if (file_exists($lang_file)) {
    include $lang_file;
    core::addLanguage($language);
} else
    exit('ERROR: Language file can not load!');
    
?>