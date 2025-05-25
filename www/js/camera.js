let scannerStarted = false;
let video = document.getElementById("video")

document.querySelector('button#button').addEventListener('click', () => {
  if (scannerStarted) return;

  scannerStarted = true;

  video.style.display = "block"

  Quagga.init({
    inputStream: {
      type: "LiveStream",
      target: document.querySelector('#video'),
      constraints: {
        facingMode: "environment"
      }
    },
    decoder: {
      readers: [
          "ean_reader",       // EAN-13
          "ean_8_reader",     // EAN-8
          "upc_reader",       // UPC-A
          "upc_e_reader",     // UPC-E
          "code_128_reader",  // Code 128
          "code_39_reader"   // Code 39]
      ]
    }
  }, function (err) {
    if (err) {
      console.error("Errore durante l'inizializzazione:", err);
      return;
    }
    Quagga.start();
  });

  Quagga.onDetected(function (data) {
    const code = data.codeResult.code;
    console.log("Codice rilevato:", code);

    Quagga.stop();
    scannerStarted = false;

    // Reindirizza alla pagina PHP
    window.location.href = `scansione_prova.php?barcode=${encodeURIComponent(code)}`;
  });
});
