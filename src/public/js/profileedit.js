    document.getElementById('image').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const profileImage = document.querySelector('.profile__image');
        const noImageMessage = document.querySelector('.image__none');
        const previewDiv = document.querySelector('.image__preview');

        while (previewDiv.firstChild) {
            previewDiv.removeChild(previewDiv.firstChild);
        }

        if (file) {
            if (profileImage) {
                profileImage.style.display = 'none';
            }

            if (noImageMessage) {
                noImageMessage.style.display = 'none';
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = '選択された画像';
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
                previewDiv.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            if (noImageMessage) {
                noImageMessage.style.display = 'block';
            }

            if (profileImage) {
                profileImage.style.display = 'block';
            }

            previewDiv.innerHTML = '';
        }
    });