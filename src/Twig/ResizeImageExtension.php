<?php

/**
 * The Custom Twig extension class.
 *
 * Contain generic method for visitors.
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.1.0
 * @package    App
 * @subpackage App\Controller
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */

namespace App\Twig;

use App\Util\ImageManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ResizeImageExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [new TwigFilter('resizeTo', [$this, 'resizeTo'])];
    }

    public function resizeTo($filename, $s)
    {
        $imageSizes       = ImageManager::registersSizes();
        $dirname          = pathinfo($filename, PATHINFO_DIRNAME);
        $extension        = pathinfo($filename, PATHINFO_EXTENSION);
        $originalFilename = pathinfo($filename, PATHINFO_FILENAME);
        if (!empty($imageSizes[$s])) {
            $size = $imageSizes[$s];
            if (!empty($size['height']) && !empty($size['width'])) {
                $filename = $dirname . '/' . $originalFilename . '-' . $size['width'] . 'x' . $size['height'] . '.' . $extension;
            }
        }

        return $filename;
    }

    public function getName()
    {
        return 'resize_image_extension';
    }

}
