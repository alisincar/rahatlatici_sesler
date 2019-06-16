<nav class="navbar fixed-bottom navbar-expand navbar-dark bg-primary">
    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <div style="position: absolute">
            <h5 class=" btn-outline-light m-1" id="username"></h5>
            <button onclick="logout()" id="logout_btn" class="btn btn-sm btn-danger">Çıkış</button>
        </div>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0 mx-auto">
            <li class="btn nav-item active" onclick="pageChange(this,'categories');">
                <h5 class="nav-link">Kitaplık</h5>
            </li>
            <li class="btn nav-item border-left" onclick="pageChange(this,'favorites');">
                <h5 class="nav-link">Favorilerim</h5>
            </li>
        </ul>

    </div>
</nav>
