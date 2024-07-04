<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Maquinarias</li>
                </ol>
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Maquinarias &nbsp;
                            <button type="button" @click="abrirModal('maquinaria','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="maquinaria">Maquinaria</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarMaquinaria(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarMaquinaria(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>
                                        <th>Maquinaria</th>
                                        <th>Valor</th>
                                        <th>Tiempo de vida util</th>
                                        <th>Depreciación anual</th>
                                        <th>Depreciación mensual</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="maquinaria in arrayMaquinarias" :key="maquinaria.id">
                                        <td>
                                            <button type="button" @click="abrirModal('maquinaria','actualizar',maquinaria)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;

                                        <template v-if="maquinaria.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarMaquinaria(maquinaria.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="activarMaquinaria(maquinaria.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                                        </td>
                                        <td v-text="maquinaria.id"></td>
                                        <td v-text="maquinaria.maquinaria"></td>
                                        <td v-text="formatCurrency(maquinaria.valor)"></td>
                                        <td v-text="maquinaria.tiempoDeVidaUtil"></td>
                                        <td v-text="formatCurrency(maquinaria.depreciacionAnual)"></td>
                                        <td v-text="formatCurrency(maquinaria.depreciacionMensual)"></td>
                                        <td v-text="maquinaria.fecha"></td>
                                        <td>
                                            <div v-if="maquinaria.estado">
                                            <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                        </td>
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
                        </div>
                    </div>
                    <!-- Fin ejemplo de tabla Listado -->
                </div>
                <!--Inicio del modal agregar/actualizar-->
                <div class="modal fade" tabindex="-1" :class="{'mostrar':modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="maquinaria" class="form-control" placeholder="Nombre de la maquinaria" @input="validarEntrada">
                                            <span class="help-block">(*) Ingrese el nombre de la maquinaria</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Valor</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="valor" class="form-control" placeholder="Valor de la maquinaria" @input="formatMoney">
                                            <span class="help-block">(*) Ingrese el valor de la maquinaria</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Vida útil en años</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="tiempoDeVidaUtil" class="form-control" placeholder="Tiempo de vida util" @input="validarTiempoDeVidaUtil">
                                            <span class="help-block">(*) Ingrese el tiempo de vida útil en años</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Fecha apróximada compra</label>
                                        <div class="col-md-9">
                                            <input type="date" v-model="fecha" class="form-control" placeholder="Fecha">
                                            <span class="help-block">(*) Ingrese la fecha apróximada de compra</span>
                                        </div>
                                    </div>
                                    <div class="form-group row div-error" v-show="errorMaquinaria">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearMaquinaria()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarMaquinaria()">Editar</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data(){
            return{
                idMaquinaria:0,
                id:'',
                maquinaria:'',
                valor:'',
                tiempoDeVidaUtil:'',
                depreciacionAnual:'',
                depreciacionMensual:'',
                fecha:'',
                estado:'',
                arrayMaquinarias : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorMaquinaria : 0,
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
                criterio : 'maquinaria',
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
            listarMaquinaria(page,buscar,criterio){
                let me=this;
                var url='/maquinaria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                // Make a request for a user with a given ID
                axios.get(url).then(function (response) {
                    // handle success
                var respuesta=response.data;
                me.arrayMaquinarias=respuesta.maquinarias.data;
                me.pagination=respuesta.pagination;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
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
                me.listarMaquinaria(page,buscar,criterio);
            },
            crearMaquinaria(){
                //valido con el metodo de validacion creado
                if(this.validarMaquinaria()){
                    return;
                }

                let me=this;
                axios.post('/maquinaria/store',{
                    'maquinaria': this.maquinaria,
                    'valor': this.valor.replace(/\D/g, ""), 
                    'tiempoDeVidaUtil': this.tiempoDeVidaUtil,
                    'fecha': this.fecha,
                    //'estado': this.estado,
                    //'dato': this.dato
                }).then(function (response) {
                me.cerrarModal();
                me.listarMaquinaria(1,'','maquinaria');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validarEntrada(event) {
                // Al menos una letra al comienzo
                if (!/^[a-zA-Z]/.test(event.target.value)) {

                    event.target.value = '';
                } else {
                    // Permitir letras y números despues de una letra
                    const regex = /[^a-zA-Z0-9\s]/g;
                    event.target.value = event.target.value.replace(regex, '');
                }
                this.maquinaria = event.target.value;
            },
            validarTiempoDeVidaUtil(event) {
                const input = event.target.value;
                const regex = /^\d{0,3}$/; // Solo tres digitos
                if (!regex.test(input)) {
                
                    event.target.value = input.slice(0, -1);
                }
                this.tiempoDeVidaUtil = event.target.value;
            },
            formatMoney(event) {
                let value = event.target.value.replace(/\D/g, ""); 
                if (value.length > 9) { 
                    value = value.slice(0, 9);
                }
                if (value !== "") {
                    value = parseInt(value).toLocaleString('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    minimumFractionDigits: 0
                    });
                }
                event.target.value = value;
                this.valor = value;
            },
            formatCurrency(value) {
                if (!value) return '';
                return parseInt(value).toLocaleString('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    minimumFractionDigits: 0
                });
            },
            editarMaquinaria(){
                if(this.validarMaquinaria()){
                    return;
                }

                let me=this;
                axios.put('/maquinaria/update',{
                    'fecha': this.fecha,
                    'tiempoDeVidaUtil': this.tiempoDeVidaUtil,
                    'valor': this.valor.replace(/\D/g, ""), 
                    'maquinaria': this.maquinaria,
                    'id': this.idMaquinaria
                    //'estado': this.estado,
                    //'dato': this.dato
                }).then(function (response) {
                me.cerrarModal();
                me.listarMaquinaria(1,'','maquinaria');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            desactivarMaquinaria(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Está seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Desactivar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/maquinaria/deactivate',{
                        'id': id
                    }).then(function (response) {
                    me.listarMaquinaria(1,'','maquinaria');
                    swalWithBootstrapButtons.fire(
                    'maquinaria desactivada!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarMaquinaria();
                }
                })
            },
            activarMaquinaria(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Quiere activar esta maquinaria?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Activar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/maquinaria/activate',{
                        'id': id
                    }).then(function (response) {
                    me.listarMaquinaria(1,'','maquinaria');
                    swalWithBootstrapButtons.fire(
                    'Maquinaria activada!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarMaquinaria();
                }
                })
            },
            validarMaquinaria(){
                this.errorMaquinaria=0;
                this.errorMensaje=[];

                if (!this.maquinaria) this.errorMensaje.push("El nombre de la maquinaria no puede estar vacio");
                if (!this.valor) this.errorMensaje.push("El valor de la maquinaria no puede estar vacio");
                if (!this.tiempoDeVidaUtil) this.errorMensaje.push("El tiempo de vida útil no puede estar vacio");
                if (!this.fecha) this.errorMensaje.push("La fecha de compra no puede estar vacia");
                if (this.errorMensaje.length) this.errorMaquinaria=1;

                return this.errorMaquinaria;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.maquinaria='';
                this.errorMaquinaria = 0,
                this.fecha='';
                this.errorMensaje = [],
                this.forceRerender();
            },
            abrirModal(modelo, accion, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                case "maquinaria":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.maquinaria='';
                            this.valor='';
                            this.tiempoDeVidaUtil='';
                            this.depreciacionAnual='';
                            this.depreciacionMensual='';
                            this.fecha='';
                            this.tituloModal='Registrar nueva maquinaria';
                            this.tipoAccion= 1;
                            break;
                        }
                        case 'actualizar':{
                            //console.log(data);
                            this.modal=1;
                            this.tituloModal='Editar maquinaria';
                            this.tipoAccion= 2;
                            this.idMaquinaria=data['id'];
                            this.maquinaria=data['maquinaria'];
                            this.valor=this.formatCurrency(data['valor']);
                            this.tiempoDeVidaUtil=data['tiempoDeVidaUtil'];
                            this.depreciacionAnual=data['depreciacionAnual'];
                            this.depreciacionMensual=data['depreciacionMensual'];
                            this.fecha=data['fecha'];
                            break;
                        }
                    }
                }
            }
            }
        },
        mounted() {
            this.listarMaquinaria(1,this.buscar,this.criterio);
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