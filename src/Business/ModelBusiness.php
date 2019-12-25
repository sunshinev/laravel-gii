<?php
/**
 * Created by PhpStorm.
 * User: jaysun
 * Date: 2019-11-21
 * Time: 17:24
 */

namespace Sunshinev\Gii\Business;

use Illuminate\Support\Facades\DB;

/**
 * Create model
 *
 * Class ModelBusiness
 * @package Sunshinev\Gii\Business
 */
class ModelBusiness extends GenerateBusiness
{

    /**
     * @var
     */
    protected $tableName;
    /**
     * @var
     */
    protected $baseModelClassName;
    /**
     * @var
     */
    protected $modelParentClassName;

    /**
     * @var
     */
    protected $tableColumns;


    /**
     * ModelBusiness constructor.
     * @param $tableName
     * @param $baseModelClassName
     * @param $modelParentClassName
     * @throws \Exception
     */
    public function __construct($tableName, $baseModelClassName, $modelParentClassName)
    {
        foreach (func_get_args() as $k => $v) {
            if (!$v) {
                throw new \Exception('params is empty!');
            }
        }

        $this->tableName            = $tableName;
        $this->baseModelClassName   = $baseModelClassName;
        $this->modelParentClassName = $modelParentClassName;

    }


    /**
     * @return array
     * @throws \ReflectionException
     */
    public function preview()
    {
        $stubFiles = [
            'observer'   => __DIR__ . '/../stubs/observer.stub',
            'base_model' => __DIR__ . '/../stubs/basemodel.stub',
            'model'      => __DIR__ . '/../stubs/model.stub'
        ];

        // basic model
        $baseModelClassNameArr = explode('\\', $this->baseModelClassName);
        $baseModelClass        = end($baseModelClassNameArr);
        $baseModelNamespace    = trim(substr($this->baseModelClassName, 0, strrpos($this->baseModelClassName, '\\')), '\\');

        // parent model class
        $modelParentClassNameArr = explode('\\', $this->modelParentClassName);
        $modelParentClass        = end($modelParentClassNameArr);

        // observer
        $observerClass     = $baseModelClass . 'Observer';
        $observerClassName = str_replace('Models', 'Observers\\Models', $baseModelNamespace) . '\\' . $observerClass;
        $observerNamespace = trim(substr($observerClassName, 0, strrpos($observerClassName, '\\')), '\\');

        // model
        $modelClass     = $baseModelClass . 'Model';
        $modelNamespace = $baseModelNamespace;
        $modelClassName = $modelNamespace . '\\' . $modelClass;

        // init table columns
        $this->getTableColumns();

        $fields = [
            '{{base_model_class}}'        => $baseModelClass,
            '{{base_model_namespace}}'    => $baseModelNamespace,
            '{{base_model_class_name}}'   => $this->baseModelClassName,
            '{{model_namespace}}'         => $modelNamespace,
            '{{observer_class_name}}'     => $observerClassName,
            '{{observer_namespace}}'      => $observerNamespace,
            '{{model_parent_class_name}}' => $this->modelParentClassName,
            '{{remarks}}'                 => $this->createProperty(),
            '{{model_parent_class}}'      => $modelParentClass,
            '{{connection}}'              => '',
            '{{table_name}}'              => $this->tableName,
            '{{attributes}}'              => $this->createAttributes(),
            '{{rules}}'                   => '',
            '{{observer_class}}'          => $observerClass,
        ];


        $list = [];

        foreach ($stubFiles as $type => $stubFilePath) {

            switch ($type) {
                case 'base_model':
                    $namespace = $baseModelNamespace;
                    $class     = $baseModelClass;
                    break;
                case 'observer':
                    $namespace = $observerNamespace;
                    $class     = $observerClass;
                    break;
                case 'model':
                    $namespace = $modelNamespace;
                    $class     = $modelClass;
                    break;
            }

            $list[] = self::handleFile($namespace, $class, $fields, $stubFilePath);
        }

        return $list;
    }


    /**
     * Fetch table columns
     *
     * https://www.doctrine-project.org/projects/doctrine-dbal/en/2.10/reference/types.html#reference
     * https://www.doctrine-project.org/projects/doctrine-dbal/en/2.10/reference/schema-manager.html#schema-manager
     */
    private function getTableColumns()
    {
        /**
         * @var \Illuminate\Database\Connection $connection
         */
        $connection = DB::connection('mysql');
        $schema     = $connection->getDoctrineSchemaManager();

        $cols = $schema->listTableColumns($this->tableName);

        $columns = [];
        foreach ($cols as $col) {
            $columns[] = [
                'name'    => $col->getName(),
                'type'    => $col->getType()->getName() ?? '', // Use Doctrine convert type
                'default' => $col->getDefault() ?? '',
                'comment' => $col->getComment() ?? '',
            ];
        }


        $createdAt = $this->modelParentClassName::CREATED_AT;
        $updatedAt = $this->modelParentClassName::UPDATED_AT;


        // table struct verify
        foreach ($columns as $key => $col) {
            if (in_array($col['name'], [$createdAt, $updatedAt])) {
                unset($columns[$key]);
            }
        }

        // Make sure always have fields `create_at` & `update_at`
        $this->tableColumns = array_merge($columns, [
            [
                'name'    => $createdAt,
                'type'    => 'datetime',
                'comment' => '',
            ],
            [
                'name'    => $updatedAt,
                'type'    => 'datetime',
                'comment' => '',
            ]
        ]);
    }

    /**
     * @return string
     */
    private function createAttributes()
    {
        $str = "\n";
        foreach ($this->tableColumns as $col) {
            // The primary key is filtered when generating attributes. The primary key does not need a default value, otherwise the write will be empty or null
            if (in_array($col['name'], ['id', '_id'])) {
                continue;
            }
            $default = isset($col['default']) ? "'" . $col['default'] . "'" : "''";
            $str     .= "        '" . $col['name'] . "' => " . $default . ",\n";
        }

        return $str;
    }

    /**
     * @return string
     */
    private function createProperty()
    {
        $str = "/**\n";
        foreach ($this->tableColumns as $col) {
            $str .= "* @property {$col['type']} $" . $col['name'] . " {$col['comment']}\n";
        }

        return $str . "*/";
    }

    /**
     * @return array
     */
    public static function getTableList()
    {
        $list = DB::connection('mysql')->select('show tables');
        $list = json_decode(json_encode($list), true);

        $tableList = [];
        foreach ($list as $item) {
            $tableList[] = array_shift($item);
        }

        ksort($tableList);

        return $tableList;
    }

}