<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active">Cambiar Contraseña</li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <legend>Cambiar Contraseña</legend>

                    <div class="content">

                        <img src="/img/HACEM.png" alt="Logo HACEM" class="logo-image">

                        <form @submit.prevent="cambiarContrasena">
                            <fieldset>
                                
                                <div class="form-group row">
                                    <label for="new-password" class="col-sm-12 col-form-label">Nueva Contraseña:</label>
                                    <div class="col-sm-12">
                                        <input type="password" id="new-password" v-model="newPassword" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="confirm-password" class="col-sm-12 col-form-label">Confirmar Nueva Contraseña:</label>
                                    <div class="col-sm-12">
                                        <input type="password" id="confirm-password" v-model="confirmPassword" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-8 offset-sm-4">
                                        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            newPassword: '',
            confirmPassword: ''
        };
    },
    methods: {
        cambiarContrasena() {
            if (this.newPassword !== this.confirmPassword) {
                Swal.fire({
                    title: 'Error',
                    text: 'Las nuevas contraseñas no coinciden',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Validar que se haya ingresado la nueva contraseña
            if (this.newPassword === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Debes ingresar una nueva contraseña',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Validar que se haya ingresado la confirmación de la nueva contraseña
            if (this.confirmPassword === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Debes confirmar la nueva contraseña',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (this.newPassword.length < 8) {
                Swal.fire({
                    title: 'Error',
                    text: 'La nueva contraseña debe tener al menos 8 caracteres',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            axios.post('/usuario/cambiar-contrasena', {
                newPassword: this.newPassword,
                newPassword_confirmation: this.confirmPassword // Asegúrate de enviar la confirmación de la contraseña
            })
            .then(response => {
                Swal.fire({
                    title: 'Éxito',
                    text: 'Contraseña cambiada correctamente',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: error.response.data.message || 'No se pudo cambiar la contraseña',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                console.log(error);
            });
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

.card-body {
    padding: 30px;
}

legend {
    margin-bottom: 20px;
}

.content {
    max-width: 400px; 
    margin: 0 auto; 
    padding: 20px;
    background-color: #f0f0f0; 
    border: 1px solid #ccc;
    border-radius: 10px; 
}

.form-group {
    margin-bottom: 1.5rem;
}

.btn-primary {
    margin-top: 10px;
}
.content input {
    border-radius: 10px;
    border: 1px solid #ccc;
    weight: 100%;
}
.content label {
    font-size : 15px;
}
.logo-image {
    margin-top: 20px;
    max-width: 100%;
    height: auto; 
    margin-bottom: 20px; 
}
</style>
