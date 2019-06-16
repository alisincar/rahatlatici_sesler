<nav class="navbar fixed-bottom navbar-expand navbar-dark bg-primary">
    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">

        <h4 class=" btn-outline-light p-2 m-2" id="username"></h4>
        <button onclick="logout()" id="logout_btn" class="btn btn-danger">Çıkış</button>

        <ul class="navbar-nav mr-auto mt-2 mt-lg-0 mx-auto">
            <li class="btn nav-item active" onclick="pageChange(this,'categories');">
                <h4 class="nav-link">Kitaplık</h4>
            </li>
            <li class="btn nav-item border-left" onclick="pageChange(this,'favorites');">
                <h4 class="nav-link">Favorilerim</h4>
            </li>
        </ul>
    </div>
</nav>
