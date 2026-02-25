@extends('layouts.app')
@section('content')
    @livewire('exam-form', ['exam' => $exam ?? null])
@endsection
