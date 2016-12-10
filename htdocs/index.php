<?php

namespace Transitive;

require_once __DIR__.'/../vendor/autoload.php';

use Transitive\Core\FrontController as FrontController;
use Transitive\Core\Route as Route;

set_include_path(__DIR__.'/../includes');
require 'conf.inc.php';

$timed = Utils\Optimization::newTimer();

$transit = new FrontController();

// $transit->obClean = false; // do not ob_get_clean to FrontController->obContent.

// TEMPORARY ROUTER. FOR TESTING PURPOSES !
$transit->addRouter(new Core\Router(array(
	'index'    => new Route('index',    PRESENTERS.'index.presenter.php',    VIEWS.'index.view.php'),
	'redirect' => new Route('redirect', PRESENTERS.'index.presenter.php',    VIEWS.'index.view.php'),
	'example0' => new Route('example0', PRESENTERS.'example0.presenter.php', VIEWS.'example0.view.php'),
	'example1' => new Route('example1', PRESENTERS.'example1.presenter.php', VIEWS.'example1.view.php'),
	'example2' => new Route('example2', PRESENTERS.'example2.presenter.php', VIEWS.'example2.view.php'),
	'nothing'  => new Route('nothing',  PRESENTERS.'none.presenter.php',     VIEWS.'none.view.php'),
	'empty1'   => new Route('',         PRESENTERS.'none.presenter.php',     ''),
	'empty2'   => new Route('',         '',                    ''),
	'empty3'   => new Route('',         ''),
	'same'     => new Route('same',     PRESENTERS.'index.presenter.php')
)));
// TEMPORARY ROUTER. FOR TESTING PURPOSES !

$transit->execute(@$_GET['requ']);

$transit->layout = function ($transit) {
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

// echo $transit->getObContent(); // presenter & view output buffer.
