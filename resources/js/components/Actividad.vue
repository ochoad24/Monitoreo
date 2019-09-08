<template>
    <div>
        <v-toolbar flat color="white">
            <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
            <v-spacer></v-spacer>
            <!--    
                DIALOGO PARA LA CREACION DE PROYECTO    
            -->
            <v-dialog v-model="dialog" persistent max-width="60%" max-height="800">
                <template v-slot:activator="{ on }">
                    <v-btn color="green darken-1" dark class="mb-2" v-on="on" @click="editar=0">Nueva Actividad</v-btn>
                </template>
                <v-card>
                    <v-toolbar dark color="green darken-1">
                        <v-btn icon dark @click="dialog = false">
                            <v-icon col="white">clear</v-icon>
                        </v-btn>
                        <v-toolbar-title>{{ formTitle }}</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-text-field v-model="actividad" label="Ingrese nombre de la actividad"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md6>
                                    <!-- DateTime Picker de Fecha Inicial --> 
                                    <v-menu
                                        ref="menu"
                                        v-model="menu"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        :return-value.sync="fechaInicio"
                                        lazy
                                        transition="scale-transition"
                                        offset-y
                                        full-width
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field 
                                                v-model="fechaInicio" 
                                                label="Ingrese fecha de inicio"
                                                prepend-icon="event"
                                                readonly
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker v-model="fechaInicio" no-title scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="menu = false">Cancelar</v-btn>
                                            <v-btn flat color="primary" @click="$refs.menu.save(fechaInicio)">Guardar</v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                    <!-- Fin del date Time Picker -->
                                </v-flex>
                                <v-flex xs12 sm12 md6>
                                    <!-- DateTime Picker de Fecha Inicial --> 
                                    <v-menu
                                        ref="menu2"
                                        v-model="menu2"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        :return-value.sync="fechaFinal"
                                        lazy
                                        transition="scale-transition"
                                        offset-y
                                        full-width
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field 
                                                v-model="fechaFinal" 
                                                label="Ingrese fecha de finalización"
                                                prepend-icon="event"
                                                readonly
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker v-model="fechaFinal" no-title scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="menu2 = false">Cancelar</v-btn>
                                            <v-btn flat color="primary" @click="$refs.menu2.save(fechaFinal)">Guardar</v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                    <!-- Fin del date Time Picker -->
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>

                     <template v-if="error">
                        <v-divider></v-divider>
                        <div class="text-xs-center" v-for="e in errorMsj" :key="e">
                            <strong class="red--text text--lighten-1" v-text="e"></strong>
                            <br>
                        </div>
                        <v-divider></v-divider>
                    </template>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="close">Cancelar</v-btn>
                        <v-btn color="blue darken-1" v-if="editar===0" flat @click="registrarActividad">Guardar</v-btn>
                        <v-btn color="blue darken-1" v-if="editar===1" flat @click="editarActividad()">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog><!-- Fin del dialogo crear proyecto -->
        </v-toolbar>


        <v-data-table :headers="headers" :items="actividades" class="elevation-1" :search="search">
            <template v-slot:items="props">
                <td class="text-xs-right">{{ props.item.actividad }}</td>
                <td class="text-xs-right">{{ props.item.tareas }}</td>
                <td class="text-xs-right">{{ props.item.tareasCompletadas }}</td>
                <td class="text-xs-right">{{ props.item.tareasPendientes }}</td>
                <td class="text-xs-right">{{ props.item.fechaInicio }}</td>
                <td class="text-xs-right">{{ props.item.fechaFinal }}</td>
                <td class="text-xs-center">
                    <template>
                        <div>
                            <v-chip color="green" text-color="white" v-if="props.item.estado">Activo</v-chip>
                            <v-chip color="red" text-color="white" v-else>Desactivado</v-chip>
                        </div>
                    </template>
                </td>
                <td class="justify-left layout px-0">
                    <v-icon small class="mr-2" @click="abrirEditar(props.item)">
                        edit
                    </v-icon>
                    <v-icon small class="mr-2" v-if="props.item.estado" @click="desactivar(props.item.id)">
                        block
                    </v-icon>
                    <v-icon small class="mr-2" v-else @click="activar(props.item.id)">
                        check_circle
                    </v-icon>
                    <v-icon small @click="deleteItem(props.item.id)">
                        delete
                    </v-icon>
                </td>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="initialize">Recargar</v-btn>
            </template>
            <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="warning">
                    No hay resultados de "{{ search }}".
                </v-alert>
            </template>
        </v-data-table>
    </div>
</template>
<script>
    import Multiselect from 'vue-multiselect';
    export default {
        components: {
            Multiselect
        },
        data: () => ({
            dialog: false,
            menu: false,
            menu2: false,
            idActividad: 0,
            editar: 0,
            actividad: '',
            fechaInicio: new Date().toISOString().substr(0, 10),
            fechaFinal: new Date().toISOString().substr(0, 10),
            search: '',
            headers: [
                { text: 'Actividad',value:'actividad' ,align: 'right'},
                { text: 'Tareas', value: 'tareas', align: 'right'},
                { text: 'Tareas Completadas', value: 'tareasCompletadas', align: 'right' },
                { text: 'Tareas Pendientes', value: 'tareasPendientes', align: 'right' },
                { text: 'Fecha de Inicio', value: 'fechaInicio', align: 'right' },
                { text: 'Fecha de Finalización', value: 'fechaFinal', align: 'right' },
                { text: 'Estado', value: 'estado', align: 'center' }
            ],
            error: 0,
            errorMsj: [],
            actividades: [],
            proyecto:0,
            editedIndex: -1,
            editedItem: {
                id: 0,
                actividad:'',
                tareas: 0,
                tareasCompletadas: 0,
                tareasPendientes:0,
                fechaInicio:new Date().toISOString().substr(0, 10),
                fechaFin:new Date().toISOString().substr(0, 10), 
                estado:0
            },
            defaultItem: {
                id: 0,
                actividad:'',
                tareas: 0,
                tareasCompletadas: 0,
                tareasPendientes:0,
                fechaInicio:new Date().toISOString().substr(0, 10),
                fechaFin:new Date().toISOString().substr(0, 10), 
                estado:0
            }
        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'Nueva Actividad' : 'Editar Actividad'
            }
        },

        watch: {
            dialog(val) {
                val || this.close()
            }
        },

        created() {
            this.initialize()
        },
        mounted(){
            let me=this;
            this.$root.$on('SeleccionProyecto', data => {
                if(data)
                {    
                    me.proyecto=data;
                    me.initialize();
                }else{
                    me.proyecto=0;
                    me.initialize();
                    swal.fire({
                    type: 'warning',
                    title: 'Advertencia',
                    text: 'Por favor seleccione un proyecto',
                    })
                }
                console.log(data);
            });
        },
        methods: {
            validate() {
                this.error = 0;
                this.errorMsj = [];
                if(!this.proyecto)
                    this.errorMsj.push('No ha seleccionado ningún proyecto. Por favor seleccione uno.')
                if (!this.actividad)
                    this.errorMsj.push('El nombre de la actividad no puede estar vacío');
                if (!this.fechaInicio)
                    this.errorMsj.push('La fecha de inicio de la actividad no puede estar vacía');
                if (!this.fechaFinal)
                    this.errorMsj.push('La fecha de finalización de la actividad no puede estar vacía');
                if (this.errorMsj.length)
                    this.error = 1;
                else 
                    this.error = 0;
                return this.error;
            },
            initialize() {
                var url='/Actividad?proyecto='+this.proyecto;
                axios.get(url)
                    .then(response => {
                        console.log(response.data);
                        this.actividades = response.data;
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            registrarActividad() {
                let me = this;
                if(this.validate() === 1) {
                    return;
                }
                axios.post('actividad/registrar', {
                    'actividad': me.actividad,
                    'fechaInicio': me.fechaInicio,
                    'fechaFinal': me.fechaFinal,
                    'idProyecto': me.proyecto
                })
                .then(function (response) {
                    console.log(response.data);
                    if(!response.data) {
                        swal.fire({
                            type: 'success',
                            title: 'Actividad Registrada!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else{
                        swal.fire({
                            type: 'error',
                            title: 'Se ha producido un error!',
                            text: `Error al registrar actividad!`
                        });
                        me.close();
                    }
                    
                    me.close();
                    me.initialize();
                })
                .catch(function (error) {
                    console.log(error.response);
                    swal.fire({
                        type: 'error',
                        title: 'Se ha producido un error!',
                        text: `Error al ingresar proyecto: ${error.response.data.message}`
                    });
                    me.close();
                });
            },
            editarActividad() {
                let me = this;
                if(this.validate() === 1) {
                    return;
                }
                axios.post('actividad/actualizar', {
                    'actividad': me.actividad,
                    'fechaInicio': me.fechaInicio,
                    'fechaFinal': me.fechaFinal,
                    'idProyecto': me.proyecto,
                    'id': me.idActividad
                })
                .then(function (response) {
                    console.log(response.data);
                    if(!response.data) {
                        swal.fire({
                            type: 'success',
                            title: 'Actividad editada!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else{
                        swal.fire({
                            type: 'error',
                            title: 'Se ha producido un error!',
                            text: `Error al editar actividad!`
                        });
                        me.close();
                    }
                    me.close();
                    me.initialize();
                })
                .catch(function (error) {
                    console.log(error.response);
                    swal.fire({
                        type: 'error',
                        title: 'Se ha producido un error!',
                        text: `Error al ingresar proyecto: ${error.response.data.message}`
                    });
                    me.close();
                });
            },
            activar(id){
                let me=this;
                const swalWithBootstrapButtons = swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false,
                });
                swalWithBootstrapButtons.fire({
                    title: '¿Quieres activar esta actividad?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButton: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if(result.value) {
                        axios.put('actividad/activate', {
                            'id': id
                        }).then(function (response) {
                            me.initialize();
                            swalWithBootstrapButtons.fire(
                                'Activado',
                                'La actividad ha sido activada!',
                                'success'
                            )
                        }).catch(function (error) {
                            swalWithBootstrapButtons.fire(
                                'Error',
                                'Error al activar actividad!',
                                'error'
                            )
                        });
                    } else{
                        swalWithBootstrapButtons.fire(
                            'Cancelado :('
                        )
                    }
                });
            },
            desactivar(id){
                let me=this;
                const swalWithBootstrapButtons = swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false,
                });
                swalWithBootstrapButtons.fire({
                    title: '¿Quieres desactivar esta actividad?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButton: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if(result.value) {
                        axios.put('actividad/deactivate', {
                            'id': id
                        }).then(function (response) {
                            me.initialize();
                            swalWithBootstrapButtons.fire(
                                'Desactivado',
                                'La actividad ha sido desactivada!',
                                'success'
                            )
                        }).catch(function (error) {
                            console.log(error.response);
                            swalWithBootstrapButtons.fire(
                                'Error',
                                'Error al desactivar actividad!',
                                'error'
                            )
                        });
                    } else{
                        swalWithBootstrapButtons.fire(
                            'Cancelado :('
                        )
                    }
                });
            },
            deleteItem(id) {
                let me=this;
                swal.fire({
                    title: '¿Quieres eliminar esta actividad?',
                    text: "Esta acción no se podrá revertir",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.value) {
                        axios.put('/actividad/delete', {
                            'id': id
                        }).then(function(response) {
                            console.log(response.data);
                            swal.fire({
                                type: 'success',
                                title: 'Actividad eliminada',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            me.initialize();
                        }).catch(function (error) {
                            console.log('catch encontrado');
                            console.log(error);
                            swal.fire({
                                type: 'error',
                                title: `Error al eliminar actividad: ${error.response.data.message}`,
                                showConfirmButton: true
                            });
                        });
                    }
                });
            },
            abrirEditar(item) {
                this.editar = 1;
                this.dialog = true;
                this.actividad = item.actividad;
                this.fechaInicio = item.fechaInicio;
                this.fechaFinal = item.fechaFinal;
                this.idActividad = item.id;
            },
            close() {
                this.editar = 0;
                this.dialog = false;
                this.fechaInicio = '';
                this.fechaFinal = '';
                this.actividad = '';
            },

            save() {
                let me=this;
                axios({
                        method: 'post',
                        url: '/User/nuevo',
                        data: {
                            nombre: me.editedItem.nombre,
                            apellido: me.editedItem.apellido,
                            usuario: me.editedItem.usuario,
                            password: me.editedItem.contrasena,
                            idRol:me.idRol.id
                        }
                    }).then(function (response) {
                        swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: response.data,
                            showConfirmButton: false,
                            timer: 1500});
                        me.initialize();
                        me.close();
                    }).catch(function (error) {
                        console.log(error.response);
                        swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: error.response.data.error,
                            showConfirmButton: true});
                        me.initialize();
                        me.close();
                    }); 
            }
        }
    }
</script>
<style lang="scss">
    @import '~vuetify/dist/vuetify.min.css';
</style>