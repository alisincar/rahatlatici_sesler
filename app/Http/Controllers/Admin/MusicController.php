<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Music;
use Illuminate\Http\Request;

class MusicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Müzikler';
        $musics = Music::paginate(20);
        return view('admin.musics.index', compact('title', 'musics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Müzik Ekle';
        $categories = Category::where('status', '1')->get();
        //Aktif Kategori yoksa kategori ekleme sayfasına yönlendiriyoruz
        if($categories->count()==0){
            return redirect()->route('categories.create')->with('error','Hiç aktif kategori yok müzik ekleyebilmek için aktif bir kategori ekleyiniz');
        }
        return view('admin.musics.add', compact('title', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $music = Music::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'status' => $request->status
        ]);
        //Müzik Seçişmişse yükleme işini yapıyoruz
        if ($request->hasFile('source')) {
            try {
                //Müziği yüklüyoruz hata olursa bunu yakalayıp kullanıcıya göstereceğiz
                $music_name = Helper::file_upload($request);
            }catch (\Exception $e){
                return redirect()->back()->with('error','Müzik Eklenirken bir hata oluştu');
            }
            $music->source=$music_name;
        }
        $directory = "uploads/musics_cover/";
        $filename = rand(1111, 9999) . time() . '.' . $request->cover_image->getClientOriginalExtension();
        $path = $directory . "/" . $filename;
        try {
            //Resmi yüklüyoruz hata olursa bunu yakalayıp kullanıcıya göstereceğiz
            Helper::image_convert($request->cover_image, $directory, $filename);
        }catch (\Exception $e){
            return redirect()->back()->with('error','Uygun resim formatı seçiniz');
        }
        $music->cover_image = $path;
        //Veritabanı sonucuna göre kullanıcıyı bilgilendiriyoruz
        if ($music->save()) {
            return redirect()->route('musics.index')->with('success', 'Müzik Eklendi');
        } else {
            return redirect()->route('musics.index')->with('error', 'Müzik Eklenemedi');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Music $music
     * @return \Illuminate\Http\Response
     */
    public function show(Music $music)
    {
        $title = 'Müzik Görüntüle';
        return view('admin.musics.index', compact('title', 'music'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Music $music
     * @return \Illuminate\Http\Response
     */
    public function edit(Music $music)
    {
        $title = 'Müzik Düzenle';
        $categories = Category::where('status', '1')->get();
        return view('admin.musics.edit', compact('title', 'music', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Music $music
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Music $music)
    {
        $music->name = $request->name;
        $music->category_id = $request->category_id;
        $music->status = $request->status;

        if ($request->hasFile('source')) {

            if($music->source != null){
                //Mevcut Müziği siliyoruz
                Helper::file_delete($music->source);
            }
            try {
                //Müziği yüklüyoruz hata olursa bunu yakalayıp kullanıcıya göstereceğiz
                $music_name = Helper::file_upload($request);
            }catch (\Exception $e){
                return redirect()->back()->with('error','Müzik Eklenirken bir hata oluştu');
            }
            $music->source=$music_name;
        }
        //Güncelleme kısmında olduğumuz için tekrar resim seçilmediyse resim işlemlerini atlıyoruz
        if($request->hasFile('cover_image')) {
            //Mevcut Resmi siliyoruz
            if($music->cover_image != null){
                Helper::file_delete($music->cover_image);
            }
            $directory = "uploads/musics_cover";
            $filename = rand(1111, 9999) . time() . '.' . $request->cover_image->getClientOriginalExtension();
            $path = $directory . "/" . $filename;
            try {
                //Resmi yüklüyoruz hata olursa bunu yakalayıp kullanıcıya göstereceğiz
                Helper::image_convert($request->cover_image, $directory, $filename);
            }catch (\Exception $e){
                return redirect()->back()->with('error','Uygun resim formatı seçiniz');
            }
            $music->cover_image = $path;
        }
        //Veritabanı sonucuna göre kullanıcıyı bilgilendiriyoruz
        if ($music->save()) {
            return redirect()->route('musics.index')->with('success', 'Müzik Güncellendi');
        } else {
            return redirect()->route('musics.index')->with('error', 'Müzik Güncellenemedi');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Music $music
     * @return \Illuminate\Http\Response
     */
    public function destroy(Music $music)
    {
        if($music->cover_image != null){
            Helper::file_delete($music->cover_image);
        }
        if($music->source != null){
            Helper::file_delete($music->source);
        }
        if ($music->delete()) {
            return response()->json(['status'=>200,'data'=>'success']);
        } else {
            return response()->json(['status'=>401,'data'=>'error']);
        }
    }
}
