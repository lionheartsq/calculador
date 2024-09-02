<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Gestionar Nómina</li>
                </ol>
                <template v-if="listado==0">
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Nómina &nbsp;
                            <button type="button" @click="abrirModal('nomina','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>

                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="fecha">Fecha</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarNomina(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarNomina(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Observación</th>
                                        <th>Tipo Nómina</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="nomina in arrayNomina" :key="nomina.id">
                                        <td>
                                        <template v-if="nomina.estado==1">
                                        <button type="button" class="btn btn-novedad btn-sm" @click="cargaNovedad(nomina.id)">
                                                <i class="icon-plus"></i><span> Novedades</span>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" @click="eliminarNomina(nomina.id)">
                                                <i class="icon-trash"></i>
                                        </button>
                                        <button type="button" @click="abrirModal('detalle','crear',nomina.id)" class="btn custom-button btn-sm">
                                            <i class="icon-cloud-upload"></i>
                                        </button>
                                        </template>
                                        <template v-if="nomina.estado==2">
                                            <button type="button" class="btn btn-warning btn-sm" @click="calcular(nomina.id)">
                                                <i class="fa fa-spinner" aria-hidden="true"></i><span> Favor espere calculando proceso</span>
                                            </button>
                                        </template>
                                        <template v-if="nomina.estado==0">
                                            <button type="button" class="btn btn-success btn-sm" @click="mostrarDetalle(nomina.id)">
                                                <i class="icon-magnifier"></i><span> Detalle</span>
                                            </button>
                                        </template>
                                        </td>
                                        <td v-text="nomina.fecha"></td>
                                        <td v-text="nomina.fechaFin"></td>
                                        <td v-text="nomina.observacion"></td>
                                        <td v-if="nomina.tipo==1">Nómina Fija</td>
                                        <td v-if="nomina.tipo==2">Nómina Destajo</td>
                                        <td v-if="nomina.tipo==3">Nómina Total (Fijos y destajo)</td>
                                        <td>
                                            <div v-if="nomina.estado">
                                            <span class="badge badge-success">Activa</span>
                                            </div>
                                            <div v-else>
                                            <span class="badge badge-danger">Cerrada</span>
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
                </template>
                <!-- Template para mostrar la carga de novedades -->
                <template v-if="listado==1">
                    <div class="container-fluid">
                            <carganovedades v-bind:identificadornomina="identificador" :key="componentKey"></carganovedades>
                            <p align="right">
                                <button class="btn btn-danger" @click="ocultarDetalle()" aria-label="Close">Cerrar</button>
                            </p>
                    </div>
                </template>
                <!-- Template para mostrar el detalle luego de generar -->
                <template v-if="listado==2">
                    <div class="container-fluid">
                        <div class="card">
                            <!--<detallenomina v-bind:identificador="identificador" :key="componentKey" @click="mostrarNovedades"></detallenomina>-->
                            <detallenomina v-bind:identificador="identificador" :key="componentKey"></detallenomina>
                            <p align="right">
                                <button class="btn btn-danger" @click="ocultarDetalle()" aria-label="Close">Cerrar</button>
                            </p>
                        </div>
                    </div>
                </template>

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

                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Tipo Nómina</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="tipoNomina">
                                                <option value="0" disabled>Seleccione un tipo de nómina</option>
                                                <option value="1">Nómina Fija</option>
                                                <option value="2">Nómina Destajo</option>
                                                <!-- <option value="3">Nómina Total (Fijos y destajo)</option> -->
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==1"  class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Fecha Inicio</label>
                                        <div class="col-md-9">
                                            <input type="date" v-model="fechaInicio" class="form-control" placeholder="Fecha de inicio">
                                            <span class="help-block">(*) Ingrese la fecha de inicio</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==1"  class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Fecha Fin</label>
                                        <div class="col-md-9">
                                            <input type="date" v-model="fechaFin" class="form-control" placeholder="Fecha de Fin">
                                            <span class="help-block">(*) Ingrese la fecha de fin</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==1" class="form-group row">

                                        <label class="col-md-3 form-control-label" for="text-input">Observación</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="observacion" class="form-control" placeholder="Observación">
                                            <span class="help-block">(*) Ingrese detalle de la nómina</span>
                                        </div>

                                    </div>
                                    <div class="form-group row div-error" v-show="errorNomina">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                    <!-- Si el tipo es 3 solo es modal para mostrar carga -->

                                    <div v-if="tipoModal==3" class="carga">
                                        <p><hr><h1>Generando, por favor espere...</h1><hr></p>
                                    </div>

                                </form>
                            </div>
                            <div v-if="tipoModal==1" class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearNomina()">Guardar</button>
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
    import detallenomina from '../components/DetalleNomina';
    import carganovedades from '../components/CargaNovedades';
    export default {
        components: {
            detallenomina,
            carganovedades
        },

        data(){
            return{
                idNomina:0,
                identificador:0,
                id:'',
                tipoNomina:0,
                observacion:'Ninguna',
                fechaInicio:'',
                fechaFin:'',
                estado:'',
                arrayNomina : [],
                listado:0,
                modal : 0,
                tituloModal : '',
                tipoModal : 0,
                tipoAccion : 0,
                errorNomina : 0,
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
                criterio : 'fecha',
                buscar : '',
                componentKey:0
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginacion
            pagesNumber: function(){
                if (this.pagination.to) {
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
            listarNomina(page,buscar,criterio){
                let me=this;
                var url='/nomina?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayNomina=respuesta.nomina.data;
                me.pagination=respuesta.pagination;
                me.forceRerender();
                    //console.log(response);
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
                me.listarNomina(page,buscar,criterio);
            },
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
            crearNomina(){
                //valido con el metodo de validacion creado
                let me=this;
                console.log(this.tipoNomina);
                axios.post('/nomina/store',{
                    'fechaInicio': this.fechaInicio,
                    'fechaFin': this.fechaFin,
                    'tipo': this.tipoNomina,
                    'observacion': this.observacion
                }).then(function (response) {
                me.cerrarModal();
                me.listarNomina(1,'','nomina');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            eliminarNomina(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                title: 'Al eliminar esta nomina se eliminaran las novedades asociadas a esta, está seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Desea eliminar esta nomina!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) =>{
                    const swalWithBootstrapButtons1 = Swal.mixin({
                    customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                     })
                    swalWithBootstrapButtons1.fire({
                    title: 'Esta seguro de eliminar la nomina y las novedades asociadas? no se podran restaurar los cambios!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-check fa-2x"></i> Esta seguro de eliminar la nomina!',
                    cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                    reverseButtons: true
                })
                .then((result1)=>{
                   //alert(result1.value);
                 if(result1.value && result.value) {
                    let me=this;
                    axios.put('/nomina/delete',{
                        'id': id
                    }).then(function (response) {
                    me.forceRerender();
                    me.listarNomina(1,'','nomina');
                    swalWithBootstrapButtons.fire(
                    'La nomina y las novedades asociadas han sido eliminadas!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result1.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarNomina();
                }
                })
                })
            },
            cargaNovedad(id){
                this.listado=1;
                this.identificador=id;
            },
            mostrarDetalle(id){
                this.listado=2;
                this.identificador=id;
            },
            calcular(id){
                this.identificador=id;
                let me=this;
                axios.post('/nomina/calcular',{
                    'id': this.identificador
                }).then(function (response) {
                me.cerrarModal('0');
                me.forceRerender();
                me.listarNomina(1,'','');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            generarDetalle(id){
                this.identificador=id;
                let me=this;
                axios.post('/nomina/calcular',{
                    'id': this.identificador
                }).then(function (response) {
                me.cerrarModal('0');
                me.forceRerender();
                me.listarNomina(1,'','');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            ocultarDetalle(){
                this.listado=0;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.fechaInicio='';
                this.fechaFin='';
                this.errorNomina = 0,
                this.errorMensaje = [],
                this.forceRerender();
            },
            abrirModal(modelo, accion,identificadornomina){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                case "nomina":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.fechaInicio='';
                            this.tipoModal=1;
                            this.tituloModal='Crear nueva nomina';
                            this.tipoAccion= 1;
                            break;
                        }
                    }
                    break;
                }
                case "detalle":
                    {
                        switch (accion) {
                            case 'crear':{
                                this.modal=1;
                                this.tipoModal=3; //carga tipos de campos y footers
                                this.tituloModal='Generando, por favor espere...';
                                this.identificador=identificadornomina;
                                this.generarDetalle(this.identificador);
                                break;
                            }
                        }

                    }
                 }
            }
        },
        mounted() {
            this.listarNomina(1,this.buscar,this.criterio);
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
    .btn-novedad {
        background-color: #198754; 
        color: #ffffff; 
    }
</style>
