@extends('layouts.admin')

@section('content')
@php ($url = route('votes.store')) @endphp
@if($action=='edit') 
  @php ($url = route('votes.update', $row->id ?? '' )) @endphp 
@endif
<div class="content-wrapper">
	<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add/Edit Vote</h1>
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
      <label for="kategori">Kategori</label>
      <select class="form-control" name="category_id">
        @foreach($category as $item)
        <option value="{{ $item->id }}" {{ $item->id==@$item->category_id ? 'selected' : '' }}>{{ $item->name }}</option>
        @endforeach
      </select>
    </div>
    
  <div class="form-group">
      <label for="kategori">Nama Voting</label>
      <input type="text" class="form-control" name="name" value="{{$row->name ?? ''}}" required>
    </div>

    <div class="form-group">
      <label for="kategori">Deskripsi</label>
      <textarea id="summernote" class="form-control" name="description" required>{{$row->description ?? ''}}</textarea>
    </div>

    @if ($action=='edit')
    <img src="{{ asset('uploads/vote/'.$row->image) }}" class="img-fluid" width="200">
    @endif

    <div class="form-group">
      <label for="kategori">Gambar</label>
      <input type="file" class="form-control" name="image">
    </div>

  <button type="submit" class="btn btn-primary">Simpan</button>
</form>
</div>
</div>
</div>
@endsection