<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:31
 */

namespace ZhyuJqueryAdmin\Controller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ZhyuJqueryAdmin\Datatables\DatatablesService;
use Zhyu\Facades\ZhyuTool;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $title = null;
    protected $id = null;
    protected $table = null;
    protected $route = null;

    protected $columns;
    protected $limit;

    protected $model;

    protected $query;

    public function __construct()
    {
        //$name = (new \ReflectionClass($this))->getShortName();
        //dd($name);
        //RepositoryApp::bind((new \ReflectionClass($this))->getShortName());
        $this->initQueryFromRequest();
    }

    /**
     * @return mixed
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param mixed $columns
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
    }


    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param string $table
     */
    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    /**
     * @return null
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param null $route
     */
    public function setRoute($route): void
    {
        $this->route = $route;
    }

    private function getTableNameFromParamsOrModel(DatatablesService $datatablesService = null, Model &$model = null) : string{
        $table = '';
        if(!is_null($datatablesService)){
            try {
                $model = app($datatablesService->getDatatables()->model());
                $table = $model->getTable();
            }catch (\Exception $e) {
            }
        }else{
            if(!is_null($this->table)) {
                $table = $this->table;
            }else{
                if(!is_null($model)) {
                    $table = $model->getTable();
                }
            }
        }

        return $table;
    }

    private function bladeAlias(&$view){
        switch($view){
            case 'index':
                $view = 'vendor.zhyu.index';
                break;
            case 'priv':
                $view = 'vendor.zhyu.priv';
                break;
            case 'form':
                $view = 'vendor.zhyu.form';
                break;
            default:
        }
    }

    protected function view($view = 'index', array $params = null, Model $model = null){
        $title = $this->titleFromModelOrParams($model, $params);
        $datatablesService = null;
        if(isset($params['datatablesService'])){
            $datatablesService = $params['datatablesService'];
        }
        $table = $this->getTableNameFromParamsOrModel($datatablesService, $model);
        if(!isset($table)){
            //throw new \Exception('please provide table name first!!!');
        }

        $route = $this->getRoute();
        if(is_null($route)){
            $route = $table;
        }

        $id = null;
        if(isset($model->id)){
            $id = $model->id;
        }

        $this->bladeAlias($view);

        $addOrUpdateUrl = $this->getAddOrUpdateUrl($model, $table, $route);

        $compacts = compact('route','table', 'title', 'id', 'datatablesService', 'addOrUpdateUrl', 'model');
        $returns = $params;
        foreach($compacts as $key => $val){
            $returns[$key] = $val;
        }
        $returns['query'] = $this->getQuery();

        return view()->first([$view, 'vendor.zhyu.form'], $returns);
    }

    /*
     * @return void
     */
    private function initQueryFromRequest() : void{
        $q = app(Request::class)->get('query');
        if(is_null($q)) return ;
        $query = ZhyuTool::urlMakeQuery('#')->decode($q);
        $this->setQuery($query);
    }

    private function titleFromModelOrParams(Model $model = null, array $params) : string{
        if(!empty($params['title'])){
            $this->setTitle($params['title']);

            return $params['title'];
        }

        try {
            $title = (string) $model;
            $this->setTitle($title);

            return $title;
        }catch(\Exception $e){
            $title = '';
        }

        return $title;
    }

    public function responseJson($message, $status = 200){
        if($message instanceof \Exception){
            $message = $message->getMessage();
        }

        return response()->json([ 'message' => $message ], $status);
    }

    private function getAddOrUpdateUrl($model = null, $table = null, $route = null) : string {
        $addOrUpdateUrl = '';
        if(!empty($model->id)){
            try{
                $addOrUpdateUrl = route($route.'.update', [ $table => $model->id, $table => $model]);
            }catch (\Exception $e){
                $name = substr($table, 0, (strlen($table)-1) );
                try{
                    $addOrUpdateUrl = route($route.'.update', [ $name => $model->id, $table => $model]);
                }catch (\Exception $e){
                    Log::error('Zhyu Controller error: ', [$e]);
                }
            }
        }else{
            try{
                $addOrUpdateUrl = route($route.'.store');
            }catch (\Exception $e){
            }
        }

        return $addOrUpdateUrl;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query): void
    {
        $this->query = $query;
    }

    public function parseQuery(string $route, string $redirect_query){
        $query_string = app(Request::class)->get('query');
        if(is_null($query_string)){

            return redirect()->route($route, [ 'query' => urlencode($redirect_query)]);
        }

        return true;
    }
}
