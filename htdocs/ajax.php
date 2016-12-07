<?php

set_include_path(dirname(__FILE__).'/../includes');
require 'conf.inc.php';

?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 7]>   <html class="lt-ie9 lt-ie8" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 8]>   <html class="lt-ie9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if gt IE 8]><html class="get-ie9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<head>
<meta charset="UTF-8">
<title>Ajax querying</title>
<base href="<?php echo (constant('SELF') == null) ? '/' : constant('SELF').'/'; ?>" />
<!--[if IE]><link rel="shortcut icon" href="style/favicon-32.ico"><![endif]-->
<link rel="icon" href="style/favicon-96.png">
<meta name="msapplication-TileColor" content="#FFF">
<meta name="msapplication-TileImage" content="style/favicon-144.png">
<link rel="apple-touch-icon" href="style/favicon-152.png">
<link rel="stylesheet" type="text/css" href="style/reset.min.css" />
<!--[if lt IE 9]><script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script src="script/polyShims.js"></script>
<script src="script/ajaxView.transit.js"></script>
</head>
<body>
	<div id="wrapper">
		<p>nothing ?</p>
	</div>
	<footer>
		<button onclick="view.get();">query</button>
	</footer>
	<script>
	var view = new View(document.getElementById('wrapper'), function(response){console.log(response)});
	view.src = 'ajaxhelper.php';
	//view.get();
	</script>
</body>
</html>