<template>
    <main class="minimo">

                    <!-- Ejemplo de tabla Listado -->
                    <div class="card">
                       <div class="card-header">
                            <i class="fa fa-align-justify"></i> Novedades
                            <button type="button" @click="abrirModal('novedad','crear',identificadornomina)" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Novedad
                            </button>
                            <button type="button" @click="abrirModal('novedad','crears',identificadornomina)" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Deducible
                            </button>
                        </div>

                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="fechaNovedad">Fecha</option>
                                        <option value="empleado">Fecha</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarNovedades(1,buscar,criterio,identificadornomina)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarNovedades(1,buscar,criterio,identificadornomina)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Fecha novedad</th>
                                        <th>Concepto</th>
                                        <th>Observación</th>
                                        <th>Cantidad</th>
                                        <th>Valor</th>
                                        <th>Empleado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="novedad in arrayNovedades" :key="novedad.id">

                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarNovedad(novedad.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </td>
                                        <td v-text="novedad.fechaNovedad"></td>

                                        <td v-if="novedad.concepto==1">Ingreso por labor</td>
                                        <td v-if="novedad.concepto==2">Horas extras y recargos</td>
                                        <td v-if="novedad.concepto==3">Prima extralegal</td>
                                        <td v-if="novedad.concepto==4">Bonificaciones</td>
                                        <td v-if="novedad.concepto==5">Comisiones</td>
                                        <td v-if="novedad.concepto==6">Viaticos</td>
                                        <td v-if="novedad.concepto==7">Conceptos que no son factor salarial</td>
                                        <td v-if="novedad.concepto==8">Auxilio de transporte destajo</td>
                                        <td v-if="novedad.concepto==50">Retención</td>
                                        <td v-if="novedad.concepto==51">Sindicato</td>
                                        <td v-if="novedad.concepto==52">Préstamos</td>
                                        <td v-if="novedad.concepto==53">Embargos</td>
                                        <td v-if="novedad.concepto==54">Descuento por libranza/otros</td>

                                        <td v-text="novedad.observacion"></td>
                                        <td v-text="novedad.cantidad"></td>
                                        <td v-text="formatCurrency(novedad.valor)"></td>
                                        <td v-text="novedad.empleado"></td>

                                    </tr>
                                </tbody>
                            </table>
                            </div>
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio,identificadornomina)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio,identificadornomina)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio,identificadornomina)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <!-- Fin ejemplo de tabla Listado -->

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
                                        <label class="col-md-3 form-control-label" for="email-input">Novedad</label>
                                        <div v-if="desplegable==1 && tipologiaNomina==1" class="col-md-9">
                                            <select class="form-control" v-model="concepto" @change='onChange($event)'>
                                                <option value="0" disabled>Seleccione tipo de novedad</option>
                                                <option value="1">Detalle Ingresos</option>
                                                <option value="2">Horas extras y recargos</option>
                                                <option value="3">Prima extralegal</option>
                                                <option value="4">Bonificaciones</option>
                                                <option value="5">Comisiones</option>
                                                <option value="6">Viaticos</option>
                                                <option value="7">Conceptos que no son factor salarial</option>
                                            </select>
                                        </div>
                                        <div v-if="desplegable==1 && tipologiaNomina==2" class="col-md-9">
                                            <select class="form-control" v-model="concepto" @change='onChange($event)'>
                                                <option value="0" disabled>Seleccione tipo de novedad</option>
                                                <option value="1">Tareas realizadas</option>
                                                <option value="8">Auxilio de transporte</option>
                                            </select>
                                        </div>
                                        <!-- Si es una salida -->
                                        <div v-else-if="desplegable==2 && tipologiaNomina==1" class="col-md-9">
                                            <select class="form-control" v-model="concepto" @change='onChange($event)'>
                                                <option value="0" disabled>Seleccione tipo de novedad</option>
                                                <option value="50">Retención</option>
                                                <option value="51">Sindicato</option>
                                                <option value="52">Préstamos</option>
                                                <option value="53">Embargos</option>
                                                <option value="54">Descuento por libranza/otros</option>
                                            </select>
                                        </div>

                                        <div v-else-if="desplegable==2 && tipologiaNomina==2" class="col-md-9">
                                            <select class="form-control" v-model="concepto" @change='onChange($event)'>
                                                <option value="0" disabled>Seleccione tipo de novedad</option>
                                                <option value="52">Préstamos</option>
                                                <option value="54">Otros descuentos</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row">

                                        <label class="col-md-3 form-control-label" for="text-input">Fecha de Novedad</label>
                                        <div class="col-md-9">
                                            <input type="date" v-model="fechaNovedad" class="form-control" placeholder="Ingrese la fecha de la novedad">
                                            <span class="help-block">(*) Ingrese fecha de novedad</span>
                                        </div>

                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row">

                                        <label class="col-md-3 form-control-label" for="text-input">Empleado</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="idEmpleado" @change='selectCaract($event)'>
                                                <option value="0" disabled>Seleccione empleado</option>
                                                <option v-for="empleado in arrayEmpleados" :key="empleado.idEmpleado" :value="empleado.idEmpleado" v-text="empleado.empleado"></option>
                                            </select>
                                            <span class="help-block">(*) Empleado</span>
                                        </div>

                                    </div>
                                <!-- -->
                                    <div v-if="tipoModal==1 && flag==1 && tipologiasalario==1" class="form-group row">

                                        <label class="col-md-3 form-control-label" for="text-input">Dias laborados</label>
                                        <div class="col-md-9">
                                            <input type="number" v-model="cantidad" class="form-control" placeholder="Dias">
                                            <span class="help-block">(*) Dias de labor</span>
                                        </div>

                                    </div>

                                    <div v-if="tipoModal==1 && flag==1 && tipologiasalario==2" class="form-group row">

                                        <label class="col-md-3 form-control-label" for="text-input">Detalle Tarea</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="observacion" class="form-control" placeholder="Detalle tarea">
                                            <span class="help-block">(*) Detalle tarea entregada</span>
                                        </div>

                                    </div>

                                    <div v-if="tipoModal==1 && flag==1 && tipologiasalario==2" class="form-group row">

                                        <label class="col-md-3 form-control-label" for="text-input">Cantidad Tareas</label>
                                        <div class="col-md-9">
                                            <input type="number" v-model="cantidad" class="form-control" placeholder="Valor">
                                            <span class="help-block">(*) Cantidad tareas entregadas</span>
                                        </div>

                                    </div>

                                    <div v-if="tipoModal==1 && flag==1 && tipologiasalario==2" class="form-group row">

                                        <label class="col-md-3 form-control-label" for="text-input">Valor Tarea</label>
                                        <div class="col-md-9">
                                            <input type="number" v-model="unitario" class="form-control" placeholder="Valor">
                                            <span class="help-block">(*) Valor tarea entregada</span>
                                        </div>

                                    </div>

                                    <div v-if="tipoModal==1 && flag==1 && tipologiasalario==2" class="form-group row">

                                            <label class="col-md-3 form-control-label" for="text-input">Porcentajes adicionales</label>

                                            <div class="col-md-3">
                                                <input type="checkbox" true-value="4" false-value="3" v-model="seguimiento" checked>
                                                <label for="liquidacion">Calcular salud y pensión</label>
                                            </div>

                                    </div>

<!--Inicio bloque extras-->
                                    <div v-if="tipoModal==1 && flag==2 && tipologiasalario==1" class="form-group row">

                                        <label class="col-md-3 form-control-label" for="text-input">Tipo de Horas Extras</label>
                                        <div class="col-md-9">
                                            <select class="form-control" v-model="extras" @change='selectExtra($event)'>
                                                <option value="0" disabled>Seleccione tipo de hora extra</option>
                                                <option value="1">Extra Diurna</option>
                                                <option value="2">Extra Nocturna</option>
                                                <option value="3">Hora Dominical o Festiva</option>
                                                <option value="4">Extra Dominical o Festiva Diurna</option>
                                                <option value="5">Extra Dominical o Festiva Nocturna</option>
                                                <option value="6">Recargos</option>
                                            </select>
                                            <span class="help-block">(*) Tipo de horas extras</span>
                                        </div>

                                    </div>

                                    <div v-if="tipoModal==1 && flag==2 && tipologiasalario==1" class="form-group row">

                                        <label class="col-md-3 form-control-label" for="text-input">Cantidad de Horas Extras</label>
                                        <div class="col-md-9">
                                            <input type="number" v-model="cantidad" class="form-control" placeholder="Extras">
                                            <span class="help-block">(*) Cantidad de horas extras</span>
                                        </div>

                                    </div>

                                    <div v-if="tipoModal==1 && flag==2 && tipologiasalario==2" class="form-group row">

                                        <label class="col-md-12 form-control-label">
                                            <center><h5>No se pueden asignar extras a un trabajo por destajo</h5></center>
                                        </label>

                                    </div>
<!--Fin bloque extras-->
                                    <div v-if="tipoModal==1 && flag>2" class="form-group row">

                                        <label class="col-md-3 form-control-label" for="text-input">Valor</label>
                                        <div class="col-md-9">
                                            <input type="number" v-model="unitario" class="form-control" placeholder="Valor">
                                            <span class="help-block">(*) Ingrese el Valor</span>
                                        </div>

                                    </div>
                                <!-- -->

                                    <div v-if="tipoModal==1 && flag>2" class="form-group row">

                                        <label class="col-md-3 form-control-label" for="text-input">Observación</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="observacion" class="form-control" placeholder="Observación">
                                            <span class="help-block">(*) Ingrese observación</span>
                                        </div>

                                    </div>

                                    <div v-if="tipoModal==1" class="form-group row div-error" v-show="errorNovedad">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div v-if="tipoModal==1" class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearNovedad()">Guardar</button>
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
    export default {
        props: {
            identificadornomina: {
            type: Number
            }
         },
        data(){
            return{
                id:'',
                tipologiasalario:0,
                observacion:'',
                idEmpleado:0,
                idExtra:0,
                extras:0,
                cantidad:1,
                valor:0,
                tipologiaNomina:1,
                fecha : '',
                flag : 0,
                concepto : 0,
                valor: 0,
                unitario:0,
                liquidacion:3,
                parafiscales:4,
                liqui:0.073,
                paraf:0.046,
                arrayNovedades : [],
                arrayEmpleados : [],
                mensajecantidad:'',
                modal : 0,
                desplegable : 0,
                listado : 0,
                seguimiento:4,
                tituloModal : '',
                variable : '',
                hoy : '',
                tipologia : 0,
                tipoModal : 0,
                tipoAccion : 0,
                errorNovedad : 0,
                extraDiurna: 0,
                extraNocturna: 0,
                horaDominical: 0,
                festivaDiurna: 0,
                festivaNocturna: 0,
                recargos: 0,
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
                criterio : 'fechaNovedad',
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
            console.log('Valor flag:');
            console.log(this.flag);
            },
            selectCaract(event) { //Busca el dato de la tipologia de sueldo
                let me=this;
                me.idEmpleado=event.target.value;

                var url='/novedades/salario?identificador=' + this.idEmpleado;
                console.log(url);
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.tipologiasalario=respuesta.tipologiasalario;
                me.baseSal=respuesta.baseSal;
                me.baseDia=respuesta.baseDia;
                me.baseHora=respuesta.baseHora;
                    //console.log(response);
                    console.log("Tipo de salario");
                    console.log(me.flag);
                    console.log(me.tipologiasalario);
                    if ((me.flag==1) && (me.tipologiasalario==1)) { //si el tipo de sueldo es fijo
                        me.observacion="Dias laborados";
                        me.unitario=me.baseDia;
                        }
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            formatCurrency(value) {
                if (!value) return '';
                return parseInt(value).toLocaleString('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    minimumFractionDigits: 0
                });
            },
            eliminarNovedad(idNovedad){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })
                swalWithBootstrapButtons.fire({
                title: 'Quiere eliminar esta novedad?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Eliminar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/novedades/eliminar',{
                        'identificador': idNovedad
                    }).then(function (response) {
                    me.forceRerender();
                    me.listarNovedades(1,'','fechaNovedad',this.identificadornomina);
                    swalWithBootstrapButtons.fire(
                    'Novedad eliminada!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarNovedades();
                }
                })
            },
            selectExtra(event) { //Busca el dato de la tipologia de sueldo
                let me=this;
                me.idExtra=event.target.value;

                var url='/novedades/selectextra';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.extraDiurna=respuesta.extraDiurna;
                me.extraNocturna=respuesta.extraNocturna;
                me.horaDominical=respuesta.horaDominical;
                me.festivaDiurna=respuesta.festivaDiurna;
                me.festivaNocturna=respuesta.festivaNocturna;
                me.recargos=respuesta.recargos;
                    //console.log(response);
                    console.log(me.extraDiurna);
                    console.log(me.extraNocturna);
                    console.log(me.horaDominical);
                    console.log(me.festivaDiurna);
                    console.log(me.festivaNocturna);
                    console.log(me.recargos);
                    if (me.idExtra==1) {
                        me.observacion="Extra Diurna";
                        me.unitario=(me.extraDiurna*me.baseHora);
                        }
                    else if (me.idExtra==2) {
                        me.observacion="Extra Nocturna";
                        me.unitario=(me.extraNocturna*me.baseHora);
                        }
                    else if (me.idExtra==3) {
                        me.observacion="Hora Dominical o Festiva";
                        me.unitario=(me.horaDominical*me.baseHora);
                        }
                    else if (me.idExtra==4) {
                        me.observacion="Extra Dominical o Festiva Diurna";
                        me.unitario=(me.festivaDiurna*me.baseHora);
                        }
                    else if (me.idExtra==5) {
                        me.observacion="Extra Dominical o Festiva Nocturna";
                        me.unitario=(me.festivaNocturna*me.baseHora);
                        }
                    else if (me.idExtra==6) {
                        me.observacion="Recargos";
                        me.unitario=(me.recargos*me.baseHora);
                        }
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarNovedades(page,buscar,criterio,identificadornomina){
                let me=this;
                var url='/novedades/gen?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&identificador=' + identificadornomina;
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayNovedades=respuesta.novedades.data;
                me.pagination=respuesta.pagination;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            currentDateTime() {
                return moment().format('YYYY-MM-DD')
            },
            cambiarPagina(page,buscar,criterio,identificadornomina){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //envia peticion para ver los valores asociados a esa pagina
                me.listarNovedades(page,buscar,criterio,this.identificadornomina);
            },
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
            crearNovedad(){
                //valido con el metodo de validacion creado
                let me=this;
                this.fecha= moment().format('YYYY-MM-DD');
                this.valor=(this.cantidad*this.unitario);
                axios.post('/novedades/store',{
                    'fechaNovedad': this.fechaNovedad,
                    'concepto':this.concepto,
                    'valor':this.valor,
                    'cantidad':this.cantidad,
                    'unitario':this.unitario,
                    'observacion':this.observacion,
                    'tipologia':this.tipologia,
                    'idEmpleado':this.idEmpleado,
                    'seguimiento':this.seguimiento,
                    'idNomina':this.identificadornomina
                }).then(function (response) {
                console.log('Enviados valores');
                me.cerrarModal('0');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarEmpleados(identificadornomina){
                let me=this;
                var url='/novedades/selectempleado?identificador='+this.identificadornomina;
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
            listarTipologia(identificadornomina){
                let me=this;
                var url='/novedades/selecttipologia?identificador='+this.identificadornomina;
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.tipologiaNomina=respuesta.tipoNomina;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            validarNovedad(){
                this.errorNovedad=0;
                this.errorMensaje=[];

                if (!this.idEmpleado) this.errorMensaje.push("El empleado no puede estar vacio");
                if (this.errorMensaje.length) this.errorNovedad=1;

                return this.errorNovedad;
            },
            cerrarModal(variable){
                this.modal=this.variable;
                this.tituloModal='';
                this.detalle='';
                this.idEmpleado=0;
                this.mensajecantidad='';
                this.cantidad=1;
                this.unitario='';
                this.valor='';
                this.tipologia='';
                this.observacion='';
                this.flag=0;
                this.tipologiasalario=0;
                this.concepto=0;
                this.errorNovedad=0;
                this.errorMensaje=[];
                this.tituloModal='';

                this.listarNovedades(1,'','fechaNovedad',this.identificadornomina);
            },
            abrirModal(modelo, accion, identificadornomina){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                    case "novedad":
                    {
                        switch (accion) {
                            case 'crear':{
                                this.modal=1;
                                this.tipologia=1;
                                this.tipoModal=1; //carga tipos de campos y footers
                                this.tituloModal='Ingreso de novedades';
                                this.desplegable= 1; //carga tipos de botón en el footer
                                this.tipoAccion= 1; //carga tipos de botón en el footer
                                this.fechaNovedad= moment().format('YYYY-MM-DD');
                                this.identificadornomina=identificadornomina;
                                console.log('Valores');
                                console.log(identificadornomina);
                                this.listarNovedades(1,'','fechaNovedad',this.identificadornomina);
                                break;
                            }
                            case 'crears':{
                                this.modal=1;
                                this.tipologia=2;
                                this.tipoModal=1; //carga tipos de campos y footers
                                this.tituloModal='Ingreso de novedades deducibles';
                                this.desplegable= 2; //carga tipos de botón en el footer
                                this.tipoAccion= 1; //carga tipos de botón en el footer
                                this.fechaNovedad= moment().format('YYYY-MM-DD');
                                this.listarNovedades(1,'','fechaNovedad',this.identificadornomina);
                                break;
                            }
                        }
                        break;
                    }

                }
            }
        },
        mounted() {
            this.listarTipologia(this.identificadornomina);
            this.listarNovedades(1,'','fechaNovedad',this.identificadornomina);
            this.listarEmpleados(this.identificadornomina);
            console.log('Valor identificador recibido');
            console.log(this.identificadornomina);
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
    .minimo {
	min-height: 250px;
    }
</style>
