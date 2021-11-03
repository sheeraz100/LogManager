@extends(backpack_view('layouts.top_left'))

@php
    $breadcrumbs = [
      'Admin' => backpack_url('dashboard'),
      'Log Manager' => backpack_url('log'),
      'Logs' => false,
    ];
@endphp

@section('header')
    <section class="container-fluid">
        <h2>
            {{ $title }}<br><small>{{ 'File name' }}: <i>{{ $file_name }}</i></small>
            <small><a href="{{ backpack_url('log') }}" class="hidden-print font-sm"><i class="la la-angle-double-left"></i> {{ 'Back to all logs' }}</a></small>
        </h2>
    </section>
@endsection

@section('content')
    <table class="table table-hover table-condensed pb-0 mb-0">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ 'User Name' }}</th>
            <th>{{ 'Email' }}</th>
            <th>{{ 'Actions' }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $key => $user)
            <tr>
                <th scope="row">{{ $key + 1 }}</th>
                <th scope="row">{{ $user->name }}</th>
                <th scope="row">{{ $user->email }}</th>
                <th scope="row"><a class="btn btn-sm btn-link" href="{{ url(config('backpack.base.route_prefix', 'admin').'/log/mail_log_to_user/'.encrypt($file_name).'/'.$user->id) }}"><i class="la la-envelope"></i> {{ 'Send Mail' }}</a></th>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('after_scripts')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.6/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
@endsection
