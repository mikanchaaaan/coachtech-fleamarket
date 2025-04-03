window.onload = function() {
    updateDisplay();

    const selectElement = document.getElementById("payment");
    selectElement.addEventListener("change", updateDisplay);
};

function updateDisplay() {
    const selectElement = document.getElementById("payment");
    const selectedValue = selectElement.options[selectElement.selectedIndex].text;

    const displayElement = document.getElementById("display");

    if (displayElement.firstChild) {
        displayElement.firstChild.nodeValue = selectedValue;
    } else {
        displayElement.appendChild(document.createTextNode(selectedValue));
    }
}