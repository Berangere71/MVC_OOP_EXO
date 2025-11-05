<?php


require_once(__DIR__."/../models/ProductModel.php");

class App{
    public static function start(){
        console("Test");
        $productModel = new ProductModel();
        $products = $productModel->getAll();
        console($products);
        foreach($products as $product){
            // Product est du type ProductEntity
            console($product->getName());
            // attention à bien ajouter des lignes dans la table Produit
        }

        $product = $productModel->get(2);
        console($product);
    }
}
