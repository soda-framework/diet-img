<?php

namespace Soda\DietImage;

use League\Glide\Server;

class ImageProcessor
{
    protected $imageServer;
    protected $baseUrl;

    public function __construct(Server $imageServer)
    {
        $this->imageServer = $imageServer;
    }

    public function optimize($imgUrl, $manipulations)
    {
        // If string is supplied, assume it is a preset
        if ($manipulations && ! is_array($manipulations)) {
            $manipulations = ['p' => $manipulations];
        }

        return $this->imageServer->getBaseUrl() . '/' . $this->imageServer->makeImage($imgUrl, $manipulations);
    }
}
