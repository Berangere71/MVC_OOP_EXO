<?php



class ProductModel
{

    private PDO $bdd;
    private PDOStatement $addProduct;
    private PDOStatement $delProduct;
    private PDOStatement $getProduct;
    private PDOStatement $getProducts;
    private PDOStatement $editProduct;


    function __construct()
    {
        // Connexion à la base de donnée
        $this->bdd = new PDO("mysql:host=bdd;dbname=app-database", "root", "root");

        // Création d'une requête préparée qui récupère tous les produits
        $this->getProducts = $this->bdd->prepare("SELECT * FROM `Produit`LIMIT :limit");
        // Création d'un requête préparée qui récupère tous les id
        $this->getProduct = $this->bdd->prepare("SELECT * FROM Produit WHERE id = :id");

        $this->addProduct = $this->bdd->prepare("INSERT INTO Produit (name,price,image) VALUES (:name,:price,:image)");

        $this->delProduct = $this->bdd->prepare("DELETE FROM Produit (name,price,image) VALUES (:name,:price,:image)");

        $this->editProduct = $this->bdd->prepare("UPDATE Produit SET name = :name, price = :price, image = :image WHERE id = :id");
    }

    public function getAll(int $limit = 50): array
    {
        // Définir la valeur de LIMIT, par défault 50
        // LIMIT étant un INT ont n'oublie pas de préciser le type PDO::PARAM_INT.
        $this->getProducts->bindValue("limit", $limit, PDO::PARAM_INT);
        // Executer la requête
        $this->getProducts->execute();
        // Récupérer la réponse 
        $rawProducts = $this->getProducts->fetchAll();

        // Formater la réponse dans un tableau de ProductEntity
        $productsEntity = [];
        foreach ($rawProducts as $rawProduct) {
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
        //requête
        $this->getProduct->bindValue(":id", $id, PDO::PARAM_INT);
        //execute
        $this->getProduct->execute();
        //résultat
        $rawProduct = $this->getProduct->fetch(PDO::FETCH_ASSOC);

        if (!$rawProduct) {
            return NULL;
        } else {
        // Créer une instance de ProductEntity avec les données récupérées
            $productEntity = new ProductEntity(
                $rawProduct['name'],
                $rawProduct['price'],
                $rawProduct['image'],
                $rawProduct['id']
            );
        // Retourner l'objet ProductEntity
            return $productEntity; 
        }
    }

    public function add(string $name, float $price, string $image): void
    {
        $this->addProduct->bindValue(":name", $name, PDO::PARAM_STR);
        $this->addProduct->bindValue(":price", $price, PDO::PARAM_INT);
        $this->addProduct->bindValue(":image", $image, PDO::PARAM_STR);

        $this->addProduct->execute();

    }


    public function del(int $id): void {

        $this->delProduct = $this->bdd->prepare("DELETE FROM `Produit` WHERE id = :id");
        $this->delProduct->bindValue(":id",$id,PDO::PARAM_INT);

        $this->delProduct->execute();

    
    }    

    public function edit(
    int $id,
    string $name,
    float $price,
    string $image
  
): ?ProductEntity {
    // Préparer la requête pour mettre à jour un produit
    $this->editProduct = $this->bdd->prepare("UPDATE `Produit` SET name = :name, price = :price, image = :image WHERE id = :id");

    // Lier les valeurs
    $this->editProduct->bindValue(":id", $id, PDO::PARAM_INT);
    $this->editProduct->bindValue(":name", $name, PDO::PARAM_STR);
    $this->editProduct->bindValue(":price", $price, PDO::PARAM_INT);
    $this->editProduct->bindValue(":image", $image, PDO::PARAM_STR);

    // Exécuter la requête
    $this->editProduct->execute();

    // Récupérer les données mises à jour
    $rawProduct = $this->get($id); // Supposons que cette méthode existe pour récupérer le produit mis à jour

    if ($rawProduct) {
        return new ProductEntity(
            $rawProduct['name'],
            $rawProduct['price'],
            $rawProduct['image'],
            $rawProduct['id']
        );
    }

    return null; // Retourner null si le produit n'existe pas
}
}



class ProductEntity
{


    private const NAME_MIN_LENGTH = 3;
    private const PRICE_MIN = 0;
    private const DEFAULT_IMG_URL = "/public/CSS/images/default.png";
    private $name;
    private $price;
    private $image;
    private $id;

    public function getName(): string
    {
        return $this->name;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getImage(): string
    {
        return $this->image;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name) : void
    {
        if (strlen($name) < 3) {
            throw new Error("Name is too short minimum 
            length is " . $this::NAME_MIN_LENGTH);
        }
        $this->name = $name;
    }
    public function setPrice(float $price)
    {
        if ($price < 0) {
            throw new Error("Price is too short minimum price is " . $this::PRICE_MIN);
        }
        $this->price = $price;
    }
    public function setImage(string $image)
    {
        if (strlen($image) <= 0) {
            $this->image = $this::DEFAULT_IMG_URL;
        }
        $this->image = $image;
    }
    function __construct(string $name, float $price, string $image, int $id)
    {
        $this->setName($name);
        $this->setPrice($price);
        $this->setImage($image);
        $this->id = $id;
    }
}
