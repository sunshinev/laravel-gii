<?php
/**
 * Created by PhpStorm.
 * User: jaysun
 * Date: 2019-11-21
 * Time: 17:24
 */

namespace Sunshinev\Gii\Business;

/**
 * Generate CRUD 
 */
class ControllerBusiness extends GenerateBusiness
{


    /**
     * @var mixed
     */
    protected $controllerClass;
    /**
     * @var string
     */
    protected $controllerNamespace;
    /**
     * @var
     */
    protected $controllerClassName;

    /**
     * @var mixed
     */
    protected $controllerClassMini;

    /**
     * @var mixed
     */
    protected $modelClass;
    /**
     * @var
     */
    protected $modelClassName;
    /**
     * @var string
     */
    protected $modelNamespace;

    /**
     * @var
     */
    protected $model;
    /**
     * @var
     */
    protected $modelKeyName;


    /**
     * Route mapping controller method
     *
     * @var array
     */
    protected $actions = [
        'list'         => 'getList',
        'detail'       => 'getDetail',
        'save'         => 'save',
        'delete'       => 'delete',
        'batch_delete' => 'batchDelete',
    ];


    /**
     * @var bool|string
     */
    protected $project;

    protected $m2cPath;

    protected $m2Path;

    protected $m2;


    /**
     * ControllerBusiness constructor.
     * @param $controllerClassName
     * @param $modelClassName
     */
    public function __construct($controllerClassName, $modelClassName)
    {
        $this->controllerClassName = $controllerClassName;
        $this->modelClassName      = $modelClassName;

        $this->model = new $modelClassName;
        // keyname
        $this->modelKeyName = $this->model->getKeyName();

        // controller
        $controllerClassNameArr    = explode('\\', $controllerClassName);
        $this->controllerClass     = end($controllerClassNameArr);
        $this->controllerNamespace = trim(substr($controllerClassName, 0, strrpos($controllerClassName, '\\')), '\\');

        // model
        $modelClassNameArr    = explode('\\', $modelClassName);
        $this->modelNamespace = trim(substr($modelClassName, 0, strrpos($modelClassName, '\\')), '\\');
        $this->modelClass     = end($modelClassNameArr);

        $this->controllerClassMini = str_replace('controller', '', strtolower($this->controllerClass));

        // /account-book/api/manage/user/list
        $urlPath = parse_url(config('app.url'))['path'] ?? '';
        // project
        $this->project = substr($urlPath, strpos($urlPath, '/'));

        $this->m2cPath = $this->getM2cPath();

        $this->m2     = $this->getM2();
        $this->m2Path = $this->getM2Path();
    }

    /**
     * @param $tableName
     * @param $modelClassName
     * @param $parentClassName
     * @return array
     * @throws \ReflectionException
     */
    public function preview()
    {

        $ret   = [];
        $ret[] = $this->handleApiRoute();
        $ret[] = $this->handleWebRoute();
        $ret[] = $this->handleController();
        $ret[] = $this->handleRender();
        $ret[] = $this->handleViewsList();
        $ret[] = $this->handleViewsEdit();
        $ret[] = $this->handleViewsCreate();
        $ret[] = $this->handleViewsDetail();
        $ret[] = $this->handleViewsLayoutDefault();


        return $ret;
    }

    /**
     * @return array
     */
    private function handleController()
    {
        $stubFile = __DIR__ . '/../stubs/controller.stub';

        // model
        $modelClass     = $this->modelClass . 'Model';
        $modelClassName = $this->modelNamespace . '\\' . $modelClass;

        $fields = [
            '{{model_class_name}}'     => $modelClassName,
            '{{controller_namespace}}' => $this->controllerNamespace,
            '{{controller_class}}'     => $this->controllerClass,
            '{{model_keyname}}'        => $this->modelKeyName,
            '{{model_class}}'          => $modelClass,
        ];

        return self::handleFile($this->controllerNamespace, $this->controllerClass, $fields, $stubFile);
    }

    /**
     * @return array
     */
    private function handleRender()
    {
        $stubFile = __DIR__ . '/../stubs/render.stub';

        $m2              = $this->m2 ? '\\' . $this->m2 : '';
        $renderNamespace = 'App\\Http\\Controllers' . $m2;

        $fields = [
            '{{render_namespace}}' => $renderNamespace,
        ];

        return self::handleFile($renderNamespace, 'RenderController', $fields, $stubFile);
    }


    /**
     * @return array
     */
    private function handleViewsList()
    {
        $stubFile = __DIR__ . '/../stubs/views/list.stub';

        // Get attributes + key
        $attributes = $this->model->getAttributes();

        // table
        $cols = array_merge([$this->modelKeyName], array_keys($attributes));

        $tableCols    = '';
        $searchFields = '';
        foreach ($cols as $col) {
            $tableCols    .= "                    {title:'{$col}', key:'{$col}'},\n";
            $searchFields .= "                    '{$col}',\n";
        }

        $fields = [
            '{{model_key_name}}'         => $this->modelKeyName,
            '{{table_cols}}'             => $tableCols,
            '{{controller_class}}'       => $this->controllerClass,
            '{{controller_class_lower}}' => strtolower($this->controllerClass),
            '{{controller_class_mini}}'  => $this->controllerClassMini,
            '{{search_fields}}'          => $searchFields,
            '{{list_api}}'               => $this->getApiUrl('list'),
            '{{delete_api}}'             => $this->getApiUrl('delete'),
            '{{batch_delete_api}}'       => $this->getApiUrl('batch_delete'),
        ];


        return self::handleViewFile($this->controllerNamespace, $this->controllerClass, $fields, $stubFile, 'list.blade');
    }

    /**
     * @return array
     */
    private function handleViewsEdit()
    {
        $stubFile = __DIR__ . '/../stubs/views/edit.stub';

        $attributes = $this->model->getAttributes();

        // table
        $cols = array_merge([$this->modelKeyName], array_keys($attributes));

        $fields = '';
        foreach ($cols as $col) {
            $fields .= "                    {$col}:'',\n";
        }

        $fields = [
            '{{controller_class_mini}}' => $this->controllerClassMini,
            '{{model_key_name}}'        => $this->modelKeyName,
            '{{fields_info}}'           => $fields,
            '{{detail_api}}'            => $this->getApiUrl('detail'),
            '{{save_api}}'              => $this->getApiUrl('save'),
        ];


        return self::handleViewFile($this->controllerNamespace, $this->controllerClass, $fields, $stubFile, 'edit.blade');
    }

    /**
     * @return array
     */
    private function handleViewsDetail()
    {
        $stubFile = __DIR__ . '/../stubs/views/detail.stub';

        $attributes = $this->model->getAttributes();

        // table
        $cols = array_merge([$this->modelKeyName], array_keys($attributes));

        $fields = '';
        foreach ($cols as $col) {
            $fields .= "                    {$col}:'',\n";
        }

        $fields = [
            '{{controller_class_mini}}' => $this->controllerClassMini,
            '{{model_key_name}}'        => $this->modelKeyName,
            '{{fields_info}}'           => $fields,
            '{{detail_api}}'            => $this->getApiUrl('detail'),
        ];


        return self::handleViewFile($this->controllerNamespace, $this->controllerClass, $fields, $stubFile, 'detail.blade');
    }

    /**
     * @return array
     */
    private function handleViewsCreate()
    {
        $stubFile = __DIR__ . '/../stubs/views/create.stub';

        $attributes = $this->model->getAttributes();

        // table
        $cols = array_merge([$this->modelKeyName], array_keys($attributes));

        $fields = '';
        foreach ($cols as $col) {
            $fields .= "                    {$col}:'',\n";
        }

        $fields = [
            '{{controller_class_mini}}' => $this->controllerClassMini,
            '{{model_key_name}}'        => $this->modelKeyName,
            '{{fields_info}}'           => $fields,
            '{{save_api}}'              => $this->getApiUrl('save'),
        ];


        return self::handleViewFile($this->controllerNamespace, $this->controllerClass, $fields, $stubFile, 'create.blade');
    }


    /**
     * @return array
     */
    private function handleViewsLayoutDefault()
    {
        $stubFile = __DIR__ . '/../stubs/views/layout_default.stub';


        $paths = ['list', 'create', 'detail', 'edit'];

        $projectPath = $this->project ? '/' . $this->project : '';

        $m2Path = $this->m2Path ? '/' . $this->m2Path : '';

        $routes = '';
        foreach ($paths as $p) {
            $routes .= "{
                    name: '{$this->controllerClassMini}_{$p}',
                    path: '/{$this->controllerClassMini}/{$p}',
                    url: '{$projectPath}{$m2Path}/layout/render?path=/{$this->controllerClassMini}/{$p}'
                },\n";
        }

        $routes .= '//-----routes append-----' . "\n";

        $menus = "{
                        icon: 'ios-people',
                        title: '{$this->controllerClassMini} list',
                        name:'{$this->controllerClassMini}_list'
                    },\n";

        $menus .= '//-----menus append-----' . "\n";

        $fields = [
            '{{routes}}'        => $routes,
            '{{menus}}'         => $menus,
            '{{default_route}}' => $this->controllerClassMini . '_list',
        ];


        return self::handleLayoutdefaultFile($this->controllerNamespace, 'layouts', $fields, $stubFile, 'default.blade');

    }


    private function handleApiRoute()
    {
        // api routes
        $apiRoutes = [];

        $m2cPath = $this->m2cPath ? '/' . $this->m2cPath : '';

        $controller = str_replace('App\\Http\\Controllers\\', '', $this->controllerClassName);
        foreach ($this->actions as $name => $action) {
            $apiRoutes[] = "Route::any('{$m2cPath}/{$name}', '{$controller}@{$action}');";
        }

        $apiRoutesStr = join("\n", $apiRoutes) . "\n";

        return self::handleRouteFile($apiRoutesStr, 'api');
    }

    private function handleWebRoute()
    {

        $m2Path = $this->m2Path ? '/' . $this->m2Path : '';
        $m2     = $this->m2 ? $this->m2 . '\\' : '';

        $routes   = [];
        $routes[] = "Route::get('{$m2Path}/layout', '{$m2}RenderController@index');";
        $routes[] = "Route::get('{$m2Path}/layout/render', '{$m2}RenderController@render');";

        $routesStr = join("\n", $routes) . "\n";

        return self::handleRouteFile($routesStr, 'web');
    }

    /**
     * @param $api
     * @return string
     */
    private function getApiUrl($api)
    {
        $m2cPath = $this->m2cPath ? $this->m2cPath . '/' : '';
        return "{$this->project}/api/{$m2cPath}" . $api;
    }

    private function getM2cPath()
    {
        $m2Path = $this->getM2Path();
        $m2Path = $m2Path ? $m2Path . '/' : '';

        return $m2Path . $this->controllerClassMini;
    }

    private function getM2Path()
    {
        return strtolower(str_replace('\\', '/', $this->getM2()));
    }

    private function getM2()
    {
        return trim(str_replace('App\\Http\\Controllers\\', '', $this->controllerNamespace . '\\'), '\\');
    }
}