<?php
declare(strict_types=1);

namespace App\Tests\Functional\Common\EntityWithValue\Builder;

use App\Entity\Product;
use Ramsey\Uuid\Uuid;

class ProductBuilder
{
    private string $name;
    private string $description;

    public function __construct()
    {
        $this->applyDefaultValues();
    }


    public function build(): Product
    {
        $product = new Product(Uuid::uuid4()->toString(), $this->name, $this->description);

        $product->setName($this->name);

        $this->applyDefaultValues();

        return $product;
    }

    public function withName(string $name): ProductBuilder
    {
        $this->name = $name;

        return $this;
    }

    private function applyDefaultValues(): void
    {
        $rand = rand(0, 100);
        $this->name = 'test '.$rand;
        $this->description = 'Product test '.$rand;
    }
}
