@extends('layouts.app')
@session('content')
	@if (count($errors) > 0)
     <div class="alert alert-danger">
         <ul>
         @foreach ($errors->all() as $error)
             <li>{{ $error }}</li>
         @endforeach
         </ul>
     </div>
	@endif

	{!! Form::open(['url' => '/upload', 'files' => true]) !!} 
	     <!-- File upload chap nhap type Image -->
	     {!! Form::file('image', ['accept' => 'image/*']) !!} <br>
	     {!! Form::submit('Upload') !!}
	{!! Form::close() !!}
@endsession


