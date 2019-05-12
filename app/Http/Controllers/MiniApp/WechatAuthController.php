<?php


namespace App\Http\Controllers\MiniApp;

use App\Http\Controllers\Controller;
use App\Models\User;
use Cache;
use Illuminate\Http\Request;
use JWTAuth;

/**
 * 微信授权登录
 *
 * @package App\Http\Controllers\MiniApp
 */
class WechatAuthController extends Controller
{
    /**
     * Wechat Login
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'code'     => 'required',
            'nickname' => 'required',
            'avatar'   => 'required',
            'gender'   => 'required',
        ], [
            'code.required'     => '授权码未获取',
            'nickname.required' => '未授权',
            'avatar.required'   => '未授权',
            'gender.required'   => '未授权',
        ]);

        $res = $this->code2Session($request->input('code'));

        $openId = $res['openid'];

        Cache::add($openId, $request->only(['nickname', 'avatar', 'gender']), 60);

        // 根据 OpenId 查询账户信息
        if (!$user = User::where('openid', $openId)->first()) {
            return response()->json([
                'status_code' => 404,
                'message'     => '请先与系统中的用户先绑定',
            ]);
        }

        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token);
    }

    /**
     * 注册
     *
     * @param Request $request
     *
     * @return
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'code'     => 'required',
            'username' => 'required',
        ], [
            'code.required'     => '授权码未获取',
            'username.required' => '请填写手机或学号',
        ]);

        $user = User::where('student_id', $request->input('username'))->orWhere('phone', $request->input('username'))->first();

        if (!$user) {
            $this->response->errorBadRequest('用户不存在！');
        }

        $res = $this->code2Session($request->input('code'));

        $openId = $res['openid'];
        if ($user->openid) {
            $this->response->errorBadRequest('该微信已经绑定过账号了。');
        }

        $userInfo = Cache::get($openId);

        $user->nickname = $userInfo['nickname'];
        $user->gender   = $userInfo['gender'];
        $user->avatar   = $userInfo['avatar'];
        $user->openid   = $res['openid'];
        $user->save();

        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token);
    }

    /**
     * 解绑
     */
    public function unbind()
    {
        $user = auth()->user();

        $user->openid = null;
        $user->save();

        return $this->response->noContent();
    }

    /**
     * 获取授权信息
     *
     * @param string $code
     *
     * @return array
     */
    public function code2Session(string $code)
    {
        $app = app('wechat.mini_program');

        /**
         * 正确响应：["session_key" => "Qbn+yQKxtLHeje5/M1zkmw==", "openid" => "oFBYi0VP4h1Pomubx8-hBN2-TPu4"]
         * 错误响应：["errcode" => 40163, "errmsg" => "code been used, hints: [ req_id: lkcczqLnRa-uIZNpA ]"]
         */
        $res = $app->auth->session($code);

        if ($errorCode = data_get($res, 'errcode', false)) {
            $this->response()->errorBadRequest($this->errorCodeToChinese($errorCode));
        }

        if (!$openId = data_get($res, 'openid', false)) {
            $this->response()->errorBadRequest('OPENID 不存在');
        }

        return $res;
    }

    /**
     * 转换 ErrorCode 为相应的提示信息
     *
     * @param int $code
     *
     * @return mixed
     */
    public function errorCodeToChinese(int $code)
    {
        $arr = [
            -1    => '系统繁忙，此时请开发者稍候再试',
            0     => '请求成功',
            40163 => '登录失败，重新登录',
            45011 => '频率限制，每个用户每分钟100次',
        ];

        return data_get($arr, $code, '登录失败，请重新尝试');
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
        ]);
    }
}