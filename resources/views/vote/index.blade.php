@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
	<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Vote</h1>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
    <a href="{{ route('votes.create')}}" class="btn btn-primary mb-3">Tambah</a>

	<table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
		<thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Voting</th>
                <th>Gambar</th>
                <th>Total Vote</th>
                <th>Created At</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        	@foreach ($vote as $idx => $item)
            <tr>
                <td>{{ $idx+=1 }}</td>
                <td>{{ $item->name }}</td>
                <td>
                  @if($item->image!='')
                  <img src="{{ asset('uploads/vote/'.$item->image) }}" class="img-fluid zoom" width="50" data-toggle="modal" data-target=".bd-example-modal-lg">
                  @endif
                </td>
                <td>{{ $item->vote }}</td>
                <td>{{ date("d-m-Y H:i:s", strtotime($item->created_at)) }}</td>
                <td>
                  <a href="{{ route('votes.edit' , $item->id) }}" class="btn btn-warning">Edit</a>
                    
                	<form method="post" action="{{ route('votes.destroy', $item->id) }}" class="d-inline-block">
                		{!! method_field('delete') . csrf_field() !!}
                		<button type="submit" class="btn btn-danger">Hapus</button>
                	</form>
                </td>
            </tr> 
            @endforeach
        </tbody>
	</table>
</div>
</div>
</div>

@endsection

