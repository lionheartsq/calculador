<template>
       <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Clientes</li>
                </ol>
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Clientes &nbsp;
                            <button type="button" @click="abrirModal('cliente','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="id">Id</option>
                                        <option value="documento">Documento</option>
                                        <option value="nombre">Nombre</option>
                                        <option value="apellido">Apellido</option>
                                        <option value="direccion">Dirección</option>
                                        <option value="telefono">Telefono</option>
                                        <option value="correo">Correo</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarCliente(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarCliente(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>
                                        <th>Documento</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Dirección</th>
                                        <th>Telefono</th>
                                        <th>Correo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="cliente in arrayClientes" :key="cliente.id">
                                        <td>
                                            <button type="button" @click="abrirModal('cliente','actualizar',cliente)" class="btn btn-info btn-sm">
                                            <i class="icon-pencil" style="color: white;"></i>
                                            </button> 

                                        <template v-if="cliente.estado">
                                            <button type="button" class="btn custom-button btn-sm" @click="desactivarCliente(cliente.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="activarCliente(cliente.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>&nbsp;

                                        <button v-if="!cliente.estado" type="button" class="btn btn-danger btn-sm" @click="eliminarCliente(cliente.id)">
                                            <i class="icon-trash"></i>
                                        </button>

                                        </td>
                                        <td v-text="cliente.id"></td>
                                        <td v-text="cliente.documento"></td>
                                        <td v-text="cliente.nombre"></td>
                                        <td v-text="cliente.apellido"></td>
                                        <td v-text="cliente.direccion"></td>
                                        <td v-text="cliente.telefono"></td>
                                        <td v-text="cliente.correo"></td>       
                                        <td>
                                            <div v-if="cliente.estado">
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
                                        <label class="col-md-3 form-control-label" for="text-input">Documento</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="documento" max="10" class="form-control" placeholder="Documento de Identificación" @input="soloNumeros">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del Cliente">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Apellido</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="apellido" class="form-control" placeholder="Apellido del Cliente">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="telefono" max="10" class="form-control" placeholder="Telefono" @input="soloNumerosTelef">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Correo</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="correo" class="form-control" placeholder="Correo" @input="validarEmail" :class="{ 'is-invalid': emailError }">
                                            <span v-if="emailError" class="text-danger">{{ emailError }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row div-error" v-show="errorCliente">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearCliente()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarCliente()">Editar</button>
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
    export default {
        data(){
            return{
                idCliente:0,
                id:'',
                documento:0,
                nombre:'',
                apellido:'',
                direccion:'',
                telefono:0,   
                correo:'',
                estado:'',
                arrayClientes : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCliente : 0,
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
                criterio : 'id',
                buscar : ''
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
            listarCliente(page,buscar,criterio){
                let me=this;
                var url='/cliente?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayClientes=respuesta.clientes.data;
                me.pagination=respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            eliminarCliente(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará el cliente. ¿Deseas continuar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/cliente/delete/${id}`)
                            .then(response => {
                                if (response.status === 200) {
                                    Swal.fire(
                                        '¡Eliminado!',
                                        'El cliente ha sido eliminado correctamente.',
                                        'success'
                                    );

                                    this.listarCliente(this.pagination.currentPage, this.buscar, this.criterio);
                                } else {
                                    Swal.fire(
                                        'Error',
                                        'No se pudo eliminar el cliente.',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                console.error("Error al eliminar el cliente:", error);
                                Swal.fire(
                                    'Error',
                                    'Se produjo un error al intentar eliminar el cliente.',
                                    'error'
                                );
                            });
                    }
                });
            },
            soloNumeros(event) {
                const input = event.target.value;
                const regex = /^\d{0,10}$/; // Permite hasta 10 dígitos

                if (!regex.test(input)) {
                    event.target.value = input.slice(0, -1);
                }

                if (input.length > 0 && input.charAt(0) === '0') {
                    event.target.value = input.slice(1); // Eliminar el primer carácter si es 0
                }

                this.documento = event.target.value;
            },
            soloNumerosTelef(event) {
                const input = event.target.value;
                const regex = /^\d{0,10}$/; // Permite hasta 10 dígitos

                if (!regex.test(input)) {
                    event.target.value = input.slice(0, -1);
                }

                if (input.length > 0 && input.charAt(0) === '0') {
                    event.target.value = input.slice(1); // Eliminar el primer carácter si es 0
                }

                this.telefono = event.target.value;
            },
            validarEmail() {
                const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                if (this.correo && !emailPattern.test(this.correo)) {
                    this.emailError = 'Ingrese un correo electrónico válido.';
                } else {
                    this.emailError = '';
                }
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //envia peticion para ver los valores asociados a esa pagina
                me.listarCliente(page,buscar,criterio);
            },
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //envia peticion para ver los valores asociados a esa pagina
                me.listarCliente(page,buscar,criterio);
            },
            crearCliente(){
                //valido con el metodo de validacion creado
                if(this.validarCliente()){
                    return;
                }

                let me=this;
                axios.post('/cliente/store',{
                    'documento': this.documento,
                    'nombre': this.nombre,
                    'apellido': this.apellido,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'correo': this.correo
                }).then(function (response) {
                me.cerrarModal();
                me.listarCliente(1,'','cliente');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            editarCliente(){
                if(this.validarCliente()){
                    return;
                }

                let me=this;
                axios.put('/cliente/update',{
                    'id': this.idCliente,
                    'documento': this.documento,
                    'nombre': this.nombre,
                    'apellido': this.apellido,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'correo': this.correo
                }).then(function (response) {
                me.cerrarModal();
                me.listarCliente(1,'','cliente');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             desactivarCliente(id){
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
                    axios.put('/cliente/deactivate',{
                        'id': id
                    }).then(function (response) {
                    me.listarCliente(1,'','cliente');
                    swalWithBootstrapButtons.fire(
                    'Cliente desactivado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarCliente();
                }
                })
            },
            activarCliente(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Quiere activar este Cliente?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Activar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/cliente/activate',{
                        'id': id
                    }).then(function (response) {
                    me.listarCliente(1,'','cliente');
                    swalWithBootstrapButtons.fire(
                    'Cliente activado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarCliente();
                }
                })
            },
            validarCliente(){
                this.errorCliente=0;
                this.errorMensaje=[];

                if (!this.documento) this.errorMensaje.push("El Documento no puede estar vacio");
                if (this.documento<0) this.errorMensaje.push("El Documento no puede ser negativo");
                if (!this.nombre) this.errorMensaje.push("El Nombre no puede estar vacio");
                if (!this.apellido) this.errorMensaje.push("El Apellido no puede estar vacio");
                if (!this.direccion) this.errorMensaje.push("La Dirección no puede estar vacia");
                if (!this.telefono) this.errorMensaje.push("El Telefono no puede estar vacio");
                if (this.telefono<0) this.errorMensaje.push("El Telefono no puede ser negativo");
                if (!this.correo) this.errorMensaje.push("El Correo no puede estar vacio");
                if (this.errorMensaje.length) this.errorCliente=1;

                return this.errorCliente;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.cliente='';
                this.documento='';
                this.nombre='';
                this.apellido='';
                this.direccion='';
                this.telefono='';
                this.correo='';
                this.errorCliente = 0,
                this.errorMensaje = [],
                this.forceRerender();
            },
            abrirModal(modelo, accion, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                case "cliente":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.cliente='';
                            this.tituloModal='Crear nuevo cliente';
                            this.tipoAccion= 1;
                            break;
                        }
                        case 'actualizar':{
                            //console.log(data);
                            this.modal=1;
                            this.tituloModal='Editar cliente';
                            this.tipoAccion= 2;
                            this.idCliente=data['id'];
                            this.documento=data['documento'];
                            this.nombre=data['nombre'];
                            this.apellido=data['apellido'];
                            this.direccion=data['direccion'];
                            this.telefono=data['telefono'];
                            this.correo=data['correo'];
                            break;
                        }
                    }
                }
            }
            }
        },
        mounted() {
            this.listarCliente(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .custom-button {
        background-color: #ff9900; 
        color: #ffffff; 
    }
</style>