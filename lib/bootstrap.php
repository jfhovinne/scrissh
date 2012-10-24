<?php
/**
 * Load dependencies and common functions
 * Display usage if required parameters are missing
 * Parse configuration file(s)
 * @package scrissh
 */
use Symfony\Component\Yaml\Parser;
set_include_path(get_include_path() . PATH_SEPARATOR . SCRISSH_ROOT . '/lib');
set_include_path(get_include_path() . PATH_SEPARATOR . SCRISSH_ROOT . '/vendor/phpseclib');

require_once('common.php');

if($argc == 1) usage();

// Poor man's autoloader
foreach (glob(SCRISSH_ROOT . '/vendor/Symfony/Component/Yaml/*.php') as $filename) {
  require_once($filename);
}

// Parse config
$yaml = new Parser();
$config = $yaml->parse(file_get_contents(SCRISSH_ROOT . '/' . $argv[1]));
