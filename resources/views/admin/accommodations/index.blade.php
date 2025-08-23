@extends('layouts.admin_account', ['title' => __db('all') . ' ' . __db('accommodations')])

@section('content')
    @include('shared-pages.accommodations.index')
@endsection