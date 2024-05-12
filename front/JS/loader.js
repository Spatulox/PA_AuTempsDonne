
function startLoading(){
    const overlay = document.getElementById('overlay');
    overlay.style.opacity = '0.8';
    overlay.style.display = 'flex';
    overlay.style.zIndex = '10000';
}

function stopLoading(){
    const overlay = document.getElementById('overlay');
    overlay.style.opacity = '0';
    overlay.style.display = 'none';
    overlay.style.zIndex = '-1';
}
