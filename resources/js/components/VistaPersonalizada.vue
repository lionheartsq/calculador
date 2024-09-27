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
                            <form @submit.prevent="actualizarDatos" enctype="multipart/form-data" class="form-horizontal">
                                <fieldset>
                                    <legend>Configuración de Vista</legend>
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Escritorio</th>
                                                <th>Documentacion</th>
                                                <th>Administracion</th>
                                                <th>Conceptos CIF</th>
                                                <th>Materiales</th>
                                                <th>Productos</th>
                                                <th>Produccion</th>
                                                <th>Kardex</th>
                                                <th>Mano de Obra</th>
                                                <th>Personas</th>
                                                <th>Nomina</th>
                                                <th>Gestion Financiera</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
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
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </fieldset>
                            </form>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            idVista: '',
            escritorio: '',
            documentacion: '',
            administracion: '',
            conceptosCif: '',
            materiales: '',
            productos: '',
            produccion: '',
            kardex: '',
            manoDeObra: '',
            personas: '',
            nomina: '',
            gestionFinanciera: '',
            idUser: '',
            menu: 0 // Add this line to manage the menu state
        };
    },
    methods: {
        listarVista() {
            let me = this;
            axios.get('/vistaPersonalizada')
                .then(function(response) {
                    var respuesta = response.data;
                    me.idVista = respuesta.idVista;
                    me.escritorio = respuesta.escritorio;
                    me.documentacion = respuesta.documentacion;
                    me.administracion = respuesta.administracion;
                    me.conceptosCif = respuesta.conceptosCif;
                    me.materiales = respuesta.materiales;
                    me.productos = respuesta.productos;
                    me.produccion = respuesta.produccion;
                    me.kardex = respuesta.kardex;
                    me.manoDeObra = respuesta.manoDeObra;
                    me.personas = respuesta.personas;
                    me.nomina = respuesta.nomina;
                    me.gestionFinanciera = respuesta.gestionFinanciera;
                    me.idUser = respuesta.idUser;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        actualizarDatos() {
            let me = this;
            axios.post('/vistaPersonalizada/actualizar', {
                'id': this.idVista,
                'idUser': this.idUser,
                'escritorio': this.escritorio,
                'documentacion': this.documentacion,
                'administracion': this.administracion,
                'conceptosCif': this.conceptosCif,
                'materiales': this.materiales,
                'productos': this.productos,
                'produccion': this.produccion,
                'kardex': this.kardex,
                'manoDeObra': this.manoDeObra,
                'personas': this.personas,
                'nomina': this.nomina,
                'gestionFinanciera': this.gestionFinanciera
            }).then(function(response) {
                Swal.fire({
                    title: 'Éxito',
                    text: 'Vista actualizada correctamente',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    localStorage.setItem('menu', '98'); 
                    window.location.reload();
                
                });
            }).catch(function(error) {
                console.log(error);
            });
        }
    },
    mounted() {
        this.listarVista();
        // Restore the menu state from localStorage
        const savedMenu = localStorage.getItem('menu');
        if (savedMenu) {
            this.menu = parseInt(savedMenu);
            localStorage.removeItem('menu'); // Remove the saved state after using it
        }
    }
};
</script>

<style>
.modal-content {
    width: 100% !important;
    position: absolute !important;
}
.mostrar {
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #3c29297a !important;
}
.div-error {
    display: flex;
    justify-content: center;
}
.text-error {
    color: red !important;
    font-weight: bold;
}
</style>