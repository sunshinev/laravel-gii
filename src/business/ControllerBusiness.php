<?php
/**
 * Created by PhpStorm.
 * User: jaysun
 * Date: 2019-11-21
 * Time: 17:24
 */

namespace Sunshinev\Gii\Business;

/**
 * CRUD创建
 * 包含视图
 * RenderController
 * 以及接口的Controller
 *
 * 同时要生成路由以及视图blade文件
 *
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
     * 路由映射控制器方法
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
        // 项目的根域名
        $this->project = substr($urlPath, strpos($urlPath, '/'));

        $this->m2cPath = $this->getM2cPath();
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

        // 转换model类
        $modelClass     = $this->modelClass . 'Model';
        $modelNamespace = trim(substr($this->modelNamespace, 0, strrpos($this->modelNamespace, '\\')), '\\');
        $modelClassName = $modelNamespace . '\\' . $modelClass;

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

        $path            = str_replace('App\\Http\\Controllers\\', '', $this->controllerNamespace);
        $modules         = strpos($path, '\\') === false ? $path : substr($path, 0, strpos($path, '\\'));
        $renderNamespace = 'App\\Http\\Controllers\\' . trim($modules, '\\');

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

        // 获取模型的attributes + key
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


        $ret = self::handleViewFile($this->controllerNamespace, $this->controllerClass, $fields, $stubFile, 'list.blade');
        // 转义script标签
        $ret['diff_content'] = str_replace(['<script>', '</script>'], [htmlentities('<script>'), htmlentities('</script>')], $ret['diff_content']);

        return $ret;
    }

    /**
     * @return array
     */
    private function handleViewsEdit()
    {
        $stubFile = __DIR__ . '/../stubs/views/edit.stub';

        // 获取模型的attributes + key
        $attributes = $this->model->getAttributes();

        // table
        $cols = array_merge([$this->modelKeyName], array_keys($attributes));

        $fields = '';
        foreach ($cols as $col) {
            $fields .= "                    {$col}:'',\n";
        }

        $fields = [
            '{{model_key_name}}' => $this->modelKeyName,
            '{{fields_info}}'    => $fields,
            '{{detail_api}}'     => $this->getApiUrl('detail'),
            '{{save_api}}'       => $this->getApiUrl('save'),
        ];


        $ret = self::handleViewFile($this->controllerNamespace, $this->controllerClass, $fields, $stubFile, 'edit.blade');
        // 转义script标签
        $ret['diff_content'] = str_replace(['<script>', '</script>'], [htmlentities('<script>'), htmlentities('</script>')], $ret['diff_content']);

        return $ret;
    }

    /**
     * @return array
     */
    private function handleViewsDetail()
    {
        $stubFile = __DIR__ . '/../stubs/views/detail.stub';

        // 获取模型的attributes + key
        $attributes = $this->model->getAttributes();

        // table
        $cols = array_merge([$this->modelKeyName], array_keys($attributes));

        $fields = '';
        foreach ($cols as $col) {
            $fields .= "                    {$col}:'',\n";
        }

        $fields = [
            '{{model_key_name}}' => $this->modelKeyName,
            '{{fields_info}}'    => $fields,
            '{{detail_api}}'     => $this->getApiUrl('detail'),
        ];


        $ret = self::handleViewFile($this->controllerNamespace, $this->controllerClass, $fields, $stubFile, 'detail.blade');
        // 转义script标签
        $ret['diff_content'] = str_replace(['<script>', '</script>'], [htmlentities('<script>'), htmlentities('</script>')], $ret['diff_content']);

        return $ret;
    }

    /**
     * @return array
     */
    private function handleViewsCreate()
    {
        $stubFile = __DIR__ . '/../stubs/views/create.stub';

        // 获取模型的attributes + key
        $attributes = $this->model->getAttributes();

        // table
        $cols = array_merge([$this->modelKeyName], array_keys($attributes));

        $fields = '';
        foreach ($cols as $col) {
            $fields .= "                    {$col}:'',\n";
        }

        $fields = [
            '{{model_key_name}}' => $this->modelKeyName,
            '{{fields_info}}'    => $fields,
            '{{save_api}}'       => $this->getApiUrl('save'),
        ];


        $ret = self::handleViewFile($this->controllerNamespace, $this->controllerClass, $fields, $stubFile, 'create.blade');
        // 转义script标签
        $ret['diff_content'] = str_replace(['<script>', '</script>'], [htmlentities('<script>'), htmlentities('</script>')], $ret['diff_content']);

        return $ret;
    }


    /**
     * @return array
     */
    private function handleViewsLayoutDefault()
    {
        $stubFile = __DIR__ . '/../stubs/views/layout_default.stub';


        $paths = ['list', 'create', 'detail', 'edit'];

        $projectPath = $this->project ? '/'.$this->project : '';

        $routes = '';
        foreach ($paths as $p) {
            $routes .= "{
                    name: '{$this->controllerClassMini}_{$p}',
                    path: '/{$this->controllerClassMini}/{$p}',
                    url: '{$projectPath}/manage/layout/render?path=/{$this->controllerClassMini}/{$p}'
                },\n";
        }

        $menus = "{
                        icon: 'ios-people',
                        title: '{$this->controllerClassMini} list',
                        name:'{$this->controllerClassMini}_list'
                    }";

        $fields = [
            '{{routes}}'        => $routes,
            '{{menus}}'         => $menus,
            '{{default_route}}' => $this->controllerClassMini . '_list',
        ];


        $ret = self::handleViewFile($this->controllerNamespace, 'layouts', $fields, $stubFile, 'default.blade');

        // 转义script标签
        $ret['diff_content'] = str_replace(['<script>', '</script>'], [htmlentities('<script>'), htmlentities('</script>')], $ret['diff_content']);

        return $ret;
    }


    private function handleApiRoute()
    {
        // api 路由
        $apiRoutes = [];

        $controller = str_replace('App\\Http\\Controllers\\', '', $this->controllerClassName);
        foreach ($this->actions as $name => $action) {
            $apiRoutes[] = "Route::any('/{$this->m2cPath}/{$name}', '{$controller}@{$action}');";
        }

        $apiRoutesStr = join("\n", $apiRoutes) . "\n";

        return self::handleRouteFile($apiRoutesStr, 'api');
    }

    private function handleWebRoute()
    {
        $path   = str_replace('App\\Http\\Controllers\\', '', $this->controllerNamespace);
        $module = strpos($path, '\\') === false ? $path : substr($path, 0, strpos($path, '\\'));

        $routes   = [];
        $routes[] = "Route::get('/{$module}/layout', '{$module}\RenderController@index');";
        $routes[] = "Route::get('/{$module}/layout/render', '{$module}\RenderController@render');";

        $routesStr = join("\n", $routes) . "\n";

        return self::handleRouteFile($routesStr, 'web');
    }

    /**
     * @param $api
     * @return string
     */
    private function getApiUrl($api)
    {
        return "{$this->project}/api/{$this->m2cPath}/" . $api;
    }

    private function getM2cPath()
    {
        return strtolower(str_replace('\\', '/', str_replace('App\\Http\\Controllers\\', '', $this->controllerNamespace))) . '/' . $this->controllerClassMini;
    }
}