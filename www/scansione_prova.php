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
                        <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                            <input type="text" value="<?php echo $BARCODE ?>" name="barcode" hidden>
                            <input type="text" value="PANORAMICA" name="page" hidden>
                            <input id="button" type="submit" value="PANORAMICA">
                        </form>
                    </li>
                    <li>
                        <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                            <input type="text" value="<?php echo $BARCODE ?>" name="barcode" hidden>
                            <input type="text" value="VALORI NUTRIZIONALI" name="page" hidden>
                            <input id="button" type="submit" value="VALORI NUTRIZIONALI">
                        </form>
                    </li>
                    <li>
                        <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                            <input type="text" value="<?php echo $BARCODE ?>" name="barcode" hidden>
                            <input type="text" value="SALUTE" name="page" hidden>
                            <input id="button" type="submit" value="SALUTE">
                        </form>
                    </li>
                    <li>
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