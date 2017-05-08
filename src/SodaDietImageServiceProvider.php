<?php

namespace Soda\DietImage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class SodaDietImageServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('soda.image', function ($app) {
            $sodaDisk = Storage::disk(config('soda.upload.default'));
            $imageServer = \League\Glide\ServerFactory::create([
                'source'            => $sodaDisk->getDriver(),
                'cache'             => $sodaDisk->getDriver(),
                'cache_path_prefix' => 'cache',
                'driver'            => 'gd',
                'base_url'          => $sodaDisk->url(''),
                'presets'           => [
                    'small'       => [
                        'w'   => 250,
                        'h'   => 250,
                        'fit' => 'max',
                    ],
                    'medium'      => [
                        'w'   => 500,
                        'h'   => 600,
                        'fit' => 'max',
                    ],
                    'large'       => [
                        'w'   => 750,
                        'h'   => 750,
                        'fit' => 'max',
                    ],
                    'crop-small'  => [
                        'w'   => 250,
                        'h'   => 250,
                        'fit' => 'crop',
                    ],
                    'crop-medium' => [
                        'w'   => 500,
                        'h'   => 600,
                        'fit' => 'crop',
                    ],
                    'crop-large'  => [
                        'w'   => 750,
                        'h'   => 750,
                        'fit' => 'crop',
                    ],
                    'loader'      => [
                        'q'    => 80,
                        'blur' => 25,
                    ],
                    'progressive' => [
                        'fm' => 'pjpg',
                    ],
                ],
            ]);

            return new ImageProcessor($imageServer);
        });

        $sodaInstance = app('soda');
        $sodaInstanceClass = get_class($sodaInstance);

        if (method_exists($sodaInstanceClass, 'macro')) {
            $sodaInstanceClass::macro('image', function () {
                return app('soda.image');
            });
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'soda.image',
        ];
    }
}
