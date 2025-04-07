// モーダル管理用
document.addEventListener('DOMContentLoaded', function () {
    const openModalButton = document.getElementById('openModalButton');
    const modal = document.getElementById('ratingModal');
    const transactionData = document.getElementById('transactionData');
    const isSeller = transactionData.dataset.isSeller === 'true';
    const isTransactionComplete = transactionData.dataset.isComplete === 'true';
    const isReviewed = transactionData.dataset.isReviewed === 'true';

    if (isSeller && isTransactionComplete && !isReviewed) {
        modal.style.display = 'block';
    }

    if (openModalButton) {
        openModalButton.addEventListener('click', function () {
            modal.style.display = 'block';
        });
    }

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
            stars.forEach(star => {
                if (star.getAttribute('data-value') <= value) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
            ratingInput.value = value;
        });
    });

    const form = document.getElementById('ratingForm');
    form.addEventListener('submit', function () {
        if (!ratingInput.value) {
            ratingInput.value = 0;
        }
    });
});
