<?php
require_once(dirname(__FILE__).'/vendor/autoload.php');
$parser = new PHPParser\Parser('SELECT * FROM ps_user');
use PHPParser\Tools\String;

echo String::getBetween('SELECT','s','l');