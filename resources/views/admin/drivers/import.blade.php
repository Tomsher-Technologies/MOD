@extends('layouts.admin_account', ['title' => __db('import') . ' ' . __db('driver')])

@section('content')
    @include('shared-pages.drivers.import')
@endsection