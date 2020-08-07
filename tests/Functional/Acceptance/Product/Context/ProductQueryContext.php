<?php
declare(strict_types=1);

namespace App\Tests\Functional\Acceptance\Product\Context;

use App\Entity\ProductRepository;
use Behat\Behat\Context\Context;

class ProductQueryContext implements Context
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Then the product with id :id is created
     */
    public function theProductWithIdIsCreated(string $id)
    {
        $product = $this->productRepository->ofId($id);

        if (null === $product) {
            throw new \Exception('Product not found');
        }
    }
}
