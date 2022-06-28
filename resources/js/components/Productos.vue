<template>
        <main class="main">
            <div id="over" class="overbox">
                <a v-on:click="hideLightbox()"><p class="cierre cursor"><b>Cerrar</b></p></a>
                <div id="content">
                    <center><img :src="fotoCarga" alt="" class="imglight"></center>
                </div>
            </div>
            <div id="fade" class="fadebox">&nbsp;</div>
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Productos</li>
                </ol>
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Productos
                            <button type="button" @click="abrirModal('producto','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="producto">Producto</option>
                                        <option value="coleccion">Coleccion</option>
                                        <!-- <option value="area">Area</option> -->
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarProducto(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarProducto(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Producto</th>
                                        <th>Referencia</th>
                                        <th>Foto</th>
                                        <th>Descripcion</th>
                                        <th>Coleccion</th>
                                        <!-- <th>Area</th> -->
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="producto in arrayProducto" :key="producto.id">
                                        <td>
                                            <button type="button" @click="abrirModal('producto','actualizar',producto)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;

                                        <template v-if="producto.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarProducto(producto.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="activarProducto(producto.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                                        </td>
                                        <td v-text="producto.producto"></td>
                                        <td v-text="producto.referencia"></td>
                                        <td><a class="cursor" v-on:click="showLightbox(`img/avatars/${producto.foto}`)">Ver producto</a></td>
                                        <td v-text="producto.descripcion"></td>
                                        <td v-text="producto.coleccion"></td>
                                        <!-- <td v-text="producto.area"></td> -->
                                        <td>
                                            <div v-if="producto.estado">
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
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 form-control-label" for="text-input">Coleccion</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" v-model="idColeccion">
                                                        <option value="0" disabled> Seleccione Coleccion</option>
                                                        <option v-for="coleccion in arrayColeccion" :key="coleccion.idColeccion" :value="coleccion.idColeccion" v-text="coleccion.coleccion"></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 form-control-label" for="text-input">Area</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" v-model="idArea">
                                                        <option value="0" disabled> Seleccione Area</option>
                                                        <option v-for="area in arrayArea" :key="area.idArea" :value="area.idArea" v-text="area.area"></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                   </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="producto" class="form-control" placeholder="Nombre de producto">
                                            <span class="help-block">(*) Ingrese el nombre del producto</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Referencia</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="referencia" class="form-control" placeholder="Nombre de referencia">
                                            <span class="help-block">(*) Ingrese el nombre de la referencia</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Capacidad</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="capacidadMensual" class="form-control" placeholder="Capacidad de producción">
                                            <span class="help-block">(*) Ingrese la capacidad de producción mensual</span>
                                        </div>
                                    </div>
                                      <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Foto</label>
                                    <div class="col-md-9">
                                        <!--poniendo :src se llama a la variable foto-->
                                        <div v-if="tipoAccion==1">
                                             <input type="file" @change="subirFoto" name="foto" class="form-control" placeholder="Nombre de foto">
                                             <br>
                                              <div v-if="fotoCarga" class="text-center">
                                             <img :src="fotoCarga"  class="rounded" alt="..." style="max-height:100px; max-width:100px; ">
                                              </div>
                                        </div>
                                        <div v-if="tipoAccion==2">
                                           <input type="file" @change="subirFoto" name="foto" class="form-control" placeholder="Nombre de foto">
                                           <br>
                                             <div v-if="fotoCarga" class="text-center">
                                            <img :src="fotoCarga"  class="rounded" alt="..." style="max-height:100px; max-width:100px; ">
                                             </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Descripcion</label>
                                        <div class="col-md-9">
                                            <input type="text" v-model="descripcion" class="form-control" placeholder="Nombre de descripcion">
                                            <span class="help-block">(*) Ingrese la descripcion</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Presentación</label>
                                            <div class="col-md-9">
                                                <select class="form-control" v-model="presentacion">
                                                    <option value="0" disabled> Seleccione presentación</option>
                                                    <option value="4">Unidad</option>
                                                    <option value="5">Par</option>
                                                </select>
                                            </div>
                                    </div>

                                    <div class="form-group row div-error" v-show="errorProducto">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearProducto()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarProducto()">Editar</button>
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
                idProducto:0,
                id:'',
                producto:'',
                referencia:'',
                foto:'',
                image:'',
                descripcion:'',
                estado:'',
                capacidadMensual:'',
                arrayProducto : [],
                idColeccion:0,
                coleccion:'',
                referencia:'',
                arrayColeccion:[],
                idArea:1,
                area:'',
                arrayArea:[],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                fotoCarga:'',
                errorProducto : 0,
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
            listarProducto(page,buscar,criterio){
                let me=this;
                var url='/producto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayProducto=respuesta.productos.data;
                me.pagination=respuesta.pagination;
                    //console.log(response);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            selectColeccion(){
                let me=this;
                var url='/coleccion/selectColeccion';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayColeccion=respuesta.colecciones;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            selectArea(){
                let me=this;
                var url='/area/selectArea';
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayArea=respuesta.areas;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la pagina actual
                me.pagination.current_page = page;
                //envia peticion para ver los valores asociados a esa pagina
                me.listarProducto(page,buscar,criterio);
            },
            indexChange: function(args) {
                let newIndex = args.value
                console.log('Current tab index: ' + newIndex)
                },
            forceRerender() {
                this.componentKey += 1;
               },
             subirFoto(e){ // subir un nuevo archivo o imagen
              let file = e.target.files[0];
                let reader = new FileReader();

                if(file['size'] < 2111775)
                {
                    reader.onloadend = (file) => {
                     this.foto = reader.result;
                    }
                     reader.readAsDataURL(file);
                      reader.onload = e => {
                     this.fotoCarga = e.target.result;
                };
                }
                else{
                    alert('El tamaño del archivo no puede ser superior a 2 MB')
                }
            },
            get_foto(){
               let foto = (this.foto.length > 100) ? this.foto : "img/avatars/"+ this.foto;
               return foto;
            },
            crearProducto(){
                //valido con el metodo de validacion creado
                if(this.validarProducto()){
                    return;
                }

                let me=this;
                axios.post('/producto/store',{
                    'producto': this.producto,
                    'referencia': this.referencia,
                    'foto': this.foto,
                    'descripcion': this.descripcion,
                    'idColeccion': this.idColeccion,
                    'idArea': this.idArea,
                    'capacidadMensual': this.capacidadMensual,
                    'presentacion': this.presentacion
                    //'dato': this.dato
                }).then(function (response) {
                me.cerrarModal();
                me.listarProducto(1,'','producto');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            editarProducto(){
                if(this.validarProducto()){
                    return;
                }

                let me=this;
                axios.put('/producto/update',{
                    'id': this.idProducto,
                    'producto': this.producto,
                    'referencia': this.referencia,
                    'foto': this.foto,
                    'descripcion': this.descripcion,
                    'idColeccion': this.idColeccion,
                    'idArea': this.idArea
                    //'dato': this.dato
                }).then(function (response) {
                me.cerrarModal();
                me.listarProducto(1,'','producto');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            desactivarProducto(id){
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
                    axios.put('/producto/deactivate',{
                        'id': id
                    }).then(function (response) {
                    me.listarProducto(1,'','producto');
                    swalWithBootstrapButtons.fire(
                    'Producto desactivado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarProducto();
                }
                })
            },
            activarProducto(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Quiere activar este producto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Activar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/producto/activate',{
                        'id': id
                    }).then(function (response) {
                    me.listarProducto(1,'','producto');
                    swalWithBootstrapButtons.fire(
                    'Producto activado!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarProducto();
                }
                })
            },
            validarProducto(){
                this.errorProducto=0;
                this.errorMensaje=[];

                if (this.idColeccion==0) this.errorMensaje.push("Debe elegir una colección existente");
                if (this.idArea==0) this.errorMensaje.push("Debe elegir un área existente");
                if (!this.producto) this.errorMensaje.push("El nombre del producto no puede estar vacio");
                if (!this.referencia) this.errorMensaje.push("La referencia no puede estar vacia");
                if (!this.capacidadMensual) this.errorMensaje.push("La capacidad de producción no puede estar vacia");
                if (!this.foto) this.errorMensaje.push("La foto no puede estar vacia");
                if (!this.descripcion) this.errorMensaje.push("La descripción no puede estar vacia");
                if (this.errorMensaje.length) this.errorProducto=1;

                return this.errorProducto;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.producto='';
                this.referencia='';
                this.fotoCarga='';
                this.descripcion='';
                this.idColeccion='';
                this.idArea='';
                this.errorProducto = 0,
                this.errorMensaje = [],
                this.capacidadMensual='';
                this.forceRerender();
            },
            abrirModal(modelo, accion, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                case "producto":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.producto='';
                            this.referencia='';
                            this.foto='';
                            this.descripcion='';
                            this.tituloModal='Crear nuevo producto';
                            this.tipoAccion= 1;
                            this.idColeccion= 1;
                            this.idArea= 1;
                            break;
                        }
                        case 'actualizar':{
                            //console.log(data);
                            this.modal=1;
                            this.tituloModal='Editar producto';
                            this.tipoAccion= 2;
                            this.idProducto=data['id'];
                            this.producto=data['producto'];
                            this.referencia=data['referencia'];
                            this.foto=data['foto'];
                            this.descripcion=data['descripcion'];
                            this.idColeccion=data['idColeccion']; // añadido para alimentar el select
                            this.idArea=data['idArea']; // añadido para alimentar el select
                            break;
                        }
                    }
              }

            }
            this.selectColeccion();
            this.selectArea();
            },
            //funciones para uso del lightbox
            showLightbox(fotoCarga) {

                this.fotoCarga=fotoCarga;
                document.getElementById('over').style.display='block';
                document.getElementById('fade').style.display='block';
            },
            hideLightbox() {
                this.fotoCarga='';
                document.getElementById('over').style.display='none';
                document.getElementById('fade').style.display='none';
            },
            //cierre de funciones para uso del lightbox
        },
        mounted() {
            this.listarProducto(1,this.buscar,this.criterio);
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
    .fadebox {
	display: none;
	position: absolute;
	top: 0%;
	left: 0%;
	width: 100%;
	height: 100%;
	background-color: black;
	z-index:3001;
	-moz-opacity: 0.8;
	opacity:.80;
	filter: alpha(opacity=80);
    }
    .overbox {
        display: none;
        position: absolute;
        top: 5%;
        left: 5%;
        width: 90%;
        height: 90%;
        z-index:3002;
        overflow: auto;
    }
    #content {
        background: transparent;
        border: solid 3px transparent;
        padding: 10px;
    }
    .cierre {
        font-weight: 9rem;
        color:#FFFFFF;
    }
    .imglight{
        max-height:500px;
    }
    .cursor{
        cursor: pointer;
    }
</style>
