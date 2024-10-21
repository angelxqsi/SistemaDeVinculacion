document.getElementById('toggleInfo').addEventListener('click', function() {
    var hiddenInfo = document.getElementById('informacion-oculta');
    var toggleButton = document.getElementById('toggleInfo');
    if (hiddenInfo.style.display === "none") {
        hiddenInfo.style.display = "block";
        toggleButton.textContent = "Ver menos";
    } else {
        hiddenInfo.style.display = "none";
        toggleButton.textContent = "Ver más";
    }
});
