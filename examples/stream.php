<?php
/**
 * Created by PhpStorm.
 * User: neoteknic
 * Date: 01/02/2018
 * Time: 21:34
 */
require_once 'common.php';

$stream = new \Phue\HueStream($hueHost, $hueUsername);
$stream->setLight(3,[255,0,0])
	->setLight(11,[255,0,0])
	->stream()->close();


