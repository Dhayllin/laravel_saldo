@extends('adminlte::page')

@section('title', 'Transferir Saldo')

@section('content_header')
    <h1>Transferir</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        <li class="breadcrumb-item active" aria-current="page">Saldo</li>
        <li class="breadcrumb-item active" aria-current="page">Transferir</li>
    </ol>
@stop


@section('content')
<div class="box">
        <div class="box-header">
            <h3>(Informe o Recebedor)</h3>
        </div>
        <div class="box-body">
            @include('admin.includes.alerts')           
        <form method="POST" action="{{ route('confirm.transfer')}}">
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="text" name="sender" placeholder="Informação de quem vai receber o saque( Nome ou E-mail)" class="form-control">
                </div>
                <div class="form-group">
                        <button type="submit" class="btn btn-success">Próxima Etapa</button>
                </div>                
            </form>
        </div>
    </div>
@stop