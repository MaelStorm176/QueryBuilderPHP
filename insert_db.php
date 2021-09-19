<?php
require 'db/sqlconnect.php';
function QueryBuilder_autoloader($class){
    $class_name = str_replace('DevCoder\\','',$class);
    //var_dump($class_name);
    require 'class/'.$class_name.'.php';
}

spl_autoload_register('QueryBuilder_autoloader');


$input = array(
    "Titre"=>$_GET["titre_input"],
    "Auteur"=>$_GET["auteur_input"],
    "Metteur_en_scene"=>$_GET["mescene_input"],
    "Cout_d_achat"=>$_GET["cout_input"],
    "DateCreation"=>$_GET["date_input"],
    "id_compagnie"=>$_GET["compagnie_input"],
    "type_de_piece"=>$_GET["type_input"],
    "publique"=>$_GET["public_input"]
);

if (isset($input)) {
    $query = (new DevCoder\QueryBuilder())->insert("piece")->columns(
        "Titre",
        "Auteur",
        "Metteur_en_scene",
        "Cout_d_achat",
        "DateCreation",
        "id_compagnie",
        "type_de_piece",
        "publique"
    );
    echo $query;
    $pdoStatement = $pdo->prepare($query);
    $pdoStatement->execute($input);
    var_dump($pdo->errorInfo());
}

header("Location: ../");
die();
?>