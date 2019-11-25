<?php

namespace Sunshinev\Gii\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sunshinev\Gii\Business\ControllerBusiness;
use Sunshinev\Gii\Business\ModelBusiness;

class CrudController extends Controller
{
    public function index(Request $request)
    {
        $response = ['files' => []];

        try {
            // preview
            if ($request->method() == 'POST') {

                $controllerClassName = trim($request->post('controller_class_name'));
                $modelClassName      = trim($request->post('model_class_name'));

                $fileList = (new ControllerBusiness($controllerClassName, $modelClassName))->preview();

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
                    $response['generate_info'] = ControllerBusiness::generateFile($fileList, $waitingFiles);
                }
            }
        } catch (\Exception $exception) {
            throw  $exception;
            $response['alert'] = [
                'type'    => 'error',
                'message' => $exception->getMessage()
            ];
        }

        $viewPath = 'gii_views::crud';
        return response()->view($viewPath, $response);
    }
}
