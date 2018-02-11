function updateSelect(value, el) {
    var divs = document.getElementsByClassName("item-box");
    for(var i = 0; i < divs.length; i++){
        if(el.value == "Size") {
            divs[i].style = "inline-block";
        } else {
            if(divs[i].dataset.size.includes(el.value)) {
                divs[i].style.display = "inline-block";
            } else {
                divs[i].style.display = "none";
            }
        }
    }
}
function changeImage(el) {
    document.getElementById('imageChecked').style.borderColor = "white";
    document.getElementById('imageChecked').id = "";
    el.style.borderColor = "#3F5765";
    el.id = "imageChecked";
    document.getElementById('mainImage').src = el.src;
}

function error(error) {
    var message = document.getElementById('message');
    message.innerHTML = "<p style='line-height: 75px; text-align: center'>" + error + "</p>";
    message.style.animation = "";
    message.style.webkitAnimation = "";
    setTimeout(function() {
        message.style.animation = "message 5s linear 0s";
        message.style.webkitAnimation = "message 5s linear 0s";
    }, 20);
}