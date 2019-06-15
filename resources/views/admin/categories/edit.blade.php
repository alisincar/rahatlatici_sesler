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
                        <form action="{{ route('categories.update',$category->id) }}" method="POST" id="customerForm"
                              accept-charset="UTF-8" enctype='multipart/form-data'>
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label for="name">Adı</label>
                                <input type="text" value="{{$category->name}}" class="form-control" id="name"
                                       name="name" placeholder="Kategori adı"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="parent_id">Üst Kategori</label>
                                <select id="parent_id" name="parent_id">
                                    <option value="0">Ana Kategori</option>
                                    @foreach($list_categories as $list_category)
                                        <option
                                            value="{{$list_category->id}}" {!! ($category->parent_id==$list_category->id)?'selected="selected"':''!!}>{{$list_category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cover_image">Kapak Resmi</label>
                                @if(isset($category->cover_image))
                                    <br>
                                    <img src="{{env('APP_URL').$category->cover_image}}" class="w-25">
                                @endif
                                <input type="file" accept="image/*" class="form-control" id="cover_image" name="cover_image"
                                       placeholder="Kategori Kapak Resmi" {!! !isset($category->cover_image)?'required':'' !!}>
                            </div>
                            <div class="form-group">
                                <label for="status">Durum</label>
                                <select id="status" name="status">
                                    <option value="1" {!! ($category->status==1)?'selected':'' !!}>Aktif</option>
                                    <option value="0" {!! ($category->status==0)?'selected':'' !!}>Pasif</option>
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

