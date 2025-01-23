<?php
$html = "";

if($_SERVER['REQUEST_METHOD']=="POST"){
    // QUESTA FUNZIONE RIMUOVE TUTTI GLI SPAZI COMPRESI LE TABULAZIONI, ECC.
    $BARCODE = $_POST['barcode'];
    

    if(isset($_POST['page'])){
        $PAGE = $_POST['page'];
    } else {
        $PAGE = "PANORAMICA";
    }

    $html = "<p>CODICE PRODOTTO: " . $BARCODE . "</p>";

    switch ($PAGE) {
        case 'PANORAMICA':
            $html .= 
            <<<COD
            <p>PAGINA DI PANORAMICA</p>
            COD;
            break;
        case 'VALORI NUTRIZIONALI':
            $html .= 
            <<<COD
            <p>PAGINA DEI VALORI NUTRIZIONALI</p>
            COD;
            break;
        case 'INGREDIENTI':
            $html .= 
            <<<COD
            <p>PAGINA DEGLI INGREDENTI</p>
            COD;
            break;
        case 'SALUTE':
            $html .= 
            <<<COD
            <p>PAGINA DELLA SALUTE</p>
            COD;
            break;
        case 'AMBIENTE':
            $html .= 
            <<<COD
            <p>PAGINA DELL'IMPRONTA AMBIENTALE</p>
            COD;
            break;
    }
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
                        <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                            <input type="text" value="<?php echo $BARCODE ?>" name="barcode" hidden>
                            <input type="text" value="PANORAMICA" name="page" hidden>
                            <input id="button" type="submit" value="PANORAMICA">
                        </form>
                    </li>
                    <li>
                        <!-- MOSTRA I VALORI NUTRIZIONALI -->
                        <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                            <input type="text" value="<?php echo $BARCODE ?>" name="barcode" hidden>
                            <input type="text" value="VALORI NUTRIZIONALI" name="page" hidden>
                            <input id="button" type="submit" value="VALORI NUTRIZIONALI">
                        </form>
                    </li>
                    <li>
                        <!-- MOSTRA GLI INGREDIENTI DEL PRODOTTO -->
                        <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                            <input type="text" value="<?php echo $BARCODE ?>" name="barcode" hidden>
                            <input type="text" value="INGREDIENTI" name="page" hidden>
                            <input id="button" type="submit" value="INGREDIENTI">
                        </form>
                    </li>
                    <li>
                        <!-- MOSTRA I LIVELLI DI INGREDIENTI DANNOSI -->
                        <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                            <input type="text" value="<?php echo $BARCODE ?>" name="barcode" hidden>
                            <input type="text" value="SALUTE" name="page" hidden>
                            <input id="button" type="submit" value="SALUTE">
                        </form>
                    </li>
                    <li>
                        <!-- MOSTRA L'IMPRONTA AMBIENTALE DEL PRODOTTO -->
                        <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                            <input type="text" value="<?php echo $BARCODE ?>" name="barcode" hidden>
                            <input type="text" value="AMBIENTE" name="page" hidden>
                            <input id="button" type="submit" value="AMBIENTE">
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