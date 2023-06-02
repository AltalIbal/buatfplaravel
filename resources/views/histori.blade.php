@extends('layouts.admin')

@section('content')

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Histori Voting</h1>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <table class="table table-sm table-bordered table-striped">
          <tr>
            <th>User</th>
            <th>Vote</th>
            <th>Created At</th>
          </tr>
          @foreach($histori as $item)
          <tr>
            <td>{{ $item->user->name }}</td>
            <td>{{ $item->vote->name }}</td>
            <td>{{ date('d-m-Y H:i:s',strtotime($item->created_at)) }}</td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>

@endsection