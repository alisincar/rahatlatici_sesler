<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /*
     * Login Fonksiyonu ile Kullanıcıdan alınan bilgilerle girişini sağlayacağız
     * Eşleşmeyen veri var ise json tipinde geri dönüş yapılacak
     * */
    public function login(Request $request)
    {
        //veri kontrolü
        $validateArray = [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ];
        $vld = Validator::make($request->all(), $validateArray);
        if ($vld->fails()) {
            return response()->json([
                'status' => 406,
                'message' => 'Not Acceptable.'
            ]);
        }
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

    /*
     * Kayıt Fonksiyonu ile Kullanıcının sisteme kayıt olması sağlanacak
     * Formdan gelen veriler kontrol edilecek ve üye kaydı yapılacak
     * Kayıt olan kişinin bilgileri TOKEN ile birlikte geri döndürülecek
     * */
    public function register(Request $request)
    {
        //veriyi kontrol ediyoruz burdaki önemli kısım mailin users tablosunda benzersiz olması gerektiği
        $validateArray = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
        $vld = Validator::make($request->all(), $validateArray);
        if ($vld->fails()) {
            return response()->json([
                'status' => 406,
                'message' => 'Not Acceptable.'
            ]);
        }
        //verinin yazılması
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        //Bearer Token
        $user->api_token = str_random(60);
        if ($user->save()) {
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
