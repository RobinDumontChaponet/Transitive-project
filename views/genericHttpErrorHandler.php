<?php

$view->setTitle(http_response_code());
$view->importStylesheet('style/genericHttpErrorHandler.css');

$view->addContent(function ($data) {
?>
<main role="main" id="main">
	<h1><?= http_response_code() ?></h1>
	<p><?= Transitive\Utils\HttpRequest::http_response_message() ?></p>
</main>
<?php
});
