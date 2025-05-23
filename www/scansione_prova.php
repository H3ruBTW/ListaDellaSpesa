<?php
session_start();
$html = "";

if(!empty($_GET['barcode'])){
    $BARCODE = htmlspecialchars($_GET['barcode']);

    $url = "https://world.openfoodfacts.org/api/v0/product/" . $BARCODE ." .json";

    // Scarico i dati JSON dall'API
    $json = file_get_contents($url);

    if ($json === false) {
        $url = "index.php?error=Errore di retrive dei dati";
        header("Location: $url");
        exit();
    }

    // Decodifico il JSON in array PHP
    $data = json_decode($json, true);

    if ($data === null) {
        $url = "index.php?error=Errore di retrive dei dati";
        header("Location: $url");
        exit();
    }

    //AVVERRA RICERCA
    if ($data['status'] == 1){
        if(($key = array_search($BARCODE, $_SESSION['last_research'])) !== false) {
            unset($_SESSION['last_research'][$key]);
            // Riordino gli indici
            $array = array_values($_SESSION['last_research']);
        }

        array_unshift($_SESSION['last_research'], $BARCODE);
    } else {
        $url = "index.php?error=Prodotto non trovato";
        header("Location: $url");
        exit();
    }
        
} else {
    $url = "index.php?error=Non è stato inserito il codice a barre";
    header("Location: $url");
    exit();
}

if(isset($_POST['page'])){
    $PAGE = $_POST['page'];
} else {
    $PAGE = "PANORAMICA";
}

$product = $data['product'];

$html = "<p>CODICE PRODOTTO:<br>";

$barcode_url = "https://barcode.tec-it.com/barcode.ashx?data=" . $BARCODE . " &code=Code128&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=96&imagetype=png";

$html .= "<img src='" . $barcode_url . "' alt='Barcode'><br>";

switch ($PAGE) {
    case 'PANORAMICA':
        $name = $product['product_name'] ?? 'Nome non disponibile';
        $brand = $product['brands'] ?? 'Marca sconosciuta';
        $quantity = $product['quantity'] ?? 'Quantità non specificata';
        $packaging = $product['packaging'] ?? 'Confezione sconosciuta';
        $categories = $product['categories'] ?? 'Categoria non definita';
        $nova_group = $product['nova_group'] ?? '';
        $nova_img = $product['nova_group'] ? "https://static.openfoodfacts.org/images/misc/nova-group-{$nova_group}.svg" : '';
    
        switch($nova_group){
            case "1":
                $phraseN = "Questo prodotto è naturale o minimamente trasformato. È composto principalmente da ingredienti semplici, senza alterazioni industriali significative.";
                break;
            case "2":
                $phraseN = "Questo prodotto contiene ingredienti trasformati usati in cucina, come oli, zuccheri o sale. È spesso usato per preparare piatti casalinghi.";
                break;
            case "3":
                $phraseN = "Questo alimento è stato trasformato attraverso processi industriali moderati. Può contenere zuccheri, oli, sale e additivi semplici.";
                break;
            case "4":
                $phraseN = "Questo prodotto è classificato come ultra-trasformato. Contiene ingredienti industriali e additivi non usati comunemente in cucina, come aromi, coloranti e emulsionanti.";
                break;
            default:
                $phraseN = "Il grado di processazione (NOVA) non è disponibile per questo prodotto.";
        }

        $html .= 
        <<<COD
        <p><span style="font-size: 30px; font-weight:bold;">PAGINA DI PANORAMICA</span></p>
        <div id="info">
            <p style="line-height: 1.5;"><b>Nome del Prodotto:</b> {$name}<br><br>
            <b>Brand:</b> {$brand}<br><br>
            <b>Peso/Quantità:</b> {$quantity}<br><br>
            <b>Confezionamento:</b> {$packaging}<br><br>
            <b>Categorie Ulteriori:</b> {$categories}<br><br>
            <b>PUNTEGGIO NOVA</b><br>
            <img src="{$nova_img}"><br>
            <em>{$phraseN}</em>
            </p>
        </div>
        COD;
        break;
    case 'VALORI NUTRIZIONALI':
        $html .= 
        <<<COD
        <p><span style="font-size: 30px; font-weight:bold;">PAGINA DEI VALORI NUTRIZIONALI</span></p>
        COD;
        break;
    case 'INGREDIENTI':
        $html .= 
        <<<COD
        <p><span style="font-size: 30px; font-weight:bold;">PAGINA DEGLI INGREDENTI</span></p>
        COD;
        break;
}

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/end.css">
        <title>Scansione</title>
    </head>
    <body>
        <div id="header">
            <center>
                <a href="index.php"><h1>PRODOTTO</h1></a>
            </center>
        </div>
        <div id="pages">
            <nav>
                <ul>
                    <li>
                        <!-- MOSTRA GLI ASPETTI PRINCIPALI DEL PRODOTTO CON I VOTI -->
                        <form action=<?php echo $_SERVER['PHP_SELF'] . "?barcode=" . $BARCODE ?> method="post">
                            <input type="text" value="PANORAMICA" name="page" hidden>
                            <input id="button" type="submit" value="PANORAMICA">
                        </form>
                    </li>
                    <li>
                        <!-- MOSTRA I VALORI NUTRIZIONALI -->
                        <form action=<?php echo $_SERVER['PHP_SELF'] . "?barcode=" . $BARCODE ?> method="post">
                            <input type="text" value="VALORI NUTRIZIONALI" name="page" hidden>
                            <input id="button" type="submit" value="VALORI NUTRIZIONALI">
                        </form>
                    </li>
                    <li>
                        <!-- MOSTRA GLI INGREDIENTI DEL PRODOTTO -->
                        <form action=<?php echo $_SERVER['PHP_SELF'] . "?barcode=" . $BARCODE ?> method="post">
                            <input type="text" value="INGREDIENTI" name="page" hidden>
                            <input id="button" type="submit" value="INGREDIENTI">
                        </form>
                    </li>
                </ul>
            </nav> 
        </div>
        <div id="content">
            <center>
                <?php echo $html ?>
            </center>
        </div>
    </body>
</html>