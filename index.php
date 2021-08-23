<?php
require 'db/sqlconnect.php';
function QueryBuilder_autoloader($class){
    $class_name = str_replace('DevCoder\\','',$class);
    //var_dump($class_name);
    require 'class/'.$class_name.'.php';
}

spl_autoload_register('QueryBuilder_autoloader');

$query = (new DevCoder\QueryBuilder())->select("*")->from("voiture")->where("puissance < 100");
$pdoStatement = $pdo->prepare($query);
$pdoStatement->execute();

$voitures = $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Query Builder</title>
</head>
<body>

    <table>
        <thead>
            <tr>
                <td>id</td>
                <td>Marque</td>
                <td>Modele</td>
                <td>Puissance</td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($voitures as $voiture) {
                echo "<tr>";
                foreach ($voiture as $propriete){
                    echo "<td>".$propriete."</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
