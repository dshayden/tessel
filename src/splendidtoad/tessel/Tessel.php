<?php

namespace SplendidToad\Tessel;

/**
 * Class Tessel
 * @package SplendidToad\Tessel
 */
// TODO define an interface that can be implemented for various types of repeats? (Vs. adding/tweaking images manually)
// TODO use a term other than 'canvas' to avoid confusion with HTML terminology

class Tessel
{
    /**
     * @var string $transPixelData A Data URI for a 1x1 transparent pixel PNG; used to instantiate the empty canvas background
     */
    public static $transPixelData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQI12NgAAIAAAUAAeImBZsAAAAASUVORK5CYII=';
    /**
     * @var TesselImage $canvas The master image on which pattern elements are to be placed.
     */
    private $canvas;
    /**
     * @var int $w The canvas width.
     */
    private $w;
    /**
     * @var int $h The canvas height.
     */
    private $h;

    /**
     * @var string The canvas background color as a hex string, IE "FFFFFF"
     * TODO this actually probably supports any format supported by SimpleImage.
     */
    private $bgColor;

    /**
     * @var float The canvas background alpha transparency, where 0.0 = totally transparent and 1.0 = totally opaque.
     */
    private $bgAlpha;
    //TODO support for BG image in addition to/instead of BG color?

    /**
     * @var array $images an array of TesselImage objects
     */
    private $images = [];

    /**
     * Tessel constructor.
     * @param int $w The canvas width.
     * @param int $h The canvas height.
     * @param string $bgColor The canvas background color.
     * @param float $bgAlpha The canvas background alpha transparency.
     */
    public function __construct($w = 600, $h = 600, $bgColor = 'ffffff', $bgAlpha = 1.0)
    {

        $this->setW($w);
        $this->setH($h);
        $this->setBgColor($bgColor);
        $this->setBgAlpha($bgAlpha);

    }


    /**
     * Add an image to the canvas.
     * @param TesselImage $image
     * TODO 'addImageFromFile' method that takes path/offset/x/y/alpha as args
     * TODO store images with assoc array key for easier reference?
     */
    public function addImage($image)
    {

        $this->images[] = $image;
    }

    /**
     * Get the canvas width.
     * @return int
     */
    public function getW()
    {
        return $this->w;
    }

    /**
     * Set the canvas width.
     * @param int $w the new canvas width
     */
    public function setW($w)
    {
        $this->w = $w;
    }

    /**
     * Get the canvas height.
     * @return int
     */
    public function getH()
    {
        return $this->h;
    }

    /**
     * Set the canvas height.
     * @param int $h the new canvas height
     */
    public function setH($h)
    {
        $this->h = $h;
    }

    /**
     * Get the canvas background color.
     * @return mixed
     */
    public function getBgColor()
    {
        return $this->bgColor;
    }

    /**
     * Set the canvas background color.
     * @param mixed $bgColor
     */
    public function setBgColor($bgColor)
    {
        $this->bgColor = $bgColor;
    }

    /**
     * Get the canvas background transparency
     * @return float
     */
    public function getBgAlpha(): float
    {
        return $this->bgAlpha;
    }

    /**
     * Set the canvas background transparency
     * @param float $bgAlpha the new transparency (where 0.0 = completely transparent and 1.0 = completely opaque)
     */
    public function setBgAlpha(float $bgAlpha)
    {
        $this->bgAlpha = $bgAlpha;
    }

    /**
     * Process all images that have been added to the canvas to create the final repeating pattern
     */
    private function renderImages()
    {

        $this->canvas = new TesselImage(Tessel::$transPixelData);

        // Make canvas area 3x3 of designated width and height so we can simulate the pattern repeat
        // across top/bottom, left/right and then crop to the final image
        $origW = $this->getW();
        $origH = $this->getH();
        $this->canvas->resize(3 * $origW, 3 * $origH);

        $this->canvas->fill($this->bgColor);

        // TODO sort the images by Z index

        foreach ($this->images as $image) {
            for ($y = 0; $y < 3; $y++) {
                for ($x = 0; $x < 3; $x++) {
                    $xCoord = ($x * $this->getW()) + $image->getX();
                    $yCoord = ($y * $this->getH()) + $image->getY();
                    //TODO - calculate image bounds before actually doing the overlay; would more efficient for larger images
                    $this->canvas->overlay($image, 'top left', $image->getAlpha(), $xCoord, $yCoord);
                }
            }
        }
        $cx1 = $origW;
        $cy1 = $origH;
        $cx2 = ($origW * 2);
        $cy2 = ($origH * 2);

        $this->canvas->crop($cx1, $cy1, $cx2, $cy2);
    }

    /**
     * Writes the image to a file.
     * @param $file
     * @param string $mimeType The image format to output as a mime type (defaults to the original mime type).
     * @param int $quality Image quality as a percentage (default 100).
     * @return TesselImage
     */
    public function toFile($file, $mimeType = null, $quality = 100)
    {
        $this->renderImages();
        return $this->canvas->toFile($file, $mimeType, $quality);
    }
}