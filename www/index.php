<?php
$html = "";

if($_SERVER['REQUEST_METHOD']=="GET"){

    if(isset($_GET['error'])){
        $error = htmlspecialchars($_GET['error']);
        $html = "<p id=\"error\">$error</p>";
    } 
}
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/index.css">
        <script src="js/index.js" defer></script>
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
                <form action="scansione_prova.php" method="post">
                    <input id="input" type="text" name="barcode" placeholder="ES.: 2387456723563"><br>
                    <input id="button" type="submit" value="INVIA">
                </form>
            </center>
        </div>
    </body>
</html>