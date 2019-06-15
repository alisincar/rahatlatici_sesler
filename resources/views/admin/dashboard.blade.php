@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
<h5>Uygulama Sürümü: <b>{{$app_info->app_version}}</b></h5>
                        <div class="row mb-4">
                            <div class="col-xl-4 col-sm-6 py-2">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body bg-success">
                                        <div class="rotate">
                                            <i class="fa fa-user fa-4x"></i>
                                        </div>
                                        <h6 class="text-uppercase">Üye</h6>
                                        <h1 class="display-4">{{$users}}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 py-2">
                                <div class="card text-white bg-danger h-100">
                                    <div class="card-body bg-danger">
                                        <div class="rotate">
                                            <i class="fa fa-list fa-4x"></i>
                                        </div>
                                        <h6 class="text-uppercase">Kategori</h6>
                                        <h1 class="display-4">{{$categories}}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6 py-2">
                                <div class="card text-white bg-info h-100">
                                    <div class="card-body bg-info">
                                        <div class="rotate">
                                            <i class="fa fa-twitter fa-4x"></i>
                                        </div>
                                        <h6 class="text-uppercase">Müzik</h6>
                                        <h1 class="display-4">{{$musics}}</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
