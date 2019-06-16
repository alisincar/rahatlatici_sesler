<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class
FavoriteController extends Controller
{
    /*
     * Kullanıcının favorilerini listeler
     * */
    public function index()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated.'
            ]);
        }
        return response()->json(['status'=>200,$user->favorites]);
    }

    /*
     * Kullanıcının favori ekleme methodu
     * İşlem başarılı olursa eklediği kaydı geri dönderir
     * */
    public function store()
    {
        $user = Auth::guard('api')->user();
        if (!$user || !\request()->music_id) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated.'
            ]);
        } else {
            $data = ['music_id' => (int)request()->music_id, 'user_id' => $user->id];
            $favorite = Favorite::where($data)->count();
            if ($favorite > 0) {
                return response()->json([
                    'status' => 304,
                    'message' => 'Not Modified.'
                ]);
            } else {
                return response()->json(['status'=>200,Favorite::create($data)]);
            }

        }

    }

    /*
     * Kullanıcının favori silme methodu
     * çıktı json tipinde verilir
     * */
    public function delete()
    {
        $user = Auth::guard('api')->user();
        if (!$user || !\request()->music_id) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated.'
            ]);
        } else {
            $data = ['music_id' => (int)request()->music_id, 'user_id' => $user->id];
            $favorite = Favorite::where($data)->first();
            if ($favorite->count() > 0) {
                $favorite->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'success.'
                ]);
            } else {
                return response()->json([
                    'status' => 304,
                    'message' => 'Not Modified.'
                ]);
            }
        }
    }
}
