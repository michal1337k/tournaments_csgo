function scr1(){
    const checkbox = document.getElementById('check1')
    checkbox.addEventListener('change', (event) => {
    if (event.currentTarget.checked) {
        document.getElementById('edit1').disabled = false;
    } else {
        document.getElementById('edit1').disabled = true;
    }
})
}