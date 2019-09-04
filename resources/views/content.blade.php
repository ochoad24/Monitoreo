@extends('layout')
@section('content')
    <template v-if="menu==0">
        <Rol></Rol>
    </template>
    <template v-if="menu==1">
        <Permiso></Permiso>
    </template>
    <template v-if="menu==2">
        <organizacion></organizacion>
    </template>
    <template v-if="menu==3">
        <proyecto></proyecto>
    </template>
    <template v-if="menu==4">
        <Usuario></Usuario>
    </template>
    <template v-if="menu==5">
        <Actividad></Actividad>
    </template>
@endsection