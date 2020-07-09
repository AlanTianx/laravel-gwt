<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class MyFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    //自定义参数验证失败后返回的数据格式
    protected function failedValidation(Validator $validator)
    {
        $error= $validator->errors()->all();
        throw new HttpResponseException($this->fail(400, $error));
    }

    protected function fail(int $code, array $errors) : JsonResponse
    {
        return response()->json([
            'code' => $code,
            'message' =>'参数校验出错',
            'errors' => $errors,
        ]);
    }
}
