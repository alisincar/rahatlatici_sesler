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
                        <form action="{{ route('categories.store') }}" method="POST" id="customerForm"
                              accept-charset="UTF-8" enctype='multipart/form-data'>

                            @csrf
                            <div class="form-group">
                                <label for="name">Adı</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Kategori adı"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="parent_id">Üst Kategori</label>
                                <select id="parent_id" name="parent_id">
                                    <option value="0" selected="selected">Ana Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cover_image">Kapak Resmi</label>
                                <input type="file" accept="image/*" class="form-control" id="cover_image" name="cover_image"
                                       placeholder="Kategori Kapak Resmi"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="status">Durum</label>
                                <select id="status" name="status">
                                    <option value="1">Aktif</option>
                                    <option value="0">Pasif</option>
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

