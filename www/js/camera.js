const codeReader = new ZXing.BrowserBarcodeReader();
const videoElement = document.getElementById('video');
let scanning = false;

document.querySelector('button#button').addEventListener('click', () => {
  if (scanning) return;
  scanning = true;

  videoElement.style.display = "block"

  codeReader.decodeOnceFromVideoDevice(null, videoElement)
    .then(result => {
      console.log("Codice rilevato:", result.text);
      scanning = false;

      // Stop webcam
      if (videoElement.srcObject) {
        videoElement.srcObject.getTracks().forEach(track => track.stop());
      }

      // Redirect con il codice rilevato
      window.location.href = `scansione_prova.php?barcode=${encodeURIComponent(result.text)}`;
    })
    .catch(err => {
      console.error("Errore o nessun codice rilevato:", err);
      scanning = false;
    });
});

