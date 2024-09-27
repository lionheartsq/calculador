<template>
        <vs-tabs :color="colorx">

        <vs-tab label="Productos" icon="open_with" @click="colorx = '#8B0000'">
        <!-- Ejemplo de tabla Listado -->
        <div>
            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="producto">Producto</option>
                                        <option value="referencia">Referencia</option>
                                        <option value="id">Id</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarProductosOrden(1,buscar,criterio,identificador)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarProductosOrden(1,buscar,criterio,identificador)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Referencia</th>
                        <th>Unidades</th>
                        <th>Costo</th>
                        <th>Precio Venta</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="producto in arrayProductos" :key="producto.id">
                        <td v-text="producto.producto"></td>
                        <td v-text="producto.referencia"></td>
                        <td v-text="producto.cantidad"></td>
                        <td v-text="formatCurrency(producto.precioCosto)"></td>
                        <td v-text="formatCurrency(producto.precioVenta)"></td>
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
        </vs-tab>

        <vs-tab label="Materia pendiente" icon="format_list_numbered" @click="colorx = '#FFA500'">
            <div class="card-body">
                <materiaprimarequerida v-bind:identificador="identificador" :key="componentKey"></materiaprimarequerida>
            </div>
        </vs-tab>

        <vs-tab label="Materia por devolver" icon="format_list_numbered" @click="colorx = '#CB3234'">
                <materiaprimasobrante v-bind:identificador="identificador" :key="componentKey"></materiaprimasobrante>
        </vs-tab>

        <vs-tab label="Materia completa" icon="format_list_numbered" @click="colorx = '#9B59B6'">
                <materiaprimacompleta v-bind:identificador="identificador" :key="componentKey"></materiaprimacompleta>
        </vs-tab>

        </vs-tabs>


</template>

<script>
    import materiaprimarequerida from '../components/MateriaPrimaRequerida';
    import materiaprimasobrante from '../components/MateriaPrimaSobrante';
    import materiaprimacompleta from '../components/MateriaPrimaCompleta';
    export default {
        components: {
            materiaprimarequerida,
            materiaprimacompleta,
            materiaprimasobrante
        },
        props: {
            identificador: {
            type: Number
            }
        },
        data(){
            return{
                colorx: '#8B0000',
                unidades:0,
                tiempo:0,
                arrayProductos : [],
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
                criterio : 'producto',
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
            listarProductosOrden(page,buscar,criterio,identificador){
                let me=this;
                var url='/ordenpedidocliente/listar?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&identificador=' + identificador;
                console.log(url);
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayProductos=respuesta.productos.data;
                me.pagination=respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                console.log(url);
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
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //envia peticion para ver los valores asociados a esa pagina
                me.listarProductosOrden(page,buscar,criterio,this.identificador);
            }
        },
        mounted() {
            this.listarProductosOrden(1,'','',this.identificador);
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
