<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Kardex de Almacen</li>
                </ol>

                <template v-if="listado==0">
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                       <div class="card-header">
                            <i class="fa fa-align-justify"></i> Kardex de Almacen
                            <button type="button" @click="abrirModal('kardex','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Entrada
                            </button>
                            <button type="button" @click="abrirModal('kardex','crears')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Salida
                            </button>
                        </div>

                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="producto">Producto</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarKardex(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarKardex(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Fecha final</th>
                                        <th>Producto</th>
                                        <th>Cantidad existente</th>
                                        <th>Unidad de medida</th>
                                        <th>Precio promedio</th>
                                        <th>Saldos</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="producto in arrayProductos" :key="producto.id">
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" @click="mostrarDetalle(producto.idGestionMateria)">
                                                <i class="icon-eye"></i><span> Ver kárdex</span>
                                            </button>
                                        </td>
                                        <td v-text="producto.fecha"></td>
                                        <td v-text="producto.producto"></td>
                                        <td v-text="producto.cantidadSaldos"></td>
                                        <td v-text="producto.unidadBase"></td>
                                        <td v-text="producto.precioSaldos"></td>
                                        <td v-text="producto.saldos"></td>
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
                            <button type="submit" @click="abrirModal('rela','crear',identificador)" class="btn btn-secondary"><i class="fa fa-plus"></i> Nuevo producto</button>
                            </div>
                        <div class="card-body">
                            <productoscotizacion v-bind:identificador="identificador" :key="componentKey"></productoscotizacion>
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
                            <detallekardexalmacen v-bind:identificador="identificador" :key="componentKey"></detallekardexalmacen>
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

                                    <div v-if="tipoModal==1" class="form-group row"> <!-- Si es una entrada -->
                                        <label class="col-md-3 form-control-label" for="email-input">Movimiento</label>
                                        <div v-if="desplegable==1" class="col-md-9">
                                            <select class="form-control" v-model="idDocumentos" @change='onChange($event)'>
                                                <option value="0" disabled>Seleccione tipo de documento</option>
                                                <option value="1">Compra de material</option>
                                                <option value="2">Devolución de producción</option>
                                                <!-- <option value="3">Inventario inicial</option> -->
                                            </select>
                                        </div>
                                        <div v-else-if="desplegable==2" class="col-md-9"> <!-- Si es una salida -->
                                            <select class="form-control" v-model="idDocumentos" @change='onChange($event)'>
                                                <option value="0" disabled>Seleccione tipo de documento</option>
                                                <option value="4">Devolución a proveedor</option>
                                                <option value="5">Entrega de material</option>
                                                <option value="6">Bajas</option>
                                                <!-- <option value="7">Ajuste de inventario</option> -->
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label v-if="flag==6" class="col-md-3 form-control-label" for="text-input">Observaciones</label>
                                        <label v-else-if="flag==1" class="col-md-3 form-control-label" for="text-input">Proveedor</label>
                                        <label v-else-if="flag==4" class="col-md-3 form-control-label" for="text-input">Proveedor</label>
                                        <div v-if="flag==6" class="col-md-9">
                                            <input type="text" v-model="observaciones" class="form-control" placeholder="observaciones">
                                            <span class="help-block">(*) Ingrese los motivos de la baja</span>
                                        </div>
                                        <div v-else-if="flag==1" class="col-md-9">
                                            <select class="form-control" v-model="observaciones">
                                                <option value="0" disabled>Seleccione proveedor</option>
                                                <option v-for="proveedor in arrayProveedores" :key="proveedor.idProveedor" :value="proveedor.idProveedor" v-text="proveedor.razonSocial"></option>
                                            </select>
                                            <span class="help-block">(*) Seleccione Proveedor</span>
                                        </div>
                                        <div v-else-if="flag==4" class="col-md-9">
                                            <select class="form-control" v-model="observaciones" @change='facturas($event)'>
                                                <option value="0" disabled>Seleccione proveedor</option>
                                                <option v-for="proveedor in arrayProveedores" :key="proveedor.idProveedor" :value="proveedor.idProveedor" v-text="proveedor.razonSocial"></option>
                                            </select>
                                            <span class="help-block">(*) Seleccione Proveedor</span>
                                        </div>
                                    </div>

                                    <!--Inicio de sección número documento-->
                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label v-if="flag==1" class="col-md-3 form-control-label" for="text-input">Factura</label>
                                        <label v-else-if="flag==2" class="col-md-3 form-control-label" for="text-input">Número de orden</label>
                                        <label v-else-if="flag==4" class="col-md-3 form-control-label" for="text-input">Número de factura</label>
                                        <label v-else-if="flag==5" class="col-md-3 form-control-label" for="text-input">Número de orden</label>
                                        <label v-else-if="flag==6" class="col-md-3 form-control-label" for="text-input">Consecutivo</label>

                                        <div v-if="flag==1" class="col-md-9">
                                            <input type="text" v-model="detalle" class="form-control" placeholder="Número de factura">
                                            <span class="help-block">(*) Ingrese el número de factura a ingresar</span>
                                        </div>

                                        <div v-else-if="flag==2" class="col-md-9">
                                            <select class="form-control" v-model="detalle" @change='materiaOrden($event)'>
                                                <option value="0" disabled>Seleccione orden de producción</option>
                                                <option v-for="orden in arrayOrdenes" :key="orden.id" :value="orden.consecutivo" v-text="orden.consecutivo"></option>
                                            </select>
                                            <span class="help-block">(*) Seleccione el número de orden de producción</span>
                                        </div>

                                        <div v-else-if="flag==4" class="col-md-9">
                                            <select class="form-control" v-model="detalle" @change='materialFactura($event)'>
                                                <option value="0" disabled>Seleccione factura</option>
                                                <option v-for="factura in arrayFactura" :key="factura.id" :value="factura.id" v-text="factura.detallado"></option>
                                            </select>
                                            <span class="help-block">(*) Seleccione el número de factura al cual va a devolver</span>
                                        </div>

                                        <div v-else-if="flag==5" class="col-md-9">
                                            <select class="form-control" v-model="detalle" @change='materiaOrden($event)'>
                                                <option value="0" disabled>Seleccione orden de producción</option>
                                                <option v-for="orden in arrayOrdenes" :key="orden.id" :value="orden.consecutivo" v-text="orden.consecutivo"></option>
                                            </select>
                                            <span class="help-block">(*) Seleccione el número de orden de producción que devuelve</span>
                                        </div>

                                        <div v-else-if="flag==6" class="col-md-9">
                                            <input type="text" v-model="detalle" class="form-control" placeholder="Consecutivo">
                                            <span class="help-block">(*) Ingrese el número del Movimiento</span>
                                        </div>

                                    </div>
                                        <!--Cierre sección número documento-->

                                    <div v-if="tipoModal==1" class="form-group row">
                                        <!--Inicio sección datos-->
                                        <label v-if="flag!=0" class="col-md-3 form-control-label" for="email-input">Material</label>

                                        <div v-if="flag==1" class="col-md-9">
                                            <select class="form-control" v-model="idProducto">
                                                <option value="0" disabled>Seleccione una materia</option>
                                                <option v-for="materia in arrayMateriaPrima" :key="materia.idMateria" :value="materia.idMateria" v-text="materia.materia"></option>
                                            </select>
                                            <span class="help-block">(*) Seleccione la materia prima</span>
                                        </div>

                                        <div v-else-if="flag==2" class="col-md-9">
                                            <select class="form-control" v-model="idProducto" @change='precioMateriaOrden($event)'>
                                                <option value="0" disabled>Seleccione una materia</option>
                                                <option v-for="materia in arrayMateriaOrden" :key="materia.id" :value="materia.id" v-text="materia.producto"></option>
                                            </select>
                                             <span class="help-block">(*) Seleccione la materia prima</span>
                                        </div>

                                        <div v-else-if="flag==4" class="col-md-9">
                                            <select class="form-control" v-model="idProducto" @change='precioMateriaCompra($event)'>
                                                <option value="0" disabled>Seleccione una materia</option>
                                                <option v-for="materia in arrayMateriaFactura" :key="materia.id" :value="materia.id" v-text="materia.producto"></option>
                                            </select>
                                             <span class="help-block">(*) Seleccione la materia prima</span>
                                        </div>

                                        <div v-else-if="flag==5" class="col-md-9">
                                            <select class="form-control" v-model="idProducto" @change='precioMateriaOrden($event)'>
                                                <option value="0" disabled>Seleccione una materia</option>
                                                <option v-for="materia in arrayMateriaOrden" :key="materia.id" :value="materia.id" v-text="materia.producto"></option>
                                            </select>
                                             <span class="help-block">(*) Seleccione la materia prima</span>
                                        </div>

                                        <div v-else-if="flag==6" class="col-md-9">
                                            <select class="form-control" v-model="idProducto" @change='precioMateriaOrden($event)'>
                                                <option value="0" disabled>Seleccione una materia</option>
                                                <option v-for="materia in arrayMateriaPrima" :key="materia.idMateria" :value="materia.idMateria" v-text="materia.materia"></option>
                                            </select>
                                             <span class="help-block">(*) Seleccione la materia prima</span>
                                        </div>

                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label v-if="flag!=0" class="col-md-3 form-control-label" for="text-input">Cantidad</label>
                                        <div v-if="flag!=0" class="col-md-9">
                                            <input type="number" v-model="cantidad" class="form-control" placeholder="Cantidad">
                                            <span class="help-block">(*) Ingrese la Cantidad</span>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row">
                                        <!--Revisar-->
                                        <label v-if="flag==1" class="col-md-3 form-control-label" for="text-input">Precio</label>
                                        <div v-if="flag==1" class="col-md-9">
                                            <input type="number" v-model="precio" class="form-control" placeholder="Precio">
                                            <span class="help-block">(*) Ingrese el Precio</span>
                                        </div>
                                        <label v-if="flag==2" class="col-md-3 form-control-label" for="text-input">Precio</label>
                                        <div v-if="flag==2" class="col-md-9"><!-- Orden->se trae de kardex promedio arrayMateriaOrden-->
                                            <input type="number" v-model="precio" step="0.01" class="form-control" readonly>
                                            <span class="help-block">(*) Precio promedio</span>
                                        </div>
                                        <label v-if="flag==4" class="col-md-3 form-control-label" for="text-input">Precio</label>
                                        <div v-if="flag==4" class="col-md-9"><!-- Factura->se trae de kardex valor arrayMateriaFactura-->
                                            <input type="number" v-model="precio" class="form-control" readonly>
                                            <span class="help-block">(*) Precio de compra</span>
                                        </div>
                                        <label v-if="flag==5" class="col-md-3 form-control-label" for="text-input">Precio</label>
                                        <div v-if="flag==5" class="col-md-9"><!-- Orden->se trae de kardex promedio arrayMateriaOrden-->
                                            <input type="number" v-model="precio" step="0.01" class="form-control" readonly>
                                            <span class="help-block">(*) Precio promedio</span>
                                        </div>
                                        <label v-if="flag==6" class="col-md-3 form-control-label" for="text-input">Precio</label>
                                        <div v-if="flag==6" class="col-md-9"><!-- Registro->se trae de kardex promedio arrayMateriaPrima-->
                                            <input type="number" v-model="precio" step="0.01" class="form-control" readonly>
                                            <span class="help-block">(*) Precio promedio</span>
                                        </div>

                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label v-if="flag!=0" class="col-md-3 form-control-label" for="text-input">Encargado</label>
                                        <div v-if="flag!=0" class="col-md-9">
                                            <select class="form-control" v-model="idEmpleado">
                                                <option value="0" disabled>Seleccione empleado</option>
                                                <option v-for="empleado in arrayEmpleados" :key="empleado.id" :value="empleado.id" v-text="empleado.nombreEmpleado"></option>
                                            </select>
                                            <span class="help-block">(*) Seleccione Empleado</span>
                                        </div>
                                    </div>
                                        <!--Cierre sección datos-->

                                    <div v-if="tipoModal==1" class="form-group row div-error" v-show="errorKardex">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div v-if="tipoModal==1" class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearKardex()">Guardar</button>
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
import detallekardexalmacen from '../components/DetalleKardexAlmacen';

    export default {
        data(){
            return{
                idProducto:0,
                idDocumentos:0,
                id:'',
                identificador:'',
                identificadorProveedor:0,
                identificadorFactura:0,
                identificadorMaterial:0,
                fecha : '',
                factura : '',
                cantidadSaldos:'',
                precioSaldos:'',
                cantidad:'',
                detalle:'',
                observaciones:'Ninguna',
                flag : 0,
                precio:'',
                saldos:'',
                arrayProductos : [],
                arrayMateriaPrima : [],
                arrayMateriaOrden : [],
                arrayMateriaFactura : [],
                arrayFactura : [],
                arrayOrdenes : [],
                arrayEmpleados : [],
                arrayProveedores : [],
                producto:'',
                modal : 0,
                desplegable : 0,
                listado : 0,
                tituloModal : '',
                variable : '',
                idOrden : 0,
                tipologia : 0,
                tipoModal : 0,
                tipoAccion : 0,
                errorKardex : 0,
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
                criterio : 'producto',
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
            onChange(event) {
            //console.log(event.target.value);
            this.flag=event.target.value;
            },
            precioMateriaCompra(event){
                //console.log(event.target.value);
                this.identificadorMaterial=event.target.value;
                let me=this;
                var url='/kardexalmacen/preciomaterialcompra?material='+this.identificadorMaterial+'&proveedor='+this.identificadorProveedor+'&factura='+this.identificadorFactura;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.precio=respuesta.valorMaterial;
                console.log(url);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            precioMateriaOrden(event){
                //console.log(event.target.value);
                this.identificadorMaterial=event.target.value;
                let me=this;
                var url='/kardexalmacen/preciomaterialorden?material='+this.identificadorMaterial;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.precio=respuesta.valorMaterial;
                console.log(url);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            materiaOrden(event){
                //console.log(event.target.value);
                this.identificadorHoja=event.target.value;
                let me=this;
                var url='/kardexalmacen/material/'+this.identificadorHoja;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayMateriaOrden=respuesta.materiales;
                console.log(url);
                console.log(this.identificadorHoja);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarProveedores(){
                //console.log(event.target.value);
                let me=this;
                var url='/proveedor/selectProveedor';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayProveedores=respuesta.proveedores;
                console.log(url);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarEmpleados(){
                let me=this;
                var url='/inventario/empleados';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayEmpleados=respuesta.empleados;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            facturas(event){
                //console.log(event.target.value);
                this.identificadorProveedor=event.target.value;
                let me=this;
                var url='/kardexalmacen/factura/'+this.identificadorProveedor;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayFactura=respuesta.materiales;
                console.log(url);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            materialFactura(event){
                //console.log(event.target.value);
                this.identificadorFactura=event.target.value;
                console.log(this.identificadorFactura);
                let me=this;
                var url='/kardexalmacen/materialfactura/'+this.identificadorFactura;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayMateriaFactura=respuesta.materiales;
                console.log(url);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarOrdenes(){
                let me=this;
                var url='/kardexalmacenordenes';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayOrdenes=respuesta.ordenes;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            currentDateTime() {
                return moment().format('YYYY-MM-DD')
            },
            listarProductos(page,buscar,criterio){
                let me=this;
                var url='/kardexalmacen?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayProductos=respuesta.productos.data;
                me.pagination=respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //envia peticion para ver los valores asociados a esa pagina
                me.listarkardex(page,buscar,criterio);
            },
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
            listarMaterias(){
                let me=this;
                var url='/kardexalmacengeneral';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayMateriaPrima=respuesta.materias;
                console.log(url);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            crearKardex(){
                //valido con el metodo de validacion creado
                if(this.validarKardex()){
                    return;
                }
                let me=this;
                this.fecha= moment().format('YYYY-MM-DD');
                axios.post('/kardexalmacen/store',{
                    'detalle':this.detalle,
                    'fecha': this.fecha,
                    'cantidad':this.cantidad,
                    'precio':this.precio,
                    'tipologia':this.tipologia,
                    'observaciones':this.observaciones,
                    'idEmpleado':this.idEmpleado,
                    'idDocumentos':this.idDocumentos,
                    'idProducto':this.idProducto
                }).then(function (response) {
                me.cerrarModal('0');
                me.listarKardex(1,'','');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            mostrarDetalle(id){
                this.listado=2;
                this.identificador=id;
            },
            ocultarDetalle(){
                this.listado=0;
            },
            validarKardex(){
                this.errorProducto=0;
                this.errorKardex=0;
                this.errorMensaje=[];

                if (!this.detalle) this.errorMensaje.push("El Detalle no puede estar vacio");
                if (!this.cantidad) this.errorMensaje.push("La Cantidad no puede estar vacia");
                if (!this.precio) this.errorMensaje.push("El Precio no puede estar vacio");
                if (!this.idProducto) this.errorMensaje.push("El producto no puede estar vacio");
                if (this.errorMensaje.length) this.errorKardex=1;

                return this.errorKardex;
            },
            cerrarModal(variable){
                this.modal=this.variable;
                this.tituloModal='';
                this.detalle='';
                this.idDocumentos='';
                this.cantidad='';
                this.observaciones='Ninguna';
                this.precio='';
                this.idEmpleado='';
                this.tipologia='';
                this.flag=0;
                this.errorKardex=0;
                this.errorMensaje=[];
                this.tituloModal='';
                this.idProducto='';

                this.listarProductos(1,this.buscar,this.criterio);
            },
            abrirModal(modelo, accion, identificador){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                    case "kardex":
                    {
                        switch (accion) {
                            case 'crear':{
                                this.modal=1;
                                this.tipologia=1;
                                this.tipoModal=1; //carga tipos de campos y footers
                                this.tituloModal='Kardex de almacén entrada';
                                this.desplegable= 1; //carga tipos de botón en el footer
                                this.tipoAccion= 1; //carga tipos de botón en el footer
                                this.fecha= moment().format('YYYY-MM-DD');
                                this.listarMaterias();
                                break;
                            }
                            case 'crears':{
                                this.modal=1;
                                this.tipologia=2;
                                this.tipoModal=1; //carga tipos de campos y footers
                                this.tituloModal='Kardex de almacén salida';
                                this.desplegable= 2; //carga tipos de botón en el footer
                                this.tipoAccion= 1; //carga tipos de botón en el footer
                                this.fecha= moment().format('YYYY-MM-DD');
                                this.listarMaterias();
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
            this.listarProductos(1,this.buscar,this.criterio);
            this.listarMaterias();
            this.listarOrdenes();
            this.listarProveedores();
            this.listarEmpleados();
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

