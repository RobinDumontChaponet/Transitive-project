<?php

use Transitive\Front;
use Transitive\Core\Route;

require __DIR__.'/../vendor/autoload.php';

$front = new Front\WebFront();
//$front->obClean = false; // do not ob_get_clean to FrontController->obContent.

/*
$front->addRouter(new Front\ListRegexRouter([
	'sitemap' => new Route(PRESENTERS.'sitemap.php', VIEWS.'sitemap.php', null, ['binder' => $front])
]));
*/
$front->addRouter(new Front\PathRouter(dirname(dirname(__FILE__)).'/presenters', dirname(dirname(__FILE__)).'/views'));

$request = @$_GET['request'];
$front->execute($request ?? 'index');

// Set page layout that wrap around our views
$front->setLayoutContent(function ($data) use ($request) {
    $request = $request ?? '';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<?= $data['view']->getMetas(); ?>
<?= $data['view']->getTitle('{{projectName}}'); ?>
<base href="<?= ($self = null == dirname($_SERVER['PHP_SELF'])) ? '/' : $self.'/'; ?>" />
<?= $data['view']->getStyles(); ?>
<?= $data['view']->getScripts(); ?>
</head>
<body lang="fr">
	<header>
		<h1><a href="<?= dirname($_SERVER['PHP_SELF']); ?>" accesskey="1">{{projectName}}</a></h1>
	</header>
	<div id="wrapper">
	<?php
    if($data['view']->hasContent('html'))
        echo $data['view']->getContent('html');
    else
        echo $data['view'];
    ?>
	</div>
	<footer>
		<p>Let's code…</p>
	</footer>
</body>
</html>
<?php
});

echo $front;

//echo $front->getObContent();
