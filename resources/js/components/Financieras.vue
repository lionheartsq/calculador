<template>
        <main>

                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Variables &nbsp;
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <table class="table table-bordered table-striped table-sm">
                                <tbody>
                                    <tr>
                                        <td class="col-6">
                                            Vacaciones
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="vacaciones" @input="ingresoPorcentaje('vacaciones')" class="form-control" placeholder="Vacaciones">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Prima
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="prima" @input="ingresoPorcentaje('prima')" class="form-control" placeholder="Prima">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Cesantías
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="cesantias" @input="ingresoPorcentaje('cesantias')" class="form-control" placeholder="Cesantías">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Intereses a las cesantías
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="intereses" @input="ingresoPorcentaje('intereses')" class="form-control" placeholder="Intereses a las cesantías">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Salud
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="salud" @input="ingresoPorcentaje('salud')" class="form-control" placeholder="Salud">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Pensión
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="pension" @input="ingresoPorcentaje('pension')" class="form-control" placeholder="Pensión">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            ARL
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="arl" @input="ingresoPorcentaje('arl')" class="form-control" placeholder="ARL">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-6">
                                            Caja de compensación
                                        </td>
                                        <td class="col-6">
                                            <input type="text" v-model="caja" @input="ingresoPorcentaje('caja')" class="form-control" placeholder="Caja de compensación">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                                <button type="button" class="btn btn-primary" @click="actualizarDatos(vacaciones,prima,cesantias,intereses,salud,pension,arl,caja)">Actualizar</button>
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
        data(){
            return{
                id : 1,
                vacaciones : '',
                prima : '',
                cesantias : '',
                intereses : '',
                salud : '',
                pension : '',
                arl : '',
                caja : ''
            }
        },
        methods : {
            listarVariables(){
                let me=this;
                var url='/financiera';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.vacaciones = respuesta.vacaciones.toString().replace(/\./g, ',');
                me.prima=respuesta.prima.toString().replace(/\./g, ',');
                me.cesantias=respuesta.cesantias.toString().replace(/\./g, ',');
                me.intereses=respuesta.intereses.toString().replace(/\./g, ',');
                me.salud=respuesta.salud.toString().replace(/\./g, ',');
                me.pension=respuesta.pension.toString().replace(/\./g, ',');
                me.arl=respuesta.arl.toString().replace(/\./g, ',');
                me.caja=respuesta.caja.toString().replace(/\./g, ',');
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
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
            actualizarDatos(vacaciones,prima,cesantias,intereses,salud,pension,arl,caja){
                
                if (!vacaciones || !prima || !cesantias || !intereses || !salud || !pension || !arl || !caja) {

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
                axios.post('/financiera/actualizar',{
                    'id': 1,
                    'vacaciones' : this.vacaciones.replace(/,/g, '.'),
                    'prima' : this.prima.replace(/,/g, '.'),
                    'cesantias' : this.cesantias.replace(/,/g, '.'),
                    'intereses' : this.intereses.replace(/,/g, '.'),
                    'salud' : this.salud.replace(/,/g, '.'),
                    'pension' : this.pension.replace(/,/g, '.'),
                    'arl' : this.arl.replace(/,/g, '.'),
                    'caja' : this.caja.replace(/,/g, '.')
                }).then(function (response) {
                swalWithBootstrapButtons.fire('Registro actualizado');
                me.listarVariables()
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.listarVariables()
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
