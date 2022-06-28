<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            @if(Auth::user()->vistas['escritorio'])
            <li @click="menu=14" class="nav-item">
                <a class="nav-link active" href="#"><i class="icon-speedometer"></i> <b>Escritorio</b></a>
            </li>
            @endif
            <!--
            <li class="nav-title" style="color:#ff671b">
                Mantenimiento
            </li>
            -->
            @if(Auth::user()->vistas['documentacion'])
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-settings"></i> <b>Documentación</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=27" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Manual de usuario versión pdf</a>
                    </li>
                    <li @click="menu=28" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Cartilla práctica de costos versión pdf</a>
                    </li>
                    <li @click="menu=29" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Cartilla práctica de costos versión digital</a>
                    </li>
                    <li @click="menu=30" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Video Tutoriales</a>
                    </li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->vistas['administracion'])
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-settings"></i> <b>Administración</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=17" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Configuración</a>
                    </li>
                    {{--
                    <li @click="menu=1" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Areas</a>
                    </li>
                    --}}
                    <li @click="menu=2" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Procesos</a>
                    </li>
                    <li @click="menu=3" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Perfiles</a>
                    </li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->vistas['conceptosCif'])
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-folder"></i> <b>Conceptos cif</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=10" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-folder-alt"></i> Conceptos</a>
                    </li>
                    <li @click="menu=11" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-folder-alt"></i> Maquinaria</a>
                    </li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->vistas['materiales'])
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wrench"></i> <b>Materiales</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=4" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Unidades</a>
                    </li>
                    <li @click="menu=5" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Clasificación</a>
                    </li>
                    <li @click="menu=6" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Materias primas</a>
                    </li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->vistas['productos'])
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> <b>Productos</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=7" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-wallet"></i> Colecciones</a>
                    </li>
                    <li @click="menu=8" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-wallet"></i> Productos</a>
                    </li>
                    <li @click="menu=9" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-wallet"></i> Hojas de costo</a>
                    </li>
                    <li @click="menu=18" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-wallet"></i> Simulador</a>
                    </li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->vistas['produccion'])
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-docs"></i> <b>Producción</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=22" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-doc"></i> Cotización</a>
                    </li>
                    <li @click="menu=23" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-doc"></i> Orden de pedido</a>
                    </li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->vistas['kardex'])
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-folder-alt"></i> <b>Kárdex</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=24" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-note"></i> Kárdex almacén</a>
                    </li>
                    <li @click="menu=26" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-note"></i> Toma inventario</a>
                    </li>
                    <li @click="menu=25" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-note"></i> Kárdex producto</a>
                    </li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->vistas['manoDeObra'])
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-folder-alt"></i> <b>Mano de Obra</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=31" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-note"></i> Tiempo Estandar</a>
                    </li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->vistas['personas'])
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-user"></i> <b>Personas</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=12" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-pencil"></i> Roles</a>
                    </li>
                    <li @click="menu=13" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-pencil"></i> Usuarios</a>
                    </li>
                    <li @click="menu=19" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-pencil"></i> Proveedores</a>
                    </li>
                    <li @click="menu=20" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-pencil"></i> Clientes</a>
                    </li>
                    <li @click="menu=21" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-pencil"></i> Empleados</a>
                    </li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->vistas['nomina'])
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-settings"></i> <b>Nómina</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=32" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Vinculación</a>
                    </li>
                    <li @click="menu=33" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Gestionar nómina</a>
                    </li>
                </ul>
            </li>
            @endif
            @if(Auth::user()->vistas['gestionFinanciera'])
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> <b>Gestión financiera</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=34" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-wallet"></i> Precio de venta</a>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> <b>Punto de equilibrio</b></a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=35" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-wallet"></i> Punto de equilibrio individual</a>
                            </li>
                            <li @click="menu=40" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-wallet"></i> Punto de equilibrio multiproducto</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> <b>Indicadores financieros</b></a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=37" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-wallet"></i> Liquidez</a>
                            </li>
                            <li @click="menu=36" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-wallet"></i> Endeudamiento</a>
                            </li>
                            <li @click="menu=41" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-wallet"></i> Rentabilidad</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> <b>Actividades o gestión</b></a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=38" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-wallet"></i> Rotación inventario</a>
                            </li>
                            <li @click="menu=39" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-wallet"></i> Rotación cartera</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
