<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li @click="menu=15" class="nav-item">
                <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
            </li>
            <li class="nav-title">
                Mantenimiento
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-settings"></i> Administración</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=2" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-pencil"></i> Procesos</a>
                    </li>
                    <li @click="menu=3" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-pencil"></i> Perfiles</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wrench"></i> Materiales</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=4" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Unidades</a>
                    </li>
                    <li @click="menu=5" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Materias</a>
                    </li>
                    <li @click="menu=6" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-notebook"></i> Gestión</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> Productos</a>
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
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-folder"></i> Conceptos cif</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=10" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-folder-alt"></i> Conceptos</a>
                    </li>
                    <li @click="menu=11" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-folder-alt"></i> Maquinaria</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-settings"></i> <b>Vista personalizada</b></a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=98" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-book-open"></i> Personalizar vista</a>
                    </li>

                </ul>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
