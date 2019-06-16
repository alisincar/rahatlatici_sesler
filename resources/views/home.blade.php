@extends('layouts.app')

@section('content')
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body page_tab" id="categories">
                    </div>

                    <div class="card-body page_tab" id="musics" style="display: none">
                        <button onclick="categoryBack()" class="btn btn-success"><i class="fa fa-arrow-left"></i> Geri</button>
                    </div>

                    <div class="card-body page_tab" id="favorites" style="display: none">
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')

@endpush
