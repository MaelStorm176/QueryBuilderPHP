<?php
use DevCoder\QueryBuilder;

require 'db/sqlconnect.php';

function QueryBuilder_autoloader($class){
    $class_name = str_replace('DevCoder\\','',$class);
    require 'class/'.$class_name.'.php';
}

spl_autoload_register('QueryBuilder_autoloader');

$results = (new QueryBuilder())
    ->select($pdo,"*")
    ->from("piece")
    ->where("publique = :publique")
    ->params([
            "publique"=>2
    ])
    ->get();

$id_compagnies = array_column($results, "id_compagnie");

$columns = (new QueryBuilder())
    ->select($pdo,"COLUMN_NAME")
    ->from("INFORMATION_SCHEMA.COLUMNS")
    ->where("TABLE_SCHEMA='deconfinee'","TABLE_NAME='piece'")
    ->get();

$compagnies = (new DevCoder\QueryBuilder())
    ->select($pdo,"id_compagnie","nom")
    ->from("compagnie")
    ->whereIn("compagnie.id_compagnie", $id_compagnies)
    ->get();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Query Builder</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div style="overflow-x: auto; box-shadow: 5px 5px #d2caca;">
            <table>
                <thead>
                <?php
                echo "<tr>";
                foreach ($columns as $column){
                    echo "<th scope='col'>".$column["COLUMN_NAME"]."</th>";
                }
                echo "</tr>";
                ?>
                </thead>
                <tbody>
                    <?php
                    foreach ($results as $result) {
                        echo "<tr>";
                        foreach ($result as $propriete){
                            echo "<td>".$propriete."</td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="form-add">
            <h2>Ajouter une pièce</h2>
            <form action="insert_db.php" method="get">
                <div>
                    <label for="titre_input">Titre</label>
                    <input type="text" name="titre_input" id="titre_input">
                </div>

                <div>
                    <label for="auteur_input">Auteur</label>
                    <input type="text" name="auteur_input" id="auteur_input">
                </div>

                <div>
                    <label for="mescene_input">Metteur en scene</label>
                    <input type="text" name="mescene_input" id="mescene_input">
                </div>

                <div>
                    <label for="cout_input">Coût d'achat</label>
                    <input type="number" name="cout_input" id="cout_input" min="0">
                </div>

                <div>
                    <label for="date_input">Date de création</label>
                    <input type="date" name="date_input" id="date_input">
                </div>

                <div>
                    <label for="compagnie_input">Compagnie</label>
                    <select name="compagnie_input" id="compagnie_input">
                    <?php
                        foreach ($compagnies as $compagny){
                            echo "<option value='".$compagny['id_compagnie']."'>".$compagny["nom"]."</option>";
                        }
                    ?>
                    </select>
                </div>

                <div>
                    <label for="type_input">Type de pièce</label>
                    <select name="type_input" id="type_input">
                        <option value="Lecture">Lecture</option>
                        <option value="Comédie">Comédie</option>
                        <option value="Pièce de théâtre">Pièce de théâtre</option>
                    </select>
                </div>

                <div>
                    <label for="public_input">Public concerné</label>
                    <select name="public_input" id="public_input">
                        <option value="0">Tout public</option>
                        <option value="1">Enfant</option>
                        <option value="2">Normal</option>
                        <option value="3">Etudiant</option>
                    </select>
                </div>
                <button type="submit">Ajouter</button>
            </form>
        </div>
    </div>
</body>
</html>
