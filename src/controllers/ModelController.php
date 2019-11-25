<?php

namespace Sunshinev\Gii\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sunshinev\Gii\Business\ModelBusiness;

class ModelController extends Controller
{

    public function index(Request $request)
    {
        $response = ['files'=>[]];

        try {
            $response['table_list'] = ModelBusiness::getTableList();

            // preview
            if ($request->method() == 'POST') {

                $tableName       = trim($request->post('table_name'));
                $modelClassName  = trim($request->post('model_class_name'));
                $parentClassName = trim($request->post('parent_class_name'));

                $fileList = ModelBusiness::preview($tableName, $modelClassName, $parentClassName);

                $response['files'] = $fileList;

                // 创建文件
                if (!is_null($request->post('generate'))) {

                    $waitingFiles = $request->post('waitingfiles');
                    // 异常
                    if (!$waitingFiles) {
                        $response['alert'] = [
                            'type'    => 'error',
                            'message' => '请选择需要创建的文件'
                        ];
                    }
                    // generate
                    $response['generate_info'] = ModelBusiness::generateFile($fileList, $waitingFiles);
                }
            }
        }catch (\Exception $exception) {
            $response['alert'] = [
                'type'    => 'error',
                'message' => $exception->getMessage()
            ];
        }

        $viewPath = 'gii_views::model';
        return response()->view($viewPath, $response);
    }
}
