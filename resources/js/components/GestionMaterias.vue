<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Gestión Materias</li>
                </ol>
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Gestión Materias &nbsp;
                            <button type="button" @click="abrirModal('gestionMateria','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="gestionMateria">Nombre</option>
                                        <option value="unidadBase">Unidad base</option>
                                        <option value="tipoMateria">Clasificación</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarGestionMateria(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarGestionMateria(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Nombre</th>
                                        <th>Unidad Base</th>
                                        <th>Precio Base</th>
                                        <th>Clasificación</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="gestionMateria in arrayGestionMateria" :key="gestionMateria.id">
                                        <td>
                                            <button type="button" @click="abrirModal('gestionMateria','actualizar',gestionMateria)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;

                                        <template v-if="gestionMateria.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarGestionMateria(gestionMateria.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="activarGestionMateria(gestionMateria.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                                        </td>
                                        <td v-text="gestionMateria.gestionMateria"></td>
                                        <td v-text="gestionMateria.unidadBase"></td>
                                        <td v-text="gestionMateria.precioBase"></td>
                                        <td v-text="gestionMateria.tipoMateria"></td>
                                        <td>
                                            <div v-if="gestionMateria.estado">
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
                                            <input type="text" v-model="gestionMateria" class="form-control" placeholder="Nombre de materia">
                                            <span class="help-block">(*) Ingrese el nombre de la materia</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Unidad Base</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idUnidadBase">
                                                <option value="0" disabled>Seleccione una unidad</option>
                                                <option v-for="unidadbase in arrayUnidadBase" :key="unidadbase.idUnidadBase" :value="unidadbase.idUnidadBase" v-text="unidadbase.unidadBase"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Precio base</label>
                                        <div class="col-md-9">
                                            <input type="number" v-model="precioBase" class="form-control" placeholder="Precio base">
                                            <span class="help-block">(*) Ingrese el precio base</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Clasificación</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idTipoMateria">
                                                <option value="0" disabled>Seleccione el tipo de clasificación</option>
                                                <option v-for="tipomateria in arrayTipoMateria" :key="tipomateria.idTipoMateria" :value="tipomateria.idTipoMateria" v-text="tipomateria.tipoMateria"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row div-error" v-show="errorGestionMateria">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearGestionMateria()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarGestionMateria()">Editar</button>
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
                idGestionMateria: 0,
                id:'',
                gestionMateria:'',
                idPrecioBase: 0,
                precioBase:0,
                estado:'',
                arrayGestionMateria : [],
                idUnidadBase: 0,
                unidadBase: 0,
                arrayUnidadBase:[],
                idTipoMateria: 0,
                tipoMateria: 0,
                arrayTipoMateria:[],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorGestionMateria : 0,
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
                criterio : 'gestionMateria',
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
            listarGestionMateria(page,buscar,criterio){
                let me=this;
                var url='/gestionmateria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayGestionMateria=respuesta.gestionmaterias.data;
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
                me.listarArea(page,buscar,criterio);
            },
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
            selectTipoMateria(){
                let me=this;
                var url='/gestionmateria/selectTipoMateria';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayTipoMateria=respuesta.tipomaterias;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            selectUnidadBase(){
                let me=this;
                var url='/gestionmateria/selectUnidadBase';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayUnidadBase=respuesta.unidadesbase;
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
                me.listarGestionMateria(page,buscar,criterio);
            },
            crearGestionMateria(){
                //valido con el metodo de validacion creado
                if(this.validarGestionMateria()){
                    return;
                }
                let me=this;
                axios.post('/gestionmateria/store',{
                    'gestionMateria': this.gestionMateria,
                    'idUnidadBase': this.idUnidadBase,
                    'precioBase': this.precioBase,
                    'idTipoMateria': this.idTipoMateria
                }).then(function (response) {
                me.cerrarModal();
                me.listarGestionMateria(1,'','gestionmateria');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            editarGestionMateria(){
                if(this.validarGestionMateria()){
                    return;
                }
                let me=this;
                axios.put('/gestionmateria/update',{
                    'id': this.id,
                    'gestionMateria': this.gestionMateria,
                    'idUnidadBase': this.idUnidadBase,
                    'precioBase': this.precioBase,
                    'idTipoMateria': this.idTipoMateria,
                    'estado': 1
                    //'dato': this.dato
                }).then(function (response) {
                me.cerrarModal();
                me.listarGestionMateria(1,'','gestionmateria');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            desactivarGestionMateria(id){
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
                    axios.put('/gestionmateria/deactivate',{
                        'id': id
                    }).then(function (response) {
                    me.listarGestionMateria(1,'','gestionmateria');
                    swalWithBootstrapButtons.fire(
                    'Gestión desactivada!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarGestionMateria();
                }
                })
            },
            activarGestionMateria(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                title: 'Quiere activar esta gestión?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Activar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/gestionmateria/activate',{
                        'id': id
                    }).then(function (response) {
                    me.listarGestionMateria(1,'','gestionmateria');
                    swalWithBootstrapButtons.fire(
                    'Gestión activada!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarGestionMateria();
                }
                })
            },
            validarGestionMateria(){
                this.errorGestionMateria=0;
                this.errorMensaje=[];
                if (!this.gestionMateria) this.errorMensaje.push("El nombre de la gestión no puede estar vacio");
                if (this.idUnidadBase==0) this.errorMensaje.push("Debe elegir una unidad base");
                if (this.idTipoMateria==0) this.errorMensaje.push("Debe elegir un tipo de materia");
                if (!this.precioBase) this.errorMensaje.push("El precio base no puede estar vacio");
                if (this.errorMensaje.length) this.errorGestionMateria=1;

                return this.errorGestionMateria;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.gestionmateria='';
                this.idTipoMateria='';
                this.gestionMateria='';
                this.idUnidadBase='';
                this.precioBase='';
                this.errorGestionMateria = 0,
                this.errorMensaje = [],
                this.forceRerender();
            },
            abrirModal(modelo, accion, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                case "gestionMateria":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.tituloModal='Crear nueva materia prima';
                            this.tipoAccion= 1;
                            this.gestionmateria='';
                            this.idUnidadBase=this.idUnidadBase;
                            this.precioBase=0;
                            this.idTipoMateria=this.idTipoMateria;
                            break;
                        }
                        case 'actualizar':{
                            //console.log(data);
                            this.modal=1;
                            this.tituloModal='Editar materia prima';
                            this.tipoAccion= 2;
                            this.gestionMateria=data['gestionMateria'];
                            this.idUnidadBase=data['idUnidadBase'];
                            this.precioBase=data['precioBase'];
                            this.idTipoMateria=data['idTipoMateria'];
                            this.id=data['id'];
                            break;
                        }
                    }
              }

            }
            this.selectGestionMateria();
            }
        },
        mounted() {
            this.listarGestionMateria(1,'','');
            this.selectTipoMateria();
            this.selectUnidadBase();
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
