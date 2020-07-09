<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Model\UserModel;
use App\Http\Requests\Api\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 简单得注册填充数据
     *
     *
     * @param UserRequest $userRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRequest $userRequest)
    {
        $pass = $userRequest->get('pass');
        $name = $userRequest->get('name');
        if (UserModel::query()->where('name', $name)->first()) {
            return response()->json(['code' => '400', 'message' => '用户已存在']);
        }
        $user = new UserModel();
        $user->name = $name;
        $user->password = password_hash($pass, PASSWORD_BCRYPT);  // 这里必须使用password_hash加密 因为jwt验证时使用了password_verify 当然可以修改jwt底层但是不建议
        $user->save();

        if (! $token = auth('api')->attempt(['name' => $name, 'password' => $pass])) {
            $user->delete();
            return response()->json(['code' => '401', 'message' => '注册失败']);
        }

        return $this->_respondWithToken($token, \auth('api')->user());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['name', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->_respondWithToken($token, auth('api')->user());
    }

    /**
     * 刷新token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->_respondWithToken(auth('api')->refresh(), []);
    }

    public function test(Request $request)
    {
        dd(444);
    }

    /**
     * 返回token
     *
     * @param $token
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    private function _respondWithToken($token, $data)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'data' => $data
        ]);
    }
}
