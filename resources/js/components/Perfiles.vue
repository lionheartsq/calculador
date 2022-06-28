<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Perfiles</li>
                </ol>
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Perfiles &nbsp;
                            <button type="button" @click="abrirModal('perfil','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="perfil">Perfil</option>
                                        <option value="proceso">Proceso</option>
                                        <option value="area">Area</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarPerfil(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarPerfil(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Perfil</th>
                                        <th>Valor Minuto</th>
                                        <th>Proceso</th>
                                        <!-- <th>Area</th> -->
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="perfil in arrayPerfil" :key="perfil.id">
                                        <td>
                                            <button type="button" @click="abrirModal('perfil','actualizar',perfil)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;

                                        <template v-if="perfil.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarPerfil(perfil.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="activarPerfil(perfil.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                                        </td>
                                        <td v-text="perfil.perfil"></td>
                                        <td v-text="perfil.valorMinuto"></td>
                                        <td v-text="perfil.proceso"></td>
                                        <!-- <td v-text="perfil.area"></td> -->
                                        <td>
                                            <div v-if="perfil.estado">
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
                                    <!--
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Area</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idArea" @change='selectRelacion(area.idArea)'>
                                                <option value="0" disabled>Seleccione un área</option>
                                                <option v-for="area in arrayArea" :key="area.idArea" :value="area.idArea" v-text="area.area"></option>
                                            </select>
                                        </div>
                                    </div>
                                    -->
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Proceso</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idProceso">
                                                <option value="0" disabled>Seleccione un proceso</option>
                                                <option v-for="relacion in arrayRelacion" :key="relacion.idProceso" :value="relacion.idProceso" v-text="relacion.proceso"></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="perfil" class="form-control" placeholder="Nombre de perfil">
                                            <span class="help-block">(*) Ingrese el nombre del perfil</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Valor Minuto</label>
                                        <div class="col-md-9">
                                            <input type="number" v-model="valorMinuto" class="form-control" placeholder="Valor Minuto">
                                            <span class="help-block">(*) Ingrese el valor del minuto</span>
                                        </div>
                                    </div>
                                    <div class="form-group row div-error" v-show="errorPerfil">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearPerfil()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarPerfil()">Editar</button>
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
                idPerfil:0, //variable usada en el modal de actualización de perfil
                id:'',
                perfil:'',
                valorMinuto:0,
                estado:'',
                arrayPerfil : [],
                idArea:1,
                area:'',
                arrayArea:[],
                idProceso:0,
                proceso:'',
                arrayRelacion:[],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPerfil : 0,
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
                criterio : 'perfil',
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
            listarPerfil(page,buscar,criterio){
                let me=this;
                var url='/perfil?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayPerfil=respuesta.perfiles.data;
                me.pagination=respuesta.pagination;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            selectArea(){
                let me=this;
                var url='/area/selectArea';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayArea=respuesta.areas;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            selectRelacion(idArea){
                let me=this;
                var url='/perfil/selectRelacion/'+this.idArea;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayRelacion=respuesta.relaciones;
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
                me.listarPerfil(page,buscar,criterio);
            },
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
            crearPerfil(){
                //valido con el metodo de validacion creado
                if(this.validarPerfil()){
                    return;
                }

                let me=this;
                axios.post('/perfil/store',{
                    'perfil': this.perfil,
                    'idProceso': this.idProceso,
                    'valorMinuto': this.valorMinuto
                }).then(function (response) {
                me.cerrarModal();
                me.listarPerfil(1,'','perfil');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            editarPerfil(){
                if(this.validarPerfil()){
                    return;
                }
                let me=this;
                axios.put('/perfil/update',{
                    'id': this.idPerfil,
                    'perfil': this.perfil,
                    'idProceso': this.idProceso,
                    'valorMinuto': this.valorMinuto
                }).then(function (response) {
                me.cerrarModal();
                me.listarPerfil(1,'','perfil');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            desactivarPerfil(id){
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
                    axios.put('/perfil/deactivate',{
                        'id': id
                    }).then(function (response) {
                    me.listarPerfil(1,'','perfil');
                    swalWithBootstrapButtons.fire(
                    'Perfil desactivado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarPerfil();
                }
                })
            },
            activarPerfil(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Quiere activar este perfil?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Activar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/perfil/activate',{
                        'id': id
                    }).then(function (response) {
                    me.listarPerfil(1,'','proceso');
                    swalWithBootstrapButtons.fire(
                    'Perfil activado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarPerfil();
                }
                })
            },
            validarPerfil(){
                this.errorArea=0;
                this.errorProceso=0;
                this.errorPerfil=0;
                this.errorMensaje=[];

                if (!this.idProceso) this.errorMensaje.push("El nombre del proceso no puede estar vacio");
                if (!this.perfil) this.errorMensaje.push("El nombre del perfil no puede estar vacio");
                if (!this.valorMinuto) this.errorMensaje.push("El valor del minuto no puede estar vacio");
                if (this.valorMinuto<0) this.errorMensaje.push("El valor del minuto no puede ser negativo");
                if (this.errorMensaje.length) this.errorPerfil=1;

                return this.errorPerfil;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.perfil='';
                this.valorMinuto='';
                this.proceso='';
                this.errorPerfil = 0,
                this.errorMensaje = [],
                this.forceRerender();
            },
            abrirModal(modelo, accion, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                case "perfil":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.perfil='';
                            this.tituloModal='Crear nuevo perfil';
                            this.tipoAccion= 1;
                            this.idProceso= 1;
                            break;
                        }
                        case 'actualizar':{
                            //console.log(data);
                            this.modal=1;
                            this.tituloModal='Editar perfil';
                            this.tipoAccion= 2;
                            this.idPerfil=data['id'];
                            this.perfil=data['perfil'];
                            this.valorMinuto=data['valorMinuto'];
                            this.idProceso=data['idProceso']; // añadido para alimentar el select
                            this.proceso=data['proceso']; //añadido para alimentar el select
                            this.idArea=data['idArea']; // añadido para alimentar el select
                            break;
                        }
                    }
              }

            }
            this.selectRelacion(this.idArea);
            }
        },
        mounted() {
            this.listarPerfil(1,this.buscar,this.criterio);
            this.selectArea();
            this.selectRelacion(1);
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
