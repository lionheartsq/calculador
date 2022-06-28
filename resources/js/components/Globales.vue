<template>
        <main>

                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Configuración Basica Empresa &nbsp;
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <table class="table table-bordered table-striped table-sm">
                                <tbody>
                                    <tr>
                                        <td>
                                            Nombre Empresa
                                        </td>
                                        <td>
                                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre Empresa">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Dirección Empresa
                                        </td>
                                        <td>
                                            <input type="text" v-model="direccion" class="form-control" placeholder="Dirección Empresa">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Telefono Empresa
                                        </td>
                                        <td>
                                            <input type="number" v-model="telefono" step="0.01" class="form-control" placeholder="Telefono Empresa">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Caja de Compensación Empresa
                                        </td>
                                        <td>
                                        <select class="form-control" v-model="cajaCompensacion">
                                        <option value="0" disabled>Seleccione la caja de compensación</option>
                                        <option v-for="caja in arrayCajas" :key="caja.id" :value="caja.id" v-text="caja.NombreCajaCompensacion">
                                        </option>
                                       </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Arl Empresa
                                        </td>
                                        <td>
                                        <select class="form-control" v-model="arl">
                                        <option value="0" disabled>Seleccione la arl</option>
                                        <option v-for="arl in arrayArl" :key="arl.id" :value="arl.id" v-text="arl.nombreArl">
                                        </option>
                                       </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Nivel de Riesgo Empresa
                                        </td>
                                        <td>
                                        <select class="form-control" v-model="nivelRiesgo">
                                        <option value="0" disabled>Seleccione el nivel de riesgo</option>
                                        <option v-for="nivel in arrayNivel" :key="nivel.id" :value="nivel.id" v-text="nivel.nivelArl">
                                        </option>
                                        </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Tipo Nomina Empresa
                                        </td>
                                        <td>
                                        <select class="form-control" v-model="idTipoNomina">
                                        <option value="0" disabled>Seleccione el tipo de nomina</option>
                                        <option v-for="tiponomina in arrayTipoNomina" :key="tiponomina.id" :value="tiponomina.id" v-text="tiponomina.tipoNomina">
                                        </option>
                                       </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                                <input type="hidden" v-model="id">
                                <!-- <button type="button" class="btn btn-success" @click="guardarDatos()">Guardar</button> -->
                                <button type="button" class="btn btn-primary" @click="actualizarDatos(id,nombre,direccion,telefono,cajaCompensacion,arl,nivelRiesgo,idTipoNomina)">Actualizar</button>
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
    export default {
        props: {
            identificador: {
            type: Number
            }
         },
        data(){
            return{
                id : '',
                identificador:0,
                nombre : '',
                direccion : '',
                telefono : '',
                cajaCompensacion : '',
                NombreCajaCompensacion:'',
                arrayCajas:[],
                arrayNivel:[],
                arrayConfiguracion:[],
                arl : '',
                nombreArl:'',
                arrayArl:[],
                nivelRiesgo : '',
                idTipoNomina : '',
                arrayTipoNomina:[],
                tipoNomina:''
            }
        },
        methods : {
            listarConfiguracionBasica(){
                let me=this;
                var url='/configuracion';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.id=respuesta.id;
                me.nombre=respuesta.nombre;
                me.direccion=respuesta.direccion;
                me.telefono=respuesta.telefono;
                me.cajaCompensacion=respuesta.cajaCompensacion;
                me.arl=respuesta.arl;
                me.nivelRiesgo=respuesta.nivelRiesgo;
                me.idTipoNomina=respuesta.idTipoNomina;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarTipoNomina(){
                let me=this;
                var url='/configuracion/tiponomina';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayTipoNomina=respuesta.tiponomina;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarCajaCompensacion(){
                let me=this;
                var url='/configuracion/cajacompensacion';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayCajas=respuesta.cajacompensacion;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarArl(){
                let me=this;
                var url='/configuracion/arl';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayArl=respuesta.arl;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            listarNivelArl(){
                let me=this;
                var url='/configuracion/nivelarl';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayNivel=respuesta.niveles;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            guardarDatos(){
                //valido con el metodo de validacion creado
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success'
                },
                buttonsStyling: false
                })
                let me=this;
                axios.post('/configuracion/store',{
                    'nombre' : this.nombre,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'cajaCompensacion' : this.cajaCompensacion,
                    'arl' : this.arl,
                    'nivelRiesgo' : this.nivelRiesgo,
                    'idTipoNomina' : this.idTipoNomina
                }).then(function (response) {
                swalWithBootstrapButtons.fire('Registro creado');
                me.listarConfiguracionBasica();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            actualizarDatos(id,nombre,direccion,telefono,cajaCompensacion,arl,nivelRiesgo,idTipoNomina){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success'
                },
                buttonsStyling: false
                })
                let me=this;
                axios.post('/configuracion/actualizar',{
                    'id': this.id,
                    'nombre' : this.nombre,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'cajaCompensacion' : this.cajaCompensacion,
                    'arl' : this.arl,
                    'nivelRiesgo' : this.nivelRiesgo,
                    'idTipoNomina' : this.idTipoNomina
                }).then(function (response) {
                swalWithBootstrapButtons.fire('Registro actualizado');
                me.listarConfiguracionBasica();
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.listarConfiguracionBasica(),
            this.listarTipoNomina(),
            this.listarCajaCompensacion(),
            this.listarArl();
            this.listarNivelArl();
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
