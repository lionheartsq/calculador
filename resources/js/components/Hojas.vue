<template>
        <main class="main">
            <div id="over" class="overbox">
                <a v-on:click="hideLightbox()"><p class="cierre cursor"><b>Cerrar</b></p></a>
                <div id="content">
                    <center><img :src="fotoCarga" alt="" class="imglight"></center>
                </div>
            </div>
            <div id="fade" class="fadebox">&nbsp;</div>
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Hojas de costos</li>
                </ol>
                      <!-- Listado -->
                <template v-if="listado">
                    <!-- Ejemplo de tabla Listado -->
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="producto">Producto</option>
                                        <option value="coleccion">Coleccion</option>
                                        <option value="area">Area</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarProducto(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarProducto(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Producto</th>
                                        <th>Referencia</th>
                                        <th>Foto</th>
                                        <th>Descripcion</th>
                                        <th>Coleccion</th>
                                        <th>Area</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="producto in arrayProducto" :key="producto.id">
                                        <td>
                                            <button type="button" @click="mostrarDetalle(producto.idHojaDeCosto,producto.producto,producto.idArea)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <!--<button type="button" class="btn btn-warning btn-sm">
                                            <i class="icon-docs" ></i>
                                            </button> &nbsp;-->
                                        </td>
                                        <td v-text="producto.producto"></td>
                                        <td v-text="producto.referencia"></td>
                                        <td><a class="cursor" v-on:click="showLightbox(`img/avatars/${producto.foto}`)">Ver producto</a></td>
                                        <td v-text="producto.descripcion"></td>
                                        <td v-text="producto.coleccion"></td>
                                        <td v-text="producto.area"></td>
                                        <td>
                                            <div v-if="producto.estado">
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
                    </div>

                </template>
                    <!-- Fin Listado -->

                    <!-- Detalle -->
                    <template v-else>
                        <div class="container-fluid">
                            <div class="card">
                                <vs-tabs :color="colorx">

                                <vs-tab label="Materia Prima" icon="open_with" @click="colorx = '#8B0000'">

                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i> Producto: {{this.productoNombre}} &nbsp;
                                        <button type="button" @click="abrirModal('gestionMateria','crear','arrayGestionMaterias')" class="btn btn-secondary">
                                            <i class="icon-plus"></i>&nbsp;Nueva materia prima
                                        </button>
                                    </div>

                                    <div class="card-body">
                                        <materiaprima v-bind:identificador="identificador" :key="componentKey" @abrirmodal="abrirModal" @eliminarMateriaPrimaProducto="eliminarMateriaPrimaProducto"></materiaprima>
                                    </div>

                                </vs-tab>

                                <vs-tab label="Mano de Obra" icon="pan_tool" @click="colorx = '#FFA500'">

                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i> Producto: {{this.productoNombre}} &nbsp;
                                        <button type="button" @click="abrirModal('gestionManoDeObra','crear')" class="btn btn-secondary">
                                            <i class="icon-plus"></i>&nbsp;Nueva mano de obra
                                        </button>
                                    </div>

                                    <div class="card-body">
                                        <manodeobra v-bind:identificador="identificador" :key="componentKey" @abrirmodal="abrirModal" @eliminarmanodeobra="eliminarManoDeObraProducto"></manodeobra>
                                    </div>

                                </vs-tab>

                                <vs-tab label="CIF" icon="account_balance" @click="colorx = '#CB3234'">

                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i> Producto: {{this.productoNombre}} &nbsp;
                                            <i class="icon-plus"></i>&nbsp;Cif asociados
                                    </div>

                                    <div class="card-body">
                                        <cif v-bind:identificador="identificador" :key="componentKey"></cif>
                                    </div>

                                </vs-tab>

                                <!-- //comentariada pestaña maquinaria; valor va incluido en la de cif
                                <vs-tab label="MAQUINARIA" icon="build" @click="colorx = '#FFC89A'">

                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i> Producto: {{this.productoNombre}} &nbsp;
                                            <i class="icon-plus"></i>&nbsp;Maquinaria
                                    </div>

                                    <div class="card-body">
                                        <maquinaria></maquinaria>
                                    </div>

                                </vs-tab>
                                -->

                                <vs-tab label="Consolidado" icon="view_list" @click="colorx = '#20603d'">

                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i> Producto: {{this.productoNombre}} &nbsp;
                                    </div>

                                    <div class="card-body">
                                        <hojadecostos v-bind:identificador="identificador" :key="componentKey"></hojadecostos>
                                    </div>

                                </vs-tab>

                                <vs-tab label="Detallado" icon="format_list_numbered" @click="colorx = '#9B59B6'">

                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i> Producto: {{this.productoNombre}} &nbsp;
                                    </div>

                                    <div class="card-body">
                                        <hojadecostosdetalle v-bind:identificador="identificador" :key="componentKey"></hojadecostosdetalle>
                                    </div>

                                </vs-tab>

                                <vs-tab label="Cerrar" icon="cancel_schedule_send" @click="ocultarDetalle()">
                                </vs-tab>

                                </vs-tabs>
                            </div>
                        </div>
                    </template>
                    <!-- Fin Detalle -->

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
                                        <label class="col-md-3 form-control-label" for="text-input">Materia Prima</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idMateriaPrima" @change='nuevoValorMateria($event)'>
                                                <option value="0" disabled>Seleccione una materia</option>
                                                <option v-for="gestionmateria in arrayGestionMaterias" :key="gestionmateria.idGestionMateria" :value="gestionmateria.idGestionMateria" v-text="gestionmateria.gestionMateria"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Cantidad <br>
                                            <span style="color:red;"><sub><b><i>( Cantidad medida en: {{unidadBase}} )</i></b></sub></span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="number" step="0.01" v-model="cantidad" class="form-control" placeholder="Cantidad de material">
                                            <span class="help-block"><b>(*) Ingrese la cantidad de material en: <span style="color:red;">{{unidadBase}}</span></b></span>
                                        </div>
                                    </div>

                                     <div v-if="tipoModal==3" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Cantidad <br>
                                            <span style="color:red;"><sub><b><i>( Cantidad medida en: {{unidadBase}} )</i></b></sub></span>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="number" step="0.01" v-model="cantidad" class="form-control" placeholder="Cantidad de material">
                                            <span class="help-block"><b>(*) Ingrese la cantidad de material en: <span style="color:red;">{{unidadBase}}</span></b></span>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Precio <br>
                                            <sub><b><i>( Precio base: $ {{valorPrecioBase}} por {{unidadBase}} )</i></b></sub></label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="precioBase" class="form-control">
                                            <span class="help-block">(*) Ingrese el precio</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==3" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Precio <br>
                                            <sub><b><i>( Precio base: $ {{valorPrecioBase}} por {{unidadBase}} )</i></b></sub></label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="precioBase" class="form-control">
                                            <span class="help-block">(*) Ingrese el precio</span>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Tipo de costo</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="tipoDeCosto">
                                                <option value="Directo">Costo Directo</option>
                                                <option value="Indirecto">Costo Indirecto</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==3" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Tipo de costo</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="tipoDeCosto">
                                                <option value="Directo">Costo Directo</option>
                                                <option value="Indirecto">Costo Indirecto</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row div-error" v-show="errorMateriaPrimaProducto">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==2" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Proceso</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idProceso" @change='selectRelacionPerfil(relacion.idProceso)'>
                                                <option value="0" disabled>Seleccione un proceso</option>
                                                <option v-for="relacion in arrayRelacion" :key="relacion.idProceso" :value="relacion.idProceso" v-text="relacion.proceso"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==2" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Perfil</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idPerfil" @change='nuevoValor($event)'>
                                                <option value="0" disabled>Seleccione un perfil</option>
                                                <option v-for="perfilrelacion in arrayPerfilRelacion" :key="perfilrelacion.idPerfil" :value="perfilrelacion.idPerfil" v-text="perfilrelacion.perfil"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==2">

                                        <div  class="form-group row">

                                            <label class="col-md-3 form-control-label" for="text-input">Tipo de Pago</label>

                                            <div class="col-md-9">
                                                <select class="form-control" v-model="seleccion" @change='onChange($event)'>
                                                    <option disabled value="0">Seleccione un tipo de pago</option>
                                                    <option value="1">Fijo</option>
                                                    <option value="2">Destajo</option>
                                                </select>
                                            </div>

                                        </div>

                                        <div  class="form-group row">

                                            <label v-if="flag==1" class="col-md-3 form-control-label" for="text-input">Tiempo<br>
                                            <sub><i>(Valor minuto: $ {{valor}})</i></sub></label>

                                            <div v-if="flag==1" class="col-md-9">
                                                <input type="number" step="0.01" v-model="tiempo" class="form-control" placeholder="Tiempo estandar de mano de obra">
                                                <span class="help-block">(*) Ingrese el tiempo estandar de mano de obra en minutos</span>
                                            </div>

                                            <label v-if="flag==2" class="col-md-3 form-control-label" for="text-input">Costo</label>

                                            <div v-if="flag==2" class="col-md-9">
                                                <input type="number" v-model="preciom" class="form-control" placeholder="Valor de mano de obra por tarea">
                                                <span class="help-block">(*) Ingrese el costo de mano de obra por destajo</span>
                                            </div>

                                        </div>

                                        <div  class="form-group row">

                                            <label v-if="flag==2" class="col-md-3 form-control-label" for="text-input">Porcentajes adicionales</label>

                                            <div v-if="flag==2" class="col-md-3">
                                                <input type="checkbox" true-value="3" false-value="0" v-model="liquidacion" checked>
                                                <label for="liquidacion">Provisión Liquidación</label>
                                            </div>

                                            <div v-if="flag==2" class="col-md-3">
                                                <input type="checkbox" true-value="4" false-value="0" v-model="parafiscales" checked>
                                                <label for="parafiscales">Seguridad Social</label>
                                            </div>

                                            <div v-if="flag==2" class="col-md-3">
                                                <label for="prueba">Total: {{parseInt((preciom*liquidacion*liqui)+(preciom*parafiscales*paraf)+parseInt(preciom))}}</label>
                                            </div>

                                        </div>

                                    </div>

                                    <div v-if="tipoModal==2" class="form-group row div-error" v-show="errorMateriaPrimaProducto">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                </form>
                            </div>

                                <!-- divs para footer tipo 1 y 2 -->

                            <div v-if="tipoModal==1" class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearMateriaPrimaProducto()">Guardar</button>
                            </div>
                            <div v-if="tipoModal==3" class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarMateriaPrimaProducto()">Editar</button>
                            </div>

                            <div v-if="tipoModal==2" class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearManoDeObraProducto()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarManoDeObraProducto()">Editar</button>
                            </div>

                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog tipo Modal 1 -->
                </div>
                <!--Fin del modal-->
        </main>
</template>

<script>
    import materiaprima from '../components/MateriaPrimaProductos';
    import manodeobra from '../components/ManoDeObraProductos';
    import hojadecostos from '../components/HojaDeCostos';
    export default {
        components: {
            materiaprima,
            manodeobra,
            hojadecostos
        },
        data(){
            return{
                colorx: '#8B0000',
                listado: 1,
                idHojaDeCosto:0,
                idProducto:0,
                id:'',
                producto:'',
                referencia:'',
                foto:'',
                descripcion:'',
                estado:'',
                idColeccion:0,
                coleccion:'',
                referencia:'',
                idArea:0,
                area:'',
                arrayProducto : [],
                cantidad:0,
                precio:0,
                tipoDeCosto:'Directo',
                idProceso:0,
                tiempo:1,
                valor:0,
                valorPrecioBase:0,
                preciom:0,
                liquidacion:3,
                parafiscales:4,
                liqui:'',
                paraf:'',
                proceso:'',
                relacion:'',
                perfilrelacion:'',
                arrayRelacion:[],
                idPerfil:0,
                perfil:'',
                valorMinuto:0,
                arrayPerfilRelacion:[],
                idMateriaPrima:0,
                gestionmateria:'',
                precioBase:0,
                unidadBase:'',
                arrayGestionMaterias:[],
                modal : 0,
                seleccion:0,
                tipoPago : 0,
                flag : 0,
                tituloModal : '',
                tipoModal : 0,
                tipoAccion : 0,
                fotoCarga:'',
                materiaprimaproducto:'',
                errorMateriaPrimaProducto : 0,
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
                identificador: 0,
                identificadorArea: 0,
                productoNombre:'',
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
            nuevoValor(event){
                //console.log(event.target.value);
                this.identificadorPerfil=event.target.value;
                let me=this;
                var url='/manodeobraproducto/valorMinuto/'+this.identificadorPerfil;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.valor=respuesta.valorminutos;
                //console.log(me.valor);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            nuevoValorMateria(event){
                console.log(event.target.value);
                this.identificadorMateria=event.target.value;
                let me=this;
                var url='/materiaprimaproducto/valorPrecioBase/'+this.identificadorMateria;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.valorPrecioBase=respuesta.valorPrecioBase;
                me.precioBase=respuesta.valorPrecioBase;
                me.unidadBase=respuesta.unidadBase;
                console.log(me.valorPrecioBase);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarVariab(){
                let me=this;
                var url='/financiera';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.liqui=respuesta.liqui;
                me.paraf=respuesta.paraf;
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
            listarProducto(page,buscar,criterio){
                let me=this;
                var url='/producto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayProducto=respuesta.productos.data;
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
                me.listarProducto(page,buscar,criterio);
            },
            mostrarDetalle(id,producto,area){
                this.listado=0;
                this.identificador=id;
                this.identificadorArea=area;
                this.productoNombre=producto;
            },
            ocultarDetalle(){
                this.listado=1;
                this.identificador=0;
                this.identificadorArea=0;
                this.productoNombre='';
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.materiaprimaproducto='';
                this.manodeobraproducto='';
                this.precioBase=0;
                this.unidadBase='';
                this.tipoModal=0;
                this.flag=0;
                this.seleccion=0;
            },
            abrirModal(modelo, accion, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){

                case "gestionMateria":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.tipoModal=1; //carga tipos de campos y footers
                            this.materiaprimaproducto='';
                            this.idMateriaPrima=this.idMateriaPrima;
                            this.cantidad='1';
                            this.precio=data['precioBase'];
                            this.idHoja=this.identificador;
                            this.tituloModal='Asignar nueva materia';
                            this.tipoAccion= 1; //carga tipos de botón en el footer
                            break;
                        }
                        case 'actualizar':{
                            //console.log(data);
                            this.modal=1;
                            this.tipoModal=3;
                            this.id=data['id'];
                            this.idMateriaPrima=data['idGestionMateria'];
                            this.gestionMateria=data['gestionMateria'];
                            this.cantidad=data['cantidad'];
                            this.precio=data['precioBase'];
                            this.tipoDeCosto=data['tipoDeCosto'];
                            this.idHoja=this.identificador;
                            this.tituloModal='Editar materia prima';
                            this.tipoAccion= 2;
                            break;
                        }
                    }
                    this.selectGestionMateria();
                    break;
                }

                case "gestionManoDeObra":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.tipoModal=2;
                            this.manodeobraproducto='';
                            this.idPerfil=data['idPerfil'];
                            this.idHoja=this.identificador;
                            this.idArea=this.identificadorArea;
                            this.tituloModal='Asignar nueva mano de obra';
                            this.tipoAccion= 1;
                            this.selectRelacion(this.idArea);
                            break;
                        }
                        case 'actualizar':{
                            console.log(data);
                            this.modal=1;
                            this.tipoModal=2;
                            this.idManoDeObraProducto=data['id'];
                            this.idPerfil=data['idPerfil'];
                            this.idProceso=data['idProceso'];
                            this.tiempo=data['tiempo'];
                            this.precio=data['precio'];
                            this.idHoja=this.identificador;
                            this.idArea=this.identificadorArea;
                            this.tituloModal='Editar mano de obra';
                            this.tipoAccion= 2;
                            this.selectRelacion(this.idArea);
                            this.selectRelacionPerfil(this.idProceso);
                            break;
                        }
                    }
                    break;
                }

            }

            },
            selectGestionMateria(){
                let me=this;
                var url='/materiaprimaproducto/selectGestionMateria/';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayGestionMaterias=respuesta.gestionmaterias;
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
            selectRelacionPerfil(idProceso){
                let me=this;
                var url='/manodeobraproducto/selectRelacionPerfil/'+this.idProceso;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayPerfilRelacion=respuesta.perfilrelaciones;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarMateriaPrimaProducto(page,buscar,criterio,identificador){
                let me=this;
                var url='/materiaprimaproducto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&identificador=' + identificador;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayMateriaPrimaProductos=respuesta.materiaprimaproductos.data;
                me.pagination=respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            crearMateriaPrimaProducto(){
                //valido con el metodo de validacion creado
                if(this.validarMateriaPrimaProducto()){
                    return;
                }

                let me=this;
                axios.post('/materiaprimaproducto/store',{
                    'idMateriaPrima': this.idMateriaPrima,
                    'cantidad': this.cantidad,
                    'precio': this.precioBase,
                    'tipoDeCosto': this.tipoDeCosto,
                    'idHoja': this.idHoja

                }).then(function (response) {
                me.cerrarModal();
                me.forceRerender();
                me.listarMateriaPrimaProducto(1,'','gestionMateria');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            editarMateriaPrimaProducto(){
                if(this.validarMateriaPrimaProducto()){
                    return;
                }

                let me=this;
                axios.put('/materiaprimaproducto/update',{
                    'id': this.id,
                    'cantidad': this.cantidad,
                    'precio': this.precio,
                    'tipoDeCosto': this.tipoDeCosto

                }).then(function (response) {
                me.cerrarModal();
                me.forceRerender();
                me.listarMateriaPrimaProducto(1,'','materiaprima');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            eliminarMateriaPrimaProducto(data=[]){
                let me=this;
                this.id=data['id'];
                axios.put('/materiaprimaproducto/deactivate',{
                    'id': this.id
                }).then(function (response) {
                me.forceRerender();
                me.listarMateriaPrimaProducto(1,'','materiaprima');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validarMateriaPrimaProducto(){
                this.errorMateriaPrimaProducto=0;
                this.errorMensaje=[];
                if (!this.cantidad) this.errorMensaje.push("La cantidad no puede estar vacia");
                if (this.errorMensaje.length) this.errorMateriaPrimaProducto=1;

                return this.errorMateriaPrimaProducto;
            },
            crearManoDeObraProducto(){
                //valido con el metodo de validacion creado
                if(this.validarManoDeObraProducto()){
                    return;
                }

                if(this.flag==1) {
                this.tipoPago=1;
                this.tiempo=this.tiempo;
                this.precio=this.valor; //este precio debo traerlo de consulta
                }
                else if(this.flag==2) {
                    this.tiempo=1;
                    this.precio=parseInt((this.preciom*this.liquidacion*0.073)+(this.preciom*this.parafiscales*0.046)+parseInt(this.preciom));

                    if(this.liquidacion==0 && this.parafiscales==0) {
                        this.tipoPago=2;
                    }
                    else if(this.liquidacion==3 && this.parafiscales==0) {
                        this.tipoPago=3;
                    }
                    else if(this.liquidacion==0 && this.parafiscales==4) {
                        this.tipoPago=4;
                    }
                    else {
                        this.tipoPago=5;
                    }
                }
                let me=this;
                axios.post('/manodeobraproducto/store',{
                    'idPerfil': this.idPerfil,
                    'tiempo': this.tiempo,
                    'precio': this.precio,
                    'tipoPago': this.tipoPago,
                    'idHoja': this.idHoja

                }).then(function (response) {
                me.cerrarModal();
                me.forceRerender();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            editarManoDeObraProducto(){
                if(this.validarManoDeObraProducto()){
                    return;
                }

                let me=this;
                axios.put('/manodeobraproducto/update',{
                    'id': this.idManoDeObraProducto,
                    'tiempo': this.tiempo,
                    'precio': this.preciom

                }).then(function (response) {
                me.cerrarModal();
                me.forceRerender();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            eliminarManoDeObraProducto(){
                let me=this;
                axios.put('/manodeobraproducto/deactivate',{
                    'id': this.id
                }).then(function (response) {
                me.forceRerender();
                me.listarManoDeObraProducto(1,'','manodeobra');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validarManoDeObraProducto(){
                this.errorManoDeObraProducto=0;
                this.errorMensaje=[];
                if (!this.tiempo) this.errorMensaje.push("El tiempo no puede estar vacio");
                if (this.errorMensaje.length) this.errorManoDeObraProducto=1;

                return this.errorManoDeObraProducto;
            },
            //funciones para uso del lightbox
            showLightbox(fotoCarga) {
                this.fotoCarga=fotoCarga;
                document.getElementById('over').style.display='block';
                document.getElementById('fade').style.display='block';
            },
            hideLightbox() {
                this.fotoCarga='';
                document.getElementById('over').style.display='none';
                document.getElementById('fade').style.display='none';
            },
            //cierre de funciones para uso del lightbox
        },
        mounted() {
            this.listarProducto(1,this.buscar,this.criterio),
            this.listarVariab()
        }
    }
</script>
<style>
    .fadebox {
	display: none;
	position: absolute;
	top: 0%;
	left: 0%;
	width: 100%;
	height: 100%;
	background-color: black;
	z-index:3001;
	-moz-opacity: 0.8;
	opacity:.80;
	filter: alpha(opacity=80);
    }
    .overbox {
        display: none;
        position: absolute;
        top: 5%;
        left: 5%;
        width: 90%;
        height: 90%;
        z-index:3002;
        overflow: auto;
    }
    #content {
        background: transparent;
        border: solid 3px transparent;
        padding: 10px;
    }
    .cierre {
        font-weight: 9rem;
        color:#FFFFFF;
    }
    .imglight{
        max-height:500px;
    }
    .cursor{
        cursor: pointer;
    }
</style>
