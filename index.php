<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de code Brandon</title>
</head>

<body>
    <p>
        Générateur de codes hexadécimaux <br />
        Combien de codes souhaitez-vous?
    </p>

    <form action="index.php" method="post">
        <p>
            <input type="text" name="code" value="0" />
            <input type="submit" value="Valider" />
        </p>
    </form>


    <?php

    ///FUNCTION CODE GENERATOR

    function CodeHexa($taille)
    {
        $cars = "ABCDEF0123456789";
        $mdp = '';
        $long = strlen($cars);

        srand((float)microtime() * 1000000);

        for ($i = 0; $i < $taille; $i++) $mdp = $mdp . substr($cars, rand(0, $long - 1), 1);

        return $mdp;
    }

    ///FUNCTION CODE GENERATOR END 



    ///BDD CONNEXION

    $servername = 'localhost';
    $username = 'root';
    $password = '';
    //make connexion
    $conn = new mysqli($servername, $username, $password);

    //test connexion 
    if ($conn->connect_error) {
        die('Erreur : ' . $conn->connect_error);
    }


    $dbco = new PDO('mysql:host=localhost;dbname=bddtest;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    ///BDD CONNEXION END



    ///BOUCLE FOR NUMBER CODE NEEDED

    print "Voici " . $_POST['code'] . "codes:  <br/>";


    for ($i = 1; $i <= $_POST['code']; ++$i) {
        $valid = 0;
        $safe = 0;
        while ($valid == 0) {
            $safe += 1;

            $sh = CodeHexa(2);
            $result = $dbco->query("SELECT * FROM codes WHERE code Like '$sh' ");

            if ($result->rowCount() == 0) {

                $valid = 1;
                $sth = $dbco->prepare("INSERT INTO codes(code)VALUES(:code)");
                $sth->bindParam(':code', $sh);

                $sth->execute();
                print '-';
                print  $sh;
                echo "<br/>";
            }
            if ($safe == 15) {
                $valid += 1;
                echo "faux";
            }
        }
    }

    ///BOUCLE FOR NUMBER CODE NEEDED





    ?>
</body>

</html>