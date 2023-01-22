@extends('layouts.app')

@section('content')
    <div class="flex justify-center h-screen items-center bg-gradient-to-r from-gray-700 via-gray-900 to-black">
        <div class="w-[36em] shadow-lg shadow-gray-800 bg-white flex flex-col items-center p-4 py-12">            
            <div class="flex flex-col items-center w-full">
                <form method="POST" action="/login" class="w-full flex justify-center">
                    <div class="w-4/5">
                        <div>
                            <label for="username" class="block">Username</label>

                            <div class="relative mt-1 rounded-md shadow-sm">
                                <input type="text" name="username" id="username" class="block w-full border border-gray-500 px-2 py-2 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-8">
                            <label for="password" class="block">Password</label>

                            <div class="relative mt-1 rounded-md shadow-sm">
                                <input type="password" name="password" id="password" class="block w-full border border-gray-500 px-2 py-2 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        
                        @if(isset($_SESSION['flash']) && isset($_SESSION['flash']['errors']))
                            <div class="mt-8">
                                @foreach($_SESSION['flash']['errors'] as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif    
                        
                        <button class="bg-black text-white px-8 py-3 mt-8 w-full hover:bg-black/80">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection    