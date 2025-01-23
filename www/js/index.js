let error = document.getElementById("error2")

error.hidden = true

let button = document.getElementById("button")
let testo = document.getElementById("input")

button.addEventListener("click", function(event){
    if(testo.value.trim = ""){
        event.preventDefault()
        error.hidden = false
    }
        

})