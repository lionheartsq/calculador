<template>
        <!-- Ejemplo de tabla Listado -->
        <div>
            <div class="form-group row">
                <div class="col-md-9">
                    <div class="input-group">
                        <select class="form-control col-md-3" v-model="criterio">
                              <option value="perfil">Perfil</option>
                              <option value="proceso">Proceso</option>
                        </select>
                        <input type="text" v-model="buscar" @keyup.enter="listarManoDeObraProductos(1,buscar,criterio,this.identificador)" class="form-control" placeholder="Texto a buscar">
                          <button type="submit" @click="listarManoDeObraProductos(1,buscar,criterio,this.identificador)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                </div>
            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Perfil</th>
                                        <th>Proceso</th>
                                        <th>Tiempo</th>
                                        <th>Costo</th>
                                        <th>Subtotal</th>
                                        <th>Tipo pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="manodeobraproducto in arrayManoDeObraProductos" :key="manodeobraproducto.id">
                                        <td>

                                            <button type="button" @click="$emit('abrirmodal','gestionManoDeObra','actualizar',manodeobraproducto)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <!--<button type="button" class="btn btn-danger btn-sm" @click="$emit('eliminarmanodeobra',manodeobraproducto.id)">
                                                <i class="icon-trash"></i>
                                            </button>-->

                                        </td>
                                        <td v-text="manodeobraproducto.perfil"></td>
                                        <td v-text="manodeobraproducto.proceso"></td>
                                        <td v-if="manodeobraproducto.tipoPago==1" v-text="manodeobraproducto.tiempo"></td>
                                        <td v-if="manodeobraproducto.tipoPago>1"><span class="badge badge-secondary">Por par</span></td>
                                        <td v-text="formatCurrency(manodeobraproducto.precio)"></td>
                                        <td v-text="formatCurrency(manodeobraproducto.subtotal)"></td>
                                        <td v-if="manodeobraproducto.tipoPago==1"><span class="badge badge-success">Sueldo Fijo</span></td>
                                        <td v-if="manodeobraproducto.tipoPago==2"><span class="badge badge-danger">Destajo sin provision</span></td>
                                        <td v-if="manodeobraproducto.tipoPago==3"><span class="badge badge-warning">Destajo + liquidación</span></td>
                                        <td v-if="manodeobraproducto.tipoPago==4"><span class="badge badge-info">Destajo + parafiscales</span></td>
                                        <td v-if="manodeobraproducto.tipoPago==5"><span class="badge badge-secondary">Destajo + provisión total</span></td>
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
                idManoDeObraProducto:0,
                idPerfil: 0,
                perfil:'',
                idProceso: 0,
                proceso:'',
                tiempo:0,
                precio:0,
                subtotal:0,
                tipoDeCosto:'Directo',
                arrayManoDeObraProductos : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorManoDeObraProducto : 0,
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
                criterio : 'perfil',
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
                listarManoDeObraProducto(page,buscar,criterio,identificador){
                let me=this;
                var url='/manodeobraproducto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&identificador=' + identificador;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayManoDeObraProductos=respuesta.manodeobraproductos.data;
                me.pagination=respuesta.pagination;
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
                me.listarManoDeObraProducto(page,buscar,criterio,this.identificador);
            }
        },
        mounted() {
            this.listarManoDeObraProducto(1,'','',this.identificador)
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
