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
   <div id="container">

       <h1 id="hlogin">Maak een account aan</h1>

       <div id="logincontainer">
           <?php
           $host = "localhost";
           $port = 8889;
           $user = "root";
           $pass = "root";
           $database = "hashingopdracht";

           $conn = new mysqli($host, $user, $pass, $database, $port);

           $melding = " ";

           // controleert of het form is verzonden
           if ($_SERVER["REQUEST_METHOD"] === "POST") {
               $Voornaam = htmlspecialchars($_POST['Voornaam']);
               $achternaam = htmlspecialchars($_POST['achternaam']);
               $email = htmlspecialchars($_POST['email']);
               $wachtwoord = $_POST['wachtwoord'];
               $herhaalWachtwoord = $_POST['herhaalWachtwoord'];
        
               // hier wordt er gekeken of de twee wachtwoorden hetzelfde zijn
               if ($wachtwoord !== $herhaalWachtwoord) {
                   $melding = "Wachtwoorden zijn niet hetzelfde.";
               }
               else {
                // hier wordt er gekeken of de e-mailadres al bestaat in de database
                   $stmt = $conn->prepare("SELECT email FROM gebruiker WHERE email = ?");
                   $stmt->bind_param("s", $email);
                   $stmt->execute();
                   $stmt->bind_result($tellen);
                   $stmt->fetch();
                   $stmt->close();
                       
                   // als er meer emailadressen zijn dan die er gebruikt word dan kan die geen account aanmaken met die e-mailadres
                   if ($tellen > 0) {
                       $melding = "deze e-mailadres bestaat al maak een nieuwe aan of log in met jouw e-mailadres.";
                   } else {
                       $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
                            
                       // hier wordt de nieuwe gebruiker toegevoegd aan de database
                       $stmtWachtwoord = $conn->prepare("INSERT INTO gebruiker (email, Voornaam, Achternaam, Wachtwoord) VALUES (?, ?, ?, ?)");
                       
                       if ($stmtWachtwoord === false) {
                           $melding = "fout bij maken van nieuwe gebruiker ";
                       } else {
                        // hier worden de gegevens van de nieuwe gebruiker in de database gezet
                           $stmtWachtwoord->bind_param("ssss", $email, $Voornaam, $achternaam, $hash);
                               
                           if ($stmtWachtwoord->execute()) {
                                $_SESSION['Voornaam'] = $Voornaam;
                                $_SESSION['email'] = $email;
                            

                                header("Location: index.php"); exit();
                           }else{
                                $melding = "Niet geldige e-mailadres of wachtwoord";
                           }
                           $stmtWachtwoord->close();
                       }
                   }

               }
           }
           $conn->close();
           ?>

           <form method="POST" id="breedte" action="registreer.php">
               <label for="naam">Voornaam:</label><br>
               <input type="text" id="Voornaam" name="Voornaam" required><br>

               <label for="achternaam">Achternaam:</label><br>
               <input type="text" id="achternaam" name="achternaam" required><br>

               <label for="email">E-mail:</label><br>
               <input type="email" id="email" name="email" required><br>

               <label for="wachtwoord">Wachtwoord:</label><br>
               <input type="password" id="wachtwoord" name="wachtwoord" min="8" required><br>
               <p>minimaal 8 tekens</p><br>

               <label for="herhaalWachtwoord">Herhaal Wachtwoord:</label><br>
               <input type="password" id="herhaal_wachtwoord" name="herhaalWachtwoord" min="8" required><br>
               <p>minimaal 8 tekens</p><br>

               <button type="submit" class="login">Registreren</button>
               
           </form>
            <a href="login.php"><button type="submit" class="login">Al een account? <br> Log hier in!</button></a>

       </div>
       
       <p id="melding"><?php echo $melding; ?></p>

   </div>
</body>
</html>
