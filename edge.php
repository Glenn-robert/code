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
    protected $ignoreFile = array(
        '.',
        '..',
        '.git',
        '.idea',
        'README.md',
        'edge.php',
        'main.php',
        'package',
    );

    function __construct()
    {
        $this->generatePackage();
    }

    public function generatePackage()
    {
        $packages = array();
        $files = scandir('.');
        foreach ($files as $file) {
            if (!in_array($file, $this->ignoreFile)) {
                $packages[$file] = md5_file('./' . $file);
            }
        }
        $ret = file_put_contents('./package', json_encode($packages));
        echo $ret ? 'Package Generate Success' : 'Package Generate Failed';
    }
}

$edge = new edge();