<?php

namespace whallysson;

use CodeBlog\Thumb\Thumb as Cropper;

/**
 * Class Thumb
 *
 * @author Whallysson Avelino <https://github.com/whallysson>
 * @package Source\Support
 */
class Thumb
{

    /** @var Cropper */
    private $cropper;

    /** @var string */
    private $uploads;

    /**
     * Thumb constructor.
     */
    public function __construct()
    {
        $this->cropper = new Cropper(CONF_IMAGE_CACHE, null, CONF_IMAGE_QUALITY['jpg'], CONF_IMAGE_QUALITY['png']);
        $this->uploads = CONF_UPLOAD_DIR;
    }

    /**
     * @param string $image
     * @param int $width
     * @param int|null $height
     * @param int|null $zc
     * @return string
     */
    public function make($image, $width, $height = null, $zc = null, $imageName = null)
    {
        $sz = $width ? "?w={$width}" : null;
        $sz .= empty($sz) && $height ? "?h={$width}" : !empty($sz) && $height ? "&h={$height}" : null;
        $sz .= !empty($zc) ? "&zc={$zc}" : null;

        if (file_exists("{$this->uploads}/{$image}") && is_file("{$this->uploads}/{$image}")) {
            return $this->cropper->imgCreate("{$this->uploads}/{$image}{$sz}", $imageName);
        }

        return $this->cropper->imgCreate("{$image}{$sz}", $imageName);
    }

    /**
     * @param string|null $image
     */
    public function flush($image = null)
    {
        if ($image) {
            $this->cropper->flush("{$this->uploads}/{$image}");
            return;
        }

        $this->cropper->flush();
        return;
    }

    /**
     * @return Cropper
     */
    public function cropper()
    {
        return $this->cropper;
    }

}
