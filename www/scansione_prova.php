<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$html = "";

if(!empty($_GET['barcode'])){
    $BARCODE = htmlspecialchars($_GET['barcode']);

    $url = "https://world.openfoodfacts.org/api/v0/product/" . $BARCODE .".json";

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

$imageUrl = $product['image_url'] ?? null;

$html = "<b>CODICE PRODOTTO:<br>";

$barcode_url = "https://barcode.tec-it.com/barcode.ashx?data=" . $BARCODE . " &code=Code128&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=96&imagetype=png";

$html .= "<img src='" . $barcode_url . "' alt='Barcode' style='border-radius: 10px'><br>IMMAGINE PRODOTTO:<br><img src='{$imageUrl}' width='100px' style='border-radius: 10px'></b>";

switch ($PAGE) {
    case 'PANORAMICA':
        $name = $product['product_name'] ?? 'Nome non disponibile';
        $brand = $product['brands'] ?? 'Marca sconosciuta';
        $quantity = $product['quantity'] ?? 'Quantità non specificata';
        $packaging = $product['packaging'] ?? 'Confezione sconosciuta';
        $categories = $product['categories'] ?? 'Categoria non definita';
        $nova_group = $product['nova_group'] ?? '';
        $nova_img = $product['nova_group'] ? "https://static.openfoodfacts.org/images/misc/nova-group-{$nova_group}.svg" : '';
        $nutriscore = strtolower($product['nutriscore_grade'] ?? '');
        $nutri_img = $nutriscore ? "https://static.openfoodfacts.org/images/misc/nutriscore-" . $nutriscore . ".svg" : "";
    
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

        switch ($nutriscore) {
            case 'a':
                $phraseNS = "Questo prodotto ha un'eccellente qualità nutrizionale (Nutri-Score A).";
                break;
            case 'b':
                $phraseNS = "Buona qualità nutrizionale secondo il Nutri-Score (B).";
                break;
            case 'c':
                $phraseNS = "Qualità nutrizionale intermedia (Nutri-Score C).";
                break;
            case 'd':
                $phraseNS = "Bassa qualità nutrizionale: consumare con moderazione (Nutri-Score D).";
                break;
            case 'e':
                $phraseNS = "Qualità nutrizionale molto bassa (Nutri-Score E). Limitare il consumo.";
                break;
            default:
                $phraseNS = "Nutri-Score non disponibile per questo prodotto.";
        }

        $html .= 
        <<<COD
        <p><span id="title" style="font-size: 30px; font-weight:bold;">PAGINA DI PANORAMICA</span></p>
        <div id="info">
            <p style="line-height: 1.5;"><b>Nome del Prodotto:</b> {$name}<br><br>
            <b>Brand:</b> {$brand}<br><br>
            <b>Peso/Quantità:</b> {$quantity}<br><br>
            <b>Confezionamento:</b> {$packaging}<br><br>
            <b>Categorie Ulteriori:</b> {$categories}<br><br>
            <b>PUNTEGGIO NOVA&emsp;</b><span id="infoN">?</span><br>
            <img src="{$nova_img}"><br>
            <em>{$phraseN}</em><br><br>
            <b>PUNTEGGIO NUTRISCORE&emsp;</b><span id="infoNS">?</span><br>
            <img src="{$nutri_img}"><br>
            <em>{$phraseNS}</em>
            </p>
        </div>
        COD;
        break;
    case 'VALORI NUTRIZIONALI':
        $nutri_img = $product['image_nutrition_url'];    

        $html .= 
        <<<COD
        <p><span id="title" style="font-size: 30px; font-weight:bold;">PAGINA DEI VALORI NUTRIZIONALI</span></p>
        <div id="info">
        COD;

        $html .= "<table><tr><th>Nutriente</th><th>Per 100g</th><th>Per Porzione Consigliata</th><th>Per confezione</th><th>Unità</th><tr>";

        $html .= RetriveValNut("energy", "Energia", $product);

        $html .= RetriveValNut("energy-kcal", "--->", $product);

        $html .= RetriveValNut("fat", "Grassi", $product);

        $html .= RetriveValNut("saturated-fat", "---> Di Cui Saturi", $product);

        $html .= RetriveValNut("carbohydrates", "Carboidrati", $product);
        
        $html .= RetriveValNut("sugars", "---> Di Cui Zuccheri", $product);
        
        $html .= RetriveValNut("proteins", "Proteine", $product);
        
        $html .= RetriveValNut("salt", "Sali", $product);
        
        $html .= RetriveValNut("fiber", "Fibre", $product);

        $html .= 
        <<<COD
        </table>
        <p><b>Etichetta</b><br>
        <img src='{$nutri_img}'><br>
        <b>Fattori Positivi</b><br><span style="color:#2ecc71">
        COD;

        $nutriments = $product['nutriments'];

        $fiber = $nutriments['fiber_100g'] ?? 0;

        if($fiber>=3)
            $html .= "Ottima fonte di fibre ({$fiber} per ogni 100 grammi)(CONSIGLIATA: >3g per 100g)<br>";
        
        $prot = $nutriments["proteins_100g"] ?? 0;
        
        if($prot>=10)
            $html .= "Ottima fonte di proteine ({$prot} per ogni 100 grammi)(CONSIGLIATA: >10g per 100g)<br>";
        
        $vitamine = [];

        foreach ($nutriments as $key => $value){
            if(is_numeric($value)){
                if($value > 0){
                    array_push($vitamine, "Vitamina " . substr($key, $pos1=strpos($key,"_")));
                }
            }
        }

        $html .= "</p></div>";
        break;
    case 'INGREDIENTI':
        $html .= 
        <<<COD
        <p><span id="title" style="font-size: 30px; font-weight:bold;">PAGINA DEGLI INGREDENTI</span></p>
        COD;
        break;
}

function RetriveValNut ($nutrient, $nutriente, $product){
    $nutriments = $product['nutriments'];
    $quantity = $product['quantity'] ?? 0;

    $per100g = $nutriments[$nutrient . '_100g'] ?? 'N/A';
    $perServing = $nutriments[$nutrient . '_serving'] ?? 'N/A';
    $perPackage = (is_numeric($quantity) && $per100g != "N/A") ? ($quantity/100)*$per100g : "N/A";
    $unit = $nutriments[$nutrient . '_unit'] ?? '';    
    return "<tr><td>" . htmlspecialchars($nutriente) . "</td><td>" . htmlspecialchars($per100g) . "</td><td>" . htmlspecialchars($perServing) . "</td><td>" . htmlspecialchars($perPackage) . "</td><td>". htmlspecialchars($unit) . "</td></tr>";
}

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/end.css">
        <link rel="stylesheet" href="css/table.css">
        <script src="js/box.js" defer></script>
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

        <div id="boxN" class="box">
            <p>Il Nutri-Score è un’etichetta a colori che valuta la qualità nutrizionale degli alimenti su una scala da A a E.<br> 
                Il punteggio tiene conto di nutrienti “positivi” come fibre, proteine e frutta/verdura, e nutrienti “negativi” come zuccheri, grassi saturi e sale.<br> 
                Un Nutri-Score A indica un prodotto nutrizionalmente più sano, mentre un E segnala un alimento da consumare con moderazione.</p>
        </div>

        <div id="boxNS" class="box">
            <p>Il NOVA Score classifica gli alimenti in base al grado di trasformazione industriale.<br> 
                I prodotti sono divisi in quattro gruppi: 1 per alimenti naturali o minimamente processati, fino a 4 per alimenti ultra-processati che spesso contengono additivi, zuccheri o grassi aggiunti.<br> 
                Questo sistema aiuta a comprendere l’impatto della lavorazione sulla qualità e salubrità degli alimenti.</p>
        </div>
    </body>
</html>