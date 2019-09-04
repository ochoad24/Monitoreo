/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vuetify from 'vuetify';
Vue.use(Vuetify);

import swal from 'sweetalert2';
window.swal = swal;

import multiselect from 'vue-multiselect';
window.multiselect = multiselect;
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('Rol', require('./components/Rol.vue').default);
Vue.component('Permiso', require('./components/Permiso.vue').default);
Vue.component('proyecto', require('./components/Proyecto.vue').default);
Vue.component('organizacion', require('./components/Organizacion.vue').default);
Vue.component('Usuario', require('./components/Usuario.vue').default);
Vue.component('Actividad', require('./components/Actividad.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
    data: {
        menu: 0,
        ruta: 'Roles',
        proyectos: [],
        proyecto:{
            id:0,
            nombre:''
        }
    },
    components: {
        multiselect
    },
    methods: {
        cargarProyecto() {
            let me=this;
            fetch('/proyecto/select')
            .then(function(response) {
                return response.json();
            })
            .then(function(myJson) {
                me.proyectos=myJson;
                // console.log(me.proyectos);
            });
        },
    },
    created() {
        this.cargarProyecto();
    },
    watch: {
        proyecto(val) {
            if(val)
            {
                this.$root.$emit("SeleccionProyecto", val.id);
                console.log(val);
            }else{
                this.$root.$emit("SeleccionProyecto", 0);
                swal.fire({
                    type: 'warning',
                    title: 'Advertencia',
                    text: 'Por favor seleccione un proyecto',
                })
            }
        }
    }, 
});
