<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "La connexion a échoué : " . $e->getMessage();
}

// Traitement du formulaire d'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST["fullname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $gender = $_POST["gender"];
    $interests = implode(", ", $_POST["interests"] ?? []);

    // Vérification des données du formulaire
    $errors = array();
    if (empty($fullName)) {
        $errors[] = "Le nom complet est obligatoire.";
    }
    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est obligatoire.";
    }
    if (empty($email)) {
        $errors[] = "L'adresse e-mail est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse e-mail n'est pas valide.";
    }
    if (empty($phone)) {
        $errors[] = "Le numéro de téléphone est obligatoire.";
    }
    if (empty($password)) {
        $errors[] = "Le mot de passe est obligatoire.";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($errors)) {
      // Vérification que l'email n'existe pas déjà dans la base de données
      $emailExists = false;
      $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM inserer WHERE email = :email");
      $stmt->bindParam(":email", $email);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($result['count'] > 0) {
          $emailExists = true;
          $errors[] = "L'adresse e-mail existe déjà.";
      }
  
      // Vérification que le numéro de téléphone n'existe pas déjà dans la base de données
      $phoneExists = false;
      $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM inserer WHERE phone = :phone");
      $stmt->bindParam(":phone", $phone);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($result['count'] > 0) {
          $phoneExists = true;
          $errors[] = "Le numéro de téléphone existe déjà.";
      }
  
      // Si les vérifications passent, alors insérer les données
      if (!$emailExists && !$phoneExists) {
          $sql = "INSERT INTO inserer (fullName, username, email, phone, password, gender, interests) VALUES (:fullName, :username, :email, :phone, :password, :gender, :interests)";
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(":fullName", $fullName);
          $stmt->bindParam(":username", $username);
          $stmt->bindParam(":email", $email);
          $stmt->bindParam(":phone", $phone);
          $stmt->bindParam(":password", $password);
          $stmt->bindParam(":gender", $gender);
          $stmt->bindParam(":interests", $interests);
          if ($stmt->execute()) {
              echo "Enregistrement réussi !";
          } else {
              echo "Erreur lors de l'enregistrement.";
          }
        }
      } 
 
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire d'inscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .navbar{
    background-color: blanchedalmond;
    overflow: hidden;
    position: fixed;
    width: 100%;
    display:flex;
    flex-direction: row;
    justify-content: space-between;
    height: 60px;
    align-items: center;
    top: 0%;
    z-index:1000;

}

.navbar_left{
    color: black;
    margin-left: 80px;
    font-weight: bolder;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
}
.navbar_right{
    color: black;
}
.navbar a{
    float: left;
    display: block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}
.navbar a:hover{
    background-color:blanchedalmond ;
    transform: translateY(-5px);
    transition: transform 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}



body{
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px;
  background: linear-gradient(135deg, #eee9d3, blanchedalmond);
}
.container{
  max-width: 700px;
  width: 130%;
  background-image: url(f1.png);
  padding: 25px 30px;
  border-radius: 5px;
  box-shadow: 0 5px 10px rgba(0,0,0,0.15);
  margin-top: 2%;
  
}
.container .title{
  

  margin-top: 5%;
  
  bottom: 0;
  height: 3px;
  width: 30px;
  border-radius: 5px;

  
}
.content form .user-details{
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  margin: 20px 0 12px 0;
}
form .user-details .input-box{
  margin-bottom: 15px;
  width: calc(100% / 2 - 20px);
}
form .input-box span.details{
  display: block;
  font-weight: 500;
  margin-bottom: 5px;
}
.user-details .input-box input{
  height: 45px;
  width: 100%;
  font-size: 16px;
  border-radius: 5px;
  padding-left: 15px;
  border: 1px solid #ccc;
  
}


 
 .category label{
   display: flex;
   align-items: center;
   cursor: pointer;
 }


 
 form .button{
   height: 45px;
   margin: 35px 0
 }
 form .button input{
   height: 100%;
   width: 100%;
   border-radius: 5px;
   border: none;
   color: #fff;
   font-size: 18px;
   font-weight: 500;
   letter-spacing: 1px;
   cursor: pointer;
   background: linear-gradient(135deg, #e8d380, #e3ac3d);
 }
 form .button input:hover{
  
  background: #d4bb7f;
  }
  .error-box {
    background-color: #ffcccc;
    border: 1px solid #ff0000;
    color: #ff0000;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 20px;
}



    </style>
</head>
<body>
    
    <div class="navbar">
        <div class="navbar_left">MNC</div>
        <div class="navbar_right">

            <a href="project.html" > HOME</a>
            <a href="log.html">SIGN IN</a>
            <a href="catalogue.html"><u>CATALOGUE</u></a>
            <a href="LUTUIF.html">LUTYIF</a>
            <a href="about.html">ABOUT</a>
            <a href="contactus.html">CONTACT</a>
            <a href="panier.html"><span class="cart">&#128722;</span><span class="cart-count">0</span></a>       </div>
        </div>
    </div>
  
    </div>
    <div class="container">
    <div class="title"><strong>Registration</strong></div>
    <br>
    <div class="content">
       <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <div class="user-details">
          <div class="input-box">
            <label class="details">Full Name</label>
            <input type="text" name="fullname" placeholder="Enter your name" required>
          </div>
          <div class="input-box">
            <span class="details">Username</span>
            <input type="text" name="username" placeholder="Enter your username" required>
          </div>
          <div class="input-box">
            <label class="details">Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
          </div>
          <div class="input-box">
            <label class="details">Phone Number</label>
            <input type="number" name="phone" placeholder="Enter your number" required>
          </div>
          <div class="input-box">
            <label class="details">Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
          </div>
          <div class="input-box">
            <label class="details">Confirm Password</label>
            <input type="password" name="confirmPassword" placeholder="Confirm your password" required>
          </div>
        </div>
        <div class="gender-details">
           <h2>Gender</h2>
           <input type="radio" name="gender" value="Femme"> <span class="dot1"> <span>Female</span>
           <input type="radio" name="gender" value="Homme"> <span class="dot2">Male</span>
        </div>
        <label><h2>Interests:</h2></label>
        <label><input type="checkbox" name="interests[]" value="homme"> Men's Fashion</label>
        <label><input type="checkbox" name="interests[]" value="femme"> Women's Fashion</label>
        <label><input type="checkbox" name="interests[]" value="enfant"> Kids's Fashion</label>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($errors)) {
            echo "<div class='error-box'>";
            echo "<p>Veuillez corriger les erreurs suivantes :</p>";
            echo "<ul>";
            foreach ($errors as $error) {
                echo "<li>" . $error . "</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
        ?>
        <div class="button">
          <input type="submit" value="Register">
        </div>
      </form>
    </div>
  </div>
</body>
</html>