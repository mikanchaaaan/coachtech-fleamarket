document.addEventListener('DOMContentLoaded', function () {
    // モーダルを開くボタン
    const openModalButton = document.getElementById('openModalButton');
    // モーダル本体
    const modal = document.getElementById('ratingModal');

    // PHPからJavaScriptにデータを渡す
    // HTML要素のdata属性から値を取得
    const transactionData = document.getElementById('transactionData');
    const isSeller = transactionData.dataset.isSeller === 'true';
    const isTransactionComplete = transactionData.dataset.isComplete === 'true';
    const isReviewed = transactionData.dataset.isReviewed === 'true';

    // 出品者が評価していない場合、モーダルを自動表示
    if (isSeller && isTransactionComplete && !isReviewed) {
        modal.style.display = 'block';  // モーダルを表示
    }

    // 購入者はボタンがクリックされたらモーダルを表示
    if (openModalButton) {
        openModalButton.addEventListener('click', function () {
            modal.style.display = 'block';  // モーダルを表示
        });
    }

    // モーダル外がクリックされたらモーダルを非表示
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // 評価の登録
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');  // Hidden input, for form submission

    stars.forEach(star => {
        star.addEventListener('click', function () {
            const value = this.getAttribute('data-value');

            // 星を選択する
            stars.forEach(star => {
                if (star.getAttribute('data-value') <= value) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });

            // 隠し入力フィールドに選択された評価をセット
            ratingInput.value = value;
        });
    });
});
