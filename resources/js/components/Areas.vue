<template>
        <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Areas</li>
                </ol>
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->

                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Areas &nbsp;
                            <button type="button" @click="abrirModal('area','crear')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="id">Id</option>
                                        <option value="area">Area</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarArea(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarArea(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>
                                        <th>Area</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="area in arrayAreas" :key="area.id">
                                        <td>
                                            <button type="button" @click="abrirModal('area','actualizar',area)" class="btn btn-info btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> 

                                        <template v-if="area.estado">
                                            <button type="button" class="btn custom-button btn-sm" @click="desactivarArea(area.id)">
                                                <i class="icon-ban"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="activarArea(area.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>&nbsp;

                                        <button v-if="!area.estado" type="button" class="btn btn-danger btn-sm" @click="eliminarArea(area.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                        
                                        </td>
                                        <td v-text="area.id"></td>
                                        <td v-text="area.area"></td>
                                        <td>
                                            <div v-if="area.estado">
                                            <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                            <span class="badge badge-warning">Desactivado</span>
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
                                            <input type="text" v-model="area" class="form-control" placeholder="Nombre de área" @input="validarEntrada">
                                            <span class="help-block">(*) Ingrese el nombre del área</span>
                                        </div>
                                    </div>
                                    <div class="form-group row div-error" v-show="errorArea">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMensaje" :key="error" v-text="error"></div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                                <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="crearArea()">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-warning" @click="editarArea()">Editar</button>
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
                idArea:0,
                id:'',
                area:'',
                estado:'',
                arrayAreas : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorArea : 0,
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
            listarArea(page,buscar,criterio){
                let me=this;
                var url='/area?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                var respuesta=response.data;
                me.arrayAreas=respuesta.areas.data;
                me.pagination=respuesta.pagination;
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
                me.listarArea(page,buscar,criterio);
            },
            crearArea(){
                //valido con el metodo de validacion creado
                if(this.validarArea()){
                    return;
                }
                let me=this;
                axios.post('/area/store',{
                    'area': this.area
                    //'estado': this.estado,
                    //'dato': this.dato
                }).then(function (response) {
                me.cerrarModal();
                me.listarArea(1,'','area');
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
                this.area = event.target.value;
            },
            editarArea(){
                if(this.validarArea()){
                    return;
                }

                let me=this;
                axios.put('/area/update',{
                    'area': this.area,
                    'id': this.idArea
                    //'estado': this.estado,
                    //'dato': this.dato
                }).then(function (response) {
                me.cerrarModal();
                me.listarArea(1,'','area');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            desactivarArea(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Esta acción desactivará el área seleccionada',
                text: '¿Desea continuar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Desactivar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/area/deactivate',{
                        'id': id
                    }).then(function (response) {
                    me.listarArea(1,'','area');
                    swalWithBootstrapButtons.fire(
                    'Área desactivada!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarArea();
                }
                })
            },
            activarArea(id){
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                title: 'Deseas activar esta areá?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-check fa-2x"></i> Activar!',
                cancelButtonText:  '<i class="fa fa-times fa-2x"></i> Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me=this;
                    axios.put('/area/activate',{
                        'id': id
                    }).then(function (response) {
                    me.listarArea(1,'','area');
                    swalWithBootstrapButtons.fire(
                    'Área activada!'
                    )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    me.listarArea();
                }
                })
            },
            eliminarArea(id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará el área. ¿Deseas continuar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/area/delete/${id}`)
                            .then(response => {
                                if (response.status === 200) {
                                    Swal.fire(
                                        '¡Eliminado!',
                                        'El área ha sido eliminada correctamente.',
                                        'success'
                                    );
                                    // Actualizar la lista de áreas después de la eliminación
                                    this.listarArea(this.pagination.currentPage, this.buscar, this.criterio);
                                } else {
                                    Swal.fire(
                                        'Error',
                                        'No se pudo eliminar el área. Verifica si está asociada con otros elementos.',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                if (error.response && error.response.status === 500) {
                                    console.error("Error al eliminar el área:", error);
                                    Swal.fire(
                                        'Error',
                                        'No se pudo eliminar el área. Verifica si está asociada con otros elementos.',
                                        'error'
                                    );
                                } else {
                                    console.error("Error al eliminar el área:", error);
                                    Swal.fire(
                                        'Error',
                                        'Se produjo un error al intentar eliminar el área.',
                                        'error'
                                    );
                                }
                            });
                    }
                });
            },
            validarArea(){
                this.errorArea=0;
                this.errorMensaje=[];

                if (!this.area) this.errorMensaje.push("El nombre del área no puede estar vacio");
                if (this.errorMensaje.length) this.errorArea=1;

                return this.errorArea;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.area='';
                this.errorArea = 0,
                this.errorMensaje = [],
                this.forceRerender();
            },
            abrirModal(modelo, accion, data=[]){
            //tres argumentos, el modelo a modificar o crear, la accion como tal y el arreglo del registro en la tabla
            switch(modelo){
                case "area":
                {
                    switch (accion) {
                        case 'crear':{
                            this.modal=1;
                            this.area='';
                            this.tituloModal='Crear nueva área';
                            this.tipoAccion= 1;
                            break;
                        }
                        case 'actualizar':{
                            //console.log(data);
                            this.modal=1;
                            this.tituloModal='Editar área';
                            this.tipoAccion= 2;
                            this.idArea=data['id'];
                            this.area=data['area'];
                            break;
                        }
                    }
                }
            }
            }
        },
        mounted() {
            this.listarArea(1,this.buscar,this.criterio);
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
    .btn {
        border-radius: 8px;
    }
    .custom-button {
        background-color: #ff9900; 
        color: #ffffff; 
    }
</style>