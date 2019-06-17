/*
* Varsayılan Sabit değerler
* */
const api_url = 'http://rahatlatici_sesler.local/api/';
const market_url = 'https://play.google.com/store';
const version = $('meta[name="version"]').attr('content');
const current_page = window.location.pathname;

/*
* Kişinin bilgilerini Cookie ile alıyoruz
* Bu kısımdaki değişkenler fonksiyonlardan çağrılacağı için herhangi bir dinleyiciyle almamıza gerek yok
* */
let username = getCookie('username');
let email = getCookie('email');
let id = getCookie('id');
let api_token = getCookie('api_token');


$(document).ready(function () {
    /* Sayfamız hazır olunca sırasıyla versiyon kontrolü yapıyoruz
    * */
    versionControl();
/* Versiyon kontrol başarılı olursa login'i kontrol ediyoruz */
    if (loginControl()) {
        /* giriş yapılmış fakat login sayfasındaysak anasayfaya yönlendiriyoruz */
        if (current_page === '/login') {
            redirect('/');
        }
        /* kitaplığı ve favorileri çağırıyoruz */
        getCategories();
        getFavorites();
        /* giriş yapıldığı için navbarı gösteriyoruz */
        $('.navbar').show();
        $('#username').text(username);
    } else {
        /* giriş yapılmadıysa navbarı gizliyoruz ve login sayfasında değilsek login sayfasına yönlendiriyoruz */
        $('.navbar').hide();
        if (current_page !== '/login') {
            redirect('login');
        }
    }
});

/* Void mantıklı versiyon kontrol fonksiyonu
 * GET methodu ile mevcut versiyonun meta tagında yazan değerini sunucuya gönderiyoruz
 * gelen sonuca göre tepki verip updateApplication() fonsiyonunu çalıştırıyoruz
 *  */
function versionControl() {
    return $.get({
        data: {'version': version},
        url: api_url + 'versionControl'
    }).done(function (data) {
        if (data.status === 200) {
            return false;
        } else {
            updateApplication();
        }
    });
}

/* Versiyon sürümü eskiyse market url'ine yönlendirme yapılıyor */
function updateApplication() {
    redirect(market_url);
}

/* Giriş yapılmış mı diye çerezlerdeki verilerin değerini kontrol eder
* Sonuc true veya false olabilir
* */
function loginControl() {
    return !(username === '' || email === '' || id === '' || api_token === '');
}

/* çıkış fonksiyonu
 * cerezlerdeki bütün kullanıcı bilgilerini temizleyecek fonksiyonu çağırıp login sayfasına yönlendirir
 *  */
function logout() {
    removeAuth();
    redirect('login');
}

/*
* Kategoriler ve içerisindeki müzikleri GET methoduyla alıyoruz
* giriş yapan kişiyi Bearer Token sayesinde tanıyoruz
* */
function getCategories() {
    $.get({
        data: {version: version},
        /* Veri alabilmek için çerezlerde kayıtlı olan Bearer tokenimizi göndermek zorundayız */
        headers: {
            'Authorization': 'Bearer ' + api_token,
            'Accept': 'application/json',
        },
        url: api_url + 'user'
    }).done(function (data) {
        /* Uygulama sürümünde ve kullanıcı doğrulamasında sorun yoksa işlemleri başlatıyoruz */
        if (data.status === 200) {
            /* Kategorileri yazdırıyoruz */
            data.data.categories.forEach(function (category) {
                $('#categories').append(category_cart(category));
                /* Kategorilerin içerisindeki müzikleri DOM öğesi olarak sayfamıza ekliyoruz */
                category.musics.forEach(function (music) {
                    /*  */
                    $('#categories').next().append('<div class="musics" id="musics' + category.id + '" style="display: none"></div>');
                    $('#musics' + category.id).append(music_cart(music, id));
                });
            });
        } else if (data.status === 410) {
            /* uygulama sürümümüz eskiyse */
            updateApplication();
        } else {
            /* sunucu bize farklı bir durum kodu dönerse hata mesajını yazdırıyoruz */
            snackbar('error', data.message);
        }
    }).fail(function (response) {
        /* get işlemi başarısız olursa */
        snackbar('error', response);
    });
}

/*
* Favorilere eklenmiş müzikleri GET methoduyla alıyoruz
* giriş yapan kişiyi Bearer Token sayesinde tanıyoruz
* */
function getFavorites() {
    $.get({
        /* Veri alabilmek için çerezlerde kayıtlı olan Bearer tokenimizi göndermek zorundayız */
        headers: {
            'Authorization': 'Bearer ' + api_token,
            'Accept': 'application/json',
        },
        url: api_url + 'user/favorites'
    }).done(function (data) {
        /* Uygulama sürümünde ve kullanıcı doğrulamasında sorun yoksa işlemleri başlatıyoruz */
        if (data.status === 200) {
            /* Favori müzikleri yazdırıyoruz */
            data.data.forEach(function (favorite) {
                /* Müzikleri Ekliyoruz */
                $('#favorites').append(music_cart(favorite.music, 'favorite'));
            });
        } else {
            /* sunucu bize farklı bir durum kodu dönerse hata mesajını yazdırıyoruz */
            snackbar('error', data.message);
        }
    }).fail(function (response) {
        /* get işlemi başarısız olursa */
        snackbar('error', response);
    });
}

/* favori fonksiyonumuza gönderilen parametreler:
 *  butonun kendisi,
 *  içeriğin idsi
 *  API bağlantı tipleri store ve delete,
 *  POST-DELETE methodları
 *  kaynağın favori mi yoksa kategori mi olduğu */
function favorite(elem, id, type, method, favorite) {
    $.post({
        type: method,
        data: {music_id: id},
        /* Veri alabilmek için çerezlerde kayıtlı olan Bearer tokenimizi göndermek zorundayız */
        headers: {
            'Authorization': 'Bearer ' + api_token,
            'Accept': 'application/json',
        },
        url: api_url + 'user/favorites/' + type
    }).done(function (data) {
        /* Sunucudaki işlem başarılı olursa butonun tipini tersine çevireceğiz aksi halde uyarı mesajı göstereceğiz */
        if (data.status === 200) {
            /* yapılan işlem silme işlemiyse */
            if (type === 'delete') {
                /* buton favori öğelerinden birine aitse */
                if (favorite === 'favorite') {
                    /* butonun ebeveynini siliyoruz */
                    $(elem).parent().parent().parent().parent().remove();
                }else{
                    /* buton favoriye ait değil yani kategoriler içerisinden favoriden silinmişse favoriler içerisindeki öğeyi siliyoruz */
                    $('#favorites').find('.music_id_'+id).remove();
                }
                /* bu müziğe ait bütün butonları tersine çeviriyoruz */
                $('.fav_btn_'+id).removeClass('btn-danger').addClass('btn-success').text('Favoriye Ekle').attr('onclick', 'favorite(this,\'' + id + '\',\'store\',\'post\',\'' + favorite + '\')');
            } else {
                if (favorite !== 'favorite') {
                    /* kaynak favorite değilse yani kategoriler içerisinden favoriye eklenmişse müziği kopyalayıp favorilere ekliyoruz */
                    $( '.music_id_'+id ).clone().appendTo( "#favorites" );
                }
                /* butonu tersine çeviriyoruz işlemi class ile yaptığımız için kopyaladığımız öğe de bundan etkilenecek */
                $('.fav_btn_'+id).removeClass('btn-success').addClass('btn-danger').text('Favoriden Çıkar').attr('onclick', 'favorite(this,\'' + id + '\',\'delete\',\'delete\',\'' + favorite + '\')');
            }
            /* sunucudan aldığımız mesajı yazdırıyoruz */
            snackbar('success', data.message);
        } else {
            /* sunucu bize farklı bir durum kodu dönerse hata mesajını yazdırıyoruz */
            snackbar('error', data.message);
        }
    }).fail(function (response) {
        /* post işlemi başarısız olursa */
        snackbar('error', response);
    });
}

/* yönlendirme fonksiyonu */
function redirect(url) {

    window.location.href = url;
}

/* Sayfa değiştirme fonksiyonu
 * bütün öğeleri gizler sadece seçilen öğeyi gösterir
 *  */
function pageChange(elem, page) {
    $('.nav-item').removeClass('active');
    $(elem).addClass('active');
    $('.page_tab').hide();
    $('#' + page).show();
}

/* kategori içeriği gösterim fonksiyonu
 * kategori içeriğini html öğesi olarak eklediğimiz için ilgili div'i gösteriyoruz ve categoriler öğesini gizliyoruz */
function getCategory(id) {
    $('#categories').hide();
    $('#musics').show();
    $('#musics' + id).show();
}

/* Kategori içeriklerini gizleyip kategorilerin kendisini gösteriyoruz */
function categoryBack() {
    $('#categories').show();
    $('#musics').hide();
    $('.musics').hide();
}

/* Giriş verilerini API'a iletim fonksiyonu */
function authPost(type) {
    var form = $('#form_' + type);
    var formData = form.serializeArray();
    $.post({
        type: 'post',
        data: formData,
        url: api_url + type
    }).done(function (data) {
        /* Giriş başarılıysa gelen verileri çerezlere saveAuth() ile yazdırıp ana sayfaya yönlendirme yapıyoruz */
        if (data.status === 200) {
            /* sunucudan aldığımız mesajı yazdırıyoruz */
            snackbar('success', data.message);
            saveAuth(data);
            redirect('/');
            form[0].reset();
        } else {
            /* Giriş başarısız ise gelen mesajı ekranda gösteriyoruz */
            snackbar('error', data.message);
        }
    }).fail(function (response) {
        /* post işlemi başarısız olursa dönen sonucu ekrana yazdırıyoruz */
        snackbar('error', response);
    });
}

/*Bildirim elementi gösterim fonksiyonu */
function snackbar(type, text) {
    var snackbar = $(".snackbar");
    snackbar.text(text);
    /* snackbar gösteriliyor */
    switch (type) {
        case "success":
            /* barın arka plan rengi ayarlanıyor */
            snackbar.addClass("yesil");
            break;
        case "error":
            /* barın arka plan rengi ayarlanıyor */
            snackbar.addClass("kirmizi");
            break;
    }
    snackbar.addClass("show");
    /* snackbar 3 saniye sonra gizlenecek */
    setTimeout(function () {
        snackbar.removeClass("show");
    }, 3000);
}

/* Çerez Oluşturma fonksiyonu */
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

/* Hafızadaki çerezin değerini çekme fonksiyonu */
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
/* Çerez silme fonsiyonu */
function eraseCookie(name) {
    /* son kullanım tarihi geçen çerezler boş değer alır mantığıyla eksi bir değer veriyoruz */
    document.cookie = name + '=; Max-Age=-99999999;';
}

function saveAuth(data) {
    /* Giriş yapan kişinin bilgileri çerezlere kaydediliyor */
    setCookie('username', data.username, 365);
    setCookie('email', data.email, 365);
    setCookie('id', data.id, 365);
    setCookie('api_token', data.api_token, 365);
}

function removeAuth() {
    /* Çıkış yapılınca çerez verilerini siliyoruz */
    eraseCookie('username');
    eraseCookie('email');
    eraseCookie('id');
    eraseCookie('api_token');
}

/* Kategori nesnesini fonksiyonların içini temiz tutmak amacıyla bu fonksiyondan alıyoruz
 * Json objesini parametre olarak alıp html öğemizin içerisinde verileri gerekli yerlere yerleştiriyoruz.
 * */
function category_cart(data) {
    /* HTML öğemizi geri dönüyoruz */
    return '<div onclick="getCategory(\'' + data.id + '\')" class="card w-100 text-center mx-auto mb-3" style="height: 150px;overflow: hidden">\n' +
        '                        <img class="card-img-top" src="' + data.cover_image + '" alt="' + data.name + '">\n' +
        '                        <div class="card-img-overlay">\n' +
        '                            <h4 class="card-text mt-5 p-2" style="background: rgba(255,255,255,0.6);">' + data.name + '</h4>\n' +
        '                        </div>\n' +
        '                    </div>';
}

/* Müzik nesnesini fonksiyonların içini temiz tutmak amacıyla bu fonksiyondan alıyoruz
 * Json objesini parametre olarak alıp html öğemizin içerisinde verileri gerekli yerlere yerleştiriyoruz.
 * burada önemli nokta 'favorite' parametresi kategorilerin altındaki müziklerde zaten is favorite isimli bir obje olması
 * favorilerin içerisinde ise böyle bir obje yok çünkü gelen müziklerin hepsi zaten favori müzikler.
 * favorite parametresi boş ise müzikler kategorilerin içeriği olanlardır.
 * */
function music_cart(data, favorite = null) {
    /* Favoriye ekle ve çıkar butonumuzu hazırlıyoruz */
    var favorite_button;
    /* bu müzik favori müzik ise favoriden çıkar değilse favoriye ekle butonu gelecek */
    if (data.is_favorite === null || favorite === null) {
        /*favorite fonksiyonumuza ekleme yapacağımızı ve kaynağımızı gönderiyoruz*/
        favorite_button = '<button onclick="favorite(this,\'' + data.id + '\',\'store\',\'post\',\'' + favorite + '\')" class="btn btn-success fav_btn_'+data.id+'">Favoriye Ekle</button>';
    } else {
        /* favorite fonksiyonumuza silme yapacağımızı ve kaynağımızı gönderiyoruz */
        favorite_button = '<button onclick="favorite(this,\'' + data.id + '\',\'delete\',\'delete\',\'' + favorite + '\')" class="btn btn-danger fav_btn_'+data.id+'">Favoriden Çıkar</button>';
    }

    /* HTML öğemizi geri dönüyoruz */
    return '<div class="card bg-light mb-3 music_id_'+data.id+'" style="height: 150px;overflow: hidden">\n' +
        '                            <img class="card-img-top" src="' + data.cover_image + '" alt="' + data.name + '">\n' +
        '                            <div class="card-img-overlay">\n' +
        '                                <div class="row">\n' +
        '                                    <h4 class="col-md-8 p-2" style="background: rgba(255,255,255,0.6);">' + data.name + '</h4>\n' +
        '                                    <div class="col-md-4">\n' +
        favorite_button +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                                <audio class="w-100 align-middle mt-2" controls>\n' +
        '                                    <source src="' + data.source + '" type="audio/mpeg">\n' +
        '                                    Your browser does not support the audio element.\n' +
        '                                </audio>\n' +
        '                            </div>\n' +
        '                        </div>';
}
