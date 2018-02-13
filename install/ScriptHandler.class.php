<?php

namespace Transitive\Project\Install;

use Composer\Script\Event;
use Composer\IO\IOInterface;

class ScriptHandler
{
    private static function _setVars(Event $event = null)
    {
        if(isset($event) && !isset(self::$io))
            self::$io = $event->getIO();

        if(!isset(self::$projectName)) {
            self::$path = realpath('.');
            self::$projectName = basename(self::$path);
        }
    }

    private static $io;
    private static $path;
    private static $projectName;

    private static function write($messages, int $verbosity = IOInterface::NORMAL)
    {
        self::$io->write($messages, true, $verbosity);
    }

    private static function _copyDirectory(string $source, string $dest) {
        $dir = opendir($source);
        @mkdir($dest);
        while(false !== ($file = readdir($dir))) {
            if ('.' != $file && '..' != $file) {
                if (is_dir($source.'/'.$file))
                    self::_copyDirectory($source.'/'.$file, $dest.'/'.$file);
                elseif(!file_exists($dest.'/'.$file)) {
                    copy($source.'/'.$file, $dest.'/'.$file);
                    self::write(' + copying "'.$dest.'/'.$file.'"');
                }
            }
        }
        closedir($dir);
    }

    private static function rename(array $files, string $in = null) {
        self::_setVars();
        $in = $in ?? self::$path;

        foreach($files as $from => $to) {
            $from = $in.'/'.$from;
            $to = $in.'/'.$to;

            if(is_file($from) && !file_exists($to)) {
                rename($from, $to);
                self::write(' * renaming "'.$from.'" to "'.$to.'"');
            }
        }
    }

    private static function setFiles(array $files, string $from = null) {
        self::_setVars();
        $from = $from ?? self::$path;

        foreach($files as $dest) {
            if(is_array($dest)) {
                $source = $from.'/'.$dest[0];
                $dest = $dest[1];
            }  else
                $source = $from.'/'.$dest;

            if(is_file($source) && !file_exists($dest)) {
                copy($source, $dest);
                self::write(' + copying "'.$source.'" as "'.$dest.'"');
            } elseif(is_dir($source))
                self::_copyDirectory($source, $dest);
        }
    }

    private static function template(string $file, array $replaces) {
        file_put_contents(
            $file,
            strtr(
                file_get_contents($file),
                $replaces
            )
        );
        self::write(' * mixing words in "'.$file.'"');
    }

    private static function deleteDirectory(string $path) {
        if (empty($path))
            return false;

        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file)
            if(is_dir($path.'/'.$file))
                self::deleteDirectory($path.'/'.$file);
            else
                unlink($path.'/'.$file);

        return @rmdir($path);
    }

    private static function remove(string $path) {
        if(is_file($path)) {
            if(unlink($path))
                self::write(' - deleting "'.$path.'"');
        } elseif(is_dir($path))
            if(self::deleteDirectory($path))
                self::write(' - deleting "'.$path.'/"');
    }

    public static function create(Event $event)
    {
        self::_setVars($event);

        self::remove('composer.json');
        self::remove('README.md');
        self::remove('LICENSE');

        self::setFiles([
            'composer.json',
            'README.md',
            'CHANGELOG.md',
        ], dirname(__FILE__));

        self::rename(['htdocs/htaccess' => 'htdocs/.htaccess']);
    }

    public static function install(Event $event)
    {
        self::_setVars($event);
        if(self::$io->isInteractive()) {
            if(self::$io->askConfirmation(PHP_EOL.'Should we create files ? [YES, no]', true))
                self::create($event);

            if(self::$io->askConfirmation(PHP_EOL.'Should we replace occurences of "{{projectName}}" with "'.self::$projectName.'" in the project files ? [YES, no]', true))
                self::setup($event);
        }

        self::clean();
    }

    public static function setup(Event $event)
    {
        self::_setVars($event);

        $replaces = ['{{projectName}}' => self::$projectName];

        self::template('composer.json', $replaces);
        self::template('README.md', $replaces);
        self::template('htdocs/index.php', $replaces);

        self::clean();
    }

    private static function clean()
    {
        if(!is_dir('install'))
            return;

        self::_setVars();
        self::remove('install');

        self::write(PHP_EOL.'Welcome to Summoner\'s rif… erm, no ! Welcome to your new Transitive project "'.self::$projectName.'". Now… get to work !'.PHP_EOL);
    }
}
