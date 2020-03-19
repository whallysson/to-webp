<?php

/**
 * UPLOAD
 */
define("CONF_UPLOAD_DIR", "uploads");
define("CONF_UPLOAD_IMAGE_DIR", "images");


/**
 * IMAGES
 */
define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);


/**
 * AUTOLOAD
 */
$baseDir   = dirname(__FILE__);
$libDir    = $baseDir . '/vendor';
$vendorDir = dirname(dirname($baseDir));
$autoload  = '/autoload.php';

if (!class_exists('whallysson\Thumb')) {
    if (file_exists($libDir . $autoload)) {
        require_once $libDir . $autoload;
    } elseif (file_exists($vendorDir . $autoload)) {
        require_once $vendorDir . $autoload;
    } else {
        require_once $baseDir . '/src/Thumb.php';
    }
}

/** Função para gerar imagens */

/**
 * @param string $image
 * @param int $width
 * @param int|null $height
 * @param int|null $zc
 * @return string
 */
function image($image, $width, $height = null, $zc = null, $imageName = null)
{
    if ($image) {
        $newimage = (new \whallysson\Thumb())->make($image, $width, $height, $zc, $imageName);

        /** WEBP */
        $newName = ($imageName ? $imageName : pathinfo(filter_var(mb_strtolower($newimage),
            FILTER_SANITIZE_STRIPPED))['filename']);
        $destination = "{$newName}-{$width}.webp";

        $webp = new \CodeBlog\ToWebP\ToWebP(CONF_UPLOAD_DIR, CONF_UPLOAD_IMAGE_DIR . "/cache");
        $webp->convert($newimage, $destination);

        return (!empty($webp->image_webp) ? $webp->image_webp : $webp->image_original);
    }

    return null;
}
