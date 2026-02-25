@extends('layouts.app')
@section('content')
    @livewire('class-manage', ['class' => $class])
@endsection
