<?php

namespace App\Http\Requests\Api;

class UserRequest extends MyFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'pass' => 'required|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '用户名必须',
            'name.min' => '用户名不能少于3个字符',
            'pass.required' => '密码必须',
            'pass.min' => '密码最少为6位',
            'pass.confirmed' => '确认密码和密码不一致',
        ];
    }
}
