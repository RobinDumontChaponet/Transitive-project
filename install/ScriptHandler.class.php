<?php

namespace Transitive\Project\Install;

use Composer\Script\Event;
use Transitive\Core\Install\ScriptHandler as CoreInstall;

function template(string $file, array $replaces) {
    file_put_contents(
        $file,
        strtr(
            file_get_contents($file),
            $replaces
        )
    );
}

function deleteDirectory(string $path) {
    if (empty($path))
        return false;

    $files = array_diff(scandir($path), array('.', '..'));
    foreach ($files as $file)
        if(is_dir($path.'/'.$file))
            deleteDirectory($path.'/'.$file);
        else
            unlink($path.'/'.$file);

    return @rmdir($path);
}

function remove(string $path) {
    if(is_file($path)) {
        if(unlink($path))
            echo ' deleting: ', $path, PHP_EOL;
    } elseif(is_dir($path))
        if(deleteDirectory($path))
            echo ' deleting: ', $path, '/', PHP_EOL;
}

class ScriptHandler
{
    public static function setup(Event $event)
    {
        $projectName = basename(realpath('.'));

        remove('composer.json');
        remove('README.md');
        remove('LICENSE');

        CoreInstall::setFiles([
            'composer.json',
            'README.md',
            'CHANGELOG.md',
        ], 'install');

        $replaces = ['{{projectName}}' => $projectName];

        template('composer.json', $replaces);
        template('README.md', $replaces);

        remove('install');

        echo PHP_EOL, PHP_EOL, 'Welcome to Summoner\' rif… erm, no ! Welcome to your new Transitive project "', $projectName, '". Now… get to work !', PHP_EOL, PHP_EOL;
    }
}
