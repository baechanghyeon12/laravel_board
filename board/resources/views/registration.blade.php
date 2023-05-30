@extends('layout.layout')

@section('title', 'Registration')
    
@section('contents')
<h1>Registration</h1>
    @include('layout.errorsvalidate')
    <form action="{{route('users.registration.post')}}" method="post">
        @csrf
        <label for="name">Name : </label>
        <input type="text" id="name" name="name">
        <br>
        <label for="email">Email : </label>
        <input type="text" id="email" name="email">
        <br>
        <label for="password">Password : </label>
        <input type="password" id="password" name="password">
        <br>
        <label for="passwordchk">Password : </label>
        <input type="password" id="passwordchk" name="passwordchk">
        <br>
        <br>
        <button type="submit">Registration</button>
        <button type="button" onclick="location.href = '{{route('users.login')}}'">Cancel</button>
    </form>
@endsection