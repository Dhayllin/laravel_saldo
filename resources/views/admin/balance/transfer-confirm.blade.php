@extends('adminlte::page')

@section('title', 'Confirmar Transferência de Saldo')

@section('content_header')
    <h1>Transferir</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        <li class="breadcrumb-item active" aria-current="page">Saldo</li>
        <li class="breadcrumb-item active" aria-current="page">Transferir</li>
        <li class="breadcrumb-item active" aria-current="page">Confirmação</li>
    </ol>
@stop


@section('content')
<div class="box">
        <div class="box-header">       
        </div>
        <div class="box-body">
            @include('admin.includes.alerts') 

            <p><strong>Recebedor: </strong>{{$sender->name}}</p> 
            <p><strong>Seu Saldo Atual: </strong> R$ {{ number_format($balance->amout, 2,',','')}}</p>   

        <form method="POST" action="{{ route('transfer.store')}}">
            {{ csrf_field() }}

            <input type="hidden" name="sender_id" value="{{$sender->id}}">

            <div class="form-group">
                <input type="text" name="value" placeholder="Valor : " class="form-control">
            </div>

            <div class="form-group">
                    <button type="submit" class="btn btn-success">Transferir</button>
            </div>                
         </form>
        </div>
    </div>
@stop