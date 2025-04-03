document.addEventListener("DOMContentLoaded", function () {
    console.log("JavaScript is loaded"); // このログが表示されるか確認

    // 画像プレビュー表示の処理を追加
    const imageInput = document.getElementById('image');
    console.log(imageInput); // これが null ならエラー

    if (imageInput) {
        imageInput.addEventListener('change', function(event) {
            console.log("Image input changed");

            const file = event.target.files[0];
            const previewContainer = document.getElementById('image-preview-container');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    console.log("FileReader loaded", e.target.result);

                    // プレビュー画像をクリアしてから新しい画像を追加
                    previewContainer.innerHTML = '';

                    // 画像を「画像を追加」の右側に表示
                    const imgElement = document.createElement('img');
                    imgElement.src = e.target.result;
                    imgElement.alt = "選択された画像";
                    imgElement.style.maxWidth = "100px";
                    imgElement.style.maxHeight = "100px";
                    imgElement.style.border = "1px solid #ccc";
                    imgElement.style.borderRadius = "5px";
                    imgElement.style.marginLeft = "10px"; // 「画像を追加」との間隔を確保

                    previewContainer.appendChild(imgElement);
                };
                reader.readAsDataURL(file);
            } else {
                console.log("No file selected");
                previewContainer.innerHTML = '';
            }
        });
    } else {
        console.error("Element #image not found");
    }

    // 送信ボタンの処理
    document.querySelector(".send").addEventListener("click", function() {
        console.log("送信ボタンがクリックされました！");

        let messageContent = document.querySelector(".chat-input input").value;
        let receiverId = document.querySelector(".messages").dataset.receiver;
        let itemId = document.querySelector(".messages").dataset.itemId;
        let imageFile = document.getElementById("image").files[0];

        console.log("選択された画像:", imageFile);

        if (messageContent.trim() === "" && !imageFile) {
            console.log("メッセージも画像も空です");
            return;
        }

        let formData = new FormData();
        formData.append("message", messageContent);
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
        .then(response => response.json())
        .then(data => {
            console.log("サーバーのレスポンス:", data);
            if (data.success) {
                console.log("メッセージが送信されました！");

                // 送信したメッセージを即時表示する
                addMessageToChat(data.message);

                // 入力欄をクリア
                document.querySelector(".chat-input input").value = "";
                document.getElementById("image").value = ""; // ファイル選択をリセット
                document.getElementById("image-preview-container").innerHTML = "";
            } else {
                console.log("メッセージ送信に失敗しました");
            }
        })
        .catch(error => console.error("エラー:", error));
    });

    // 送信したメッセージを追加する関数
    function addMessageToChat(message) {
        console.log("追加するメッセージ:", message);

        let chatBox = document.querySelector(".messages");

        let messageHtml = '';

        // 相手からのメッセージか、自分からのメッセージかで表示を分ける
        if (message.sender_id === authUserId) {
            // 自分が送信したメッセージ
            messageHtml = `
                <div class="sent">
                    <div class="message">
                        <div class="sender-name">${message.sender_name}</div>
                        <div class="sender-icon">
                            ${message.sender_image ? `<img src="${message.sender_image}" alt="プロフィール画像" class="icon-img">` : `<div class="profile-image__none"></div>`}
                        </div>
                    </div>
                    <div class="message-content">${message.message}</div>
                    <div class="message-image">
                        ${message.image ? `<img src="${message.image}" alt="送信画像" class="message-img">` : ''}
                    </div>
                    <div class="options">
                        <button>編集</button>
                        <button>削除</button>
                    </div>
                </div>
            `;
        } else {
            // 相手からのメッセージ
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
                <div class="message-image">
                    ${message.image ? `<img src="${message.image}" alt="送信画像" class="message-img">` : ''}
                </div>
            `;
        }

        chatBox.insertAdjacentHTML("beforeend", messageHtml);
        chatBox.scrollTop = chatBox.scrollHeight; // スクロールを最新メッセージに移動
    }
});
