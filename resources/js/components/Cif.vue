<template>
        <!-- Ejemplo de tabla Listado -->
        <div>
            <div class="form-group row">
                <div class="col-md-9">
                    <div class="input-group">
                        <select class="form-control col-md-3" v-model="criterio">
                        <option value="concepto">Concepto</option>
                        </select>
                        <input type="text" v-model="buscar" @keyup.enter="listarConcepto(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                        <button type="submit" @click="listarConcepto(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                </div>
            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <!--<th>Opciones</th>-->
                                        <th>Id</th>
                                        <th>Concepto</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <!--<td><span class="badge badge-success">Maquinaria</span></td>-->
                                        <td>0</td>
                                        <td>Depreciación mensual total</td>
                                        <td>{{ formatCurrency(maquinaria) }}</td>
                                    </tr>
                                    <tr v-for="concepto in arrayConceptos" :key="concepto.id">
                                        <!--<td>
                                            <button type="button" @click="abrirModal('concepto','actualizar',concepto)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;

                                        <template v-if="concepto.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarConcepto(concepto.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="activarConcepto(concepto.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                                        </td>-->
                                        <td v-text="concepto.id"></td>
                                        <td v-text="concepto.concepto"></td>
                                        <td v-text="formatCurrency(concepto.valor)"></td>
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
                    <!-- Fin ejemplo de tabla Listado -->
        </div>
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
                idConcepto:0,
                id:'',
                concepto:'',
                valor:'',
                estado:'',
                maquinaria:'',
                arrayConceptos : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorConcepto : 0,
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
                criterio : 'concepto',
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
            listarConcepto(page,buscar,criterio){
                let me=this;
                var url='/concepto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayConceptos=respuesta.conceptos.data;
                me.pagination=respuesta.pagination;
                    //console.log(response);
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
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //envia peticion para ver los valores asociados a esa pagina
                me.listarConcepto(page,buscar,criterio);
            },
            maquinariaTotal(identificador){
                let me=this;
                var url='/hojadecosto/depreciacion/'+this.identificador;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.maquinaria=respuesta.maquinarias;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            }
        },
        mounted() {
            this.maquinariaTotal(this.identificador);
            this.listarConcepto(1,this.buscar,this.criterio)
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
