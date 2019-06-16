### Özellikler

- Yönetici Paneli
	- Kategori ekleme düzenleme silme
	- Müzik ekleme düzenleme silme
	- Dashboard
- Ön Yüz
	- Üyelik (Kayıt - Giriş)
	- Kitaplık
	- Favoriler (ekleme - çıkarma)
	- Version kontrolü
- API
	- version kontrol
	- üye bilgisi
	- kategorilerin içerisindeki müziklerle birlikte listelenmesi
	- sadece bir kategorinin içerisindeki müziklerin listelenmesi
	- Favori listeleme ekleme ve silme
	- Üyelik kayıt ve giriş


# Kurulum
Laradock nginx içerisinde rahatlatici_sesler.conf dosyası oluşturup ardından içerisi aşağıdaki gibi yapılandırılmalı

        server {
        
            listen 80;
            listen [::]:80;
        
            # For https
            # listen 443 ssl;
            # listen [::]:443 ssl ipv6only=on;
            # ssl_certificate /etc/nginx/ssl/default.crt;
            # ssl_certificate_key /etc/nginx/ssl/default.key;
        
            server_name rahatlatici_sesler.local;
            root /var/www/rahatlatici_sesler/public;
            index index.php index.html index.htm;
        
            location / {
                 try_files $uri $uri/ /index.php$is_args$args;
            }
        
            location ~ \.php$ {
                try_files $uri /index.php =404;
                fastcgi_pass php-upstream;
                fastcgi_index index.php;
                fastcgi_buffers 16 16k;
                fastcgi_buffer_size 32k;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                #fixes timeouts
                fastcgi_read_timeout 600;
                include fastcgi_params;
            }
        
            location ~ /\.ht {
                deny all;
            }
        
            location /.well-known/acme-challenge/ {
                root /var/www/letsencrypt/;
                log_not_found off;
            }
        
            error_log /var/log/nginx/rahatlatici_sesler_error.log;
            access_log /var/log/nginx/rahatlatici_sesler_access.log;
        }
ardından 
    
    laradock içerisinde docker-compose up -d nginx mysql

env ve migration yapılandırmasından sonra projeyi çalıştırabilirsiniz.

# Teknik

**Uygulamanın teknik özeti**

Uygulama şeması şu şekildedir:

Kategoriler, Müzikler, Üyeler ve Yöneticiler olmak üzere 4 veritabanı tablosu bulunmaktadır.

Laravel yapısı ile yönetici paneli dizayn edilmiştir.

Ön yüz Html ve javascript+jQuery kullanılarak yapılandırılmıştır.

API Bearer token doğrulama methodu ile api Auth kullanılarak yapılandırılmıştır.

**API bağlantı methodları**
--
    login->Post  [https://site.com/api/login] parametreler: {email,password}
    register->Post [https://site.com/api/register] parametreler: {email,password,name}


    version kontrol->post [https://site.com/api/versionControl] parametreler: {version}

    kitaplık ve kullanıcı bilgisi->get [https://site.com/api/user/] parametreler: {version}

    kategori ve içerisindeki müzikler->get [https://site.com/api/user/category]  parametreler: {category_id}

    favoriler->get [https://site.com/api/user/favorites/] 

    favori ekleme->post [https://site.com/api/user/favorites/store] parametreler: {music_id}

    favori silme->delete [https://site.com/api/user/favorites/delete] parametreler: {music_id}

**Login Register ve versiyon kontrol dışında bütün bağlantılara aşağıdaki headers bilgileri gönderilmelidir**

    Authorization : Bearer {token}

    Accept : application/json
