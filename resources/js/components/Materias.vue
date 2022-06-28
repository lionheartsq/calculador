<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Clasificación</li>
                </ol>
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Clasificación &nbsp;
                            <button type="button" @click="abrirModal('tipoMateria','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="id">Id</option>    
                                        <option value="tipoMateria">Clasificación</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarMateria(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarMateria(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>
                                        <th>Clasificación</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="materia in arrayMaterias" :key="materia.id">
                                        <td>
                                            <button type="button" @click="abrirModal('tipoMateria','actualizar',materia)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;

                                        <template v-if="materia.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarMateria(materia.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="activarMateria(materia.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                        </td>
                                        <td v-text="materia.id"></td>
                                        <td v-text="materia.tipoMateria"></td>
                                        <td>
                                            <div v-if="materia.estado">
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
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="tipoMateria" class="form-control" placeholder="Nombre de materia">
                                            <span class="help-block">(*) Ingrese el nombre de la clasificación</span>
                                        </div>
                                    </div>

                                    <div class="form-group row div-error" v-show="errorMateria">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearMateria()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarMateria()">Editar</button>
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
                materia_id:0,
                id:'',
                tipoMateria:'',
                estado:'',
                arrayMaterias : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorMateria : 0,
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
            listarMateria(page,buscar,criterio){
                let me=this;
                var url='/materia?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayMaterias=respuesta.materias.data;
                me.pagination=respuesta.pagination;
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
                me.listarMateria(page,buscar,criterio);
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
                me.listarMateria(page,buscar,criterio);
            },
            crearMateria(){
                //valido con el metodo de validacion creado
                if(this.validarMateria()){
                    return;
                }

                let me=this;
                axios.post('/materia/store',{
                    'tipoMateria': this.tipoMateria
                }).then(function (response) {
                me.cerrarModal();
                me.listarMateria(1,'','tipoMateria');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            editarMateria(){
                if(this.validarMateria()){
                    return;
                }

                let me=this;
                axios.put('/materia/update',{
                    'tipoMateria': this.tipoMateria,
                    'id': this.materia_id
                }).then(function (response) {
                me.cerrarModal();
                me.listarMateria(1,'','tipoMateria');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             desactivarMateria(id){
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
                    axios.put('/materia/deactivate',{
                        'id': id
                    }).then(function (response) {
                    me.listarMateria(1,'','tipoMateria');
                    swalWithBootstrapButtons.fire(
                    'Clasificación desactivada!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarMateria();
                }
                })
            },
            activarMateria(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Quiere activar esta Clasificación?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Activar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/materia/activate',{
                        'id': id
                    }).then(function (response) {
                    me.listarMateria(1,'','tipoMateria');
                    swalWithBootstrapButtons.fire(
                    'Clasificación activada!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarMateria();
                }
                })
            },
           validarMateria(){
                this.errorMateria=0;
                this.errorMensaje=[];

                if (!this.tipoMateria) this.errorMensaje.push("El nombre de la clasificación no puede estar vacio");
                if (this.errorMensaje.length) this.errorMateria=1;

                return this.errorMateria;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.tipoMateria='';
                this.errorMateria = 0,
                this.errorMensaje = [],
                this.forceRerender();
            },
            abrirModal(modelo, accion, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                case "tipoMateria":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.tipoMateria='';
                            this.tituloModal='Crear nueva Clasificación';
                            this.tipoAccion= 1;
                            break;
                        }
                        case 'actualizar':{
                            //console.log(data);
                            this.modal=1;
                            this.tituloModal='Editar materia';
                            this.tipoAccion= 2;
                            this.materia_id=data['id'];
                            this.tipoMateria=data['tipoMateria'];
                            break;
                        }
                    }
                }
            }
            }
        },
        mounted() {
            this.listarMateria(1,this.buscar,this.criterio);
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
</style>