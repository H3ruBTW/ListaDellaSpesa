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
    foreach($_SESSION['last_research'] as $output){
        $results .= "<a href=\"scansione_prova.php?barcode=" . $output . "\">" . $output . "</a><br>";
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
                <form action="scansione_prova.php" method="get">
                    <input id="input" type="text" name="barcode" placeholder="ES.: 2387456723563"><br>
                    <input id="button" type="submit" value="INVIA">
                </form>
                <br>
                <p>
                <span style="font-size: 30px; font-weight:bold;">RISULTATI PRECEDENTI</span> <br><br>
                <?= $results ?>
                <br><a href="?remove=1">DELETE SEARCH HISTORY</a>
                </p>
            </center>
        </div>
    </body>
</html>