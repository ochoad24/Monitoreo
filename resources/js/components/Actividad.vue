<template>
    <div>
        <v-toolbar flat color="white">
            <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="600px">
                <template v-slot:activator="{ on }">
                    <v-btn color="primary" dark class="mb-2" v-on="on">Nuevo Usuario</v-btn>
                </template>
                <v-card>
                    <v-card-title>
                        <span class="headline">{{ formTitle }}</span>
                    </v-card-title>

                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field v-model="editedItem.nombre" label="Nombre"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field v-model="editedItem.apellido" label="Apellido"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field v-model="editedItem.usuario" label="Usuario"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field v-model="editedItem.contrasena" label="Contraseña"
                                        :type="'password'"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field v-model="editedItem.repetir" label="Repetir contraseña"
                                        :type="'password'"></v-text-field>
                                </v-flex>
                                <!-- <v-flex xs12 sm6 md6>
                                    <multiselect v-model="idRol" :options="roles" placeholder="Seleccione un Rol"
                                        label="nombre" track-by="nombre"></multiselect>
                                </v-flex> -->
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <template v-if="error">
                        <v-divider></v-divider>
                        <div class="text-xs-center">
                            <strong class="red--text text--lighten-1" v-for="e in errorMsj" :key="e"
                                v-text="e"></strong>
                            <br>
                        </div>
                        <v-divider></v-divider>
                    </template>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="close">Cancelar</v-btn>
                        <v-btn color="blue darken-1" flat @click="save">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-toolbar>


        <v-data-table :headers="headers" :items="actividades" class="elevation-1" :search="search">
            <template v-slot:items="props">
                <td class="text-xs-left">{{ props.item.actidad }}</td>
                <td class="text-xs-left">{{ props.item.tareas }}</td>
                <td class="text-xs-left">{{ props.item.tareasCompletadas }}</td>
                <td class="text-xs-left">{{ props.item.tareasPendientes }}</td>
                <td class="text-xs-left">{{ props.item.fechaInicio }}</td>
                <td class="text-xs-left">{{ props.item.fechaPendiente }}</td>
                <td class="text-xs-left"><template>
                        <div class="text-xs-left">
                            <v-chip color="green" text-color="white" v-if="props.item.estado">Activo</v-chip>
                            <v-chip color="red" text-color="white" v-else>Desactivado</v-chip>
                        </div>
                    </template></td>
                <td class="justify-left layout px-0">
                    <v-icon small class="mr-2" v-if="props.item.estado" @click="desactivar(props.item)">
                        block
                    </v-icon>
                    <v-icon small class="mr-2" v-else @click="activar(props.item)">
                        check_circle
                    </v-icon>
                    <v-icon small @click="deleteItem(props.item)">
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
            search: '',
            headers: [
                { text: 'Actividad',value:'actividad' ,align: 'left'},
                { text: 'Tareas', value: 'tareas', align: 'right'},
                { text: 'Tareas Completadas', value: 'tareasCompletas', align: 'right' },
                { text: 'Tareas Pendientes', value: 'tareasPendienteas', align: 'right' },
                { text: 'Fecha de Inicio', value: 'fechaInicio', align: 'right' },
                { text: 'Fecha de Finalización', value: 'fechaFin', align: 'right' },
                { text: 'Estado', value: 'estado', align: 'right' }
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
                return this.editedIndex === -1 ? 'Nuevo Usuario' : 'Editar Usuario'
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
                // if (!this.editedItem.nombre)
                //     this.errorMsj.push('El nombre no puede estar vacio');
                                
                if (this.errorMsj.length)
                    this.error = 1;
                return this.error;
            },
            initialize() {
                var url='/Actividad?proyecto='+this.proyecto;
                axios.get(url)
                    .then(response => {
                        this.actividades = response.data;
                    })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            activar(item){
                let me=this;
                swal.fire({
                    title: 'Quieres activar a este Usuario?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Activalo!',
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.value) {
                       axios({
                        method: 'put',
                        url: '/User/activar',
                        data: {
                            id:item.id,
                            }
                        }).then(response => {
                            me.initialize();
                            swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: response.data,
                            showConfirmButton: false,
                            timer: 1500});
                        }).catch(error => {
                            swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: error.response.data.error,
                            showConfirmButton: true});
                        });
                    }
                });
            },
            desactivar(item){
                let me=this;
                swal.fire({
                    title: 'Quieres desactivar a este Usuario?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Activalo!',
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.value) {
                       axios({
                        method: 'put',
                        url: '/User/desactivar',
                        data: {
                            id:item.id,
                            }
                        }).then(response => {
                            me.initialize();
                            swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: response.data,
                            showConfirmButton: false,
                            timer: 1500});
                        }).catch(error => {
                            swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: error.response.data.error,
                            showConfirmButton: true});
                        });
                    }
                });
            },
            deleteItem(item) {
                let me=this;
                swal.fire({
                    title: 'Quieres eliminar este Usuario?',
                    text: "No podras revertir la eliminacion!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Eliminalo!',
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.value) {
                        axios.delete(`/User/${item.id}/delete`).then(response => {
                            me.initialize();
                            swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: response.data,
                            showConfirmButton: false,
                            timer: 1500});
                        }).catch(error => {
                            swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: error.response.data.error,
                            showConfirmButton: true});
                        });
                    }
                });
            },

            close() {
                this.dialog = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
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