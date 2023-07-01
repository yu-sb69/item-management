require('./bootstrap');

function updateStock(itemId, stock) {
    // POSTリクエストを送信
    axios.post('/items/update-stock', { itemId: itemId, stock: stock })
        .then(response => {
        // 成功時の処理
        console.log(response.data);
        })
        .catch(error => {
        // エラー時の処理
        console.error(error);
        });
    }
