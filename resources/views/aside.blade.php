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
                                class="fas fa-user"></i></span><span class="pcoded-mtext">Usuarios</span></a>
                    <ul class="pcoded-submenu">
                        <li><a @click="menu=0" v-on:click="ruta='Roles'">Roles</a></li>
                        <li><a @click="menu=1" v-on:click="ruta='Permisos'">Permisos</a></li>
                        <li><a @click="menu=4" v-on:click="ruta='Usuarios'">Usuarios<span></a></li>   
                    </ul>
                </li>
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project"
                    class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i
                                class="fas fa-folder"></i></span><span class="pcoded-mtext">Proyectos</span></a>
                    <ul class="pcoded-submenu">
                        <li><a @click="menu=2" v-on:click="ruta='Organizaciones'">Organizaciones</a></li>
                        <li><a @click="menu=3" v-on:click="ruta='Proyectos'">Proyectos<span></a></li>
                        
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->