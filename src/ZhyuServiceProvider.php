<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:36
 */

namespace ZhyuJqueryAdmin;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use ZhyuJqueryAdmin\Commands\MakeCrudCommand;
use ZhyuJqueryAdmin\Commands\MakeDatatableCommand;
use ZhyuJqueryAdmin\Commands\MakeResourceCollectionCommand;
use ZhyuJqueryAdmin\Commands\MakeResourceCommand;
use ZhyuJqueryAdmin\Decorates\Buttons\NormalButton;
use ZhyuJqueryAdmin\Decorates\Buttons\SimpleButton;


class ZhyuServiceProvider extends ServiceProvider
{
    protected $commands = [
        MakeCrudCommand::class,
        MakeDatatableCommand::class,
        MakeResourceCommand::class,
        MakeResourceCollectionCommand::class,
    ];

    public function register(){
        $this->loadFunctions();

        if(!$this->isLumen() && env('ZHYU_USE_ADMIN_FUNCTIONS', false) === true) {
            $this->app->bind('button.create', function ($app, $params) {
                return new NormalButton(@$params['data'], @$params['url'], 'btn btn-info m-r-15', 'fa fa-plus fa-fw', null, $params['text'], @$params['title']);
            });
            $this->app->bind('button.edit', function ($app, $params) {
                return new SimpleButton($params['data'], @$params['url'], 'btn btn-info btn-circle btn-sm m-l-5', 'ti-pencil-alt', null, $params['text'], @$params['title']);
            });
            $this->app->bind('button.show', function ($app, $params) {
                return new SimpleButton($params['data'], @$params['url'], 'btn btn-warning btn-circle btn-sm m-l-5', 'ti-file', null, $params['text'], @$params['title']);
            });
            $this->app->bind('button.destroy', function ($app, $params) {
                return new SimpleButton($params['data'], @$params['url'], 'btn btn-danger btn-circle btn-sm m-l-5', 'ti-trash', null, $params['text'], @$params['title']);
            });
        }

        $this->app->bind('ZhyuGate', function()
        {
            return app()->make(\ZhyuJqueryAdmin\Helpers\ZhyuGate::class);
        });

        $this->registerAliases();
    }

    public function boot(){
        if ($this->isLumen()) {

            require_once 'Lumen.php';
        }else {

            if(env('ZHYU_USE_ADMIN_FUNCTIONS', false) === true) {
                $must_exists_classes = [
                    \App\User::class,
                    \App\Usergroup::class,
                ];
                foreach ($must_exists_classes as $class) {
                    if (env('ZHYU_RESOURCE_ENABLE', false) === true && !class_exists($class)) {
                        throw new \Exception('this file must exists: ' . $class);
                    }
                }

                $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
                $this->loadTranslationsFrom(__DIR__ . '/lang', 'zhyu');

                $this->loadViewsFrom(__DIR__ . '/blades', 'zhyu');
                $this->loadMigrationsFrom(__DIR__ . '/databases/migrations');

                $this->publishes([
                    __DIR__ . '/blades' => resource_path('views/vendor/zhyu'),
                    __DIR__ . '/assets/js' => resource_path('js'),
                    __DIR__ . '/lang/en' => resource_path('lang/en'),
                    __DIR__ . '/lang/tw' => resource_path('lang/tw'),
                    __DIR__ . '/assets/public_js' => public_path('js'),
                ], 'zhyu');

                $this->publishes([
                    __DIR__ . '/Resources/vendor' => app_path('Http/Resources'),
                ], 'zhyu:view');

                if (env('ZHYU_RESOURCE_ENABLE', false) && Schema::hasTable('resources')) {
                    View::composer('vendor.zhyu.blocks.sidemenu', 'Zhyu\Http\View\Composers\Sidemenu');
                }
            }
        }

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    protected function loadFunctions(){
        foreach (glob(__DIR__.'/functions/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ZhyuServiceProvider::class,
        ];
    }

    /**
     * Register aliases.
     *
     * @return null
     */
    protected function registerAliases()
    {
        if (class_exists('Illuminate\Foundation\AliasLoader')) {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();

            $loader->alias('ZhyuGate', \Zhyu\Facades\ZhyuGate::class);
        }
    }

    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen');
    }
}
