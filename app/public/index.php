<h1> My first MVC</h1>

<?php
require_once(__DIR__."/../core/App.php");

function console(mixed $data) : void{
    ob_start(); # démarre la capture du flux de sortie
    var_dump($data);
    $debug_str = ob_get_clean(); # capture le flux de sortie et l'efface
    file_put_contents("php://stdout", $debug_str);  
    
    //La fonction console est conçue pour afficher des informations
    //  de débogage sur une variable. Elle capture les informations
    //  de var_dump() dans un tampon, puis les écrit dans la sortie
    //  standard. Cela peut être particulièrement utile pour déboguer
    //  des scripts PHP, car cela permet de voir le contenu des variables
    //  sans perturber le flux normal de sortie HTML envoyé au navigateur.
}

App::start();
?>