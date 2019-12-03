@extends('adminlte::page')

@section('title', 'Saldo')

@section('content_header')
    <h1>Saldo</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        <li class="breadcrumb-item active" aria-current="page">Saldo</li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
        <a class="btn btn-primary" href="{{ route('balance.deposit')}}"> <i class="fa fa-cart-plus"></i>
                Recarregar</a>
            @if($amout > 0)
                <a class="btn btn-danger" href="{{ route('balance.withdraw')}}"> <i class="fa fa-cart-arrow-down"></i>
                        Sacar</a>
            @endif 
            @if($amout > 0)
                <a class="btn btn-info" href="{{ route('balance.transfer')}}"> <i class="fa fa-exchange-alt"></i>
                        Transferir</a>
            @endif

        </div>
        <div class="box-body">
                @include('admin.includes.alerts')   
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>R$ {{ $amout}}</h3>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cash"></i>
                    </div>
                    <a href="#" class="small-box-footer">Histórico <i class="fa fa-arrow-circle-right"></i></a>
                </div>
        </div>
    </div>
@stop
