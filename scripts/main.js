function openNav() {
  document.getElementById("sideNav").style.height = "100%";
  setTimeout(function() {
      document.getElementById("open").style.display = "none";
  }, 50)
}

function closeNav() {
  document.getElementById("sideNav").style.height = "0px";
  setTimeout(function() {
      document.getElementById("open").style.display = "inline";
  }, 250);
}

function loadImg() {
    document.getElementById('loadCircleDiv').style.display = "inline-block";
}


function addToCart(id) {
  var e = document.getElementById("sizeOptions");
  var strUser = e.options[e.selectedIndex].value;
  if (window.XMLHttpRequest){
      xmlhttp=new XMLHttpRequest();
  } else {
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState===4 && xmlhttp.status===200) {
            var json = JSON.parse(xmlhttp.responseText);
            if(json.error != null) {
                error(json.error);
            } else {
                itemMessage();
            }
        }
    };
  xmlhttp.open("GET", "/api/Cart.php?id=" + id + "&size=" + strUser + '&color=' + document.querySelector('input[name="color"]:checked').value + '&function=add', true);
  xmlhttp.send();
}

function toggleExtraBoxes(el) {
    if(el.parentElement.style.maxHeight == '200px') {
        el.style.display = "none";
        el.parentElement.children[1].style.display = "inline";
        el.parentElement.style.maxHeight = '60px';
    } else {
        el.style.display = "none";
        el.parentElement.children[2].style.display = "inline";
        el.parentElement.style.maxHeight = '200px';
    }
}