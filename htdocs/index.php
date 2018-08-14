<?php

use Transitive\Web;
use Transitive\Routing;
use Transitive\Utils;

if((@require __DIR__.'/../vendor/autoload.php') === false) {
	echo 'Dependencies are not installed, please run "composer install" first';
	exit(1);
}

$timed = Utils\Optimization::newTimer();

$front = new Web\Front();

$front->addRouter(new Routing\PathRouter(dirname(dirname(__FILE__)).'/presenters', dirname(dirname(__FILE__)).'/views'));

$front->obClean = false; // do not ob_get_clean to FrontController->obContent.

$front->setLayoutContent(function ($data) {
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
