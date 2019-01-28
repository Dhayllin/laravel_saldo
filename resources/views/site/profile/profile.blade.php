@extends('site.layouts.app')

@section('title', 'Meu Perfil')

@section('content')
   <h1> Meu Perfil </h1>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success')}}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error')}}
    </div>
@endif
<form action="{{ route('profile.update') }}" method="post">
    
        {{ csrf_field() }}

        <div class="form-group">
            <label for="name">Nome:</label>
            <input class="form-control" value="{{ auth()->user()->name}}" type="text" name="name" placeholder="Nome" >
        </div>
        <div class="form-group">
            <label for="email">E-Mail:</label>
            <input class="form-control"  value="{{ auth()->user()->email}}"type="email" name="email" placeholder="E-Mail">
        </div>
        <div class="form-group">
            <label for="password">Senha:</label>
            <input class="form-control" type="password" name="password" placeholder="Senha">
        </div>  
        <div class="form-group">
            <label for="image">Imagem:</label>
            <input class="form-control" type="file" name="image">
        </div>
        <div class="form-group">
            <div  class="btn btn-info">
                <button class="form-control" type="submit">Atualizar Perfil</button>
            </div>
        </div>
   </form>      
@endsection