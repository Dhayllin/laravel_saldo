@extends('adminlte::page')

@section('title', 'Nova Recarga')

@section('content_header')
    <h1>Fazer Retirada</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        <li class="breadcrumb-item active" aria-current="page">Saldo</li>
        <li class="breadcrumb-item active" aria-current="page">Retirada</li>
    </ol>
@stop


@section('content')
<div class="box">
        <div class="box-header">
        </div>
        <div class="box-body">
            @include('admin.includes.alerts')           
        <form method="POST" action="{{ route('withdraw.store')}}">
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="text" name="value" placeholder="Valor retirada" class="form-control">
                </div>
                <div class="form-group">
                        <button type="submit" class="btn btn-success">Sacar</button>
                </div>                
            </form>
        </div>
    </div>
@stop