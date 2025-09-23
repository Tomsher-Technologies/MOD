@extends('layouts.admin_account', ['title' => __db('assign_driver')])

@section('content')
    <x-back-btn title="{{ __db('assign') . ' ' . __db('driver') }}"
        back-url="{{ Session::has('assign_drivers_last_url') ? Session::get('assign_drivers_last_url') : route('drivers.index') }}" />

    @include('shared-pages.drivers.assign')
@endsection
