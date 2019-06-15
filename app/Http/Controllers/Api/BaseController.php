<?php

namespace App\Http\Controllers\Api;

use App\AppInfo;
use App\Category;
use App\Music;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    /*
     * Bearer Token ile giriş yapan kişinin kim olduğu tespit ediliyor
     * Sürüm Kontrolü ardından kategoriler ve kişinin bilgileri json tipinde geri dönderilecek
     * */
    public function index()
    {
        $user = Auth::guard('api')->user();
        $app_info=AppInfo::select('app_version')->orderBy('id','DESC')->first();
        $user_version=\request()->version;

        if (!$user) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated.'
            ]);
        }else{
            if($app_info->app_version>$user_version){
                return response()->json([
                    'status' => 410,
                    'message' => 'Update Application.'
                ]);
            }else{
                /*
                 * Kategorilere ait müzikleride kategorilerle birlikte almak istersek with kullanacağız
                 * Kategori içine girildikçe müzikleri çekmek için getCategory fonksiyonunu kullanabiliriz
                 * */
                $categories=Category::where('status','1')->with('music')->get();
                $data=['user'=>$user,'categories'=>$categories];
                return response()->json(['status'=>200,'data'=>$data]);
            }
        }
    }

   /*
    * Kategorinin bilgilerini ve
    * */
    public function getCategory()
    {
        $user = Auth::guard('api')->user();
        if (!$user || !\request()->category_id) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthenticated.'
            ]);
        }else {
            $category = Category::where(['id' => (int)\request()->category_id, 'status' => '1'])->with('music')->first();
            return response()->json(['status'=>200,$category]);
        }
    }
}
