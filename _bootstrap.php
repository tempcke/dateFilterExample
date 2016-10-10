<?php

define('TESTS_DIR', dirname(__FILE__));

$composer_autoloader = realpath(TESTS_DIR . "/../vendor/autoload.php");

if (file_exists($composer_autoloader)) {
  include_once $composer_autoloader;
}
else {
  $projectBaseDir = realpath(substr(__DIR__.'/',0,strrpos(__DIR__.'/','/vendor/')));
  $composer_autoloader = $projectBaseDir.'/vendor/autoload.php';
  if (file_exists($composer_autoloader)) {
    include_once $composer_autoloader;
  }
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);