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
$transit->setLayoutContent(function ($data) use ($request) {
    $request = $request ?? '';
?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 7]>   <html class="lt-ie9 lt-ie8" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 8]>   <html class="lt-ie9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if gt IE 8]><html class="get-ie9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<head>
<meta charset="UTF-8">
<?= $data['view']->getMetas(); ?>
<?= $data['view']->getTitle('{{projectName}}'); ?>
<base href="<?= ($self = null == dirname($_SERVER['PHP_SELF'])) ? '/' : $self.'/'; ?>" />
<!--[if IE]><link rel="shortcut icon" href="style/favicon-32.ico"><![endif]-->
<!--
<link rel="icon" href="style/favicon-96.png">
<meta name="msapplication-TileColor" content="#FFF">
<meta name="msapplication-TileImage" content="style/favicon-144.png">
<link rel="apple-touch-icon" href="style/favicon-152.png">
-->
<?= $data['view']->getStyles(); ?>
<?= $data['view']->getScripts(); ?>
</head>
<body lang="fr" class="no-js">
	<header>
		<h1><a href="<?= dirname($_SERVER['PHP_SELF']); ?>" accesskey="1">{{projectName}}</h1>
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
	<script>
	document.body.classList.remove('no-js');
	</script>
</body>
</html>

<?php
});

echo $front;

//echo $transit->getObContent();
