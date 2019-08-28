@extends('layout')
@section('content')
    <template v-if="menu==0">
        <Rol></Rol>
    </template>
    <template v-if="menu==1">
        <h1>Otro</h1>
    </template>
@endsection