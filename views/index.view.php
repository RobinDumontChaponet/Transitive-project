<?php

$view->setTitle('test');
$view->addMetaTag('author', 'r-dc');
// $view->importScript('url');
//$view->addScript('alert("test")');
$view->addStyle('body {background:lightgrey}');
$view->linkStylesheet('style/animations.css');

$view->content = function ($data) { ?>

<article>
	<h1>Seems like it works !</h1>
	<h2>See :</h2>
	<ul>
		<li><a href="example0">example0</a></li>
		<li><a href="example1">example1</a></li>
		<li><a href="example2">example2</a></li>
	</ul>
</article>
<br />

<?php
};
