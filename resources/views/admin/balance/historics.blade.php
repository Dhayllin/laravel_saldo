@extends('adminlte::page')

@section('title', 'Histórico de Movimentações')

@section('content_header') 
    <h1>Histórico</h1>  
    <ol class="breadcrumb">       
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        <li class="breadcrumb-item active" aria-current="page">Saldo</li>
        <li class="breadcrumb-item active" aria-current="page">Histórico</li>   
    </ol>
@stop

@section('content')
<div class="box">
        <div class="box-header">
        <form action="{{ route('historic.search')}}" method="post" class="form form-inline">

            {{ csrf_field() }}
            <input type="text" name="id" class="form form-control" placeholder="ID">
            <input type="date" name="date" class="form form-control">
            <select name="type" class="form form-control">
                <option value="">--Selecione o tipo --</option>                    
                @foreach ($types as $key => $type)
                <option value="{{$key}}">{{$type}}</option>                        
                @endforeach                    
            </select>

            <button type="submit" class="btn btn-primary">Pesquisar</button>
        </form>
        </div>
        <div class="box-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>?Sender?</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($historics as $historic)
                        <tr>
                            <td>{{$historic->id}}</td>
                            <td>{{number_format($historic->amout,2,',','.')}}</td>
                            <td>{{$historic->type($historic->type)}}</td>
                            <td>{{$historic->getDateAtrribute($historic->date)}}</td>
                            <td>
                                @if($historic->user_id_trasaction)
                                      {{$historic->user()->get()->first()->name}}
                                @else
                                    -
                                @endif
                            </td>

                        </tr>
                    @empty                        
                    @endforelse
                    
                </tbody>
            </table>
            {{ $historics->links() }}
        </div>
 </div>
@stop