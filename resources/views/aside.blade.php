<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            <a href="index.html" class="b-brand">
                <div class="b-bg">
                    <i class="feather icon-trending-up"></i>
                </div>
                <span class="b-title">Menu</span>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item pcoded-menu-caption">
                    <label>Usuarios</label>
                </li>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project"
                    class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i
                                class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                    <ul class="pcoded-submenu">
                        <li class=""><a @click="menu=0" v-on:click="ruta='Roles'">Roles</a></li>
                        <li class=""><a @click="menu=1" v-on:click="ruta='Permisos'">Permisos</a></li>
                        <li class=""><a @click="menu=2">Categoria 1</a></li>
                        <li class=""><a @click="menu=3">Categoria 2<span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->