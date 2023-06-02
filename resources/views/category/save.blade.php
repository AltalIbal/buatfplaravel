@extends('layouts.admin')

@section('content')
@php ($url = route('categories.store')) @endphp
@if($action=='edit') 
  @php ($url = route('categories.update', $row->id ?? '' )) @endphp 
@endif
<div class="content-wrapper">
	<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add/Edit Kategori</h1>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
	<form method="post" action="{{$url}}" enctype="multipart/form-data">
  @csrf
  {{ $action == 'edit' ? method_field('PUT') : '' }}

  <div class="form-group">
      <label for="kategori">Nama Kategori</label>
      <input type="text" class="form-control" name="name" value="{{$row->name ?? ''}}" required>
    </div>

    <div class="form-group">
      <label for="kategori">Range Harga</label>
      <input type="text" class="form-control" name="price" value="{{$row->price ?? ''}}" required>
    </div>

    <div class="form-group">
      <label for="kategori">Deskripsi</label>
      <input type="text" class="form-control" name="description" value="{{$row->description ?? ''}}" required>
    </div>

  <button type="submit" class="btn btn-primary">Simpan</button>
</form>
</div>
</div>
</div>
@endsection