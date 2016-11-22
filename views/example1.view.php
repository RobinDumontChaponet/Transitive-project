<?php

$view->setTitle('test');
$view->addMetaTag('author', 'r-dc');
$view->importScript('url');
//$view->addScript('alert("test")');
$view->addStyle('body {background:lightgrey}');
$view->linkStylesheet('style/animations.css');

$view->content = array('Content can be in an array.', function (&$data) { ?>

<p>
Array items can then be displayed independently by their key using <code>View->displayContent($arrayKey)</code>
</p>

<?php

echo $data['test'];

});

?>