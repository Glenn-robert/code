<?php

class information
{
    function __construct()
    {
        echo php_uname('s') . PHP_EOL;
        echo php_uname('n') . PHP_EOL;
        echo php_uname('r') . PHP_EOL;
        echo php_uname('m') . PHP_EOL;
    }
}

$information = new information();
