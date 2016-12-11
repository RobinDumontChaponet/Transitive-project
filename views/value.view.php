<?php

$view->setTitle('Value');
$view->addMetaTag('author', 'r-dc');
$view->addStyle('body {background:lightgrey}');

$view->content = $view->getData('value');
