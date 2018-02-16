<?php

require __DIR__.'/../vendor/autoload.php';

// Transitive\Utils\Sessions::start();

$front = new Transitive\Core\WebFront();

// $transit->obClean = false;

/*
$transit->addRouter(new Core\ListRegexRouter([
    'articles/(?\'id\'\d*)'              => new Route(PRESENTERS.'article.php',         VIEWS.'article.php'),
    'tags/(?\'nId\'[^\/]*)/articles$'    => new Route(PRESENTERS.'tag-articles.php',    VIEWS.'tag-articles.php'),
    'tags/(?\'nId\'[^\/]*)/description$' => new Route(PRESENTERS.'tag-description.php', VIEWS.'tag-description.php'),
    'tags/(?\'id\'\d*)'                  => new Route(PRESENTERS.'tag.php',             VIEWS.'tag.php'),
]));
*/
$front->addRouter(new Transitive\Core\PathRouter(dirname(dirname(__FILE__)).'/presenters', dirname(dirname(__FILE__)).'/views'));

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

//echo $transit->getObContent();
