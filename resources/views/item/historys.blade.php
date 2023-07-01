@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="card-title">履歴一覧</h3>
                        <div class="d-flex">
                            <div>
                                <a href="{{ url('items/add') }}" class="btn btn-default">商品登録</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>アイテム名</th>
                                <th>アクション</th>
                                <th>変更前の個数</th>
                                <th>変更後の個数</th>
                                <th>変更日時</th>
                            </tr>
                        </thead>
                        <tbody id="search-results">
                        @foreach ($histories as $history)
                            <tr>
                                <td>{{ $history->name }}</td>
                                <td>{{ $history->action }}</td>
                                <td>{{ $history->previous_quantity }}</td>
                                <td>{{ $history->new_quantity }}</td>
                                <td>{{ $history->changed_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
