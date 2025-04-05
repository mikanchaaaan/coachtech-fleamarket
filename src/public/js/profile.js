// 未読メッセージ数を取得してDOMに反映する関数
function fetchUnreadMessageCounts() {
    fetch('/api/unread-message-count')
        .then(response => response.json())
        .then(data => {
            // totalUnreadCountを表示
            const totalUnreadCountElement = document.querySelector('.transaction-exhibition .unread-count-total');
            if (totalUnreadCountElement) {
                totalUnreadCountElement.textContent = data.totalUnreadCount;
                // 未読メッセージが1以上の場合に表示
                if (data.totalUnreadCount > 0) {
                    totalUnreadCountElement.style.display = 'inline-block';  // 表示
                } else {
                    totalUnreadCountElement.style.display = 'none';  // 非表示
                }
            }

            // 各商品の未読メッセージ数を反映
            const exhibitions = data.exhibitions;
            console.log(data); // デバッグ用
            exhibitions.forEach(exhibition => {
                const exhibitionElement = document.querySelector(`[data-exhibition-id="${exhibition.id}"]`);
                console.log(`Exhibition ID: ${exhibition.id}`);
                console.log('Element found:', exhibitionElement);
                console.log('Unread count:', exhibition.unread_messages_count);

                if (exhibitionElement) {
                    const unreadCountElement = exhibitionElement.querySelector('.unread-count');

                    // unread_messages_count が 1以上の場合は表示、それ以外は非表示
                    if (exhibition.unread_messages_count > 0) {
                        unreadCountElement.textContent = exhibition.unread_messages_count;
                        unreadCountElement.style.display = 'inline-block'; // 表示
                    } else {
                        unreadCountElement.style.display = 'none'; // 非表示
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching unread message count:', error);
        });
}

// 初回読み込みとブラウザ復帰時に呼び出すように設定
document.addEventListener("DOMContentLoaded", function () {
    fetchUnreadMessageCounts();

// ブラウザに戻ってきたときにも再取得
document.addEventListener("visibilitychange", function () {
    if (document.visibilityState === "visible") {
        fetchUnreadMessageCounts();
    }
    });
});
