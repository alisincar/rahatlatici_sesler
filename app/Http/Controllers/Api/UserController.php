<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
     * Login Fonksiyonu ile Kullanıcıdan alınan bilgilerle girişini sağlayacağız
     * Eşleşmeyen veri var ise json tipinde geri dönüş yapılacak
     * */
    public function login(Request $request)
    {
        #Todo: Use Validation
        $user = User::where('email', $request->email)->first();
        if (Hash::check($request->password, $user->password)) {
            //Bearer Token
            $user->api_token = str_random(60);
            $user->save();
            return response()->json([
                'status' => 200,
                'api_token' => $user->api_token,
                'username' => $user->name,
                'email' => $user->email,
                'id' => $user->id
            ]);
        }
        return response()->json([
            'status' => 401,
            'message' => 'Unauthenticated.'
        ]);
    }
}
