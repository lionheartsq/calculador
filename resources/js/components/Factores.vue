<template>
        <main>

                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Factor Nomina &nbsp;
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <table class="table table-bordered table-striped table-sm">
                                <tbody>
                                    <tr>
                                        <td class="col-6">
                                            Extra Diurna
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="extraDiurna"  @input="ingresoPorcentaje('extraDiurna')" class="form-control" placeholder="Extra Diurna">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Extra Nocturna
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="extraNocturna" @input="ingresoPorcentaje('extraNocturna')" class="form-control" placeholder="Extra Nocturna">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Hora Dominical
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="horaDominical" @input="ingresoPorcentaje('horaDominical')" class="form-control" placeholder="Hora Dominical">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Festiva Diurna
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="festivaDiurna" @input="ingresoPorcentaje('festivaDiurna')" class="form-control" placeholder="Festiva Diurna">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Festiva Nocturna
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="festivaNocturna" @input="ingresoPorcentaje('festivaNocturna')" class="form-control" placeholder="Festiva Nocturna">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Recargos
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="recargos" @input="ingresoPorcentaje('recargos')" class="form-control" placeholder="Recargos">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Salario mínimo mensual legal vigente
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="minimolegal" @input="formatMoney" class="form-control" placeholder="SMMLV">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Auxilio de transporte
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="auxilio" @input="formatMoneyAuxilio" class="form-control" placeholder="Auxilio">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                                <!-- <button type="button" class="btn btn-success" @click="guardarDatos()">Guardar</button> -->
                                <button v-if="id==1" type="button" class="btn btn-primary" @click="actualizarDatos(extraDiurna,extraNocturna,horaDominical,festivaDiurna,festivaNocturna,recargos,minimolegal,auxilio)">Actualizar</button>
                            </form>
                            </div>
                        </div>
                    </div>
                    <!-- Fin ejemplo de tabla Listado -->
                </div>
                <!--Inicio del modal agregar/actualizar-->

                <!--Fin del modal-->
        </main>
</template>

<script>
import Swal from 'sweetalert2';

    export default {
         props: {
            identificador: {
            type: Number
            }
         },
        data(){
            return{
                id : 1,
                identificador:0,
                extraDiurna : '',
                extraNocturna : '',
                horaDominical : '',
                festivaDiurna : '',
                festivaNocturna : '',
                recargos : '',
                minimolegal : '',
                auxilio : ''
            }
        },
        methods : {
            listarFactorNomina(){
                let me=this;
                var url='/factores';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.extraDiurna=respuesta.extraDiurna;
                me.extraNocturna=respuesta.extraNocturna;
                me.horaDominical=respuesta.horaDominical;
                me.festivaDiurna=respuesta.festivaDiurna;
                me.festivaNocturna=respuesta.festivaNocturna;
                me.recargos=respuesta.recargos;
                me.minimolegal = parseFloat(respuesta.minimolegal).toLocaleString('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    minimumFractionDigits: 0
                });
                me.auxilio= parseFloat(respuesta.auxilio).toLocaleString('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    minimumFractionDigits: 0
                });
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            guardarDatos(){
                //valido con el metodo de validacion creado
                let me=this;
                axios.post('/factores/store',{
                    'nombre' : this.nombre,
                    'extraDiurna' : this.extraDiurna,
                    'extraNocturna' : this.extraNocturna,
                    'horaDominical' : this.horaDominical,
                    'festivaDiurna' : this.festivaDiurna,
                    'festivaNocturna' : this.festivaNocturna,
                    'recargos' : this.recargos,
                    'minimolegal' : this.minimolegal.replace(/\D/g, ""), 
                    'auxilio' : this.auxilio.replace(/\D/g, ""), 
                }).then(function (response) {
                me.listarFactorNomina();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            ingresoPorcentaje(campo) {
                let valor = this[campo];

                valor = valor.replace(/[^0-9.,]/g, '');

                valor = valor.replace(/,/g, '.');

                if (/^-?\d+(\.\d{0,2})?$/.test(valor)) {

                    if (!/\./.test(valor)) {
                    valor = valor.substring(0, 6);
                    }
                    this[campo] = valor; 
                } else {                  
                    this[campo] = this[campo].slice(0, -1);
                }
            },
            actualizarDatos(extraDiurna,extraNocturna,horaDominical,festivaDiurna,festivaNocturna,recargos){
                
                if (!extraDiurna || !extraNocturna || !horaDominical || !festivaDiurna || !festivaNocturna || !recargos) {

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Por favor, completa todos los campos del formulario.',
                });
                return; 
                }
                
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success'
                },
                buttonsStyling: false
                })
                let me=this;
                axios.post('/factores/actualizar',{
                    'id': 1,
                    'extraDiurna' : this.extraDiurna,
                    'extraNocturna' : this.extraNocturna,
                    'horaDominical' : this.horaDominical,
                    'festivaDiurna' : this.festivaDiurna,
                    'festivaNocturna' : this.festivaNocturna,
                    'recargos' : this.recargos,
                    'minimolegal' : this.minimolegal.replace(/\D/g, ""), 
                    'auxilio' : this.auxilio.replace(/\D/g, ""), 
                }).then(function (response) {
                swalWithBootstrapButtons.fire('Registro actualizado');
                me.listarFactorNomina()
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            formatMoney(event) {
                let value = event.target.value.replace(/\D/g, ""); 
                if (value.length > 8) { 
                    value = value.slice(0, 8);
                }
                if (value !== "") {
                    value = parseInt(value).toLocaleString('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    minimumFractionDigits: 0
                    });
                }
                event.target.value = value;
                this.minimolegal = value;
                },

                formatMoneyAuxilio(event) {
                let value = event.target.value.replace(/\D/g, ""); 
                if (value.length > 8) { 
                    value = value.slice(0, 8);
                }
                if (value !== "") {
                    value = parseInt(value).toLocaleString('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    minimumFractionDigits: 0
                    });
                }
                event.target.value = value;
                this.auxilio = value;
                }
        },
        mounted() {
            this.listarFactorNomina()
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
