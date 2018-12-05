@extends('adminlte::page')

@section('title', 'Saldo')

@section('content_header')
    <h1>Saldo</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Financeiro</li>
        <li class="breadcrumb-item active" aria-current="page">Saldo</li>
    </ol>
@stop

@section('content')
    <div class="box">
        <div class="box-header">
            <a class="btn btn-primary" href=""> <i class="fa fa-cart-plus"></i>
                Recarregar</a>
            <a class="btn btn-danger" href=""> <i class="fa fa-cart-arrow-down"></i>
                Sacar</a>
        </div>
        <div class="box-body">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>R$ 90,00</h3>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cash"></i>
                    </div>
                    <a href="#" class="small-box-footer">Histórico <i class="fa fa-arrow-circle-right"></i></a>
                </div>
        </div>
    </div>
@stop
