<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Usuarios</li>
                </ol>
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Usuarios &nbsp;
                            <button type="button" @click="abrirModal('usuario','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="name">Usuario</option>
                                        <option value="rol">Rol</option>
                                        <option value="email">Email</option>
                                        <option value="id">Id</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarUsuario(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarUsuario(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="usuario in arrayUsuario" :key="usuario.id">
                                        <td>
                                            <button type="button" @click="abrirModal('usuario','actualizar',usuario)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;

                                        <template v-if="usuario.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarUsuario(usuario.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="activarUsuario(usuario.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                                        </td>
                                        <td v-text="usuario.id"></td>
                                        <td v-text="usuario.name"></td>
                                        <td v-text="usuario.email"></td>
                                        <td v-text="usuario.rol"></td>
                                        <td>
                                            <div v-if="usuario.estado">
                                            <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            </div>
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <!-- Fin ejemplo de tabla Listado -->
                </div>

                <div v-if="loading" class="overlay">
                    <div class="spinner"></div>
                </div>

                <!--Inicio del modal agregar/actualizar-->
                <div class="modal fade" tabindex="-1" :class="{'mostrar':modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Rol</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idRol">
                                                <option v-for="rol in arrayRol" :key="rol.id" :value="rol.idRol" v-text="rol.rol"></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="name" class="form-control" placeholder="Nombre de usuario">
                                            <span class="help-block">(*) Ingrese el nombre del usuario</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">E-mail</label>
                                        <div class="col-md-9">
                                            <input type="email" v-model="email" class="form-control" placeholder="Correo electrónico">
                                            <span class="help-block">(*) Ingrese el correo electrónico</span>
                                        </div>
                                    </div>
                                    <div class="form-group row div-error" v-show="errorUsuario">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearUsuario()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarUsuario()">Editar</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!--Fin del modal-->
        </main>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

    export default {
        data(){
            return{
                idUser:0,
                id:'',
                name:'',
                email:'',
                estado:'',
                arrayUsuario : [],
                idRol:0,
                rol:'',
                idexRol:0,
                arrayRol:[],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorUsuario : 0,
                errorMensaje : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'name',
                buscar : '',
                loading: false 
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginacion
            pagesNumber: function(){
                if (!this.pagination.to) {
                    return[];
                }

                var from=this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods : {
            listarUsuario(page,buscar,criterio){
                let me=this;
                var url='/usuario?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayUsuario=respuesta.usuarios.data;
                me.pagination=respuesta.pagination;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
            selectRol(){
                let me=this;
                var url='/rol/selectRol';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayRol=respuesta.roles;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //envia peticion para ver los valores asociados a esa pagina
                me.listarUsuario(page,buscar,criterio);
            },

                crearUsuario() {
            // Valida los campos del usuario
            if (this.validarUsuario()) {
                return;
            }

            // Modal de confirmación
            Swal.fire({
                title: '¿Estás seguro de crear este usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, crear usuario',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.loading = true; 
                    // Realiza la petición POST al servidor para crear el usuario
                    axios.post('/usuario/store', {
                        'name': this.name,
                        'idRol': this.idRol,
                        'email': this.email
                    }).then((response) => {
                        // Desactiva la pantalla de carga antes de mostrar el modal de éxito
                        this.loading = false;
                        // Cierra el modal y actualiza la lista de usuarios
                        this.cerrarModal();
                        this.listarUsuario(1, '', 'name');

                        // Usuario creado correctamente
                        Swal.fire({
                            title: 'Éxito',
                            text: 'Usuario creado correctamente',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }).catch((error) => {
                        // Desactiva la pantalla de carga antes de mostrar el modal de error
                        this.loading = false; 
                        console.log(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ha ocurrido un error al crear el usuario.'
                        });
                    });
                }
            });
        },

        editarUsuario() {
            // Valida los campos del usuario
            if (this.validarUsuario()) {
                return;
            }

            // Modal de confirmación
            Swal.fire({
                title: '¿Estás seguro de editar este usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, editar usuario',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.loading = true;
                    // Realiza la petición PUT al servidor para editar el usuario
                    axios.put('/usuario/update', {
                        'id': this.idUser,
                        'name': this.name,
                        'idRol': this.idRol,
                        'email': this.email,
                        'idexRol': this.idexRol
                    }).then((response) => {
                        // Desactiva la pantalla de carga antes de mostrar el modal de éxito
                        this.loading = false;
                        // Cierra el modal y actualiza la lista de usuarios
                        this.cerrarModal();
                        this.listarUsuario(1, '', 'name');

                        // Usuario actualizado correctamente
                        Swal.fire({
                            title: 'Éxito',
                            text: 'Usuario actualizado correctamente',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    }).catch((error) => {
                        // Desactiva la pantalla de carga antes de mostrar el modal de error
                        this.loading = false;
                        console.log(error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ha ocurrido un error al actualizar el usuario.'
                        });
                    });
                }
            });
        },

            desactivarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Está seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Desactivar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/usuario/deactivate',{
                        'id': id
                    }).then(function (response) {
                    me.listarUsuario(1,'','name');
                    swalWithBootstrapButtons.fire(
                    'Usuario desactivado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarUsuario();
                }
                })
            },
            activarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Quiere activar este usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Activar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/usuario/activate',{
                        'id': id
                    }).then(function (response) {
                    me.listarUsuario(1,'','name');
                    swalWithBootstrapButtons.fire(
                    'Usuario activado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarUsuario();
                }
                })
            },
            validarUsuario(){
                this.errorUsuario=0;
                this.errorMensaje=[];

                if (!this.name) this.errorMensaje.push("El nombre del usuario no puede estar vacio");
                if (!this.email) this.errorMensaje.push("El correo del usuario no puede estar vacio");
                if (this.errorMensaje.length) this.errorUsuario=1;

                return this.errorUsuario;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.name='';
                this.email='';
                this.errorUsuario = 0,
                this.errorMensaje = [],
                this.forceRerender();
            },
            abrirModal(modelo, accion, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                case "usuario":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.name='';
                            this.tituloModal='Crear nuevo usuario';
                            this.tipoAccion= 1;
                            this.idRol= 1;
                            break;
                        }
                        case 'actualizar':{
                            //console.log(data);
                            this.modal=1;
                            this.tituloModal='Editar usuario';
                            this.tipoAccion= 2;
                            this.idUser=data['id'];
                            this.name=data['name'];
                            this.idRol=data['idRol']; // añadido para alimentar el select
                            this.rol=data['rol']; //añadido para alimentar el select
                            this.email=data['email']; //añadido para alimentar el select
                            this.idexRol=data['idexRol'];
                            break;
                        }
                    }
              }

            }

            }
        },
        mounted() {
            this.listarUsuario(1,this.buscar,this.criterio);
            this.selectRol();
        }
    }
</script>
<style>
.modal-content {
    width: 100% !important;
    position: absolute !important;
}
.mostrar {
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #3c29297a !important;
}
.div-error {
    display: flex;
    justify-content: center;
}
.text-error {
    color: red !important;
    font-weight: bold;
}
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
}
.spinner {
    border: 4px solid rgba(0, 0, 0, 0.1);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border-left-color: #09f;
    animation: spin 1s ease infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
