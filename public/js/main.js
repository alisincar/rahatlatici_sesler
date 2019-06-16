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
        getData();
    } else {
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

function getData() {
    $.get({
        data: {version: version},
        headers: {
            'Authorization': 'Bearer ' + api_token,
            'Accept': 'application/json',
        },
        url: api_url + 'user'
    }).done(function (data) {
        if (data.status === 200) {
            data.data.categories.forEach(function (category) {
                $('#categories').append(category_cart(category));
                category.musics.forEach(function (music) {
                    $('#categories').next().html('<button onclick="categoryBack()" class="btn btn-success"><i class="fa fa-arrow-left"></i> Geri</button><div class="musics" id="musics'+category.id+'" style="display: none"></div>');
                    $('#musics'+category.id).append(music_cart(music));
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

function redirect(url) {
    window.location.href = url;
}

function getCategory(id) {
    $('#categories').hide();
    $('#musics').show();
    $('#musics'+id).show();
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
    return '<div onclick="getCategory(\''+data.id+'\')" class="card w-100 text-center mx-auto mb-3" style="height: 150px;overflow: hidden">\n' +
        '                        <img class="card-img-top" src="' + data.cover_image + '" alt="' + data.name + '">\n' +
        '                        <div class="card-img-overlay">\n' +
        '                            <h4 class="card-text mt-5">' + data.name + '</h4>\n' +
        '                        </div>\n' +
        '                    </div>';
}

function music_cart(data) {
    return '<div class="card bg-light mb-3" style="height: 150px;overflow: hidden">\n' +
        '                            <img class="card-img-top" src="' + data.cover_image + '" alt="' + data.name + '">\n' +
        '                            <div class="card-img-overlay">\n' +
        '                                <div class="row">\n' +
        '                                    <h4 class="col-md-8">' + data.name + '</h4>\n' +
        '                                    <div class="col-md-4">\n' +
        '                                        <button class=" btn btn-success">Favoriye Ekle</button>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                                <audio class="w-100 align-middle mt-2" controls>\n' +
        '                                    <source src="" type="audio/mpeg">\n' +
        '                                    Your browser does not support the audio element.\n' +
        '                                </audio>\n' +
        '                            </div>\n' +
        '                        </div>';
}
