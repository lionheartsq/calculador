<template>
        <!-- Ejemplo de tabla Listado -->
        <div>
            <div class="table-responsive">
            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                       <th>Habilidad</th>
                       <th>Esfuerzo</th>
                       <th>Condiciones</th>
                       <th>Consistencia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="westing in arrayWesting" :key="westing.id">
                     <td>
                    <select class="form-control" v-model="idHabilidad">
                    <option v-for="habilidad in arrayHabilidades" :key="habilidad.id" :value="habilidad.id" v-text="habilidad.nombreHabilidad">
                    </option>
                    </select>
                    </td>
                     <td>
                    <select class="form-control" v-model="idEsfuerzo">
                    <option v-for="esfuerzo in arrayEsfuerzo" :key="esfuerzo.id" :value="esfuerzo.id" v-text="esfuerzo.nombreEsfuerzo"></option>
                     </select>
                    </td>
                    <td>
                    <select class="form-control" v-model="idCondiciones">
                    <option v-for="condiciones in arrayCondiciones" :key="condiciones.id" :value="condiciones.id" v-text="condiciones.nombreCondicion"></option>
                    </select>
                    </td>
                    <td>
                    <select class="form-control" v-model="idConsistencia">
                    <option v-for="consistencia in arrayConsistencia" :key="consistencia.id" :value="consistencia.id" v-text="consistencia.nombreConsistencia"></option>
                    </select>
                    </td>
                     <input type="hidden" v-model="idTiempoEstandar" id="idTiempoEstandar">
                    </tr>
                </tbody>
            </table>
             <div class="container-buttons">
             <p align="right">
            <button type="button" class="btn btn-success" @click="guardarWestingHouse()">Guardar</button>
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
                idWesting:0,
                identificador:0,
                arrayWesting:[],
                arrayHabilidades:[],
                arrayEsfuerzo:[],
                arrayCondiciones:[],
                arrayConsistencia:[],
                nombreHabilidad: '',
                nombreEsfuerzo: '',
                nombreCondicion: '',
                nombreConsistencia: '',
                idTiempoEstandar:0,
                idHabilidad:0,
                idEsfuerzo:0,
                idCondiciones:0,
                idConsistencia:0,
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
                listarWesting(page,identificador){
                let me=this;
                var url='/tiempoestandar/listarwesting?page=' + page + '&id='+identificador;
                console.log(url);
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayWesting=respuesta.westinghouse.data;
                me.idHabilidad=me.arrayWesting[0].idHabilidad;
                me.idEsfuerzo=me.arrayWesting[0].idEsfuerzo;
                me.idCondiciones=me.arrayWesting[0].idCondiciones;
                me.idConsistencia=me.arrayWesting[0].idConsistencia;
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
                me.listarWesting(page,this.identificador);
            },
        forceRerender() {
                this.componentKey += 1;
               },
        listarHabilidad(){
                let me=this;
                var url='/tiempoestandar/habilidad';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayHabilidades=respuesta.habilidades;
                me.forceRerender();
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
        listarEsfuerzo(){
                let me=this;
                var url='/tiempoestandar/esfuerzo';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayEsfuerzo=respuesta.esfuerzo;
                me.forceRerender();
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
        listarCondiciones(){
                let me=this;
                var url='/tiempoestandar/condiciones';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayCondiciones=respuesta.condiciones;
                me.forceRerender();
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
        listarConsistencia(){
                let me=this;
                var url='/tiempoestandar/consistencia';
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayConsistencia=respuesta.consistencia;
                me.forceRerender();
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
        guardarWestingHouse(){
                //valido con el metodo de validacion creado
                let me=this;
                axios.post('/tiempoestandar/guardarWestingHouse',{
                    'id':this.arrayWesting[0].id,
                    'idHabilidad': this.idHabilidad,
                    'idEsfuerzo':this.idEsfuerzo,
                    'idCondiciones':this.idCondiciones,
                    'idConsistencia':this.idConsistencia,
                    'idTiempoEstandar':this.identificador
                }).then(function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Datos guardados',
                        text: 'El registro se ha guardado exitosamente',
                    }).then(() => {
                        //me.clear();
                        me.listarWesting(1, me.identificador);
                        me.forceRender();
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        clear(){
                this.idHabilidad="";
                this.idEsfuerzo= "";
                this.idCondiciones="";
                this.idConsistencia="";
                this.idTiempoEstandar="";
                this.update = 0;
            }
        },
        mounted() {
            this.listarWesting(1,this.identificador),
            this.listarHabilidad(),
            this.listarEsfuerzo(),
            this.listarCondiciones(),
            this.listarConsistencia();
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
