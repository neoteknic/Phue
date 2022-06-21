<?php
/**
 * Example: Delete test scene.
 *
 * Usage: HUE_HOST=127.0.0.1 HUE_USERNAME=1234567890 php delete-scene.php
 */
require_once 'common.php';

$client = new \Phue\Client($hueHost, $hueUsername);

echo 'Deleting scene phue-test:', "\n";

$scenes = $client->getScenes();
$scenes['phue-test']->delete();

echo "Done.", "\n";
