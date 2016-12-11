<?php

$view->setTitle('test');
$view->addMetaTag('author', 'r-dc');
// $view->importScript('url');
$view->addScript('alert("test")');
$view->addStyle('body {background:lightgrey}');
$view->linkStylesheet('style/animations.css');

$view->content = function ($data) { ?>

Content can be a string (or other php types) or a closure (with closures, code highlighting works for html content and php is a great templating langage in itself, but of course any template engine can be used).
Content can be in an array too, see <a href="example1">example1</a>.

<?php
};
