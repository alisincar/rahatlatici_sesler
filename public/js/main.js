var api_url = 'http://rahatlatici_sesler.local/api/';
var market_url = 'https://play.google.com/store';
var username = getCookie('username');
var email = getCookie('email');
var id = getCookie('id');
var api_token = getCookie('api_token');
var version = $('meta[name="version"]').attr('content');
var current_page = window.location.pathname;

$(document).ready(function () {
    versionControl();

    if (loginControl()) {
        if (current_page === '/login') {
            redirect('/');
        }
        getCategories();
        getFavorites();
        $('.navbar').show();
        $('#username').text(username);
    } else {
        $('.navbar').hide();
        if (current_page !== '/login') {
            redirect('login');
        }
    }
});

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

function updateApplication() {
    redirect(market_url);
}

function loginControl() {
    return !(username === '' || email === '' || id === '' || api_token === '');
}

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
            updateApplication();
        } else {
            snackbar('error', data.message);
        }
    }).fail(function (response) {
        snackbar('error', response);
    });
}

/*
* Favorilere eklenmiş müzikleri GET methoduyla alıyoruz
* giriş yapan kişiyi Bearer Token sayesinde tanıyoruz
* */
function getFavorites() {
    $.get({
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
            snackbar('error', data.message);
        }
    }).fail(function (response) {
        snackbar('error', response);
    });
}

function favorite(elem, id, type, method, favorite) {

    $.post({
        type: method,
        data: {music_id: id},
        headers: {
            'Authorization': 'Bearer ' + api_token,
            'Accept': 'application/json',
        },
        url: api_url + 'user/favorites/' + type
    }).done(function (data) {
        if (data.status === 200) {
            if (type === 'delete') {
                if (favorite === 'favorite') {
                    $(elem).parent().parent().parent().parent().remove();
                }else{
                    $('#favorites').find('.music_id_'+id).remove();
                }
                $('.fav_btn_'+id).removeClass('btn-danger').addClass('btn-success').text('Favoriye Ekle').attr('onclick', 'favorite(this,\'' + id + '\',\'store\',\'post\',\'' + favorite + '\')');
            } else {
                if (favorite !== 'favorite') {
                    $( '.music_id_'+id ).clone().appendTo( "#favorites" );
                }
                $('.fav_btn_'+id).removeClass('btn-success').addClass('btn-danger').text('Favoriden Çıkar').attr('onclick', 'favorite(this,\'' + id + '\',\'delete\',\'delete\',\'' + favorite + '\')');
            }
            snackbar('success', data.message);
        } else {
            snackbar('error', data.message);
        }
    }).fail(function (response) {
        snackbar('error', response);
    });
}

function redirect(url) {
    window.location.href = url;
}

function pageChange(elem, page) {
    $('.nav-item').removeClass('active');
    $(elem).addClass('active');
    $('.page_tab').hide();
    $('#' + page).show();
}

function getCategory(id) {
    $('#categories').hide();
    $('#musics').show();
    $('#musics' + id).show();
}

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
        if (data.status === 200) {
            snackbar('success', data.message);
            saveAuth(data);
            redirect('/');
            form[0].reset();
        } else {
            snackbar('error', data.message);
        }
    }).fail(function (response) {
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
            snackbar.addClass("yesil");
            break;
        case "error":
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

function eraseCookie(name) {
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

function category_cart(data) {
    return '<div onclick="getCategory(\'' + data.id + '\')" class="card w-100 text-center mx-auto mb-3" style="height: 150px;overflow: hidden">\n' +
        '                        <img class="card-img-top" src="' + data.cover_image + '" alt="' + data.name + '">\n' +
        '                        <div class="card-img-overlay">\n' +
        '                            <h4 class="card-text mt-5 p-2" style="background: rgba(255,255,255,0.6);">' + data.name + '</h4>\n' +
        '                        </div>\n' +
        '                    </div>';
}

function music_cart(data, favorite = null) {
    var favorite_button;
    if (data.is_favorite === null || favorite === null) {
        favorite_button = '<button onclick="favorite(this,\'' + data.id + '\',\'store\',\'post\',\'' + favorite + '\')" class="btn btn-success fav_btn_'+data.id+'">Favoriye Ekle</button>';
    } else {
        favorite_button = '<button onclick="favorite(this,\'' + data.id + '\',\'delete\',\'delete\',\'' + favorite + '\')" class="btn btn-danger fav_btn_'+data.id+'">Favoriden Çıkar</button>';
    }
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
