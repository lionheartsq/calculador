<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>

                    <li class="breadcrumb-item active">Vista personalizada</li>
                </ol>
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">

                                <div class="table-responsive">
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Escritorio</th>
                                            <th>Documentacion</th>
                                            <th>Administracion</th>
                                            <th>Conceptos cif</th>
                                            <th>Materiales</th>
                                            <th>Productos</th>
                                            <th>Produccion</th>
                                            <th>Kardex</th>
                                            <th>Mano de obra</th>
                                            <th>Personas</th>
                                            <th>Nomina</th>
                                            <th>Gestion Financiera</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <!--  -->
                                        <input type="hidden" v-model="idVista" value="idVista">
                                        <input type="hidden" v-model="idUser" value="idUser">
                                        <td><input type="checkbox" v-model="escritorio" value="escritorio"></td>
                                        <td><input type="checkbox" v-model="documentacion" value="documentacion"></td>
                                        <td><input type="checkbox" v-model="administracion" value="administracion"></td>
                                        <td><input type="checkbox" v-model="conceptosCif" value="conceptosCif"></td>
                                        <td><input type="checkbox" v-model="materiales" value="materiales"></td>
                                        <td><input type="checkbox" v-model="productos" value="productos"></td>
                                        <td><input type="checkbox" v-model="produccion" value="produccion"></td>
                                        <td><input type="checkbox" v-model="kardex" value="kardex"></td>
                                        <td><input type="checkbox" v-model="manoDeObra" value="manoDeObra"></td>
                                        <td><input type="checkbox" v-model="personas" value="personas"></td>
                                        <td><input type="checkbox" v-model="nomina" value="nomina"></td>
                                        <td><input type="checkbox" v-model="gestionFinanciera" value="gestionFinanciera"></td>
                                        <!-- <td><VueToggles @click="value = !value" v-model="this.escritorio"/></td> -->
                                    </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary" @click="actualizarDatos(idVista,escritorio,documentacion,administracion,conceptosCif,materiales,productos,produccion,kardex,manoDeObra,personas,nomina,gestionFinanciera,idUser)">Actualizar</button>
                                </form>
                                </div>
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
        data(){
            return{
                idVista:'',
                escritorio:'',
                documentacion:'',
                administracion:'',
                conceptosCif:'',
                materiales:'',
                productos:'',
                produccion:'',
                kardex:'',
                manoDeObra:'',
                personas:'',
                nomina:'',
                gestionFinanciera:'',
                idUser:''
            }
        },
        computed:{

        },
        methods : {
            listarVista(){
                let me=this;
                var url='/vistaPersonalizada';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.idVista= respuesta.idVista;
                me.escritorio= respuesta.escritorio;
                me.documentacion= respuesta.documentacion;
                me.administracion= respuesta.administracion;
                me.conceptosCif= respuesta.conceptosCif;
                me.materiales= respuesta.materiales;
                me.productos= respuesta.productos;
                me.produccion= respuesta.produccion;
                me.kardex= respuesta.kardex;
                me.manoDeObra= respuesta.manoDeObra;
                me.personas= respuesta.personas;
                me.nomina= respuesta.nomina;
                me.gestionFinanciera= respuesta.gestionFinanciera;
                me.idUser= respuesta.idUser;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            actualizarDatos(idVista,escritorio,documentacion,administracion,conceptosCif,materiales,productos,produccion,kardex,manoDeObra,personas,nomina,gestionFinanciera,idUser){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success'
                },
                buttonsStyling: false
                })
                let me=this;
                axios.post('/vistaPersonalizada/actualizar',{
                    'id':this.idVista,
                    'idUser':this.idUser,
                    'escritorio' : this.escritorio,
                    'documentacion' : this.documentacion,
                    'administracion' : this.administracion,
                    'conceptosCif' : this.conceptosCif,
                    'materiales' : this.materiales,
                    'productos' : this.productos,
                    'produccion' : this.produccion,
                    'kardex' : this.kardex,
                    'manoDeObra' : this.manoDeObra,
                    'personas' : this.personas,
                    'nomina' : this.nomina,
                    'gestionFinanciera' : this.gestionFinanciera
                }).then(function (response) {
                // swalWithBootstrapButtons.fire('Vista actualizada');
                location. reload();
                //me.listarConfiguracionBasica();
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.listarVista();
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
