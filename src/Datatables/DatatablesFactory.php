<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 23:10
 */

namespace ZhyuJqueryAdmin\Datatables;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use ZhyuJqueryAdmin\Datatables\Configs\ResourceDatatables;
use ZhyuJqueryAdmin\Datatables\Configs\TestDatatables;
use ZhyuJqueryAdmin\Datatables\Configs\UserDatatables;
use ZhyuJqueryAdmin\Datatables\Configs\UsergroupDatatables;


class DatatablesFactory {

    const systems = [
        'resources' => ResourceDatatables::class,
        'user' => UserDatatables::class,
        'usergroup' => UsergroupDatatables::class,
    ];

    public static function bind(string $name = null){
        try {
            App::bind(DatatablesInterface::class, self::getClassName($name));
        }catch (\Exception $e){
            Log::info('DatatablesFactory can not bind: ', ['code', $e->getCode(), 'file' => $e->getFile(), 'line' => $e->getLine(), 'message' => $e->getMessage()]);
        }
    }

    private static function getClassName(string $name = null){
        if(!empty($name) && key_exists($name, self::systems)) {
            $className = self::systems[$name];
        }else{
            $lut = config('datatables');
            if(is_null($lut)){

                throw new \Exception('Please create config/datatables.php');
            }
            $className = Collection::make($lut)->get($name, TestDatatables::class);
        }

        if($className==TestDatatables::class){

            throw new \Exception('Please create mapping datatables map in config/datatables.php');
        }

        return $className;
    }


}