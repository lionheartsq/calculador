/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./popper');

window.Vue = require('vue');

import Vuesax from 'vuesax';

import 'vuesax/dist/vuesax.css'; //Vuesax styles

import 'material-icons/iconfont/material-icons.css';

window.Vue.use(Vuesax, {
    // options here
  });

import VueCurrencyFilter from 'vue-currency-filter';

Vue.use(VueCurrencyFilter,[
    {
      symbol : '$',
      thousandsSeparator: '.',
      fractionCount: 0,
      fractionSeparator: ',',
      symbolPosition: 'front',
      symbolSpacing: true
    },
    { // default name 'currency_2'
      name: 'currency_2',
      symbol: '',
      thousandsSeparator: '.',
      fractionCount: 0,
      fractionSeparator: ',',
      symbolPosition: 'front',
      symbolSpacing: false
      //avoidEmptyDecimals: '--'
    }
    ]);

    Vue.filter('redondeo', function (value) {
        // devuelve el valor procesado
        if (!value) return ''
        value = value.toFixed(0)
        return value;
      });

      Vue.filter('redondeodec', function (value) {
        // devuelve el valor procesado
        if (!value) return ''
        value = value.toFixed(2)
        return value;
      });
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example', require('./components/ExampleComponent.vue').default);
Vue.component('admin', require('./components/Admin.vue').default);
Vue.component('areas', require('./components/Areas.vue').default);
Vue.component('bienvenida', require('./components/Bienvenida.vue').default);
Vue.component('cif', require('./components/Cif.vue').default);
Vue.component('clientes', require('./components/Clientes.vue').default);
Vue.component('colecciones', require('./components/Colecciones.vue').default);
Vue.component('conceptos', require('./components/Conceptos.vue').default);
Vue.component('configuracion', require('./components/Configuracion.vue').default);
Vue.component('cotizacion', require('./components/Cotizacion.vue').default);
Vue.component('detallecotizacion', require('./components/DetalleCotizacion.vue').default);
Vue.component('detallekardexalmacen', require('./components/DetalleKardexAlmacen.vue').default);
Vue.component('detallekardexproducto', require('./components/DetalleKardexProducto.vue').default);
Vue.component('detallekardexproduccion', require('./components/DetalleKardexProduccion.vue').default);
Vue.component('empleados', require('./components/Empleados.vue').default);
Vue.component('empresa', require('./components/Empresa.vue').default);
Vue.component('factores', require('./components/Factores.vue').default);
Vue.component('financieras', require('./components/Financieras.vue').default);
Vue.component('globales', require('./components/Globales.vue').default);
Vue.component('gestionmaterias', require('./components/GestionMaterias.vue').default);
Vue.component('hojadecostos', require('./components/HojaDeCostos.vue').default);
Vue.component('hojadecostosdetalle', require('./components/HojaDeCostosDetalle.vue').default);
Vue.component('hojadecostossimulador', require('./components/HojaDeCostosSimulador.vue').default);
Vue.component('hojas', require('./components/Hojas.vue').default);
Vue.component('kardexalmacen', require('./components/Kardexalmacen.vue').default);
Vue.component('kardexproduccion', require('./components/Kardexproduccion.vue').default);
Vue.component('kardexproducto', require('./components/Kardexproducto.vue').default);
Vue.component('manodeobra', require('./components/ManoDeObraProductos.vue').default);
Vue.component('maquinaria', require('./components/Maquinaria.vue').default);
Vue.component('maquinarias', require('./components/Maquinarias.vue').default);
Vue.component('materiaprima', require('./components/MateriaPrimaProductos.vue').default);
Vue.component('materiaprimacompleta', require('./components/MateriaPrimaCompleta.vue').default);
Vue.component('materiaprimarequerida', require('./components/MateriaPrimaRequerida.vue').default);
Vue.component('materiaprimasobrante', require('./components/MateriaPrimaSobrante.vue').default);
Vue.component('materias', require('./components/Materias.vue').default);
Vue.component('ninguno', require('./components/Ninguno.vue').default);
Vue.component('ordendepedido', require('./components/Ordendepedido.vue').default);
Vue.component('perfiles', require('./components/Perfiles.vue').default);
Vue.component('proveedores', require('./components/Proveedores.vue').default);
Vue.component('procesos', require('./components/Procesos.vue').default);
Vue.component('productos', require('./components/Productos.vue').default);
Vue.component('productossimulacion', require('./components/ProductosSimulacion.vue').default);
Vue.component('productosordenpedido', require('./components/ProductosOrdenPedido.vue').default);
Vue.component('productosordenpedidoconsolidado', require('./components/ProductosOrdenPedidoConsolidado.vue').default);
Vue.component('productoscotizacion', require('./components/ProductosCotizacion.vue').default);
Vue.component('roles', require('./components/Roles.vue').default);
Vue.component('simulador', require('./components/Simulador.vue').default);
Vue.component('tomainventarios', require('./components/TomaInventarios.vue').default);
Vue.component('realizarinventario', require('./components/RealizarInventario.vue').default);
Vue.component('detalleinventario', require('./components/DetalleInventario.vue').default);
Vue.component('unidades', require('./components/Unidades.vue').default);
Vue.component('usuarios', require('./components/Usuarios.vue').default);
Vue.component('manual', require('./components/Manual.vue').default);
Vue.component('cartilla', require('./components/Cartilla.vue').default);
Vue.component('cartilladigital', require('./components/CartillaDigital.vue').default);
Vue.component('videotutoriales', require('./components/Videos.vue').default);
Vue.component('tiempoestandar', require('./components/TiempoEstandar.vue').default);
Vue.component('detalletiempoestandar', require('./components/DetalleTiempoEstandar.vue').default);
Vue.component('westinghouse', require('./components/WestingHouse.vue').default);
Vue.component('pds', require('./components/Pds.vue').default);
Vue.component('ciclos', require('./components/Ciclos.vue').default);
Vue.component('vinculacion', require('./components/Vinculacion.vue').default);
Vue.component('novedades', require('./components/Novedades.vue').default);
Vue.component('carganovedades', require('./components/CargaNovedades.vue').default);
Vue.component('cierrenomina', require('./components/CierreNomina.vue').default);
Vue.component('detallenomina', require('./components/DetalleNomina.vue').default);

Vue.component('puntoequilibrio', require('./components/Puntoequilibrio.vue').default);
Vue.component('precioventa', require('./components/Precioventa.vue').default);
Vue.component('endeudamiento', require('./components/Endeudamiento.vue').default);
Vue.component('liquidez', require('./components/Liquidez.vue').default);
Vue.component('rentabilidad', require('./components/Rentabilidad.vue').default);
Vue.component('rotacioninventario', require('./components/Rotacioninventario.vue').default);
Vue.component('rotacioncartera', require('./components/Rotacioncartera.vue').default);

Vue.component('hojadecostossimuladorfinanciero', require('./components/HojaDeCostosSimuladorFinanciero.vue').default);
Vue.component('simuladorfinanciero', require('./components/SimuladorFinanciero.vue').default);
Vue.component('productossimuladorfinanciero', require('./components/ProductosSimuladorFinanciero.vue').default);

Vue.component('vistapersonalizada', require('./components/VistaPersonalizada.vue').default);

Vue.component('pruebas', require('./components/Pruebas.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data :{
        menu : 0
    }
});
