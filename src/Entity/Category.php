<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="App\Entity\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     */
    private string $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(name="description", type="string", length=255)
     */
    private string $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category", orphanRemoval = true, cascade={"persist", "remove"})
     */
    private Collection $products;

    public function __construct(string $id, string $name, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->products = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getProducts(): ArrayCollection
    {
        return $this->products;
    }

    public function setProducts(ArrayCollection $products): void
    {
        $this->products = $products;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription()
        ];
    }
}
