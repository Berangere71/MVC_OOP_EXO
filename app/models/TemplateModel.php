<?php
/**
 * 1. Complétez les requêtes SQL selon votre table.
 * 2. Les méthodes add et edit doivent être adaptées pour gérer les colonnes spécifiques à votre modèle.
 */
 
require_once(__DIR__ . "/../controllers/TemplateController.php");

class TemplateModel {
    private PDO $bdd;
    private PDOStatement $addTemplate;
    private PDOStatement $delTemplate;
    private PDOStatement $getTemplate;
    private PDOStatement $getTemplates;
    private PDOStatement $editTemplate;

    function __construct()
    {
        $this->bdd = new PDO("mysql:host=lamp-mysql;dbname=boutique", "root", "root");

        // Exemple adapté à une table `Template` avec colonnes `name`, `price`, `image`
        $this->addTemplate = $this->bdd->prepare(
            "INSERT INTO `Template` (`name`, `price`, `image`) VALUES (:name, :price, :image);"
        );

        $this->delTemplate = $this->bdd->prepare(
            "DELETE FROM `Template` WHERE `id` = :id;"
        );

        $this->getTemplate = $this->bdd->prepare(
            "SELECT * FROM `Template` WHERE `id` = :id;"
        );

        $this->editTemplate = $this->bdd->prepare(
            "UPDATE `Template` SET `name` = :name, `price` = :price, `image` = :image WHERE `id` = :id;"
        );

        $this->getTemplates = $this->bdd->prepare(
            "SELECT * FROM `Template` LIMIT :limit"
        );
    }

    // Exemple : adaptez les paramètres à votre table
    public function add(string $name, float $price, ?string $image = null) : void
    {
        $this->addTemplate->bindValue("name", $name, PDO::PARAM_STR);
        $this->addTemplate->bindValue("price", $price);
        $this->addTemplate->bindValue("image", $image, PDO::PARAM_STR);
        $this->addTemplate->execute();
    }

    public function del(int $id) : void
    {
        $this->delTemplate->bindValue("id", $id, PDO::PARAM_INT);
        $this->delTemplate->execute();
    }

    public function get(int $id): ?TemplateEntity
    {
        $this->getTemplate->bindValue("id", $id, PDO::PARAM_INT);
        $this->getTemplate->execute();
        $rawTemplate = $this->getTemplate->fetch(PDO::FETCH_ASSOC);

        // Si l'enregistrement n'existe pas, je renvoie NULL
        if (!$rawTemplate) {
            return NULL;
        }
        return new TemplateEntity(
            $rawTemplate["name"],
            (float)$rawTemplate["price"],
            $rawTemplate["image"],
            (int)$rawTemplate["id"]
        );
    }

    public function getAll(int $limit = 50) : array
    {
        $this->getTemplates->bindValue("limit", $limit, PDO::PARAM_INT);
        $this->getTemplates->execute();
        $rawTemplates = $this->getTemplates->fetchAll(PDO::FETCH_ASSOC);

        $templatesEntity = [];
        foreach ($rawTemplates as $rawTemplate) {
            $templatesEntity[] = new TemplateEntity(
                $rawTemplate["name"],
                (float)$rawTemplate["price"],
                $rawTemplate["image"],
                (int)$rawTemplate["id"]
            );
        }

        return $templatesEntity;
    }

    // Les paramètres autres que l'id sont optionnels (nullable) — on ne force pas la modification de tous les champs
    public function edit(int $id, ?string $name = null, ?float $price = null, ?string $image = null) : ?TemplateEntity
    {
        $originalTemplateEntity = $this->get($id);

        // Si l'enregistrement n'existe pas, je renvoie NULL
        if (!$originalTemplateEntity) {
            return NULL;
        }

        // Bind des valeurs : on prend la nouvelle valeur si fournie, sinon on garde l'original
        $this->editTemplate->bindValue("id", $id, PDO::PARAM_INT);
        $this->editTemplate->bindValue("name", $name ?? $originalTemplateEntity->getName(), PDO::PARAM_STR);
        $this->editTemplate->bindValue("price", $price ?? $originalTemplateEntity->getPrice());
        $this->editTemplate->bindValue("image", $image ?? $originalTemplateEntity->getImage(), PDO::PARAM_STR);

        $this->editTemplate->execute();

        // Retourne l'entité mise à jour
        return $this->get($id);
    }
}

class TemplateEntity {
    private ?int $id;
    private string $name;
    private float $price;
    private ?string $image;

    function __construct(string $name, float $price, ?string $image = null, ?int $id = null)
    {
        $this->name = $name;
        $this->price = $price;
        $this->image = $image;
        $this->id = $id;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getPrice() : float {
        return $this->price;
    }

    public function getImage() : ?string {
        return $this->image;
    }
}