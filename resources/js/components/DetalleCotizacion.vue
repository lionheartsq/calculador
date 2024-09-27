<template>
        <!-- Ejemplo de tabla Listado -->
        <div>
            <div class="card">
                <div class="card-body">

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
                        <td v-text="formatCurrency(producto.valor)"></td>
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
            </div>
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
                var url='/cotizacioncliente/listar?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&identificador=' + identificador;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayProductos=respuesta.productos.data;
                me.pagination=respuesta.pagination;
                console.log(this.identificador);
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
                me.listarProductosOrden(page,buscar,criterio,this.identificador);
            }
        },
        mounted() {
            this.listarProductosOrden(1,'','',this.identificador)
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
    .card {
        border: none;
    }
    .card-body{
        padding: 30px;
        border: none;
    }
</style>
