<?php

namespace Transitive;

/*
 * Architecture-related
 */
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('SELF', (dirname($_SERVER['PHP_SELF']) == '/' ? '' : dirname($_SERVER['PHP_SELF'])));
define('CONTROLLERS_INC', ROOT_PATH.'/controllers/');
define('MODELS_INC', ROOT_PATH.'/models/');
define('VIEWS_INC', ROOT_PATH.'/views/');
define('DATA_PATH', ROOT_PATH.'/data/');
define('WEB_DATA', SELF.'data/');

/*
if(class_exists('Transitive\Core\FrontController')) {
    Core\FrontController::$presenterIncludePath = ROOT_PATH.'/presenters/';
    Core\FrontController::$viewIncludePath = ROOT_PATH.'/views/';
}
*/

/*
 * Database
 */
/*
if(class_exists('Transitive\Utils\Database')) {
    Utils\Database::addDatabase('data', new Utils\Database('dbName', 'dbUser', 'dbPassword')); // Add database configuration to pool. The connection is established only later when Database::getInstanceById is called.
}
*/

/*
 * Locales
 */
setlocale(LC_ALL, 'fr_FR.utf8', 'fr', 'fr_FR', 'fr_FR@euro', 'fr-FR', 'fra');

/*
 * Debugging
 */
// Console.log
define('JS_DEBUG', 'true'); // send (to server), true (display in client console) or false_
// @TODO
