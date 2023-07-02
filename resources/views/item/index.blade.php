@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="card-title">商品一覧</h3>
                        <div class="d-flex">
                            <div class="mr-2">
                                <select class="form-control" id="column-select">
                                    <option value="name">名前</option>
                                    <option value="type">種別</option>
                                </select>
                            </div>
                            <div class="mr-3">
                                <input type="text" class="form-control" id="keyword-input" placeholder="キーワードを入力">
                            </div>
                            <div class="mr-3">
                                <button class="btn btn-default" onclick="searchItems()">検索</button>
                            </div>
                            <div class="mr-3">
                                <button class="btn btn-default" onclick="showAllItems()">検索クリア</button>
                            </div>
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
                                <th>ID</th>
                                <th>名前</th>
                                <th>種別</th>
                                <th>詳細</th>
                                <th>在庫数</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody id="search-results">
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->detail }}</td>
                                    <td>{{ $item->stock }}</td>
                                    <td>
                                        <input type="number" class="form-control stock-input" value="{{ $item->stock }}" data-item-id="{{ $item->id }}">
                                    </td>
                                    <td>
                                        <div style="display: inline-block;">
                                            <button class="btn btn-sm btn-primary" onclick="updateStock('{{ $item->id }}')">変更</button>
                                        </div>
                                        <div style="display: inline-block;">
                                            <form action="{{ route('items.delete', $item->id) }}" method="post" onsubmit="return confirm('本当に削除しますか？')">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-sm btn-danger" type="submit">削除</button>
                                            </form>
                                        </div>
                                    </td>
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
    <script>
        function updateStock(itemId) {
            const stockInput = document.querySelector(`input[data-item-id="${itemId}"]`);
            const newStock = stockInput.value;

            fetch(`/update-stock/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ stock: newStock })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('在庫数が更新されました');
                        location.reload();
                    } else {
                        alert('在庫数の更新に失敗しました');
                    }
                })
                .catch(error => {
                    console.error('エラー:', error);
                });
            }


        // 検索
        function searchItems() {
            const columnSelect = document.getElementById('column-select');
            const keywordInput = document.getElementById('keyword-input');
            const column = columnSelect.value;
            const keyword = keywordInput.value;

            fetch(`/search-items?column=${column}&keyword=${keyword}`)
        .then(response => response.json())
        .then(data => {
            const searchResults = document.getElementById('search-results');
            let html = '';

            data.items.forEach(item => {
                html += `
                        <tr>
                            <td>${item.id}</td>
                            <td>${item.name}</td>
                            <td>${item.type}</td>
                            <td>${item.detail}</td>
                            <td>${item.stock}</td>
                            <td>
                                <input type="number" class="form-control stock-input" value="${item.stock}" data-item-id="${item.id}">
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="updateStock('${item.id}')">変更</button>
                                <div style="display: inline-block;">
                                    <form action="{{ route('items.delete', $item->id) }}" method="post" onsubmit="return confirm('本当に削除しますか？')">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-danger" type="submit">削除</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    `;

            });

            searchResults.innerHTML = html;
        })
        .catch(error => {
            console.error('エラー:', error);
        });
        }

        // 検索クリア
        function showAllItems() {
            // キーワード入力欄をクリア
            const keywordInput = document.getElementById('keyword-input');
            keywordInput.value = '';

            // カラムを初期状態に戻す
            const columnSelect = document.getElementById('column-select');
            columnSelect.selectedIndex = 0;

            // 全ての商品を表示
            fetch(`/all-items`)
                .then(response => response.json())
                .then(data => {
                    const searchResults = document.getElementById('search-results');
                    let html = '';

                    data.items.forEach(item => {
                        html += `
                            <tr>
                                <td>${item.id}</td>
                                <td>${item.name}</td>
                                <td>${item.type}</td>
                                <td>${item.detail}</td>
                                <td>${item.stock}</td>
                                <td>
                                    <input type="number" class="form-control stock-input" value="${item.stock}" data-item-id="${item.id}">
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="updateStock('${item.id}')">変更</button>
                                    <div style="display: inline-block;">
                                    <form action="{{ route('items.delete', $item->id) }}" method="post" onsubmit="return confirm('本当に削除しますか？')">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-danger" type="submit">削除</button>
                                    </form>
                                </div>
                                </td>
                            </tr>
                        `;
                    });

                    searchResults.innerHTML = html;
                })
                .catch(error => {
                    console.error('エラー:', error);
                });
        }
    </script>
@stop
