let giàRilevato = false;

function avviaScanner() {
    giàRilevato = false;
    document.getElementById("scanner").style.display = "block";

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#scanner'),
            constraints: {
            facingMode: "environment"
            }
        },
        decoder: {
            readers: ["ean_reader", "code_128_reader", "code_39_reader"]
        }
    }, function (err) {
        if (err) {
            console.error(err);
            return;
        }
        Quagga.start();
        });

        Quagga.onDetected(function (data) {
        if (giàRilevato) return;

        giàRilevato = true;
        const codice = data.codeResult.code;
        document.getElementById("result").textContent = codice;
        Quagga.stop();

        window.location.href = `scansione_prova.php?barcode=${encodeURIComponent(codice)}`;
    });
}

let button = document.querySelector("button#button")

button.addEventListener("click", avviaScanner)
