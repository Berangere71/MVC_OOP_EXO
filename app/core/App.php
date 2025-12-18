<?php


require_once(__DIR__."/../models/ProductModel.php");

require_once(__DIR__."/../controllers/productController.php");

require_once(__DIR__."/Router.php");

const ROOT_APP_PATH = "MVC_OOP_EXO";




class App {
    public static function start(){

        $uri = str_replace(ROOT_APP_PATH,"",$_SERVER["REQUEST_URI"]);

        $uri_elements = explode("/",$uri);

        $controllerName = isset($uri_elements[1])?$uri_elements[1]:"";
        $methodName = isset($uri_elements[2])?$uri_elements[2]:"";
        $params = array_splice($uri_elements,3);

        // Je récupère le controller
        $controller = Router::getController($controllerName);

        // Appel de la méthode view 
        // La méthode view va executer la méthode en fonction de l'url
        $controller->view($methodName,$params);
        // console("Test");
        $productModel = new ProductModel();
        $products = $productModel->getAll();
        // console($products);
        foreach($products as $product){
            // Product est du type ProductEntity
            // console($product->getName());
            // attention à bien ajouter des lignes dans la table Produit
        }

        // $product = $productModel->get(2);
        // console($product);
        $controller = Router::getController("product");
        $controller = new ProductController();
        $controller->show([3]);

        /**
         * Récupère l'uri.
         */
        $uri = $_SERVER["REQUEST_URI"];

        /**
         * Récupère un tableau des élements de l'uri en séparant
         * la string via le caractère '/'
         */
        $uri_elements = explode("/",$uri);
        // Pour l'uri /product/show/3
        // $uri_elements  => ["","product","show","3"]

        $controllerName = $uri_elements[1] ?? "";
        $methodName = $uri_elements[2] ?? "";
        // supprime les 3 premiers éléments pour ne conserver que les paramètres
        $params = array_splice($uri_elements,3); 
        // Pour l'uri /product/show/3
        // $params => ["3"]
        // Pour l'uri /product/show/3/4/5
        // $params => ["3","4","5"]

        console($controllerName);
        console($methodName);
        console($params);


      $productModel->Add("cacahuete",1.55,"https:unsplash.it/100/100?5");   
    
    }
   
}




