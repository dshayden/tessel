<?php

namespace SplendidToad\Tessel;

use claviska\SimpleImage;

/**
 * Class TesselImage
 * @package SplendidToad\Tessel
 */
class TesselImage extends SimpleImage
{
    private $x = 0;
    private $y = 0;
    private $z = 0;
    private $alpha = 1;
    private $scale = 1;
    private $rotation = 0;

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getZ()
    {
        return $this->z;
    }

    /**
     * @param mixed $z
     */
    public function setZ($z)
    {
        $this->z = $z;
    }

    /**
     * @return mixed
     */
    public function getAlpha()
    {
        return $this->alpha;
    }

    /**
     * @param mixed $alpha
     */
    public function setAlpha($alpha)
    {
        $this->alpha = $alpha;
    }


    /**
     * @return mixed
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * @param mixed $scale
     */
    public function setScale($scale)
    {
        $this->scale = $scale;
    }

    /**
     * @return mixed
     */
    public function getRotation()
    {
        return $this->rotation;
    }

    /**
     * @param mixed $rotation
     */
    public function setRotation($rotation)
    {
        $this->rotation = $rotation;
    }

    public function overlay($overlay, $anchor = 'center', $opacity = 1, $xOffset = 0, $yOffset = 0) {
        // Load overlay image as necessary
        if(!($overlay instanceof TesselImage) && !($overlay instanceof SimpleImage)) {
            $overlay = new TesselImage($overlay);
        }

        parent::overlay($overlay, $anchor, $opacity, $xOffset, $yOffset);

    }



}