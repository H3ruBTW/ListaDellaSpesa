<?php
$html = "";

if($_SERVER['REQUEST_METHOD']=="POST"){
    define($BARCODE, $_POST["barcode"]);

    if(isset($_POST['page'])){
        define($PAGE, $_POST["page"]);
    } else {
        define($PAGE, "PANORAMICA");
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
                <h1>PRODOTTO</h1>
            </center>
        </div>
        <div id="pages">
            <nav>
                <ul>
                    <li>
                        <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                            <input type="text" value="<?php echo $BARCODE ?>" hidden>
                            <input type="text" value="PANORAMICA" name="page" hidden>
                            <input type="submit" value="PANORAMICA">
                        </form>
                    </li>
                    <li>
                        <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                            <input type="text" value="<?php echo $BARCODE ?>" name="barcode" hidden>
                            <input type="text" value="VALORI NUTRIZIONALI" name="page" hidden>
                            <input type="submit" value="VALORI NUTRIZIONALI">
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