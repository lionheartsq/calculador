<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Empleados</li>
                </ol>

                <template v-if="listado">

                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Empleados &nbsp;
                            <button type="button" @click="abrirModal('empleado','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="id">Id</option>
                                        <option value="documento">Documento</option>
                                        <option value="area">Area</option>
                                        <option value="proceso">Proceso</option>
                                        <option value="perfil">Perfil</option>
                                        <option value="nombre">Nombre</option>
                                        <option value="apellido">Apellido</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarEmpleado(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarEmpleado(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Documento</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Area</th>
                                        <th>Proceso</th>
                                        <th>Perfil</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="empleado in arrayEmpleados" :key="empleado.id">
                                        <td>

                                            <button type="button" @click="mostrarDetalle(empleado.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;

                                            <button type="button" @click="abrirModal('empleado','actualizar',empleado)" class="btn btn-info btn-sm">
                                            <i class="icon-pencil" style="color: white;"></i>
                                            </button> 

                                        <template v-if="empleado.estado">
                                            <button type="button" class="btn custom-button btn-sm" @click="desactivarEmpleado(empleado.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="activarEmpleado(empleado.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>&nbsp;

                                        <!--<button v-if="!empleado.estado" type="button" class="btn btn-danger btn-sm">
                                            <i class="icon-trash"></i>
                                        </button>-->

                                        </td>
                                        <td v-text="empleado.documento"></td>
                                        <td v-text="empleado.nombre"></td>
                                        <td v-text="empleado.apellido"></td>
                                        <td v-text="empleado.area"></td>
                                        <td v-text="empleado.proceso"></td>
                                        <td v-text="empleado.perfil"></td>
                                        <td>
                                            <div v-if="empleado.estado">
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

                </template>

                    <!-- Detalle -->
                    <template v-else>
                        <div class="container-fluid">
                            <div class="card">
                              <div class="form-group row">
                                <div class="col-md-9">
                                  <div class="input-group">
                                   <div v-for="detalleempleado in arrayDetalleEmpleados" :key="detalleempleado.id">
                                    &nbsp;<b>Empleado: </b> {{detalleempleado.nombre}} {{detalleempleado.apellido}}
                                   </div>
                                   </div>
                                </div>
                             </div>
                                <vs-tabs :color="colorx">

                                <vs-tab label="Datos Contacto" icon="open_with" @click="colorx = '#CB3234'">

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Dirección</th>
                                                        <th>Telefono</th>
                                                        <th>Correo</th>
                                                        <th>Contacto emergencia</th>
                                                        <th>Teléfono emergencia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr v-for="detalleempleado in arrayDetalleEmpleados" :key="detalleempleado.id">
                                                        <td v-text="detalleempleado.direccion"></td>
                                                        <td v-text="detalleempleado.telefono"></td>
                                                        <td v-text="detalleempleado.correo"></td>
                                                        <td v-text="detalleempleado.contacto"></td>
                                                        <td v-text="detalleempleado.telefonocontacto"></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </vs-tab>

                                <vs-tab label="Datos Adicionales" icon="account_balance" @click="colorx = '#F84E13'">

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Genero</th>
                                                        <th>Tipo de Sangre</th>
                                                        <th>Enfermedades Existentes</th>
                                                        <th>Eps</th>
                                                        <th>Fondo de pensiones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr v-for="detalleempleado in arrayDetalleEmpleados" :key="detalleempleado.id">
                                                        <td v-if="detalleempleado.genero==1">Masculino</td>
                                                        <td v-else>Femenino</td>
                                                        <td v-text="detalleempleado.tipoSangre"></td>
                                                        <td v-text="detalleempleado.enfermedades"></td>
                                                        <td v-text="detalleempleado.nombreEps"></td>
                                                        <td v-text="detalleempleado.nombrePensiones"></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </vs-tab>

                                <vs-tab label="Datos Vinculación" icon="pan_tool" @click="colorx = '#4611DC'">

                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Tipo contrato</th>
                                                        <th>Tipo salario</th>
                                                        <th>Nivel de Riesgo</th>
                                                        <th>Salario Mensual</th>
                                                        <th>Fecha Inicio</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr v-for="vinculacionempleado in arrayVinculacionEmpleados" :key="vinculacionempleado.id">
                                                        <td v-if="vinculacionempleado.tipocontrato">Término fijo</td>
                                                        <td v-else-if="vinculacionempleado.tipocontrato==2">Término indefinido</td>
                                                        <td v-if="vinculacionempleado.tiposalario">Sueldo fijo</td>
                                                        <td v-else-if="vinculacionempleado.tiposalario==2">Destajo</td>
                                                        <td v-text="vinculacionempleado.nivel"></td>
                                                        <td v-text="formatCurrency(vinculacionempleado.salarioBasicoMensual)"></td>
                                                        <td v-text="vinculacionempleado.fechainicio"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
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
                                <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Documento</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="documento" class="form-control" placeholder="Documento de Identificación" @input="soloNumeros">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Area</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idArea" @change='selectRelacion(area.idArea)'>
                                                <option value="0" disabled>Seleccione un área</option>
                                                <option v-for="area in arrayArea" :key="area.idArea" :value="area.idArea" v-text="area.area"></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Proceso</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idProceso" @change='selectRelacionPerfil(relacion.idProceso)'>
                                                <option value="0" disabled>Seleccione un proceso</option>
                                                <option v-for="relacion in arrayRelacion" :key="relacion.idProceso" :value="relacion.idProceso" v-text="relacion.proceso"></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Perfil</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idPerfil">
                                                <option value="0" disabled>Seleccione un perfil</option>
                                                <option v-for="perfilrelacion in arrayPerfilRelacion" :key="perfilrelacion.idPerfil" :value="perfilrelacion.idPerfil" v-text="perfilrelacion.perfil"></option>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del Empleado">
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Apellido</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="apellido" class="form-control" placeholder="Apellido del Empleado">
                                        </div>
                                    </div>
                                    <!--<div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Genero</label>
                                         <div class="col-md-9">
                                        <input type="radio" v-model="genero" value="Masculino">
                                        <label for="male">Masculino</label><br>
                                        <input type="radio" v-model="genero" value="Femenino">
                                        <label for="female">Femenino</label><br>
                                         </div>
                                    </div>-->
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Genero</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="genero">
                                                <option value="0" disabled>Seleccione el genero</option>
                                                <option value="1">Masculino</option>
                                                <option value="2">Femenino</option>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="direccion" class="form-control" placeholder="Dirección del Empleado">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="telefono" class="form-control" placeholder="Telefono del Empleado" @input="soloNumerosTelefono">
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Correo</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="correo" class="form-control" placeholder="Correo del Empleado" @input="validarEmail" :class="{ 'is-invalid': emailError }">
                                            <span v-if="emailError" class="text-danger">{{ emailError }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Contacto emergencia</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="contacto" class="form-control" placeholder="Contacto de emergencia" @input="soloNumerosContEmerg">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Telefono emergencia</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="telefonocontacto" class="form-control" placeholder="Telefono de emergencia" @input="soloNumerosTelEmerg">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="email-input">Seleccione la Eps</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idEps">
                                                <option value="0" disabled>Seleccione la Eps</option>
                                                <option v-for="eps in arrayEps" :key="eps.id" :value="eps.id" v-text="eps.nombreEps"></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="email-input">Seleccione la Administradora Pensiones</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idPensiones">
                                                <option value="0" disabled>Seleccione la Administradora de Pensiones</option>
                                                <option v-for="pensiones in arrayPensiones" :key="pensiones.id" :value="pensiones.id" v-text="pensiones.nombrePensiones"></option>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Tipo de Sangre</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="tipoSangre" class="form-control" placeholder="Tipo de sangre">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Enfermedades Existentes</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="enfermedades" class="form-control" placeholder="Enfermedades existentes">
                                        </div>
                                    </div>
                                    <div class="form-group row div-error" v-show="errorEmpleado">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearEmpleado()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarEmpleado()">Editar</button>
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
                idEmpleado:0,
                id:'',
                documento: 0,
                listado: 1,
                nombre:'',
                apellido:'',
                genero:'',
                direccion:'',
                telefonocontacto:0,
                contacto:'',
                telefono: 0,
                tipoSangre:'',
                enfermedades:'',
                correo:'',
                estado:'',
                arrayEmpleados : [],
                arrayDetalleEmpleados: [],
                arrayVinculacionEmpleados: [],
                idEps:0,
                nombreEps: '',
                arrayEps:[],
                idPensiones:0,
                nombrePensiones: '',
                arrayPensiones:[],
                idArea: 0,
                area : '',
                arrayArea: [],
                idProceso: 0,
                proceso:'',
                relacion:'',
                perfilrelacion:'',
                colorx:'#8B0000',
                arrayRelacion: [],
                idPerfil:0,
                perfil:'',
                arrayPerfilRelacion:[],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEmpleado : 0,
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
            listarEmpleado(page,buscar,criterio){
                let me=this;
                var url='/empleado?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayEmpleados=respuesta.empleados.data;
                me.pagination=respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                })
            },
            listarDetalleEmpleado(id){
                let me=this;
                var url='/empleado/detalleEmpleado?id='+id;
                console.log('Url peticion empleado');
                console.log(url);
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayDetalleEmpleados=respuesta.detalleempleados;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            soloNumeros(event) {
                const input = event.target.value;
                const regex = /^\d{0,10}$/; // Permite hasta 10 dígitos

                if (!regex.test(input)) {
                    event.target.value = input.slice(0, -1);
                }

                if (input.length > 0 && input.charAt(0) === '0') {
                    event.target.value = input.slice(1); // Eliminar el primer carácter si es 0
                }

                this.documento = event.target.value;
            },
            soloNumerosTelefono(event) {
                const input = event.target.value;
                const regex = /^\d{0,10}$/; // Permite hasta 10 dígitos

                if (!regex.test(input)) {
                    event.target.value = input.slice(0, -1);
                }

                if (input.length > 0 && input.charAt(0) === '0') {
                    event.target.value = input.slice(1); // Eliminar el primer carácter si es 0
                }

                this.telefono = event.target.value;
            },
            soloNumerosContEmerg(event) {
                const input = event.target.value;
                const regex = /^\d{0,10}$/; // Permite hasta 10 dígitos

                if (!regex.test(input)) {
                    event.target.value = input.slice(0, -1);
                }

                if (input.length > 0 && input.charAt(0) === '0') {
                    event.target.value = input.slice(1); // Eliminar el primer carácter si es 0
                }

                this.contacto = event.target.value;
            },
            soloNumerosTelEmerg(event) {
                const input = event.target.value;
                const regex = /^\d{0,10}$/; // Permite hasta 10 dígitos

                if (!regex.test(input)) {
                    event.target.value = input.slice(0, -1);
                }

                if (input.length > 0 && input.charAt(0) === '0') {
                    event.target.value = input.slice(1); // Eliminar el primer carácter si es 0
                }

                this.telefonocontacto = event.target.value;
            },
            validarEmail() {
                const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                if (this.correo && !emailPattern.test(this.correo)) {
                    this.emailError = 'Ingrese un correo electrónico válido.';
                } else {
                    this.emailError = '';
                }
            },
            formatCurrency(value) {
                if (!value) return '';
                return parseInt(value).toLocaleString('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    minimumFractionDigits: 0
                });
            },
            listarVinculacionEmpleado(id){
                let me=this;
                var url='/empleado/vinculacionEmpleado?id='+id;
                console.log('Url peticion empleado');
                console.log(url);
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayVinculacionEmpleados=respuesta.vinculacionempleados;
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
            mostrarDetalle(id){
                this.listado=0;
                this.identificador=id;
                console.log('Identificador empleado');
                console.log(this.identificador);
                this.listarDetalleEmpleado(id);
                this.listarVinculacionEmpleado(id);
            },
            ocultarDetalle(){
                this.listado=1;
                this.identificador=0;
            },
            selectPerfil(){
                let me=this;
                var url='/perfil/selectPerfil';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayPerfiles=respuesta.perfiles;
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
            selectRelacionPerfil(idProceso){
                let me=this;
                var url='/empleado/selectRelacionPerfil/'+this.idProceso;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayPerfilRelacion=respuesta.perfilrelaciones;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            selectEps(){
                let me=this;
                var url='/empleado/selectEps';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayEps=respuesta.eps;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            selectPensiones(){
                let me=this;
                var url='/empleado/selectPensiones';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayPensiones=respuesta.pensiones;
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
                me.listarEmpleado(page,buscar,criterio);
            },
            crearEmpleado(){
                //valido con el metodo de validacion creado
                if(this.validarEmpleado()){
                    return;
                }

                let me=this;
                axios.post('/empleado/store',{
                    'documento': this.documento,
                    'idPerfil': this.idPerfil,
                    'nombre': this.nombre,
                    'apellido': this.apellido,
                    'genero': this.genero,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'correo': this.correo,
                    'telefonocontacto': this.telefonocontacto,
                    'contacto': this.contacto,
                    'idEps':this.idEps,
                    'idPensiones':this.idPensiones,
                    'tipoSangre':this.tipoSangre,
                    'enfermedades':this.enfermedades
                }).then(function (response) {
                me.cerrarModal();
                me.listarEmpleado(1,'','empleado');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            editarEmpleado(){
                if(this.validarEmpleado()){
                    return;
                }
                let me=this;
                axios.put('/empleado/update',{
                    'id': this.idEmpleado,
                    'documento': this.documento,
                    'idPerfil': this.idPerfil,
                    'nombre': this.nombre,
                    'apellido': this.apellido,
                    'genero':this.genero,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'correo': this.correo,
                    'telefonocontacto': this.telefonocontacto,
                    'contacto': this.contacto,
                    'idEps':this.idEps,
                    'idPensiones':this.idPensiones,
                    'tipoSangre':this.tipoSangre,
                    'enfermedades':this.enfermedades
                }).then(function (response) {
                me.cerrarModal();
                me.listarEmpleado(1,'','empleado');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            desactivarEmpleado(id){
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
                    axios.put('/empleado/deactivate',{
                        'id': id
                    }).then(function (response) {
                    me.listarEmpleado(1,'','empleado');
                    swalWithBootstrapButtons.fire(
                    'Empleado desactivado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarEmpleado();
                }
                })
            },
            activarEmpleado(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Quiere activar este empleado?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Activar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/empleado/activate',{
                        'id': id
                    }).then(function (response) {
                    me.listarEmpleado(1,'','empleado');
                    swalWithBootstrapButtons.fire(
                    'Empleado Activado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarEmpleado();
                }
                })
            },
            validarEmpleado(){
                this.errorArea=0;
                this.errorProceso=0;
                this.errorPerfil=0;
                this.errorEmpleado=0;
                this.errorMensaje=[];

                if (!this.documento) this.errorMensaje.push("El Documento no puede estar vacio");
                if (this.documento<0) this.errorMensaje.push("El Documento no puede ser negativo");
                if (!this.nombre) this.errorMensaje.push("El Nombre no puede estar vacio");
                if (!this.apellido) this.errorMensaje.push("El Apellido no puede estar vacio");
                if (!this.genero) this.errorMensaje.push("El Genero no puede estar vacio");
                if (!this.direccion) this.errorMensaje.push("La Dirección no puede estar vacia");
                if (!this.telefono) this.errorMensaje.push("El Telefono no puede estar vacio");
                if (this.telefono<0) this.errorMensaje.push("El Telefono puede ser negativo");
                if (!this.correo) this.errorMensaje.push("El Correo no puede estar vacio");
                if (!this.tipoSangre) this.errorMensaje.push("El contacto no puede estar vacio");
                if (!this.enfermedades) this.errorMensaje.push("El telefono de contacto no puede estar vacio");
                if (!this.tipoSangre) this.errorMensaje.push("El tipo de sangre no puede estar vacio");
                if (!this.enfermedades) this.errorMensaje.push("Las enfermedades no pueden estar vacias");
                if (this.errorMensaje.length) this.errorEmpleado=1;

                return this.errorEmpleado;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.empleado='';
                this.documento="";
                this.idArea="";
                this.idProceso="";
                this.idPerfil=
                this.nombre="";
                this.apellido= "";
                this.genero="";
                this.direccion="";
                this.telefono="";
                this.correo="";
                this.contacto="";
                this.telefonocontacto="";
                this.idEps="";
                this.idPensiones="";
                this.tipoSangre="";
                this.enfermedades="";
                this.errorEmpleado = 0,
                this.errorMensaje = [],
                this.forceRerender();
            },
            abrirModal(modelo, accion, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                case "empleado":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.empleado='';
                            this.idPerfil=data['idPerfil'];
                            this.tituloModal='Crear nuevo empleado';
                            this.tipoAccion= 1;
                            this.selectRelacion(this.idArea);
                            break;
                        }
                         case 'actualizar':{
                            //console.log(data);
                            this.modal=1;
                            this.tituloModal='Editar empleado';
                            this.tipoAccion= 2;
                            this.idEmpleado=data['id'];
                            this.documento=data['documento'];
                            this.idArea=data['idArea'];
                            this.proceso=data['proceso']; //añadido para alimentar el select
                            this.idPerfil=data['idPerfil'];
                            this.nombre=data['nombre'];
                            this.apellido=data['apellido'];
                            this.genero=data['genero'];
                            this.direccion=data['direccion'];
                            this.telefono=data['telefono'];
                            this.correo=data['correo'];
                            this.contacto=data['contacto'];
                            this.telefonocontacto=data['telefonocontacto'];
                            this.tipoSangre=data=['tipoSangre'];
                            this.enfermedades=data=['enfermedades'];
                            this.selectRelacion(this.idArea);
                            this.selectRelacionPerfil(this.idProceso);
                            break;
                        }
                    }
              }
            break;
            }
            }
        },
        mounted() {
            this.listarEmpleado(1,this.buscar,this.criterio);
            this.selectArea();
            this.selectPerfil();
            this.selectEps();
            this.selectPensiones();
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
