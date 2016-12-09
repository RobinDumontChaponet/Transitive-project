<?php

namespace Transitive;

require_once __DIR__.'/../vendor/autoload.php';

use Transitive\Core\FrontController as FrontController;

set_include_path(__DIR__.'/../includes');
require 'conf.inc.php';


$timed = Utils\Optimization::newTimer();

$transit = new FrontController();

// $transit->obClean = false; // do not ob_get_clean to $transit->obContent.

// TEMPORARY ROUTER. FOR TESTING PURPOSES !
$transit->addRouter(new Core\Router(array(
	'index'    => new Core\Route('index',    PRESENTERS.'index',    VIEWS.'index'),
	'redirect' => new Core\Route('redirect', PRESENTERS.'index',    VIEWS.'index'),
	'example0' => new Core\Route('example0', PRESENTERS.'example0', VIEWS.'example0'),
	'example1' => new Core\Route('example1', PRESENTERS.'example1', VIEWS.'example1'),
	'example2' => new Core\Route('example2', PRESENTERS.'example2', VIEWS.'example2'),
	'nothing'  => new Core\Route('nothing',  PRESENTERS.'none',     VIEWS.'none'),
	'empty1'   => new Core\Route('',         PRESENTERS.'none',     ''),
	'empty2'   => new Core\Route('',         '',                    '')
)));
// TEMPORARY ROUTER. FOR TESTING PURPOSES !

$transit->execute(@$_GET['requ']);

$transit->layout = function($transit) {
	global $timed;
?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 7]>   <html class="lt-ie9 lt-ie8" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 8]>   <html class="lt-ie9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if gt IE 8]><html class="get-ie9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<head>
<meta charset="UTF-8">
<?php $transit->printMetas() ?>
<?php $transit->printTitle('TITLE') ?>
<base href="<?php echo (constant('SELF') == null) ? '/' : constant('SELF').'/'; ?>" />
<!--[if IE]><link rel="shortcut icon" href="style/favicon-32.ico"><![endif]-->
<link rel="icon" href="style/favicon-96.png">
<meta name="msapplication-TileColor" content="#FFF">
<meta name="msapplication-TileImage" content="style/favicon-144.png">
<link rel="apple-touch-icon" href="style/favicon-152.png">
<link rel="stylesheet" type="text/css" href="style/reset.min.css" />
<?php $transit->printStyles() ?>
<!--[if lt IE 9]><script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<?php $transit->printScripts() ?>
</head>
<body>
	<div id="wrapper">
		<?php $transit->printContent(); ?>
	</div>
	<footer>
	<?php
    $timed->printResult();
    Utils\Optimization::listIncludes();
    ?>
	</footer>
</body>
</html>

<?php };

$transit->print();

// echo $transit->getObContent();
