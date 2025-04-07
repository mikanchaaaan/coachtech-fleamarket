document.getElementById('image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewDiv = document.querySelector('.image__preview');
    const button = document.querySelector('.image__select--button');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewDiv.innerHTML = `<img src="${e.target.result}" alt="選択された画像">`;
            previewDiv.style.display = 'block';
            button.style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        previewDiv.innerHTML = '';
        previewDiv.style.display = 'none';
        button.style.display = 'block';
    }
});