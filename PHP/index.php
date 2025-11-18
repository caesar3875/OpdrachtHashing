<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="mijncss.css">
</head>
<body>
    <div id="container">

        <h1 id="hlogin">Log in</h1>

        <div id="logincontainer">
            <?php

            // hier doe ik de database aanroepen
            $host = "localhost";
            $port = 8889;
            $user = "root";
            $pass = "root";
            $database = "Hashingopdracht"; 

            // hier maak ik verbinding met de database
            $conn = new mysqli($host, $user, $pass, $database, $port);

            $melding = " ";

            // controleert of het form is verzonden
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // haalt het e-mailadres en wachtwoord uit het formulier
                $email = htmlspecialchars($_POST['email']);
                $wachtwoord = $_POST['wachtwoord'];
  
                $stmt = $conn->prepare("SELECT Wachtwoord, email FROM gebruiker WHERE email = ?");
                    
                if ($stmt === false) {
                    $melding = "fout bij laden";
                } else {
                    // hier wordt er gekeken of het wachtwoord klopt met de gehashte wachtwoord in de database

                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $stmt->bind_result($hashedWachtwoord, $email);
                    $stmt->fetch();
                    $stmt->close();

                    if ($hashedWachtwoord && password_verify($wachtwoord, $hashedWachtwoord)) {
                        $_SESSION['email'] = $email;

                        // Email loggen 
                        $log->info('Gebruiker ingelogd', ['email' => $email]);

                        header("Location: index.php"); 
                        exit();
                    } else {
                        $melding = "niet geldig e-mailadres of wachtwoord";
                    }   
                }
            }

            $conn->close();
            ?>

            <form id="breedte" method="POST" action="inloggen.php"> 
                <label for="email">E-mail:</label><br>
                <input type="email" id="email" name="email" required><br>

                <label for="wachtwoord">Wachtwoord:</label><br>
                <input type="password" id="wachtwoord" name="wachtwoord" min="8" required><br>
               <p>minimaal 8 tekens</p> <br>

                <button type="submit" class="login">Log in</button>
            </form>
            <a href="registreer.php"><button type="button" class="login">Nog geen account? <br> Maak er hier een!</button></a>

        </div>
        
        <p id="melding"><?php echo $melding; ?></p>

    </div>
</body>
</html>
