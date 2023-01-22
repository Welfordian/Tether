@extends('layouts.app')

@section('content')
    <div class="flex flex-col justify-center h-screen items-center">
        <h1 class="text-4xl">Whoops, something went wrong!</h1>
        
        @if(isset($exception))
            <h2 class="mt-12 italic">({{ $exception->getMessage() }})</h2>
        @endif    
    </div>
@endsection    