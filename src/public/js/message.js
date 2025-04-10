// 編集と削除ボタンの設定
function attachEditDeleteListeners() {

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {

            let messageDiv = this.closest('.sent').querySelector('.message-content');
            let messageId = this.getAttribute('data-message-id');
            let originalContent = messageDiv.innerText;
            let input = document.createElement('textarea');
            input.value = originalContent;
            input.classList.add('edit-input');
            messageDiv.innerHTML = '';
            messageDiv.appendChild(input);
            input.focus();

            input.addEventListener('blur', function () {
                let newContent = input.value.trim();
                if (newContent === originalContent) {
                    messageDiv.innerHTML = originalContent;
                    return;
                }

                fetch(`/message/${messageId}/edit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ content: newContent })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            messageDiv.innerHTML = newContent;
                        } else {
                            messageDiv.innerHTML = originalContent;
                            alert("編集に失敗しました");
                        }
                    })
                    .catch(() => {
                        messageDiv.innerHTML = originalContent;
                        alert("エラーが発生しました");
                    });
            });

            input.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' && !event.shiftKey) {
                    event.preventDefault();
                    input.blur();
                }
            });
        });
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            let messageId = this.getAttribute('data-message-id');

            if (confirm('本当に削除しますか？')) {
                fetch(`/message/${messageId}/delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('.sent').remove();
                        } else {
                            alert("削除に失敗しました");
                        }
                    });
            }
        });
    });
}

// メッセージを追加
function addMessageToChat(message) {
    let chatBox = document.querySelector(".messages");
    let messageHtml = '';

    if (message.sender_id === authUserId) {
        messageHtml = `
        <div class="sent">
            <div class="message">
                <div class="sender-name">${message.sender_name}</div>
                <div class="sender-icon">
                    ${message.sender_image ? `<img src="${message.sender_image}" alt="プロフィール画像" class="icon-img">` : `<div class="profile-image__none"></div>`}
                </div>
            </div>
            <div class="message-content">${message.message}</div>
            ${message.image ? `<div class="message-image"><img src="${message.image}" alt="送信画像" class="message-img"></div>` : ''}
            <div class="options">
                <button class="edit-btn" data-message-id="${message.id}">編集</button>
                <button class="delete-btn" data-message-id="${message.id}">削除</button>
            </div>
        </div>`;
    } else {
        messageHtml = `
        <div class="received">
            <div class="message">
                <div class="receiver-icon">
                    ${message.receiver_image ? `<img src="${message.receiver_image}" alt="プロフィール画像" class="icon-img">` : `<div class="profile-image__none"></div>`}
                </div>
                <div class="receiver-name">${message.receiver_name}</div>
            </div>
        </div>
        <div class="message-content">${message.message}</div>
        ${message.image ? `<div class="message-image"><img src="${message.image}" alt="送信画像" class="message-img"></div>` : ''}`;
    }

    chatBox.insertAdjacentHTML("beforeend", messageHtml);
    chatBox.scrollTop = chatBox.scrollHeight;
    attachEditDeleteListeners();
}

// チャット画面での処理
document.addEventListener("DOMContentLoaded", function () {
    console.log('DOM fully loaded');

    // 未読メッセージを既読に変更
    const messageIds = document.querySelectorAll('.msg');

    messageIds.forEach(messageElement => {
        const id = messageElement.dataset.messageId;
        const isRead = messageElement.dataset.isRead;
        const senderId = messageElement.dataset.senderId;
        const currentUserId = document.querySelector('meta[name="current-user-id"]').content;

        if (isRead == 0 && senderId !== currentUserId) {
            fetch(`/message/${id}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageElement.dataset.isRead = 1;
                    messageElement.classList.add('read');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });

    // 画像プレビュー
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('image-preview-container');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewContainer.innerHTML = '';
                    const imgElement = document.createElement('img');
                    imgElement.src = e.target.result;
                    imgElement.alt = "選択された画像";
                    imgElement.style.maxWidth = "100px";
                    imgElement.style.maxHeight = "100px";
                    imgElement.style.border = "1px solid #ccc";
                    imgElement.style.borderRadius = "5px";
                    imgElement.style.marginLeft = "10px";
                    previewContainer.appendChild(imgElement);
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.innerHTML = '';
            }
        });
    }

    // 入力内容を保存
    const messageInput = document.querySelector(".chat-input input");
    if (messageInput) {
        messageInput.addEventListener("input", function () {
            localStorage.setItem("messageContent", messageInput.value);
        });

        const savedMessage = localStorage.getItem("messageContent");
        if (savedMessage) {
            messageInput.value = savedMessage;
        }
    }

    // メッセージ送信処理
    document.querySelector(".send").addEventListener("click", function () {
        let messageContent = document.querySelector(".chat-input input").value;
        let receiverId = document.querySelector(".messages").dataset.receiver;
        let itemId = document.querySelector(".messages").dataset.itemId;
        let imageFile = document.getElementById("image").files[0];
        let formData = new FormData();

        formData.append("content", messageContent);
        formData.append("receiver_id", receiverId);
        formData.append("item_id", itemId);
        if (imageFile) {
            formData.append("image", imageFile);
        }

        fetch("/message/send", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();

            if (response.ok && data.success) {
                document.querySelector(".chat-input input").value = "";
                document.getElementById("image").value = "";
                document.getElementById("image-preview-container").innerHTML = "";
                addMessageToChat(data.message);
                document.querySelector(".form__error").innerHTML = "";
                localStorage.removeItem("messageContent");
            } else if (response.status === 422) {
                const errorBox = document.querySelector(".form__error");
                errorBox.innerHTML = "";
                Object.values(data.errors).forEach(messages => {
                    messages.forEach(msg => {
                        const p = document.createElement("p");
                        p.textContent = msg;
                        errorBox.appendChild(p);
                    });
                });
            }
        })
        .catch(error => {
            console.error("通信エラー:", error);
        });
    });

    attachEditDeleteListeners();
});