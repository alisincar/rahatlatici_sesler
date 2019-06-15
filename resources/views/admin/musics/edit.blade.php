@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{$title}}</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('musics.update',$music->id) }}" method="POST" id="customerForm"
                              accept-charset="UTF-8" enctype='multipart/form-data'>
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label for="name">Adı</label>
                                <input type="text" value="{{$music->name}}" class="form-control" id="name"
                                       name="name" placeholder="Müzik adı"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="category_id">Kategori</label>
                                <select id="category_id" name="category_id">
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" {!! ($music->category_id==$category->id)?'selected="selected"':''!!}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cover_image">Kapak Resmi</label>
                                @if(isset($music->cover_image))
                                    <br>
                                    <img src="{{env('APP_URL').$music->cover_image}}" class="w-25">
                                @endif
                                <input type="file" accept="image/*" class="form-control" id="cover_image" name="cover_image"
                                       placeholder="Kategori Kapak Resmi" {!! !isset($music->cover_image)?'required':'' !!}>
                            </div>
                            <div class="form-group">
                                <label for="source">Müzik</label>
                                @if(isset($music->source))
                                    <br>
                                    <audio class="w-25" controls>
                                        <source src="{{env('APP_URL').$music->source}}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @endif
                                <input type="file" accept="audio/*" class="form-control" id="source" name="source"
                                       {!! !isset($music->source)?'required':'' !!}>
                            </div>
                            <div class="form-group">
                                <label for="status">Durum</label>
                                <select id="status" name="status">
                                    <option value="1" {!! ($music->status==1)?'selected':'' !!}>Aktif</option>
                                    <option value="0" {!! ($music->status==0)?'selected':'' !!}>Pasif</option>
                                </select>
                            </div>
                            <button class="btn btn-primary">Ekle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

