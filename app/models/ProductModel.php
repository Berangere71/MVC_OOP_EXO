<?php



class ProductModel{

    private PDO $bdd;
    private PDOStatement $addProduct;
    private PDOStatement $delProduct;
    private PDOStatement $getProduct;
    private PDOStatement $getProducts;
    private PDOStatement $editProduct;


    function __construct()
    {
        // Connexion à la base de donnée
        $this->bdd = new PDO("mysql:host=bdd;dbname=app-database","root","root");

        // Création d'une requête préparée qui récupère tous les produits
        $this->getProducts = $this->bdd->prepare("SELECT * FROM `Produit`LIMIT :limit");
        // Création d'un requête préparée qui récupère tous les id
        $this->getProduct = $this->bdd->prepare("SELECT * FROM 'Produit' WHERE id= :id");

        $this->addProduct = $this->bdd->prepare("INSERT INTO 'Produit'(name,price,image,id) VALUES (:name,:price,:image,:id)");

        $this->delProduct = $this->bdd->prepare("DELETE FROM 'Produit'(name,price,image,id) VALUES (:name,:price,:image,:id)");

        $this->editProduct = $this->bdd->prepare("UPDATE 'Produit' SET name = :name, price = :price, image = :image WHERE id = :id");


    }

    public function getAll(int $limit = 50) : array
    {
        // Définir la valeur de LIMIT, par défault 50
        // LIMIT étant un INT ont n'oublie pas de préciser le type PDO::PARAM_INT.
        $this->getProducts->bindValue("limit",$limit,PDO::PARAM_INT);
        // Executer la requête
        $this->getProducts->execute();
        // Récupérer la réponse 
        $rawProducts = $this->getProducts->fetchAll();
        
        // Formater la réponse dans un tableau de ProductEntity
        $productsEntity = [];
        foreach($rawProducts as $rawProduct){
            $productsEntity[] = new ProductEntity(
                $rawProduct["name"],
                $rawProduct["price"],
                $rawProduct["image"],
                $rawProduct["id"]
            );
        }
        
        // Renvoyer le tableau de ProductEntity
        return $productsEntity;
    }

    public function get(int $id): ?ProductEntity
    {
      $this->getProduct->bindValue(":id", $id, PDO::PARAM_INT);
      $this->getProduct->execute();
      $rawproduct = $this->getProduct->fetch(PDO::FETCH_ASSOC);
      
      if (!$rawProduct){
        return NULL;
      }
      else {
        // Créer une instance de ProductEntity avec les données récupérées
        $productEntity = new ProductEntity(
            $rawProduct['name'],
            $rawProduct['price'],
            $rawProduct['image'],
            $rawProduct['id']
        );

        return $productEntity; // Retourner l'objet ProductEntity
        }
    }

    public function add(string $name, float $price,string $image) : void
    {   
    }

    public function del(int $id) : void
    {
    }
    public function edit(int $id,string $name = NULL,
    float $price = NULL, string $image = NULL) : ProductEntity | NULL
    {
        return NULL;
    }
}


class ProductEntity{
    
    
    private const NAME_MIN_LENGTH = 3;
    private const PRICE_MIN = 0;
    private const DEFAULT_IMG_URL = "/public/CSS/images/default.png";
    private $name;
    private $price;
    private $image;
    private $id;

    public function getName() : string{
        return $this->name;
    }
    public function getPrice() : float{
        return $this->price;
    }
    public function getImage() : string{
        return $this->image;
    }
    public function getId() : int{
        return $this->id;
    }

    public function setName(string $name){
        if(strlen($name) < $this::NAME_MIN_LENGTH){
            throw new Error("Name is too short minimum 
            length is ".$this::NAME_MIN_LENGTH);
        }
        $this->name = $name;
    }
    public function setPrice(float $price){
        if($price < 0){
            throw new Error("Price is too short minimum price is ".$this::PRICE_MIN);
        }
        $this->price = $price;
    }
    public function setImage(string $image){
        if(strlen($image) <= 0){
            $this->image = $this::DEFAULT_IMG_URL;
        }
        $this->image = $image;

            function __construct(string $name,float $price,string $image,int $id)
    {
        $this->setName($name);
        $this->setPrice($price);
        $this->setImage($image);
        $this->id = $id;
    }
}
}