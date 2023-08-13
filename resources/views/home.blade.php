@extends('layouts.app')

@section('titulo')
    Esta es la homepage
@endsection

@section('contenido')

    <x-listar-post :posts="$posts" />


@endsection
