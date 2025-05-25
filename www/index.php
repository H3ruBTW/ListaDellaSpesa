<?php
session_start();
$html = "";

if($_SERVER['REQUEST_METHOD']=="GET"){

    if(isset($_GET['error'])){
        $error = htmlspecialchars($_GET['error']);
        $html = "<p id=\"error\">$error</p>";
    } 

    if(isset($_GET['remove'])){
        deleteSearchHistory();
    }
}

function deleteSearchHistory(){
    session_unset();
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$results = "";

if(!isset($_SESSION['last_research'])){
    $_SESSION['last_research'] = array();
} else {
    $i=0;

    foreach($_SESSION['last_research'] as $output){
        if($i<10){

            $results .= "<a href=\"scansione_prova.php?barcode=" . $output . "\"><button id=\"ris\">";

            $url = "https://world.openfoodfacts.org/api/v0/product/" . $output .".json";

            // Scarico i dati JSON dall'API
            $json = file_get_contents($url);

            if ($json === false) {
                $results .= $output . "<br>";
                $results .= "</button></a>";
                continue;
            }

            // Decodifico il JSON in array PHP
            $data = json_decode($json, true);

            if ($data === null) {
                $results .= $output . "<br>";
                $results .= "</button></a>";
                continue;
            }

            //AVVERRA RICERCA
            if ($data['status'] == 1){
                $product = $data['product'];

                $imageUrl = $product['image_url'] ?? null;

                if ($imageUrl) {
                    $results .= "<br><img src=\"$imageUrl\" alt=\"Immagine prodotto\" style=\"max-width:50px;\">";
                }

                $nome = strtoupper($product['product_name']) ?? 'Nome non disponibile';
                $results .= "<br><p>" . $nome . "</p>";
            } 
            $results .= "</button></a><br>";
        } else {
            break;
        }
        
    }
}
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/index.css">
        <script src="https://unpkg.com/@ericblade/quagga2/dist/quagga.min.js"></script>
        <script src="js/camera.js" defer></script>
        <title>Scansione</title>
    </head>
    <body>
        <div id="header">
            <center>
                <h1>SCANSIONE</h1>
            </center>
        </div>
        
        <div id="content">
            <center>
                <p>
                INSERIRE IL CODICE A BARRE<br>
                QUI SOTTO
                </p>
                <?php echo $html ?>
                <form action="scansione_prova.php" method="get">
                    <input id="input" type="text" name="barcode" placeholder="ES.: 2387456723563" style="border-radius:10px"><br>
                    <input id="button" type="submit" value="INVIA" style="border-radius:10px">
                </form>
                <br>
                <button id="button" style="border-radius:10px"><img src="img/foto.png"></button><br><br>
                <div id="video"></div>
                <p>
                <span style="font-size: 30px; font-weight:bold;">RISULTATI PRECEDENTI</span> <br><br>
                <?= $results ?>
                <br><a href="?remove=1">DELETE SEARCH HISTORY</a>
                </p>
            </center>
        </div>
    </body>
</html>