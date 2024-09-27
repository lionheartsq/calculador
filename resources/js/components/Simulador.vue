<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Simulador</li>
                </ol>

                <template v-if="listado==0">
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                       <div class="card-header">
                            <i class="fa fa-align-justify"></i> Simulación
                            <button type="button" @click="abrirModal('simulacion','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nueva
                            </button>
                        </div>

                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="descripcion">Descripción</option>
                                        <option value="id">Id</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarSimulacion(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarSimulacion(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Acciones</th>
                                        <th>Id</th>
                                        <th>Fecha</th>
                                        <th>Detalle</th>
                                        <th>Tipo cálculo</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="simulacion in arraySimulaciones" :key="simulacion.id">
                                        <td>
                                        <template v-if="simulacion.estado==1">
                                            <button type="button" class="btn btn-success btn-sm" @click="mostrarProductos(simulacion.id)">
                                                <i class="icon-plus"></i><span> Agregar</span>
                                            </button>
                                            <template v-if="simulacion.cantidad_productos > 0">
                                                <button type="button" class="btn btn-generar btn-sm" @click="abrirModal('detalle', 'crear', simulacion.id)">
                                                    <i class="icon-cloud-upload"></i><span> Generar</span>
                                                </button>
                                            </template>
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarSimulacion(simulacion.id)">
                                                <i class="icon-trash"></i><span> Eliminar</span>
                                            </button>

                                            <span>({{ simulacion.cantidad_productos }} productos)</span>

                                        </template>
                                        <template v-if="simulacion.estado==2">
                                            <button type="button" class="btn btn-detalle btn-sm" @click="mostrarDetalle(simulacion.id)">
                                                <i class="icon-magnifier"></i><span> Detalle</span>
                                            </button>
                                        </template>
                                        </td>
                                        <td v-text="simulacion.id"></td>
                                        <td v-text="simulacion.fecha"></td>
                                        <td v-text="simulacion.descripcion"></td>
                                        <td>
                                            <div v-if="simulacion.tipoCif==1">
                                            <span class="badge badge-warning">Cálculo por horas mano de obra</span>
                                            </div>
                                            <div v-else>
                                            <span class="badge badge-info">Cálculo por horas máquina</span>
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

                <template v-if="listado==1">
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Productos &nbsp;
                            <button type="submit" @click="abrirModal('rela','crear')" class="btn btn-secondary"><i class="fa fa-plus"></i> Nuevo producto</button>
                            </div>
                        <div class="card-body">
                            <productossimulacion v-bind:identificador="identificador" :key="componentKey" @abrirmodal="abrirModal" @eliminarmateria="eliminarProducto"></productossimulacion>
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
                            <hojadecostossimulador v-bind:identificador="identificador" :key="componentKey" @eliminarproducto="eliminarProducto"></hojadecostossimulador>
                            <p align="right">
                                <button class="btn btn-danger btn-custom" @click="ocultarDetalle()" aria-label="Close">Cerrar</button>
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
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre simulación</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="detalle" class="form-control" placeholder="Descripción de simulación" @input="validarEntrada">
                                            <span class="help-block">(*) Ingrese la Descripción de la Simulación</span>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="email-input">Tipo de cálculo</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="tipocif">
                                                <option value="0" disabled>Elija tipo de cálculo</option>
                                                <option value="1">Hora hombre</option>
                                                <option value="2">Hora máquina</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row div-error" v-show="errorSimulacion">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                    <!-- divs para modal tipo 2 -->

                                    <div v-if="tipoModal==2" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Producto</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idProducto">
                                                <option value="0" disabled>Seleccione un producto</option>
                                                <option v-for="posible in arrayPosibles" :key="posible.idProducto" :value="posible.idProducto" v-text="posible.producto"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div v-if="tipoModal==2" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Unidades</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="unidades" class="form-control" placeholder="Unidades a producir" @input="soloNumeros">
                                            <span class="help-block">(*) Ingrese la cantidad a producir</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==4" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Unidades</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="unidades" class="form-control" placeholder="Unidades a producir" @input="soloNumeros">
                                            <span class="help-block">(*) Ingrese la cantidad a producir</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==2" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="email-input">Tiempo</label>
                                        <div class="col-md-9">
                                            <input type="text" step="0.01" v-model="tiempo" class="form-control" placeholder="Tiempo estandar de mano de obra" @input="soloNumerosTiempo">
                                            <span class="help-block">(*) Ingrese el tiempo de mano de obra en horas</span>
                                        </div>
                                    </div>
                                     <div v-if="tipoModal==4" class="form-group row">
                                        <label class="col-md-3 form-control-label" for="email-input">Tiempo</label>
                                        <div class="col-md-9">
                                            <input type="text" step="0.01" v-model="tiempo" class="form-control" placeholder="Tiempo estandar de mano de obra" @input="soloNumerosTiempo">
                                            <span class="help-block">(*) Ingrese el tiempo de mano de obra en horas</span>
                                        </div>
                                    </div>
                                    <div v-if="tipoModal==2" class="form-group row div-error" v-show="errorSimulacion">
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
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearSimulacion()">Guardar</button>
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
    import Swal from 'sweetalert2';
    import moment from 'moment';
    import productossimulacion from '../components/ProductosSimulacion';
    import hojadecostossimulador from '../components/HojaDeCostosSimulador';
    export default {
        components: {
            productossimulacion,
            hojadecostossimulador
        },
        data(){
            return{
                idSimulacion:0,
                id:'',
                identificador:'',
                detalle:'',
                estado:'',
                arraySimulaciones : [],
                modal : 0,
                arrayPosibles : [],
                listado : 0,
                tituloModal : '',
                variable : '',
                fecha : '',
                registro:'',
                idProducto: 0,
                unidades:'',
                tiempo:'',
                tipoModal : 0,
                tipoAccion : 0,
                tipocif : 0,
                tipoCif : '',
                errorSimulacion : 0,
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
                criterio : 'descripcion',
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
            listarSimulacion(page,buscar,criterio){
                let me=this;
                var url='/simulacion?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                //console.log(url);
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arraySimulaciones=respuesta.simulaciones.data;
                me.pagination=respuesta.pagination;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            eliminarSimulacion(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/simulacion/${id}`)
                            .then(response => {
                                // Actualiza la lista después de la eliminación
                                this.listarSimulacion(1, this.buscar, this.criterio);
                                Swal.fire(
                                    'Eliminado!',
                                    'La simulación ha sido eliminada.',
                                    'success'
                                );
                            })
                            .catch(error => {
                                console.error('Hubo un error al eliminar la simulación:', error);
                                Swal.fire(
                                    'Error!',
                                    'Hubo un problema al eliminar la simulación.',
                                    'error'
                                );
                            });
                    }
                });
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

                this.unidades = event.target.value;
            },
            soloNumerosTiempo(event) {
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
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //envia peticion para ver los valores asociados a esa pagina
                me.listarSimulacion(page,buscar,criterio);
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
                var url='/rela/posibles?id=' + this.identificador;
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
            crearSimulacion(){
                //valido con el metodo de validacion creado
                 if(this.validarSimulación()){
                    return;
                }

                let me=this;
                axios.post('/simulacion/store',{
                    'detalle': this.detalle,
                    'fecha': this.fecha,
                    'tipoCif': this.tipocif
                }).then(function (response) {
                me.cerrarModal('0');
                me.listarSimulacion(1,'','');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validarEntrada(event) {
                // Al menos una letra al comienzo
                if (!/^[a-zA-Z]/.test(event.target.value)) {

                    event.target.value = '';
                } else {
                    // Permitir letras y números despues de una letra
                    const regex = /[^a-zA-Z0-9\s]/g;
                    event.target.value = event.target.value.replace(regex, '');
                }
                this.detalle = event.target.value;
            },
            crearRelacion(){
                //valido con el metodo de validacion creado
                let me=this;
                axios.post('/rela/store',{
                    'idProducto': this.idProducto,
                    'unidades': this.unidades,
                    'tiempo': this.tiempo,
                    'idSimulacion': this.identificador
                }).then(function (response) {
                me.cerrarModal('0');
                me.forceRerender();
                me.listarSimulacion(1,'','');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            editarRelacion(){
                let me=this;
                axios.put('/rela/update',{
                   'id': this.id,
                   'unidades': this.unidades,
                   'tiempo': this.tiempo,
                   'idSimulacion':this.identificador
                }).then(function (response) {
                me.cerrarModal();
                me.forceRerender();
                me.listarSimulacion(1,'','simulacion');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             eliminarProducto(id){
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
                    axios.put('/rela/delete',{
                        'id': id
                    }).then(function (response) {
                    me.forceRerender();
                    me.listarSimulacion(1,'','simulacion');
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
                    me.listarSimulacion();
                }
                })
            },
            mostrarProductos(id){
                this.listado=1;
                this.identificador=id;
            },
            mostrarDetalle(id){
                this.listado=2;
                this.identificador=id;
            },
            generarDetalle(id){
                this.identificador=id;
                let me=this;
                axios.post('/simulacion/estado',{
                    'id': this.identificador
                }).then(function (response) {
                me.cerrarModal('0');
                me.forceRerender();
                me.listarSimulacion(1,'','');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            ocultarDetalle(){
                this.listado=0;
            },
            validarSimulación(){
                this.errorSimulacion=0;
                this.errorMensaje=[];

                if (!this.detalle) this.errorMensaje.push("El nombre de la simulación no puede estar vacio");
                if (!this.tipocif) this.errorMensaje.push("El tipo de calculo no puede estar vacio");
                if (this.errorMensaje.length) this.errorSimulacion=1;

                return this.errorSimulacion;
            },
            cerrarModal(variable){
                this.modal=this.variable;
                this.tituloModal='';
                this.detalle='';
                this.errorSimulacion = 0,
                this.idProducto='';
                this.unidades='';
                this.tiempo='';
                this.errorMensaje = [],
                this.forceRerender();
            },
            abrirModal(modelo, accion,identificador,data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                    case "simulacion":
                    {
                        switch (accion) {
                            case 'crear':{
                                this.modal=1;
                                this.tipoModal=1; //carga tipos de campos y footers
                                this.tituloModal='Crear nueva simulación';
                                this.idSimulacion=this.identificador;
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
                            this.unidades=data['unidades'];
                            this.tiempo=data['tiempo'];
                            this.idSimulacion=this.identificador;
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
            this.listarSimulacion(1,this.buscar,this.criterio);
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
    .btn-custom {
        margin-right: 30px; 
    }
    .btn-generar {
        background-color: #ff671b; 
        color: #ffffff;
    }
    .btn-detalle {
        background-color: #17a2b8; 
        color: #ffffff;
        padding: 0.25rem 0.7rem;
    }
    .btn-danger {
        margin-left: 10px;
    }
</style>
