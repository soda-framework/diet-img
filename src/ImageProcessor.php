<?php

namespace Soda\DietImage;

use Illuminate\Support\Facades\Cache;
use League\Glide\Server;

class ImageProcessor
{
    protected $imageServer;
    protected $baseUrl;

    public function __construct(Server $imageServer)
    {
        $this->imageServer = $imageServer;
    }

    public function optimize($imgUrl, $manipulations = [], $cache = true)
    {
        // If string is supplied, assume it is a preset
        if ($manipulations && !is_array($manipulations)) {
            $manipulations = ['p' => $manipulations];
        }

        return $cache ? Cache::rememberForever($this->getCacheKey($imgUrl, $manipulations), function () use ($imgUrl, $manipulations) {
            return $this->generateImage($imgUrl, $manipulations);
        }) : $this->generateImage($imgUrl, $manipulations);
    }

    protected function getCacheKey($imgUrl, array $manipulations = [])
    {
        return $this->imageServer->getBaseUrl().'/'.$this->imageServer->getCachePath($imgUrl, $manipulations);
    }

    protected function generateImage($imgUrl, $manipulations = [])
    {
        return $this->imageServer->getBaseUrl().'/'.$this->imageServer->makeImage($imgUrl, $manipulations);
    }
}
