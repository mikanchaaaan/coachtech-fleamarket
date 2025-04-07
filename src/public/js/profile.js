// 未読メッセージ数を取得して反映する関数
function fetchUnreadMessageCounts() {
    fetch('/api/unread-message-count')
        .then(response => response.json())
        .then(data => {
            const totalUnreadCountElement = document.querySelector('.transaction-exhibition .unread-count-total');
            if (totalUnreadCountElement) {
                totalUnreadCountElement.textContent = data.totalUnreadCount;
                if (data.totalUnreadCount > 0) {
                    totalUnreadCountElement.style.display = 'inline-block';
                } else {
                    totalUnreadCountElement.style.display = 'none';
                }
            }
            const exhibitions = data.exhibitions;
            exhibitions.forEach(exhibition => {
                const exhibitionElement = document.querySelector(`[data-exhibition-id="${exhibition.id}"]`);

                if (exhibitionElement) {
                    const unreadCountElement = exhibitionElement.querySelector('.unread-count');
                    if (exhibition.unread_messages_count > 0) {
                        unreadCountElement.textContent = exhibition.unread_messages_count;
                        unreadCountElement.style.display = 'inline-block';
                    } else {
                        unreadCountElement.style.display = 'none';
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching unread message count:', error);
        });
}

document.addEventListener("DOMContentLoaded", function () {
    fetchUnreadMessageCounts();

document.addEventListener("visibilitychange", function () {
    if (document.visibilityState === "visible") {
        fetchUnreadMessageCounts();
    }
    });
});
