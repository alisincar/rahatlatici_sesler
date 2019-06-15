<?php

namespace App\Helpers;

use Intervention\Image\Facades\Image;
use Iyzipay\Request;

class Helper
{
    /*
     * Resim Yükleme Fonksiyonu
     * Controllerdan request aracılığıyla alınan veriler işlenir
     * klasör yoksa yeni klasör açar varsa mevcut klasöre dosyayı yazar
     *
     * */
    public static function image_convert($image, $directory, $filename)
    {
        if ($image != null) {
            if (file_exists($directory)) {
                Image::make($image->getRealPath())->save(public_path("{$directory}/{$filename}"));
            } else {
                if (mkdir($directory, 0755, true)) {
                    Image::make($image->getRealPath())->save(public_path("{$directory}/{$filename}"));
                } else {
                    return redirect()->back()->with('error', 'Resim yüklerken hata oluştu');
                }
            }
        } else {
            return redirect()->back()->with('error', 'Resim seçilmedi');
        }
    }

    public static function file_delete($path)
    {
        if (file_exists(public_path($path))) {
            return unlink(public_path($path));
        }else{
            return false;
        }
    }

    /*
     * Dosya Yükleme fonksiyonu
     * Dosya uzantısına göre klasör açar ve dosyayı o dosyaya yerleştirir
     * Çıktı olarak dosyanın yolunu geri döner
     * */
    public static function file_upload($request)
    {
        $file = $request->file('source');
        $extention = $file->getClientOriginalExtension();
        $name = str_slug($request->name) . '.' . $extention;
        $location = '/uploads/musics/' . $extention;
        $path = public_path($location);
        $file->move($path, $name);
        return $location . '/' . $name;
    }
}
