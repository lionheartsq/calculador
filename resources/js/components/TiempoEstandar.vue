<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Tiempo Estandar</li>
                </ol>
                      <!-- Listado -->
                <template v-if="listado==1">
                    <!-- Ejemplo de tabla Listado -->
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header">
                            <i class="fa fa-align-justify"></i> Tiempo Estandar
                            <button type="button" @click="abrirModal('tiempoestandar','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                           </div>
                            <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="id">Id</option>
                                        <option value="idEmpleado">Empleado</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarTiempoEstandar(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarTiempoEstandar(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Empleado</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="tiempoestandar in arrayTiempoEstandar" :key="tiempoestandar.id">
                                        <td>
                                            <template v-if="tiempoestandar.estado==1">
                                            <button type="button" class="btn btn-danger btn-sm" @click="mostrarTiempoEstandar(tiempoestandar.id)">
                                                <i class="icon-plus"><span>Gestionar</span></i>
                                            </button>

                                                <template v-if="tiempoestandar.cantidad_ciclos > 0">
                                                    <button type="button" class="btn btn-calcular btn-sm" @click="abrirModal('detalle', 'crear', tiempoestandar.id)">
                                                        <i class="icon-cloud-upload"><span>Calcular</span></i>
                                                    </button>
                                                </template>

                                            <span>({{ tiempoestandar.cantidad_ciclos }} ciclos)</span>
                                            </template>
                                            <template v-if="tiempoestandar.estado==0">
                                            <button type="button" class="btn btn-success btn-sm" @click="mostrarDetalle(tiempoestandar.id)">
                                                <i class="icon-magnifier"></i><span>Ver Detalle Tiempo Estandar</span>
                                            </button>
                                        </template>
                                        </td>
                                        <td v-text="tiempoestandar.nombreEmpleado"></td>
                                        <td v-text="tiempoestandar.fecha"></td>
                                        <td>
                                            <div v-if="tiempoestandar.estado">
                                            <span class="badge badge-warning">En Proceso</span>
                                            </div>
                                            <div v-else>
                                            <span class="badge badge-success">Finalizado</span>
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
                    <template v-if="listado==2">
                        <div class="container-fluid">
                            <div class="card">
                                <vs-tabs :color="colorx">

                                <vs-tab label="Ciclos" icon="pan_tool" @click="colorx = '#8B0000'">

                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i> Ciclos &nbsp;
                                        <button type="button" @click="abrirModal('ciclos','crear')" class="btn btn-secondary">
                                            <i class="icon-plus"></i>&nbsp;Nueva Iteración
                                        </button>
                                    </div>

                                    <div class="card-body">
                                        <ciclos v-bind:identificador="identificador" :key="componentKey" @abrirmodal="abrirModal"></ciclos>
                                    </div>

                                </vs-tab>

                                <vs-tab label="Westing House" icon="pan_tool" @click="colorx = '#FFA500'">

                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i> Westing House &nbsp;
                                    </div>

                                    <div class="card-body">
                                        <westinghouse v-bind:identificador="identificador" :key="componentKey" @abrirmodal="abrirModal"></westinghouse>
                                    </div>

                                </vs-tab>

                               <vs-tab label="Pds" icon="pan_tool" @click="colorx = '#4611DC'">

                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i> Pds &nbsp;
                                    </div>

                                    <div class="card-body">
                                        <pds v-bind:identificador="identificador" :key="componentKey" @abrirmodal="abrirModal"></pds>
                                    </div>

                                </vs-tab>

                                <vs-tab label="Cerrar" icon="cancel_schedule_send" @click="ocultarDetalle()">
                                </vs-tab>

                                </vs-tabs>
                            </div>
                        </div>
                    </template>

                <template v-if="listado==3">
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                       <i class="fa fa-align-justify"></i> Detalle del Tiempo Estandar &nbsp;
                        </div>
                        <div class="card-body">
                            <detalletiempoestandar v-bind:identificador="identificador" :key="componentKey"></detalletiempoestandar>
                            <p align="right">
                                <button  class="btn btn-danger" @click="ocultarDetalle()" aria-label="Close">Cerrar</button>
                            </p>
                        </div>
                    </div>
                    <!-- Fin ejemplo de tabla Listado -->
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

                                     <!--Inicia el modal para crear tiempo estandar-->
                                     <div v-if="tipoModal==1" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="email-input">Empleado</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idEmpleado">
                                                <option value="0" disabled>Seleccione un empleado</option>
                                                <option v-for="empleado in arrayEmpleados" :key="empleado.id" :value="empleado.id" v-text="empleado.nombreEmpleado"></option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--<div v-if="tipoModal==1" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input"># Piezas de la Toma de Tiempos <br></label>
                                        <div class="col-md-9">
                                            <input type="number" step="0.01" v-model="numeroPiezas" class="form-control" placeholder="# Piezas de la Toma de Tiempos">
                                            <span class="help-block">(*) Ingrese el numero de piezas</span>
                                        </div>
                                    </div>-->
                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Piezas por Par <br></label>
                                        <div class="col-md-9">
                                            <input type="text" step="0.01" v-model="piezasPar" class="form-control" placeholder="Piezas por Par" @input="soloNumeros">
                                            <span class="help-block">(*) Ingrese las piezas por par</span>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row div-error" v-show="errorTiempoEstandar">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div> 
                                    <div v-if="tipoModal==1" class="modal-footer">
                                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                   <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearTiempoTiempoEstandar()">Guardar</button>
                                   </div>
                                    <!--Termina el modal para crear tiempo estandar-->
                                    
                                    <!--Inicia el modal para crear ciclos-->
                                    <div v-if="tipoModal==2" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Tiempo en Ciclos<br></label>
                                        <div class="col-md-9">
                                            <input type="text" step="0.01" v-model="tiempo" class="form-control" placeholder="Tiempo en Ciclos" @input="soloNumerosCiclos">
                                            <span class="help-block">(*) Ingrese el tiempo en ciclos</span>
                                        </div>
                                    </div>

                                     <div v-if="tipoModal==2" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Piezas <br></label>
                                        <div class="col-md-9">
                                            <input type="text" step="0.01" v-model="piezas" class="form-control" placeholder="Piezas" @input="soloNumerosPiezas">
                                            <span class="help-block">(*) Ingrese las piezas</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==2" class="form-group row">
                                        <div class="col-md-9">
                                            <input type="hidden" v-model="idTiempoEstandar" id="idTiempoEstandar">
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==2" class="form-group row div-error" v-show="errorCiclos">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>
                                    <!--Termina el modal para crear ciclos-->
                                 <!-- Si el tipo es 3 solo es modal para mostrar carga -->

                                    <div v-if="tipoModal==3" class="carga">
                                        <p><hr><h1>Calculando, por favor espere...</h1><hr></p>
                                    </div>
                                </form>
                            </div>

                                <!-- divs para footer tipo 2 y 3 -->

                            <div v-if="tipoModal==2" class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearCiclos()">Guardar</button>
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
    import moment from 'moment';
    import detalletiempoestandar from '../components/DetalleTiempoEstandar.vue';
    import ciclos from '../components/Ciclos';
    import westinghouse from '../components/WestingHouse';
    import pds from '../components/Pds';
    export default {
        components: {
            detalletiempoestandar,
            ciclos,
            westinghouse,
            pds
        },
        data(){
            return{
                colorx: '#8B0000',
                listado: 1,
                identificador:0,
                idTiempoEstandar:0,
                id:'',
                fecha : '',
                piezasPar:0,
                estado: '',
                variable : '',
                idEmpleado: 0,
                nombreEmpleado: '',
                arrayEmpleados : [],
                arrayTiempoEstandar : [],
                tituloModal : '',
                tipoModal : 0,
                tipoAccion : 0,
                tiempo:0,
                piezas:0,
                errorTiempoEstandar : 0,
                errorCiclos:0,
                errorMensaje : [],
                modal : 0,
                seleccion:0,
                flag : 0,
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
                identificador: 0,
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
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
            listarTiempoEstandar(page,buscar,criterio){
                let me=this;
                var url='/tiempoestandar?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayTiempoEstandar=respuesta.tiempoestandar.data;
                me.pagination=respuesta.pagination;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            soloNumeros(event) {
                const input = event.target.value;
                const regex = /^\d*$/; // Permite cualquier cantidad de dígitos

                if (!regex.test(input)) {
                    event.target.value = input.slice(0, -1);
                }

                if (input.length > 0 && input.charAt(0) === '0') {
                    event.target.value = input.slice(1); // Eliminar el primer carácter si es 0
                }

                this.piezasPar = event.target.value;
            },
            soloNumerosCiclos(event) {
                const input = event.target.value;
                const regex = /^\d*$/; // Permite cualquier cantidad de dígitos

                if (!regex.test(input)) {
                    event.target.value = input.slice(0, -1);
                }

                if (input.length > 0 && input.charAt(0) === '0') {
                    event.target.value = input.slice(1); // Eliminar el primer carácter si es 0
                }

                this.tiempo = event.target.value;
            },
            soloNumerosPiezas(event) {
                const input = event.target.value;
                const regex = /^\d*$/; // Permite cualquier cantidad de dígitos

                if (!regex.test(input)) {
                    event.target.value = input.slice(0, -1);
                }

                if (input.length > 0 && input.charAt(0) === '0') {
                    event.target.value = input.slice(1); // Eliminar el primer carácter si es 0
                }

                this.piezas = event.target.value;
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //envia peticion para ver los valores asociados a esa pagina
                me.listarTiempoEstandar(page,buscar,criterio);
            },
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
            listarEmpleados(){
                let me=this;
                var url='/tiempoestandar/empleados';
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
            crearTiempoTiempoEstandar(){
                //valido con el metodo de validacion creado
                 //valido con el metodo de validacion creado
                if(this.validarTiempoEstandar()){
                    return;
                }

                let me=this;
                this.fecha= moment().format('YYYY-MM-DD');
                axios.post('/tiempoestandar/store',{
                    'fecha': this.fecha,
                    'idEmpleado': this.idEmpleado,
                    'piezasPar':this.piezasPar
                }).then(function (response) {
                me.cerrarModal('0');
                me.listarTiempoEstandar(1,'','');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             validarTiempoEstandar(){
                this.errorTiempoEstandar=0;
                this.errorMensaje=[];
                
                if (!this.piezasPar) this.errorMensaje.push("Las piezas por par no pueden estar vacias");
                if (this.errorMensaje.length) this.errorTiempoEstandar=1;

                return this.errorTiempoEstandar;
            },
            mostrarTiempoEstandar(id){
                this.listado=2;
                this.identificador=id;
                (this.identificador);
            },
            mostrarDetalle(id){
                this.listado=3;
                this.identificador=id;
                (this.identificador);
            },
            generarDetalle(id){
                this.identificador=id;
                let me=this;
                axios.post('/tiempoestandar/estado',{
                    'id': this.identificador
                }).then(function (response) {
                me.cerrarModal('0');
                me.forceRerender();
                me.listarTiempoEstandar(1,'','');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            ocultarDetalle(){
                this.listarTiempoEstandar(1,this.buscar,this.criterio);
                this.forceRerender();
                this.listado=1;
            },
            cerrarModal(variable){
                this.modal=this.variable;
                this.tituloModal='';
                this.detalle='';
                this.idEmpleado='';
                this.numeroPiezas='';
                this.piezasPar='';
                this.tiempo='';
                this.piezas='';
                this.errorTiempoEstandar = 0,
                this.errorCiclos=0,
                this.errorMensaje = [],
                this.forceRerender();

            },
            abrirModal(modelo, accion, identificador, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){

                case "tiempoestandar":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                                this.tipoModal=1; //carga tipos de campos y footers
                                this.tituloModal='Crear tiempo estandar';
                                this.tipoAccion= 1; //carga tipos de botón en el footer
                                this.fecha= moment().format('YYYY-MM-DD');
                                break;
                        }
                    }
                    break;
                }

                case "ciclos":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.tipoModal=2;
                            this.idTiempoEstandar=this.identificador;
                            this.tituloModal='Crear nuevo ciclo';
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
                                this.tipoModal=5; //carga tipos de campos y footers
                                this.tituloModal='Calculando, por favor espere...';
                                this.identificador=identificador;
                                break;
                            }
                        }
                        this.generarDetalle(this.identificador);
                        break;
                    }

            }

            },
            listarCiclos(page,buscar,criterio,identificador){
                let me=this;
                var url='/tiempoestandar/listarciclos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&identificador=' + identificador;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayCiclos=respuesta.ciclos.data;
                me.pagination=respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            crearCiclos(){
                //valido con el metodo de validacion creado
                if(this.validarCiclos()){
                    return;
                }

                let me=this;
                axios.post('/tiempoestandar/guardarciclos',{
                    'idCiclos': this.idCiclos,
                    'tiempo': this.tiempo,
                    'piezas': this.piezas,
                    'idTiempoEstandar':this.idTiempoEstandar

                }).then(function (response) {
                me.cerrarModal();
                me.forceRerender();
                me.listarCiclos(1,'','ciclos');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validarCiclos(){
                this.errorCiclos=0;
                this.errorMensaje=[];
                if (!this.tiempo) this.errorMensaje.push("El tiempo en ciclos no puede estar vacio");
                if (!this.piezas) this.errorMensaje.push("las piezas no pueden estar vacias");
                if (this.errorMensaje.length) this.errorCiclos=1;

                return this.errorCiclos;
            },
        },
        mounted() {
            this.listarTiempoEstandar(1,this.buscar,this.criterio),
            this.listarEmpleados();
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
    .btn-calcular {
        background-color: #ff671b; 
        color: #ffffff;
    }
</style>
