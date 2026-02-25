@extends('layouts.app')
@section('content')
    @livewire('take-exam', ['exam' => $exam])
@endsection
