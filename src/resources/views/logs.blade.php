@extends(backpack_view('layouts.top_left'))

@php
  $breadcrumbs = [
    'Admin' => backpack_url('dashboard'),
    'Log Manager' => backpack_url('log'),
    'Existing Logs' => false,
  ];
@endphp

@section('header')
    <section class="container-fluid">
      <h2>
        {{ backpack_url('log') }}<br><small>{{ 'Log Manager' }}</small>
      </h2>
    </section>
@endsection

@section('content')
<!-- Default box -->
  <div class="card">
    <div class="card-body p-0">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
      <table class="table table-hover table-condensed pb-0 mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ 'File Name' }}</th>
            <th>{{ 'Date' }}</th>
            <th>{{ 'Last modified' }}</th>
            <th class="text-right">{{ 'File size' }}</th>
            <th>{{ 'Actions' }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($files as $key => $file)
          <tr>
            <th scope="row">{{ $key + 1 }}</th>
            <td>{{ $file['file_name'] }}</td>
            <td>{{ \Carbon\Carbon::createFromTimeStamp($file['last_modified'])->formatLocalized('%d %B %Y') }}</td>
            <td>{{ \Carbon\Carbon::createFromTimeStamp($file['last_modified'])->formatLocalized('%H:%M') }}</td>
            <td class="text-right">{{ round((int)$file['file_size']/1048576, 2).' MB' }}</td>
            <td>
                <a class="btn btn-sm btn-link" href="{{ url(config('backpack.base.route_prefix', 'admin').'/log/preview/'. encrypt($file['file_name'])) }}"><i class="la la-eye"></i> {{ 'Preview' }}</a>
                <a class="btn btn-sm btn-link" href="{{ url(config('backpack.base.route_prefix', 'admin').'/log/download/'.encrypt($file['file_name'])) }}"><i class="la la-cloud-download"></i> {{ 'Download' }}</a>
                <a class="btn btn-sm btn-link" href="{{ url(config('backpack.base.route_prefix', 'admin').'/log/mail/'.encrypt($file['file_name'])) }}"><i class="la la-envelope"></i> {{ 'Mail' }}</a>
                @if (config('backpack.logmanager.allow_delete'))
                    <a class="btn btn-sm btn-link" data-button-type="delete" href="{{ url(config('backpack.base.route_prefix', 'admin').'/log/delete/'.encrypt($file['file_name'])) }}"><i class="la la-trash-o"></i> {{ 'Delete' }}</a>
                @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

    </div><!-- /.box-body -->
  </div><!-- /.box -->

@endsection

@section('after_scripts')
<script>
  jQuery(document).ready(function($) {

    // capture the delete button
    $("[data-button-type=delete]").click(function(e) {
        e.preventDefault();
        var delete_button = $(this);
        var delete_url = $(this).attr('href');

        if (confirm("{{ 'Are your sure you want to delete this log file?' }}") == true) {
            $.ajax({
                url: delete_url,
                type: 'DELETE',
                data: {
                  _token: "<?php echo csrf_token(); ?>"
                },
                success: function(result) {
                    // delete the row from the table
                    delete_button.parentsUntil('tr').parent().remove();

                    // Show an alert with the result
                    new Noty({
                        text: "<strong>{{ 'Done' }}</strong><br>{{ 'The log file was deleted.' }}",
                        type: "success"
                    }).show();
                },
                error: function(result) {
                    // Show an alert with the result
                    new Noty({
                        text: "<strong>{{ 'Error' }}</strong><br>{{ 'The log file has NOT been deleted.' }}",
                        type: "warning"
                    }).show();
                }
            });
        } else {
            new Noty({
                text: "<strong>{{ 'It&#039;s ok' }}</strong><br>{{ 'The log file has NOT been deleted.' }}",
                type: "info"
            }).show();
        }
      });

  });
</script>
@endsection
