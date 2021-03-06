<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-01
 * Time: 22:34
 */

namespace ZhyuJqueryAdmin\Repositories\Eloquents;

use Illuminate\Support\Collection;
use Zhyu\Repositories\Contracts\RepositoryInterface;

class RepositoryApp{
    private static function parseName($name){
        $name = str_replace('Controller', '', $name);
        $name = strtolower($name);
        return $name;
    }
    public static function bind($name){
        $systems = [
            'resources' => ResourceRepository::class,
            'user' => UserRepository::class,
            'usergroup' => UsergroupRepository::class,
        ];
        if(key_exists($name, $systems)){
            $class = $systems[$name];
        }else {
            $class = config('repository.' . self::parseName($name));
            if (strlen($class) == 0) {
//            throw new \Exception("this config $name dos't exists!");
                return;
            }
        }
        app()->bind(RepositoryInterface::class, function($app) use($class){

            return new $class($app, new Collection());
        });
    }
}