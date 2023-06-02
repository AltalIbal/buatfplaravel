@extends('layouts.admin')

@section('content')
<div class="content-wrapper">
	<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Kategori</h1>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
    <a href="{{ route('categories.create')}}" class="btn btn-primary mb-3">Tambah</a>

	<table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
		<thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Range Harga</th>
                <th>Deskripsi</th>
                <th>Created At</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        	@foreach ($category as $idx => $item)
            <tr>
                <td>{{ $idx+=1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ date("d-m-Y H:i:s", strtotime($item->created_at)) }}</td>
                <td>
                  <a href="{{ route('categories.edit' , $item->id) }}" class="btn btn-warning">Edit</a>
                    
                	<form method="post" action="{{ route('categories.destroy', $item->id) }}" class="d-inline-block">
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

