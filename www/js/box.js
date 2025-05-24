let title = document.getElementById("title")

if(title.innerText === "PAGINA DI PANORAMICA"){

    let infoN = document.getElementById("infoN")
    let infoNS = document.getElementById("infoNS")

    let boxN = document.getElementById("boxN")
    let boxNS = document.getElementById("boxNS")

    function showBox(event, box){
        box.classList.add('visible');
        box.style.top = (event.clientY + 10) + "px";
        box.style.left = (event.clientX + 10) + "px";
    }

    function hideBox(box){
        box.classList.remove('visible');
    }

    infoN.addEventListener("mousemove", function(e){showBox(e, boxN)})
    infoN.addEventListener("mouseleave", function(){hideBox(boxN)})

    infoNS.addEventListener("mousemove", function(e){showBox(e, boxNS)})
    infoNS.addEventListener("mouseleave", function(e){hideBox(boxNS)})
}
    
