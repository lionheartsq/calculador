<template>
        <!-- Ejemplo de tabla Listado -->
        <div>
            <div class="table-responsive">
            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Esfuerzo Mental</th>
                        <th>Esfuerzo Fisico</th>
                        <th>Suplementarios</th>
                        <th>Personales</th>
                        <th>Valor Espera en Sg</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="pds in arrayPds" :key="pds.id">
                    <td>
                    <select class="form-control" v-model="idEsfuerzoMental">
                    <option v-for="esfuerzomental in arrayEsfuerzoMental" :key="esfuerzomental.id" :value="esfuerzomental.id" v-text="esfuerzomental.nombreEsfuerzoMental">
                     </option>
                    </select>
                    </td>
                    <td>
                     <select class="form-control" v-model="idEsfuerzoFisico">
                    <option v-for="esfuerzofisico in arrayEsfuerzoFisico" :key="esfuerzofisico.id" :value="esfuerzofisico.id" v-text="esfuerzofisico.nombreEsfuerzoFisico"></option>
                    </select>
                    </td>
                    <td>
                    <select class="form-control" v-model="idSuplementario">
                    <option v-for="suplementario in arraySuplementarios" :key="suplementario.id" :value="suplementario.id" v-text="suplementario.nombreSuplementario"></option>
                    </select>
                    </td>
                    <td>
                    <select class="form-control" v-model="idPersonales">
                    <option v-for="personales in arrayPersonales" :key="personales.id" :value="personales.id" v-text="personales.nombrePersonales"></option>
                    </select>
                    </td>
                    <td>
                    <input type="number" id="valorEspera" v-model="valorEspera" class="form-control" placeholder="Valor Espera en Sg">
                    </td>
                    <input type="hidden" v-model="idTiempoEstandar" id="idTiempoEstandar">
                    </tr>
                </tbody>
            </table>
             <div class="container-buttons">
             <p align="right">
            <button type="button" class="btn btn-success" @click="crearPds()">Guardar</button>
                     </p>
                    </div>
                </form>
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
                    <!-- Fin ejemplo de tabla Listado -->
        </div>
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
                idPds:0,
                identificador:0,
                arrayPds:[],
                arrayEsfuerzoMental:[],
                arrayEsfuerzoFisico:[],
                arraySuplementarios:[],
                arrayPersonales:[],
                arrayEspera:[],
                nombreEsfuerzoMental: '',
                nombreEsfuerzoFisico: '',
                nombreSuplementarios: '',
                nombrePersonales: '',
                nombreEspera: '',
                idTiempoEstandar:0,
                idEsfuerzoMental:0,
                idEsfuerzoFisico:0,
                idSuplementario:0,
                idPersonales:0,
                idEspera:1,
                idMonotonia:1,
                valorEspera:0,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
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
                listarPds(page,identificador){
                let me=this;
                var url='/tiempoestandar/listarpds?page=' + page + '&id='+identificador;
                console.log(url);
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayPds=respuesta.pds.data;
                me.idEsfuerzoMental=me.arrayPds[0].idEsfuerzoMental;
                me.idEsfuerzoFisico=me.arrayPds[0].idEsfuerzoFisico;
                me.idSuplementario=me.arrayPds[0].idSuplementario;
                me.idPersonales=me.arrayPds[0].idPersonales;
                me.idEspera=me.arrayPds[0].idEspera;
                me.idMonotonia=me.arrayPds[0].idMonotonia;
                me.valorEspera=me.arrayPds[0].valorEspera;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
        cambiarPagina(page){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //envia peticion para ver los valores asociados a esa pagina
                me.listarCiclos(page,this.identificador);
            },
        forceRerender() {
                this.componentKey += 1;
               },
        listarEsfuerzoMental(){
                let me=this;
                var url='/tiempoestandar/esfuerzomental';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayEsfuerzoMental=respuesta.esfuerzomental;
                me.forceRender();
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
        listarEsfuerzoFisico(){
                let me=this;
                var url='/tiempoestandar/esfuerzofisico';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayEsfuerzoFisico=respuesta.esfuerzofisico;
                me.forceRender();
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
        listarSuplementario(){
                let me=this;
                var url='/tiempoestandar/suplementarios';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arraySuplementarios=respuesta.suplementarios;
                me.forceRender();
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
        listarPersonales(){
                let me=this;
                var url='/tiempoestandar/personales';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayPersonales=respuesta.personales;
                me.forceRender();
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
        listarEspera(){
                let me=this;
                var url='/tiempoestandar/espera';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayEspera=respuesta.espera;
                me.forceRender();
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
        crearPds() {
            let me = this;

            // Verificar si hay ciclos antes de enviar la solicitud
            if (!me.arrayPds || me.arrayPds.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se han creado ciclos. Por favor, cree al menos un ciclo antes de guardar.',
                });
                return;
            }

            axios.post('/tiempoestandar/guardarPds', {
                'id': this.arrayPds[0].id,
                'idEsfuerzoMental': this.idEsfuerzoMental,
                'idEsfuerzoFisico': this.idEsfuerzoFisico,
                'idSuplementario': this.idSuplementario,
                'idPersonales': this.idPersonales,
                'idMonotonia': this.idMonotonia,
                'idEspera': this.idEspera,
                'valorEspera': this.valorEspera,
                'idTiempoEstandar': this.identificador
            }).then(function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Datos guardados',
                    text: 'El registro se ha guardado correctamente',
                }).then(() => {
                    me.listarPds(1, me.identificador);
                    me.forceRender();
                });
            }).catch(function (error) {
                
                if (error.response && error.response.data && error.response.data.message) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se han definido ciclos o la cantidad de ciclos es cero.',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado. Por favor, intenta nuevamente.',
                    });
                }
                console.log(error);
            });
        },
         clear(){
                this.idEsfuerzoMental="";
                this.idEsfuerzoFisico= "";
                this.idSuplementario="";
                this.idPersonales="";
                this.idEspera="";
                this.idMonotonia="";
                this.valorEspera="";
                this.idTiempoEstandar="";
            }
        },
        mounted() {
            this.listarPds(1,this.identificador),
            this.listarEsfuerzoMental(),
            this.listarEsfuerzoFisico(),
            this.listarSuplementario(),
            this.listarPersonales();
            this.listarEspera();
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
        z-index: 2000;
    }
    .mostrar{
        display: list-item !important;
        height: 100% !important;
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
