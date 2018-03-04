<?php
namespace App\Http\Validator;

class GetDataGraphValidator
{
    public static function validate($request)
    {
        $errors = [];
        if (empty($request->names)) {
            $errors[] = '馬が選択されていません。';
        }

        if (empty($request->yaxis)) {
            $errors[] = '縦軸が選択されていません。';
        }

        if (empty($request->xaxis)) {
            $errors[] = '横軸が選択されていません。';
        }
        return $errors;
    }
}
