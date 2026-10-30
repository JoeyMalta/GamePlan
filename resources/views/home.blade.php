@extends('layouts.main')

@section('title', 'Gameplan')

@section('content')
    {{-- $upcomingEvents komt uit HomeController@index --}}
    <x-hero :events="$upcomingEvents" />
@endsection
