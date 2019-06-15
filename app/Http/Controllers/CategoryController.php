<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Kategoriler';
        $categories = Category::paginate(20);
        return view('admin.categories.index', compact('title', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Kategori Ekle';
        $categories = Category::get();
        return view('admin.categories.add', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'status' => $request->status
        ]);

        $directory = "uploads/categories/";
        $filename = rand(1111, 9999) . time() . '.' . $request->cover_image->getClientOriginalExtension();
        $path = $directory . "/" . $filename;
        try {
            //Resmi yüklüyoruz hata olursa bunu yakalayıp kullanıcıya göstereceğiz
            Helper::image_convert($request->cover_image, $directory, $filename);
        }catch (\Exception $e){
            return redirect()->back()->with('error','Uygun resim formatı seçiniz');
        }
        $category->cover_image = $path;
        //Veritabanı sonucuna göre kullanıcıyı bilgilendiriyoruz
        if ($category->save()) {
            return redirect()->route('categories.index')->with('success', 'Kategori Eklendi');
        } else {
            return redirect()->route('categories.index')->with('error', 'Kategori Eklenemedi');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $title = 'Kategori Görüntüle';
        return view('admin.categories.index', compact('title', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $title = 'Kategori Düzenle';
        $list_categories = Category::where('parent_id', 0)->get();
        return view('admin.categories.edit', compact('title', 'category', 'list_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->status = $request->status;

        //Güncelleme kısmında olduğumuz için tekrar resim seçilmediyse resim işlemlerini atlıyoruz
        if($request->hasFile('cover_image')) {
            if($category->cover_image != null){
                Helper::file_delete($category->cover_image);
            }
            $directory = "uploads/categories";
            $filename = rand(1111, 9999) . time() . '.' . $request->cover_image->getClientOriginalExtension();
            $path = $directory . "/" . $filename;
            try {
                //Resmi yüklüyoruz hata olursa bunu yakalayıp kullanıcıya göstereceğiz
                Helper::image_convert($request->cover_image, $directory, $filename);
            }catch (\Exception $e){
                return redirect()->back()->with('error','Uygun resim formatı seçiniz');
            }
            $category->cover_image = $path;
        }
        //Veritabanı sonucuna göre kullanıcıyı bilgilendiriyoruz
        if ($category->save()) {
            return redirect()->route('categories.index')->with('success', 'Kategori Güncellendi');
        } else {
            return redirect()->route('categories.index')->with('error', 'Kategori Güncellenemedi');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->cover_image != null){
            Helper::file_delete($category->cover_image);
        }
        //Veritabanı sonucuna göre kullanıcıyı bilgilendiriyoruz
        if ($category->delete()) {
            return response()->json(['status'=>200,'data'=>'success']);
        } else {
            return response()->json(['status'=>401,'data'=>'error']);
        }
    }
}
