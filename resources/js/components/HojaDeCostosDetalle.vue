<template>
    <main class="minimo">

            <div class="row">
                <div class="table-responsive col-md-5">
                    <h4>Hoja de Costos</h4>
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <td>Orden de producción : </td>
                                <td>Costeo por capacidad</td>
                            </tr>
                            <tr>
                                <td>Referencia del producto : </td>
                                <td>{{this.referenciap}}</td>
                            </tr>
                            <tr>
                                <td>Nombre del producto : </td>
                                <td>{{this.nombrep}}</td>
                            </tr>
                            <tr>
                                <td>Numero de unidades : </td>
                                <td>{{this.capacidadtotales}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive col-md-4">
                    <h4>Costos de producción totales</h4>
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <td>Materia Prima</td>
                                <td>{{this.materiatotales | currency}}</td>
                            </tr>
                            <tr>
                                <td>Mano de Obra</td>
                                <td>{{this.manodeobratotales | currency}}</td>
                            </tr>
                            <tr>
                                <td>Cif Totales</td>
                                <td>{{this.ciftotales | currency}}</td>
                            </tr>
                             <tr>
                                <td bgcolor="#a2f1a2"><b>Costo {{this.presentacion}}</b></td>
                                <td bgcolor="#a2f1a2"><b>{{this.costopar | currency}}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive col-md-3">
                    <center>
                        <img :src="'img/avatars/' + this.fotop" alt="" style="max-width:200px; max-height:200px;">
                    </center>
                </div>

            </div>

            <div class="row">
                <div class="table-responsive col-md-4">
                    <h4>Materia prima</h4>
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <td>Total por {{this.presentacion}}</td>
                                <td>{{this.acumuladomp | currency}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive col-md-3">
                    <h4>Mano de obra</h4>
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <td>Total por {{this.presentacion}}</td>
                                <td>{{this.acumuladomo | currency}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive col-md-5">
                    <h4>Costos indirectos</h4>
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <td>Total por {{this.presentacion}}</td>
                                <td>{{this.cifunidad | currency}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">

                <div class="table-responsive col-md-4">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Detalle</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="materiaprima in arrayMateriaPrima" :key="materiaprima.id">
                                <td>{{materiaprima.gestionMateria}}</td>
                                <td>{{materiaprima.subtotal | currency}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive col-md-3">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Detalle</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="manodeobra in arrayManoDeObra" :key="manodeobra.id">
                                <td>{{manodeobra.proceso}}</td>
                                <td>{{manodeobra.subtotal | currency}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive col-md-5">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Detalle</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Depreciación</td>
                                <td>{{depreciacion | currency}}</td>
                            </tr>
                            <tr v-for="concepto in arrayConceptos" :key="concepto.id">
                                <td>{{concepto.concepto}}</td>
                                <td>{{concepto.valor | currency}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

    </main>
</template>

<script>
    export default {
        props: {
            identificador: {
            type: Number
            }
         },
        data(){
            return{
                listado : 0,
                producto:'',
                referencia:'',
                unidades:'',
                tiempo:'',
                produccion:'',
                acumuladocifto:'',
                acumuladocalculo:'',
                valorbase:'',
                arrayProductos:[],
                idProducto:'',
                total:'',
                acumuladomd:0,
                acumuladomi:0,
                acumuladomo:0,
                acumuladocif:0,
                acumuladomaquinaria:0,
                acumuladocift:0,
                acumuladototal:0,
                arrayTotales:[],
                arrayMateriaPrima:[],
                arrayManoDeObra:[],
                arrayConceptos:[],
                depreciacion:0,
                modal : 0,
                tipoModal : 0,
                acumuladomp : 0,
                acumuladomo : 0,
                cifunidad : 0,
                capacidadtotales : 0,
                ciftotales : 0,
                materiatotales : 0,
                manodeobratotales : 0,
                consolidadototales : 0,
                nombrep : '',
                referenciap : '',
                fotop : '',
                presentacion : '',
                costopar : ''
            }
        },
        methods : {
            costeoUnidad(identificador){
                let me=this;
                var url='/hojadecosto/unitariogen?identificador=' + this.identificador;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.acumuladomp=respuesta.acumuladomp;
                me.acumuladomo=respuesta.acumuladomo;
                me.cifunidad=respuesta.cifunitario;
                me.capacidadtotales=respuesta.capacidadproducto;//estimadoproduccion es simulado, capacidadproducto es real
                me.ciftotales=respuesta.acumuladocift;
                me.nombrep=respuesta.nombrep;
                me.referenciap=respuesta.referenciap;
                me.fotop=respuesta.fotop;
                me.presentacion=respuesta.presentacion;
                me.costopar=respuesta.costopar;
                me.acumuladomd=respuesta.acumuladomd;
                me.acumuladomi=respuesta.acumuladomi;
                me.materiatotales=(me.acumuladomp*me.capacidadtotales);
                me.manodeobratotales=(me.acumuladomo*me.capacidadtotales);
                me.consolidadototales=(me.materiatotales+me.manodeobratotales+me.ciftotales);
                //console.log(url);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            costeoDetalle(identificador){
                let me=this;
                var url='/hojadecosto/detallegen?identificador=' + this.identificador;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayMateriaPrima=respuesta.materiaprimaproductos;
                me.arrayManoDeObra=respuesta.manodeobraproductos;
                me.arrayConceptos=respuesta.conceptos;
                me.depreciacion=respuesta.acumuladomaquinaria;
                //console.log(url);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
        },
        mounted() {
            this.costeoUnidad(this.identificador),
            this.costeoDetalle(this.identificador)
        }
    }
</script>

<style>
    .minimo {
	min-height: 150px;
    }
</style>
