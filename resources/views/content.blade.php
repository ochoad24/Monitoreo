@extends('layout')
@section('content')
    <template v-if="menu==2">
        <!--proyecto></proyecto-->
        <organizacion></organizacion>
    </template>
    <template v-if="menu==3">
        <!--proyecto></proyecto-->
        <proyecto></proyecto>
    </template>
@endsection