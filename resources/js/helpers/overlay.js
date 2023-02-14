function overlay() {
    var overlay = document.getElementById('overlay');
    // check if overlay is already displayed
    if (overlay.style.display === 'block') {
        // if yes, hide it
        overlay.style.display = 'none';
    } else {
        // if not, display it
        overlay.style.display = 'block';
    }
}

export default overlay;
