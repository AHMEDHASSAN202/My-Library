<?php
/**
 * Created by PhpStorm.
 * User: AHMED
 */

require 'Functions.php';

$dir = __DIR__ . DIRECTORY_SEPARATOR;

_addLogoToImage($dir . 'original', $dir . 'logo.png', $dir . 'front', 100, 100, 10, 10, true);

_makeThumbnails($dir . 'front', $dir . 'thumbnails', 210, 120, null, true);