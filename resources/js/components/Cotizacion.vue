<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Cotización</li>
                </ol>

                <template v-if="listado==0">
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                       <div class="card-header">
                            <i class="fa fa-align-justify"></i> Cotización
                            <button type="button" @click="abrirModal('cotizacion','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nueva
                            </button>
                        </div>

                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="id">Id</option>
                                        <option value="consecutivo">Consecutivo</option>
                                        <option value="fecha">Fecha</option>
                                        <option value="condicionEntrega">Condición Entrega</option>
                                        <option value="vigencia">Vigencia</option>
                                        <option value="idCliente">Cliente</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarCotizacion(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarCotizacion(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Consecutivo</th>
                                        <th>Fecha</th>
                                        <th>Condición Entrega</th>
                                        <th>Vigencia</th>
                                        <th>Cliente</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="cotizacion in arrayCotizaciones" :key="cotizacion.id">
                                        <td>
                                        <template v-if="cotizacion.estado==1">
                                            <button type="button" class="btn btn-danger btn-sm" @click="mostrarProductos(cotizacion.id)">
                                                <i class="icon-plus"></i><span> Agregar</span>
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('detalle','crear',cotizacion.id)">
                                                <i class="icon-cloud-upload"></i><span> Generar</span>
                                            </button>
                                        </template>
                                        <template v-if="cotizacion.estado==2">
                                            <button type="button" class="btn btn-success btn-sm" @click="mostrarDetalle(cotizacion.id)">
                                                <i class="icon-magnifier"></i><span> Detalle</span>
                                            </button>
                                        </template>
                                        </td>
                                        <td v-text="cotizacion.consecutivo"></td>
                                        <td v-text="cotizacion.fecha"></td>
                                        <td v-text="cotizacion.condicionEntrega"></td>
                                        <td v-text="cotizacion.vigencia"></td>
                                        <td v-text="cotizacion.nombreCliente"></td>
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

                <template v-if="listado==1">
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Productos &nbsp;
                            <button type="submit" @click="abrirModal('rela','crear')" class="btn btn-secondary"><i class="fa fa-plus"></i> Nuevo producto</button>
                            </div>
                        <div class="card-body">
                            <productoscotizacion v-bind:identificador="identificador" :key="componentKey" @abrirmodal="abrirModal" @eliminarproducto="eliminarProducto"></productoscotizacion>
                            <p align="right">
                                <button class="btn btn-danger" @click="ocultarDetalle()" aria-label="Close">Cerrar</button>
                            </p>
                        </div>
                    </div>
                    <!-- Fin ejemplo de tabla Listado -->
                </div>
                </template>

                    <!-- Template para mostrar el detalle luego de generar -->
                <template v-if="listado==2">
                    <div class="container-fluid">
                        <div class="card">
                            <detallecotizacion v-bind:identificador="identificador" :key="componentKey"></detallecotizacion>
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
                            <div class="modal-header" v-if="tipoModal!=3">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Condición Entrega</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="condicionEntrega" class="form-control" placeholder="Condición Entrega">
                                            <span class="help-block">(*) Ingrese la Condición Entrega</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Vigencia</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="vigencia" class="form-control" placeholder="Vigencia">
                                            <span class="help-block">(*) Ingrese la Vigencia</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="email-input">Cliente</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idCliente">
                                                <option value="0" disabled>Seleccione un cliente</option>
                                                <option v-for="cliente in arrayClientes" :key="cliente.id" :value="cliente.id" v-text="cliente.nombreCliente"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row div-error" v-show="errorCotizacion">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                    <!-- divs para modal tipo 2 -->

                                    <div v-if="tipoModal==2" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Producto</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idProducto" @change='precioProduccion($event)'>
                                                <option value="0" disabled>Seleccione un producto</option>
                                                <option v-for="posible in arrayPosibles" :key="posible.idProducto" :value="posible.idProducto" v-text="posible.producto"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==2" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Unidades</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="cantidad" class="form-control" placeholder="Unidades a producir">
                                            <span class="help-block">(*) Ingrese la cantidad solicitada</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==4" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Unidades</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="cantidad" class="form-control" placeholder="Unidades a producir">
                                            <span class="help-block">(*) Ingrese la cantidad solicitada</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==2" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Precio Venta</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="precioVenta" class="form-control" placeholder="Precio venta unidad">
                                            <span class="help-block">(*) Ingrese el precio acordado. El costo es {{costopar}}</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==4" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Precio Venta</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="precioVenta" class="form-control" placeholder="Precio venta unidad">
                                            <span class="help-block">(*) Ingrese el precio acordado. El costo es {{costopar}}</span>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==2" class="form-group row div-error" v-show="errorCotizacion">
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
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearCotizacion()">Guardar</button>
                            </div>
                            <div v-if="tipoModal==2" class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearRelacion()">Guardar</button>
                            </div>
                            <div v-if="tipoModal==4" class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarRelacion()">Editar</button>
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
    import moment from 'moment';
    import productoscotizacion from '../components/ProductosCotizacion';
    import detallecotizacion from '../components/DetalleCotizacion';
    export default {
        components: {
            productoscotizacion,
            detallecotizacion
        },
        data(){
            return{
                idCotizacion:0,
                id:'',
                identificador:'',
                fecha : '',
                condicionEntrega:'',
                vigencia:'',
                estado:'',
                idCliente: 0,
                nombreCliente: '',
                arrayClientes : [],
                arrayCotizaciones : [],
                arrayProductos : [],
                modal : 0,
                listado : 0,
                tituloModal : '',
                variable : '',
                registro:'',
                idProducto: 0,
                cantidad:'',
                valor:'',
                costopar:'',
                precioVenta:'',
                tipoModal : 0,
                tipoAccion : 0,
                errorCotizacion : 0,
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
            currentDateTime() {
                return moment().format('YYYY-MM-DD')
            },
            precioProduccion(event){
                //console.log(event.target.value);
                this.identificadorProducto=event.target.value;
                let me=this;
                var url='/cotizacioncliente/precioproductos/'+this.identificadorProducto;
                console.log('Url Seguimiento Valor de producto');
                console.log(url);
                console.log(me.identificadorProducto);
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.costopar=respuesta.costopar;
                console.log('Valor de producto');
                console.log(me.costopar);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarCotizacion(page,buscar,criterio){
                let me=this;
                var url='/cotizacion?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                //console.log(url);
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayCotizaciones=respuesta.cotizaciones.data;
                me.pagination=respuesta.pagination;
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
                me.listarCotizacion(page,buscar,criterio);
            },
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
            listarPosibles(id){
                let me=this;
                var url='/cotizacioncliente/posibles?id=' + this.identificador;
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayPosibles=respuesta.posibles;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarClientes(){
                let me=this;
                var url='/cotizacion/clientes';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayClientes=respuesta.clientes;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            crearCotizacion(){
                //valido con el metodo de validacion creado
                if(this.validarCotización()){
                    return;
                }
                let me=this;
                this.fecha= moment().format('YYYY-MM-DD');
                axios.post('/cotizacion/store',{
                    'fecha': this.fecha,
                    'condicionEntrega':this.condicionEntrega,
                    'vigencia':this.vigencia,
                    'idCliente': this.idCliente
                }).then(function (response) {
                me.cerrarModal('0');
                me.listarCotizacion(1,'','');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            crearRelacion(){
                //valido con el metodo de validacion creado
                let me=this;
                axios.post('/cotizacioncliente/store',{
                    'cantidad': this.cantidad,
                    'precioVenta': this.precioVenta,
                    'idProducto': this.idProducto,
                    'idCotizacion': this.identificador,
                }).then(function (response) {
                me.cerrarModal('0');
                me.forceRerender();
                me.listarCotizacion(1,'','');
                me.listarPosibles(this.identificador);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             editarRelacion(){
                let me=this;
                axios.put('/cotizacioncliente/update',{
                   'id': this.id,
                   'cantidad': this.cantidad,
                   'precioVenta': this.precioVenta,
                   'idCotizacion': this.identificador,
                }).then(function (response) {
                me.cerrarModal();
                me.forceRerender();
                me.listarCotizacion(1,'','cotizacion');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             eliminarProducto(id){
                let me=this;
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                title: 'Quiere eliminar este producto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Eliminar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/cotizacioncliente/delete',{
                        'id': id
                    }).then(function (response) {
                    me.forceRerender();
                    me.listarCotizacion(1,'','cotizacion');
                    swalWithBootstrapButtons.fire(
                    'Producto eliminado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarCotizacion();
                }
                })
            },
            mostrarProductos(id){
                this.listado=1;
                this.identificador=id;
                this.listarPosibles(this.identificador);
            },
            mostrarDetalle(id){
                this.listado=2;
                this.identificador=id;
                identificador=this.identificador;
                console.log(this.identificador);
            },
            generarDetalle(id){
                this.identificador=id;
                let me=this;
                axios.post('/cotizacion/estado',{
                    'id': this.identificador
                }).then(function (response) {
                me.cerrarModal('0');
                me.forceRerender();
                me.listarCotizacion(1,'','');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            ocultarDetalle(){
                this.listado=0;
            },
            validarCotización(){
                this.errorCotizacion=0;
                this.errorMensaje=[];

                if (!this.condicionEntrega) this.errorMensaje.push("La Condición de entrega no puede estar vacia");
                if (!this.vigencia) this.errorMensaje.push("La Vigencia no puede estar vacia");
                if (!this.idCliente) this.errorMensaje.push("El cliente no puede estar vacio");
                if (this.errorMensaje.length) this.errorCotizacion=1;

                return this.errorCotizacion;
            },
            cerrarModal(variable){
                this.modal=this.variable;
                this.tituloModal='';
                this.detalle='';
                this.condicionEntrega='';
                this.idCliente='';
                this.vigencia='';
                this.idProducto='';
                this.cantidad='';
                this.precioVenta='';
                this.errorCotizacion = 0,
                this.errorMensaje = [],
                this.forceRerender();
            },
            abrirModal(modelo, accion, identificador,data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                    case "cotizacion":
                    {
                        switch (accion) {
                            case 'crear':{
                                this.modal=1;
                                this.tipoModal=1; //carga tipos de campos y footers
                                this.tituloModal='Crear nueva cotización';
                                this.tipoAccion= 1; //carga tipos de botón en el footer
                                this.fecha= moment().format('YYYY-MM-DD');
                                break;
                            }
                        }
                        //this.selectGestionMateria();
                        break;
                    }

                    case "rela":
                    {
                        switch (accion) {
                            case 'crear':{
                                this.modal=1;
                                this.tipoModal=2; //carga tipos de campos y footers
                                this.tituloModal='Agregar productos';
                                this.tipoAccion= 1; //carga tipos de botón en el footer
                                break;
                            }
                            case 'actualizar':{
                            console.log("data de salida:");
                            console.log(data);
                            this.modal=1;
                            this.tipoModal=4;
                            this.id=data['idRegistro'];
                            console.log("Id de producto:");
                            console.log(this.id);
                            this.cantidad=data['cantidad'];
                            this.precioVenta=data['precioVenta'];
                            this.idCotizacion=this.identificador;
                            this.tituloModal='Editar productos';
                            this.tipoAccion= 2;
                            break;
                           }
                        }
                        this.listarPosibles(this.identificador);
                        break;
                    }

                    case "detalle":
                    {
                        switch (accion) {
                            case 'crear':{
                                this.modal=1;
                                this.tipoModal=3; //carga tipos de campos y footers
                                this.tituloModal='Generando, por favor espere...';
                                this.identificador=identificador;
                                break;
                            }
                        }
                        this.generarDetalle(this.identificador);
                        break;
                    }

                }
            }
        },
        mounted() {
            this.listarCotizacion(1,this.buscar,this.criterio);
            this.listarClientes();
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
    .carga{
        background-color: #3c29297a !important;
        width: 100% !important;
        height: 100% !important;
        text-align: center;
        color: #ffffffff;
    }
</style>
