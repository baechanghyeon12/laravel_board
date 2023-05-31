@extends('layout.layout')

@section('title', 'Edit')
    
@section('contents')
<h1>UsersEdit</h1>
    @include('layout.errorsvalidate')
    <form action="{{route('users.update',['id' => session('id')])}}" method="post">
        @csrf
        @method('put')
        <label for="name">Name : </label>
        <input type="text" id="name" name="name" value="{{count($errors) > 0 ? old('name') : $data->name}}">
        <br>
        <label for="email">Email : </label>
        <input type="text" id="email" name="email" value="{{count($errors) > 0 ? old('email') : $data->email}}">
        <br>
        <label for="password">Password : </label>
        <input type="password" id="password" name="password">
        <br>
        <label for="passwordchk">Password : </label>
        <input type="password" id="passwordchk" name="passwordchk">
        <br>
        <br>
        <button type="submit">Edit</button>
        <button type="button" onclick="location.href = '{{route('boards.index')}}'">Cancel</button>
    </form>
@endsection
