<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/index.css">
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
                <form method="POST" action="scansione_prova.php">
                    <input id="input" type="text" name="barcode" placeholder="ES.: 2387456723563"><br>
                    <input id="button" type="submit" value="INVIA">
                </form>
            </center>
        </div>
    </body>
</html>