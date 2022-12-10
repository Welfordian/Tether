@extends('layouts.app')

@section('content')
    @config('admin_username')
    
    <form method="POST">
        <input type="text" name="username" />
        
        <button type="submit">Check Username</button>
    </form>
@endsection    