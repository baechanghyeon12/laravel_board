@extends('layout.layout')

@section('title', 'Login')
    
@section('contents')
<h1>Login</h1>
    @include('layout.errorsvalidate')
    <div>{{isset($success) ? $success : ""}}</div>
    <form action="{{route('users.login.post')}}" method="post">
        @csrf
        <label for="email">Email : </label>
        <input type="text" id="email" name="email">
        <label for="password">Password : </label>
        <input type="password" id="password" name="password">
        <br>
        <br>
        <button type="submit">Login</button>
        <button type="button" onclick="location.href = '{{route('users.registration')}}'">Registration</button>
    </form>
@endsection