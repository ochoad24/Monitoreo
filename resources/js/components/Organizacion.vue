<template>
    <div>
        <v-toolbar flat color="white">
            <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
            <v-spacer></v-spacer>
            <v-toolbar-title>Organizaciones</v-toolbar-title>
            <v-divider class="mx-2" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" persistent max-width="650px">
                <template v-slot:activator="{ on }">
                    <v-btn color="primary" dark class="mb-2" v-on="on" @click="editar=0">Agregar Organización</v-btn>
                </template>
                <v-card>
                    <v-card-title>
                        <span class="headline">{{ formTitle }}</span>
                    </v-card-title>

                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-text-field v-model="nombre" label="Nombre de la organización"></v-text-field>
                                </v-flex>
                                <v-flex xs12>
                                    <v-select
                                        v-model="select"
                                        :hint="`${select.departamento}`"
                                        :items="departamentos"
                                        item-text="departamento"
                                        item-value="id"
                                        label="Seleccionar departamento"
                                        persistent-hint
                                        return-object
                                        single-line
                                    ></v-select>
                                </v-flex>
                                <v-flex xs12>
                                    <v-text-field v-model="municipio" label="Municipio de ubucación de la organización"></v-text-field>
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
                        <v-btn color="blue darken-1" v-if="editar===0" flat @click="registrarOrganizacion">Guardar</v-btn>
                        <v-btn color="blue darken-1" v-if="editar===1" flat @click="editarOrganizacion()">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-toolbar>
        <v-data-table :headers="headers" :items="organizaciones" class="elevation-1" :search="search">
            <template v-slot:items="props">
                <td>{{ props.item.nombre }}</td>
                <td class="text-xs-right">{{ props.item.departamento }}</td>
                <td class="text-xs-right">{{ props.item.municipio }}</td>
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
            select: [],
            errorMsj: [],
            departamentos: [],
            search: '',
            IdOrganizacion: 0,
            IdDepartamento: -1,
            editar: 0,
            headers: [
                { text: 'Nombre', align: 'left', value: 'nombre'},
                { text: 'Departamento', align: 'right', value: 'departamento'},
                { text: 'Municipio', align: 'right', value: 'municipio' }
            ],
            municipio: '',
            nombre: '',
            organizaciones: [],
            editedIndex: -1
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
            this.cargaDepartamentos();
            this.initialize();
        },

        methods: {
            initialize() {
                this.dialog = false;
                let me = this;
                axios.get('/org')
                .then(function (response) {
                    console.log("busqueda");
                    var respuesta = response.data;
                    me.organizaciones = respuesta;
                })
                .catch(function (error) {
                    console.log(error.response);
                });
            },
            cargaDepartamentos() {
                let me = this;
                axios.get('/org/dept')
                .then(function (response) {
                    me.departamentos = response.data;
                })
                .catch(function (error) {
                    console.log(error.response);
                });
            },

            validate() {
                this.error = 0;
                this.errorMsj = [];
                if (!this.nombre)
                    this.errorMsj.push('El nombre de la organización no puede estar vacio');
                if (!this.municipio)
                    this.errorMsj.push('El municipio no puede estar vacío');
                if (this.select.length > 0)
                    this.errorMsj.push('Por favor seleccione un departamento');
                if (this.errorMsj.length)
                    this.error = 1;
                else 
                    this.error = 0;
                return this.error;
            },
            registrarOrganizacion() {
                let me = this;
                if(this.validate() === 1) {
                    return;
                }
                this.IdDepartamento = this.select.id;
                axios.post('org/registrar', {
                    'nombre': me.nombre,
                    'municipio': me.municipio,
                    'IdDepartamento': me.select.id
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

            editarOrganizacion() {
                let me = this;
                if(this.validate() === 1) {
                    return;
                }
                axios.put('/org/actualizar', {
                    'id': me.IdOrganizacion,
                    'nombre': me.nombre,
                    'municipio': me.municipio,
                    'IdDepartamento': me.select.id
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
                this.IdOrganizacion = item.IdOrganizacion; 
                this.dialog = true;
                this.nombre = item.nombre;
                this.municipio = item.municipio;
            },

            deleteItem(item) {
                const index = this.desserts.indexOf(item)
                confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
            },

            close() {
                this.dialog = false;
                this.editar = 0;
                this.IdDepartamento = 0;
                this.IdOrganizacion = 0;
                this.nombre = "";
                this.municipio = '';
                this.select = [];
                this.error = 0;
                this.errorMsj = [];
            }
        }
    }
</script>
<style lang="scss">
    @import '~vuetify/dist/vuetify.min.css';
</style>