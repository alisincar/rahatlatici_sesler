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
                        <div style="float: right;padding:5px 20px;">
                            <a href="{{ route('musics.create') }}">
                                <button class="btn btn-primary">Ekle</button>
                            </a>
                        </div>
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Adı</th>
                                <th scope="col">Durum</th>
                                <th scope="col">İşlemler</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($musics as $music)
                                <tr>
                                    <td>
                                        {!!  (isset($music->parent))?'<small>'.$music->parent->name.'> </small><br>':''  !!}
                                        {{ $music->name }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-{{$music->status=='1'?'success':'secondary'}}">{{$music->status=='1'?'Aktif':'Pasif'}}</button>
                                    </td>
                                    <td>

                                        <a href="{{ route('musics.edit',$music->id) }}">
                                            <button class="btn-sm btn btn-info text-white">Düzenle</button>
                                        </a>

                                        <button onclick="deleteItem(this,'{{route('musics.destroy',$music->id)}}')" class="btn-sm btn btn-danger">Sil</button>

                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center bg-light"><h4>Henüz müzik ekli değil.</h4>Müzik eklemek için
                                            <a href="{{ route('musics.create') }}">
                                                <button class="btn btn-sm btn-primary">Ekle</button>
                                            </a> butonuna basınız</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {!! $musics->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

