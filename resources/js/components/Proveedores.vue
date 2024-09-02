<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Proveedores</li>
                </ol>
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Proveedores &nbsp;
                            <button type="button" @click="abrirModal('proveedor','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="id">Id</option>
                                        <option value="nit">Nit</option>
                                        <option value="razonSocial">Razón Social</option>
                                        <option value="contacto">Contacto</option>
                                        <option value="telefono">Telefono</option>
                                        <option value="direccion">Dirección</option>
                                        <option value="correo">Correo</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarProveedor(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarProveedor(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>
                                        <th>Nit</th>
                                        <th>Razón Social</th>
                                        <th>Contacto</th>
                                        <th>Telefono</th>
                                        <th>Dirección</th>
                                        <th>Correo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="proveedor in arrayProveedores" :key="proveedor.id">
                                        <td>
                                            <button type="button" @click="abrirModal('proveedor','actualizar',proveedor)" class="btn btn-info btn-sm">
                                            <i class="icon-pencil" style="color: white;"></i>
                                            </button> 

                                        <template v-if="proveedor.estado">
                                            <button type="button" class="btn custom-button btn-sm" @click="desactivarProveedor(proveedor.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="activarProveedor(proveedor.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>&nbsp;

                                        <button v-if="!proveedor.estado" type="button" class="btn btn-danger btn-sm" @click="eliminarProveedor(proveedor.id)">
                                            <i class="icon-trash"></i>
                                        </button>

                                        </td>
                                        <td v-text="proveedor.id"></td>
                                        <td v-text="proveedor.nit"></td>
                                        <td v-text="proveedor.razonSocial"></td>
                                        <td v-text="proveedor.contacto"></td>
                                        <td v-text="proveedor.telefono"></td>
                                        <td v-text="proveedor.direccion"></td>
                                        <td v-text="proveedor.correo"></td>       
                                        <td>
                                            <div v-if="proveedor.estado">
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
                                        <label class="col-md-3 form-control-label" for="text-input">Nit</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="nit" max="10" class="form-control" placeholder="Nit" @input="soloNumeros">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Razón Social</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="razonSocial" class="form-control" placeholder="Razón Social">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Contacto</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="contacto" class="form-control" placeholder="Contacto" @input="soloNumerosContacto">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="telefono" max="10" class="form-control" placeholder="Telefono" @input="soloNumerosTelefono">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Correo</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="correo" class="form-control" placeholder="Correo" @input="validarEmail" :class="{ 'is-invalid': emailError }">
                                            <span v-if="emailError" class="text-danger">{{ emailError }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row div-error" v-show="errorProveedor">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearProveedor()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarProveedor()">Editar</button>
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
                idProveedor:0,
                id:'',
                nit:0,
                razonSocial:'',
                contacto:'',
                telefono:'',
                direccion:'',
                correo:'',
                estado:'',
                arrayProveedores : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorProveedor : 0,
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
            listarProveedor(page,buscar,criterio){
                let me=this;
                var url='/proveedor?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayProveedores=respuesta.proveedores.data;
                me.pagination=respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            eliminarProveedor(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará el proveedor. ¿Deseas continuar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/proveedor/delete/${id}`)
                            .then(response => {
                                if (response.status === 200) {
                                    Swal.fire(
                                        '¡Eliminado!',
                                        'El proveedor ha sido eliminado correctamente.',
                                        'success'
                                    );

                                    this.listarProveedor(this.pagination.currentPage, this.buscar, this.criterio);
                                } else {
                                    Swal.fire(
                                        'Error',
                                        'No se pudo eliminar el proveedor.',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                console.error("Error al eliminar el proveedor:", error);
                                Swal.fire(
                                    'Error',
                                    'Se produjo un error al intentar eliminar el proveedor.',
                                    'error'
                                );
                            });
                    }
                });
            },
            soloNumeros(event) {
                const input = event.target.value;
                const regex = /^\d{0,10}$/; // Permite solo 10 dígitos

                if (!regex.test(input)) {
                    event.target.value = input.slice(0, -1);
                }

                if (input.length > 0 && input.charAt(0) === '0') {
                    event.target.value = input.slice(1); // Eliminar el primer carácter si es 0
                }

                this.nit = event.target.value;
            },
            soloNumerosContacto(event) {
                const input = event.target.value;
                const regex = /^\d{0,10}$/; // Permite solo 10 dígitos

                if (!regex.test(input)) {
                    event.target.value = input.slice(0, -1);
                }

                if (input.length > 0 && input.charAt(0) === '0') {
                    event.target.value = input.slice(1); // Eliminar el primer carácter si es 0
                }

                this.contacto = event.target.value;
            },
            soloNumerosTelefono(event) {
                const input = event.target.value;
                const regex = /^\d{0,10}$/; // Permite solo 10 dígitos

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
                me.listarProveedor(page,buscar,criterio);
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
                me.listarProveedor(page,buscar,criterio);
            },
            crearProveedor(){
                //valido con el metodo de validacion creado
                if(this.validarProveedor()){
                    return;
                }

                let me=this;
                axios.post('/proveedor/store',{
                    'nit': this.nit,
                    'razonSocial': this.razonSocial,
                    'contacto': this.contacto,
                    'telefono': this.telefono,
                    'direccion': this.direccion,
                    'correo': this.correo
                }).then(function (response) {
                me.cerrarModal();
                me.listarProveedor(1,'','proveedor');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            editarProveedor(){
                if(this.validarProveedor()){
                    return;
                }

                let me=this;
                axios.put('/proveedor/update',{
                    'id': this.idProveedor,
                    'nit': this.nit,
                    'razonSocial': this.razonSocial,
                    'contacto': this.contacto,
                    'telefono': this.telefono,
                    'direccion': this.direccion,
                    'correo': this.correo
                }).then(function (response) {
                me.cerrarModal();
                me.listarProveedor(1,'','proveedor');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             desactivarProveedor(id){
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
                    axios.put('/proveedor/deactivate',{
                        'id': id
                    }).then(function (response) {
                    me.listarProveedor(1,'','proveedor');
                    swalWithBootstrapButtons.fire(
                    'Proveedor desactivado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarProveedor();
                }
                })
            },
            activarProveedor(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Quiere activar este Proveedor?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Activar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/proveedor/activate',{
                        'id': id
                    }).then(function (response) {
                    me.listarProveedor(1,'','proveedor');
                    swalWithBootstrapButtons.fire(
                    'Proveedor activado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarProveedor();
                }
                })
            },
            validarProveedor(){
                this.errorProveedor=0;
                this.errorMensaje=[];

                if (!this.nit) this.errorMensaje.push("El Nit no puede estar vacio");
                if (this.nit<0) this.errorMensaje.push("El Nit no puede ser negativo");
                if (!this.razonSocial) this.errorMensaje.push("La Razón Social no puede estar vacia");
                if (!this.contacto) this.errorMensaje.push("El Contacto no puede estar vacio");
                if (!this.telefono) this.errorMensaje.push("El Telefono no puede estar vacio");
                if (this.telefono<0) this.errorMensaje.push("El Telefono no puede ser negativo");
                if (!this.direccion) this.errorMensaje.push("La Dirección no puede estar vacia");
                if (!this.correo) this.errorMensaje.push("El Correo no puede estar vacio");
                if (this.errorMensaje.length) this.errorProveedor=1;

                return this.errorProveedor;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.proveedor='';
                this.nit='';
                this.razonSocial='';
                this.contacto='';
                this.telefono='';
                this.direccion='';
                this.correo='';
                this.errorProveedor = 0,
                this.errorMensaje = [],
                this.forceRerender();
                
            },
            abrirModal(modelo, accion, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                case "proveedor":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.proveedor='';
                            this.tituloModal='Crear nuevo proveedor';
                            this.tipoAccion= 1;
                            break;
                        }
                        case 'actualizar':{
                            //console.log(data);
                            this.modal=1;
                            this.tituloModal='Editar proveedor';
                            this.tipoAccion= 2;
                            this.idProveedor=data['id'];
                            this.nit=data['nit'];
                            this.razonSocial=data['razonSocial'];
                            this.contacto=data['contacto'];
                            this.telefono=data['telefono'];
                            this.direccion=data['direccion'];
                            this.correo=data['correo'];
                            break;
                        }
                    }
                }
            }
            }
        },
        mounted() {
            this.listarProveedor(1,this.buscar,this.criterio);
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