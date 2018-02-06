<?php

session_start();

date_default_timezone_set('Europe/London');

define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('INCLUDE_META', ROOT . '/resources/page/components/head/meta.php');
define('INCLUDE_STYLE', ROOT . '/resources/page/components/head/stylesheets.php');
define('INCLUDE_SCRIPTS', ROOT . '/resources/page/components/head/scripts.php');
define('INCLUDE_HEADER', ROOT . '/resources/page/components/header.php');
define('INCLUDE_FOOTER', ROOT . '/resources/page/components/footer.php');

require (ROOT . '/resources/page/user/user.php');

?>