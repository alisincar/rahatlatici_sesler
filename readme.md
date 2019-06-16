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
