<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\ItemHistory;

class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 商品一覧
     */
    public function index()
    {
        // 商品一覧取得
        $items = Item
            ::where('items.status', 'active')
            ->select()
            ->get();

        return view('item.index', compact('items'));    
    }

    /**
     * 商品登録
     */
    public function add(Request $request)
    {
        // POSTリクエストのとき
        if ($request->isMethod('post')) {
            // バリデーション
            $this->validate($request, [
                'name' => 'required|max:100',
                'stock' => 'required|integer|min:0',
            ]);
    
            // 商品登録
            $item = Item::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'type' => $request->type,
                'detail' => $request->detail,
                'stock' => $request->stock,
            ]);
    
            // 履歴を保存
            ItemHistory::create([
                'item_id' => $item->id,
                'name' => $item->name,
                'action' => '登録',
                'previous_quantity' => null,
                'new_quantity' => $item->stock,
                'changed_at' => now(),
            ]);
    
            return redirect('/items');
        }
    
        return view('item.add');

    }

    /**
     * 在庫数変更
     */
    public function updateStock(Request $request, $itemId)
    {
        $stock = $request->input('stock');
    
        // 商品を取得して在庫数を更新
        $item = Item::find($itemId);
        if ($item) {
            // 変更前の在庫数を取得
            $previousQuantity = $item->stock;
    
            // 在庫数を更新
            $item->stock = $stock;
            $item->save();
    
            // 履歴を保存
            ItemHistory::create([
                'item_id' => $item->id,
                'name' => $item->name,
                'action' => '個数変更',
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $stock,
                'changed_at' => now(),
            ]);
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
    }


    /**
     * 削除
     */
    public function deleteItem($itemId)
    {
        $item = Item::find($itemId);
        // アイテムを削除する前に履歴を保存
        ItemHistory::create([
            'item_id' =>$item->id,
            'name' => $item->name,
            'action' => '削除',
            'previous_quantity' => $item->stock,
            'new_quantity' => null,
            'changed_at' => now(),
        ]);
    
        $item->delete();
        return redirect('items');
    }

    
    /**
     * 検索
     */

    public function search(Request $request)
{
    $column = $request->input('column');
    $keyword = $request->input('keyword');

    // 指定された列とキーワードでデータを検索するクエリを作成
    $query = Item::query();

    if ($column === 'name') {
        $query->where('name', 'like', "%$keyword%");
    } elseif ($column === 'type') {
        $query->where('type', 'like', "%$keyword%");
    }

    $items = $query->get();

    return response()->json(['items' => $items]);
}


    /**
     * 検索クリア
     */

public function getAllItems()
{
    $items = Item::all();

    return response()->json(['items' => $items]);
}



public function historys()
{
    // 商品履歴レコードを日時の降順で取得します
    $histories = ItemHistory::orderByDesc('changed_at')->get();

    // 履歴レコードを表示するビューを返します
    return view('item.historys', compact('histories'));
}

}