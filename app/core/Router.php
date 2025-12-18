<?php

// Je vais créer les routes /product/... j'ai donc besoin
// de controleur ProductController
require_once(__DIR__."/../controllers/productController.php");
require_once(__DIR__."/../controllers/homeController.php");
require_once(__DIR__."/../controllers/NotFoundController.php");


class Router{
    public static function getController(string $controllerName){
        switch ($controllerName) {
            // Si la route est /product 
            case 'product':
                // Je renvoi le controleur ProductController
                return new ProductController();
                break;

            // Route : /    
            case'':
                return new HomeController();
                break;
            default:
            // Si aucune route de match
                return new NotFoundController();
                break;
        }
    }
}