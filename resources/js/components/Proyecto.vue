<template>
    <div>
        <v-toolbar flat color="white">
            <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
            <v-dialog v-model="dialog" persistent max-width="650px">
                <template v-slot:activator="{ on }">
                    <v-btn color="primary" dark class="mb-2" v-on="on" @click="editar=0">Nuevo Proyecto</v-btn>
                </template>
                <v-card>
                    <v-card-title>
                        <span class="headline">{{ formTitle }}</span>
                    </v-card-title>

                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-text-field v-model="titulo" label="Titulo del proyecto"></v-text-field>
                                </v-flex>
                                <v-flex xs12>
                                    <v-textarea v-model="descripcion" label="Descripción del proyecto"></v-textarea>
                                </v-flex>
                                <v-flex xs12 sm12 md6>
                                    <!-- DateTime Picker de Fecha Inicial --> 
                                    <v-menu
                                        ref="menu"
                                        v-model="menu"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        :return-value.sync="fechaI"
                                        lazy
                                        transition="scale-transition"
                                        offset-y
                                        full-width
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field 
                                                v-model="fechaI" 
                                                label="Ingrese fecha de inicio"
                                                prepend-icon="event"
                                                readonly
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker v-model="fechaI" no-title scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="menu = false">Cancelar</v-btn>
                                            <v-btn flat color="primary" @click="$refs.menu.save(fechaI)">Guardar</v-btn>
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
                                        :return-value.sync="fechaF"
                                        lazy
                                        transition="scale-transition"
                                        offset-y
                                        full-width
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field 
                                                v-model="fechaF" 
                                                label="Ingrese fecha de finalización"
                                                prepend-icon="event"
                                                readonly
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker v-model="fechaF" no-title scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="menu2 = false">Cancelar</v-btn>
                                            <v-btn flat color="primary" @click="$refs.menu2.save(fechaF)">Guardar</v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                    <!-- Fin del date Time Picker -->
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>

                     <template v-if="error">
                        <v-divider></v-divider>
                        <div class="text-xs-center">
                            <strong class="red--text text--lighten-1" v-for="e in errorMsj" :key="e" v-text="e"></strong>
                            <br>
                        </div>
                        <v-divider></v-divider>
                    </template>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="close">Cancelar</v-btn>
                        <v-btn color="blue darken-1" v-if="editar===0" flat @click="registrarProyecto">Guardar</v-btn>
                        <v-btn color="blue darken-1" v-if="editar===1" flat @click="editarProyecto()">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-toolbar>
        <v-data-table :headers="headers" :items="proyectos" class="elevation-1" :search="search">
            <template v-slot:items="props">
                <td>{{ props.item.Titulo }}</td>
                <td class="text-xs-right">{{ props.item.Descripcion }}</td>
                <td class="text-xs-right">{{ props.item.FechaInicio }}</td>
                <td class="text-xs-right">{{ props.item.FechaFin }}</td>
                <td class="justify-center layout px-0">
                    <v-icon small class="mr-2" @click="abrirEditar(props.item)">
                        edit
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
    export default {
        data: () => ({
            dialog: false,
            error: 0,
            errorMsj: [],
            search: "",
            IdProyecto: 0,
            Estado: 1,
            editar: 0,
            headers: [
                { text: 'Titulo', align: 'left'},
                { text: 'Descripcion', value: 'descripcion', align: 'right'},
                { text: 'Fecha de inicio', value: 'fechaI', align: 'right' },
                { text: 'Fecha de finalización', value: 'fechaF', align: 'right' }
            ],
            menu: false,
            menu2: false,
            titulo: '',
            descripcion: '',
            fechaI: new Date().toISOString().substr(0, 10),
            fechaF: new Date().toISOString().substr(0, 10),
            estado: 0,
            proyectos: [],
            editedIndex: -1,
            editedItem: {
                name: '',
                calories: 0,
                fat: 0,
                carbs: 0,
                protein: 0
            }
        }),

        computed: {
            formTitle() {
                return this.editar === 0 ? 'Nuevo Proyecto' : 'Editar Proyecto'
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

        methods: {
            initialize() {
                this.dialog = false;
                let me = this;
                axios.get('/proyecto')
                .then(function (response) {
                    console.log("busqueda");
                    var respuesta = response.data;
                    me.proyectos = respuesta;
                })
                .catch(function (error) {
                    console.log(error.response);
                });
            },
            validate() {
                this.error = 0;
                this.errorMsj = [];
                if (!this.titulo)
                    this.errorMsj.push('El título del proyecto no puede estar vacio');
                if (!this.fechaI==null)
                    this.errorMsj.push('La fecha de inicio del proyecto no puede estar vacía');
                if (!this.FechaFin==null)
                    this.errorMsj.push('La fecha de finalización del proyecto no puede estar vacía');
                if (this.errorMsj.length)
                    this.error = 1;
                else 
                    this.error = 0;
                return this.error;
            },
            registrarProyecto() {
                let me = this;
                if(this.validate() === 1) {
                    return;
                }
                axios.post('proyecto/registrar', {
                    'Titulo': me.titulo,
                    'Descripcion': me.descripcion,
                    'FechaInicio': me.fechaI,
                    'FechaFin': me.fechaF
                })
                .then(function (response) {
                    console.log(response.data);
                    me.close();
                    me.initialize();
                })
                .catch(function (error) {
                    console.log(error.response);
                    me.close();
                });
            },

            editarProyecto() {
                let me = this;
                if(this.validate() === 1) {
                    return;
                }
                axios.put('/proyecto/actualizar', {
                    'id': me.IdProyecto,
                    'Titulo': me.titulo,
                    'Descripcion': me.descripcion,
                    'FechaInicio': me.fechaI,
                    'FechaFin': me.fechaF,
                    'Estado': me.Estado
                })
                .then(function (response) {
                    me.initialize();
                    me.close();
                })
                .catch(function (error) {
                    console.log(error.response);
                    me.close();
                })
            },
            
            abrirEditar(item) {
                this.editar = 1;
                this.IdProyecto = item.IdProyecto; 
                this.dialog = true;
                this.titulo = item.Titulo;
                this.descripcion = item.Descripcion;
                this.fechaI = item.FechaInicio;
                this.fechaF = item.FechaFin;
                this.Estado = item.Estado;
            },

            deleteItem(item) {
                const index = this.desserts.indexOf(item)
                confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
            },

            close() {
                this.dialog = false;
                this.editar = 0;
                this.IdProyecto = 0;
                this.titulo = "";
                this.descripcion = '';
                this.fechaI = '';
                this.fechaF = '';
                this.Estado = 0;
            }
        }
    }
</script>
<style lang="scss">
    @import '~vuetify/dist/vuetify.min.css';
</style>