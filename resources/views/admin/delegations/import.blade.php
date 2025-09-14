@extends('layouts.admin_account', ['title' => __db('import') . ' ' . __db('delegation')])

@section('content')
    @include('shared-pages.delegations.import')
@endsection