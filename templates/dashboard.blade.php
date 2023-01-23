@extends('layouts.app')

@section('content')
    <div class="flex justify-center h-screen items-center bg-gradient-to-r from-gray-700 via-gray-900 to-black">
        <div class="w-[36em] shadow-lg shadow-gray-800 bg-white flex flex-col items-center p-4 py-12">
            <div class="flex flex-col items-center w-full">
                <h1>Hello, {{ auth()->user()['username'] }}!</h1>
                
                <form method="POST" action="{{ route('logout') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <button type="submit" class="">Logout</button>
                </form>
            </div>
        </div>
    </div>
@endsection    