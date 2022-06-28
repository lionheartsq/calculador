    @extends('principal')
    @section('contenido')

    <template v-if="menu==0">
        <bienvenida></bienvenida>
    </template>

    <template v-if="menu==1">
        <areas></areas>
    </template>

    <template v-if="menu==2">
        <procesos></procesos>
    </template>

    <template v-if="menu==3">
        <perfiles></perfiles>
    </template>

    <template v-if="menu==4">
        <unidades></unidades>
    </template>

    <template v-if="menu==5">
        <materias></materias>
    </template>

    <template v-if="menu==6">
        <gestionmaterias></gestionmaterias>
    </template>

    <template v-if="menu==7">
        <colecciones></coleciones>
    </template>

    <template v-if="menu==8">
        <productos></productos>
    </template>

    <template v-if="menu==9">
        <hojas></hojas>
    </template>

    <template v-if="menu==10">
        <conceptos></conceptos>
    </template>

    <template v-if="menu==11">
        <maquinarias></maquinarias>
    </template>

    <template v-if="menu==12">
        <roles></roles>
    </template>

    <template v-if="menu==13">
        <usuarios></usuarios>
    </template>

    <template v-if="menu==14">
        <admin></admin>
    </template>

    <template v-if="menu==15">
        <empresa></empresa>
    </template>

    <template v-if="menu==16">
        <ninguno></ninguno>
    </template>

    <template v-if="menu==17">
        <configuracion></configuracion>
    </template>

    <template v-if="menu==18">
        <simulador></simulador>
    </template>

    <template v-if="menu==19">
        <proveedores></proveedores>
    </template>

    <template v-if="menu==20">
        <clientes></clientes>
    </template>

    <template v-if="menu==21">
        <empleados></empleados>
    </template>

    <template v-if="menu==22">
        <cotizacion></cotizacion>
    </template>

    <template v-if="menu==23">
        <ordendepedido></ordendepedido>
    </template>

    <template v-if="menu==24">
        <kardexalmacen></kardexalmacen>
    </template>

    <template v-if="menu==25">
        <kardexproducto></kardexproducto>
    </template>

    <template v-if="menu==26">
        <tomainventarios></tomainventarios>
    </template>

    <template v-if="menu==27">
        <manual></manual>
    </template>

    <template v-if="menu==28">
        <cartilla></cartilla>
    </template>

    <template v-if="menu==29">
        <cartilladigital></cartilladigital>
    </template>

    <template v-if="menu==30">
        <videotutoriales></videotutoriales>
    </template>

    <template v-if="menu==31">
        <tiempoestandar></tiempoestandar>
    </template>

    <template v-if="menu==32">
        <vinculacion></vinculacion>
    </template>

    <template v-if="menu==33">
        <cierrenomina></cierrenomina>
    </template>

    <template v-if="menu==34">
        <precioventa></precioventa>
    </template>

    <template v-if="menu==35">
        <puntoequilibrio></puntoequilibrio>
    </template>

    <template v-if="menu==36">
        <endeudamiento></endeudamiento>
    </template>

    <template v-if="menu==37">
        <liquidez></liquidez>
    </template>

    <template v-if="menu==38">
        <rotacioninventario></rotacioninventario>
    </template>

    <template v-if="menu==39">
        <rotacioncartera></rotacioncartera>
    </template>

    <template v-if="menu==40">
        <simuladorfinanciero></simuladorfinanciero>
    </template>

    <template v-if="menu==41">
        <rentabilidad></rentabilidad>
    </template>

    <template v-if="menu==98">
        <vistapersonalizada></vistapersonalizada>
    </template>

    <template v-if="menu==99">
        <pruebas></pruebas>
    </template>

    @endsection
