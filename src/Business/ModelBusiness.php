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
 * create model
 *
 * Class ModelBusiness
 * @package Sunshinev\Gii\Business
 */
class ModelBusiness extends GenerateBusiness
{

    /**
     * @param $tableName
     * @param $modelClassName
     * @param $parentClassName
     * @return array
     * @throws \ReflectionException
     */
    public static function preview($tableName, $baseModelClassName, $modelParentClassName)
    {

        // check argvs

        foreach (func_get_args() as $k => $v) {
            if (!$v) {
                throw new \Exception('Missing Params');
            }
        }

        $stubFiles = [
            'observer'   => __DIR__ . '/../stubs/observer.stub',
            'base_model' => __DIR__ . '/../stubs/basemodel.stub',
            'model'      => __DIR__ . '/../stubs/model.stub'
        ];

        // basic model
        $baseModelClassNameArr = explode('\\', $baseModelClassName);
        $baseModelClass        = end($baseModelClassNameArr);
        $baseModelNamespace    = trim(substr($baseModelClassName, 0, strrpos($baseModelClassName, '\\')), '\\');

        // parent model class
        $modelParentClassNameArr = explode('\\', $modelParentClassName);
        $modelParentClass        = end($modelParentClassNameArr);

        // observer
        $observerClass     = $baseModelClass . 'Observer';
        $observerClassName = str_replace('Models', 'Observers\\Models', $baseModelNamespace) . '\\' . $observerClass;
        $observerNamespace = trim(substr($observerClassName, 0, strrpos($observerClassName, '\\')), '\\');

        // model
        $modelClass     = $baseModelClass . 'Model';
        $modelNamespace = $baseModelNamespace;//trim(substr($baseModelNamespace, 0, strrpos($baseModelNamespace, '\\')), '\\');
        $modelClassName = $modelNamespace . '\\' . $modelClass;

        // table struct
        $tableStruct = self::createTableStruct($tableName, $modelParentClassName);

        $fields = [
            '{{base_model_class}}'        => $baseModelClass,
            '{{base_model_namespace}}'    => $baseModelNamespace,
            '{{base_model_class_name}}'   => $baseModelClassName,
            '{{model_namespace}}'         => $modelNamespace,
            '{{observer_class_name}}'     => $observerClassName,
            '{{observer_namespace}}'      => $observerNamespace,
            '{{model_parent_class_name}}' => $modelParentClassName,
            '{{remarks}}'                 => self::createRemarks($tableStruct),
            '{{model_parent_class}}'      => $modelParentClass,
            '{{connection}}'              => '',
            '{{table_name}}'              => $tableName,
            '{{attributes}}'              => self::createAttributes($tableStruct),
            '{{rules}}'                   => '',
            '{{observer_class}}'          => $observerClass,
        ];


        $list = [];

        foreach ($stubFiles as $type => $stubFilePath) {

            switch ($type) {
                case 'base_model':
                    $namespace = $baseModelNamespace;
                    $class     = $baseModelClass;
                    $classname = $baseModelClassName;
                    break;
                case 'observer':
                    $namespace = $observerNamespace;
                    $class     = $observerClass;
                    $classname = $observerClassName;
                    break;
                case 'model':
                    $namespace = $modelNamespace;
                    $class     = $modelClass;
                    $classname = $modelClassName;
                    break;
            }

            $list[] = self::handleFile($namespace, $class, $fields, $stubFilePath);
        }

        return $list;
    }


    /**
     * @param $tableStruct
     * @return string
     */
    private static function createAttributes($tableStruct)
    {
        $str = "\n";
        foreach ($tableStruct as $col) {
            // The primary key is filtered when generating attributes. The primary key does not need a default value, otherwise the write will be empty or null
            if (in_array($col['Field'], ['id', '_id'])) {
                continue;
            }
            $default = isset($col['Default']) ? "'" . $col['Default'] . "'" : "''";
            $str     .= "        '" . $col['Field'] . "' => " . $default . ",\n";
        }

        return $str;
    }

    /**
     * @param $tableStruct
     * @return string
     */
    private static function createRemarks($tableStruct)
    {
        $str = "/**\n";
        foreach ($tableStruct as $col) {
            $str .= "* @property $" . $col['Field'] . "\n";
        }

        return $str . "*/";
    }


    /**
     *
     *  array(9) {
     * ["Field"]=>
     * string(2) "id"
     * ["Type"]=>
     * string(19) "bigint(20) unsigned"
     * ["Collation"]=>
     * NULL
     * ["Null"]=>
     * string(2) "NO"
     * ["Key"]=>
     * string(3) "PRI"
     * ["Default"]=>
     * NULL
     * ["Extra"]=>
     * string(14) "auto_increment"
     * ["Privileges"]=>
     * string(31) "select,insert,update,references"
     * ["Comment"]=>
     * string(2) "id"
     * }
     */
    private static function createTableStruct($tableName, $parentClassName)
    {
        // desc table
        $tableStruct = DB::connection('mysql')->select('show full fields from ' . $tableName);
        $tableStruct = json_decode(json_encode($tableStruct), true);

        $createdAt = $parentClassName::CREATED_AT;
        $updatedAt = $parentClassName::UPDATED_AT;


        // table struct verify
        foreach ($tableStruct as $key => $col) {
            if (in_array($col['Field'], [$createdAt, $updatedAt])) {
                unset($tableStruct[$key]);
            }
        }

        // Make sure always have fields `create_at` & `update_at`
        $tableStruct = array_merge($tableStruct, [
            [
                'Field'      => $createdAt,
                'Type'       => 'timestamp',
                'Collation'  => '',
                'Null'       => 'YES',
                'Key'        => '',
                'Default'    => '',
                'Extra'      => '',
                'Privileges' => '',
                'Comment'    => '',
            ],
            [
                'Field'      => $updatedAt,
                'Type'       => 'timestamp',
                'Collation'  => '',
                'Null'       => 'YES',
                'Key'        => '',
                'Default'    => '',
                'Extra'      => '',
                'Privileges' => '',
                'Comment'    => '',
            ]
        ]);


        return $tableStruct;
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