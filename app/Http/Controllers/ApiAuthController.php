<?php

namespace App\Http\Controllers;

use JWTAuth;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\User;

class ApiAuthController extends Controller
{
    public function login(Request $request) {
        // emailとpasswordがあるか確認
        if ($request->has('email') && $request->has('password')) {
            try {
                $credentials = [
                    'email' => $request->email,
                    'password' => $request->password
                ];
                // もしトークンが発行されなかったらエラー
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'ログインに失敗しました。'], 401);
                }

                // ユーザー情報を取得
                if (Auth::attempt($credentials)) {
                    $user = Auth::user();
                }

                // ユーザー情報とアクセストークンをjsonで返す
                return response()->json(compact('user', 'token'));

            }catch (Exception $e) {
                return response()->json(['error' => 'ログインにしっぱいしました。'], 500);
            }
        }else {
            return response()->json(['error' => '入力項目が間違っています。'], 400);
        }
    }

    public function showName(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(compact('user'));
    }

    public function postRegister(Request $request) {
        Log::info($request);
        $rules = [
            'name' => 'required',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|max:64|alpha_num'
        ];
        $messages = [];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['error' => 'input error'], 400);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json(['error' => 'mailaddress error'], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'ログインに失敗しました。'], 401);
        }

        return response()->json(compact('token'));
    }
}
