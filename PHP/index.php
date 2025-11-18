<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

    if(isset($_SESSION['Voornaam'])){
        echo "<p class='session'>Welkom " . htmlspecialchars($_SESSION['Voornaam']) . "!</p>";
    } 
    else {
        echo "<p class='session'>Je bent niet ingelogd</p>";
        echo "
        <script>
            const antwoord = window.prompt('Je bent niet ingelogd! Wil je een account maken? (ja/nee)');
            
            // Als de gebruiker JA invult → naar registreer pagina
            if(antwoord && antwoord.toLowerCase() === 'ja'){
                window.location.href = 'registreer.php';
            }
            // Als de gebruiker NEE invult → ook naar registreerpagina (zoals jij vroeg)
            else {
                window.location.href = 'registreer.php';
            }
        </script>
        ";
    }
    ?>
</body>
</html>
