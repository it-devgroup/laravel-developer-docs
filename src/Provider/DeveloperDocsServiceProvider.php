<?php

namespace ItDevgroup\LaravelDeveloperDocs\Provider;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use ItDevgroup\LaravelDeveloperDocs\Console\Command\DeveloperDocsPublishCommand;
use ItDevgroup\LaravelDeveloperDocs\DeveloperDocsService;
use ItDevgroup\LaravelDeveloperDocs\DeveloperDocsServiceInterface;

/**
 * Class DeveloperDocsServiceProvider
 * @package ItDevgroup\LaravelDeveloperDocs\Provider
 */
class DeveloperDocsServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->loadCustomConfig();
        $this->loadCustomPublished();
        $this->loadCustomCommands();

        if (!Config::get('developer_docs.enable')) {
            return;
        }

        $this->loadCustomClasses();
        $this->loadFilesystem();
        $this->loadCustomRoutes();
        $this->loadCustomView();
    }

    /**
     * @return void
     */
    private function loadCustomConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/developer_docs.php', 'developer_docs');
    }

    /**
     * @return void
     */
    private function loadCustomCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                DeveloperDocsPublishCommand::class
            );
        }
    }

    /**
     * @return void
     */
    private function loadCustomPublished()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../../config' => base_path('config')
                ],
                'config'
            );
        }
    }

    /**
     * @return void
     */
    private function loadCustomClasses()
    {
        $this->app->singleton(DeveloperDocsServiceInterface::class, DeveloperDocsService::class);
    }

    /**
     * @return void
     */
    private function loadFilesystem()
    {
        Config::set(
            'filesystems.disks.' . DeveloperDocsService::DEFAULT_FILESYSTEM,
            [
                'driver' => 'local',
                'root' => Config::get('developer_docs.files_folder')
            ]
        );
    }

    /**
     * @return void
     */
    private function loadCustomRoutes()
    {
        $option = [
            'prefix' => Config::get('developer_docs.route_prefix'),
            'namespace' => 'ItDevgroup\LaravelDeveloperDocs\Http\Controllers',
        ];

        $controllerPrefix = get_class($this->app) == 'Laravel\Lumen\Application'
            ? 'Lumen' : 'Laravel';

        $this->app->router->group(
            $option,
            function ($router) use ($controllerPrefix) {
                $router->get(
                    '/',
                    [
                        'uses' => $controllerPrefix . 'DeveloperDocsController@page',
                        'as' => 'developer.docs.page',
                    ]
                );
            }
        );

        $this->app->router->group(
            $option,
            function ($router) use ($controllerPrefix) {
                $router->get(
                    '/get',
                    [
                        'uses' => $controllerPrefix . 'DeveloperDocsController@get',
                        'as' => 'developer.docs.get',
                    ]
                );
            }
        );

        $this->app->router->group(
            $option,
            function ($router) use ($controllerPrefix) {
                $router->get(
                    '/image',
                    [
                        'uses' => $controllerPrefix . 'DeveloperDocsController@image',
                        'as' => 'developer.docs.image',
                    ]
                );
            }
        );
    }

    /**
     * @return void
     */
    private function loadCustomView()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'developerDocs');

        View::composer(
            'developerDocs::page',
            function (\Illuminate\Contracts\View\View $view) {
                $view->with(
                    'js',
                    [
                        __DIR__ . '/../../assets/js/chunk-vendors.js',
                        __DIR__ . '/../../assets/js/app.js',
                    ]
                );
                $view->with(
                    'css',
                    [
                        __DIR__ . '/../../assets/css/app.css',
                        __DIR__ . '/../../assets/css/chunk-vendors.css',
                    ]
                );
                $view->with(
                    'routePrefix',
                    Config::get('developer_docs.route_prefix')
                );
                $view->with(
                    'customJs',
                    Config::get('developer_docs.custom_resource.js')
                );
                $view->with(
                    'customCss',
                    Config::get('developer_docs.custom_resource.css')
                );
            }
        );
    }
}
