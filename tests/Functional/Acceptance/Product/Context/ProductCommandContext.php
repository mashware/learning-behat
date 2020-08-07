<?php
declare(strict_types=1);

namespace App\Tests\Functional\Acceptance\Product\Context;

use Behat\Behat\Context\Context;
use App\Entity\ProductRepository;
use App\Tests\Functional\Common\EntityWithValue\Builder\ProductBuilder;

class ProductCommandContext implements Context
{
    private ProductBuilder $productBuilder;
    private ProductRepository $productRepository;

    public function __construct(
        ProductBuilder $productBuilder,
        ProductRepository $productRepository
    ) {
        $this->productBuilder = $productBuilder;
        $this->productRepository = $productRepository;
    }

    /**
     * @Given a product
     */
    public function aProduct()
    {
        $this->productRepository->addAndSave($this->productBuilder->build());
    }

    /**
     * @Given a product with the name :name
     */
    public function aProductWithTheName(string $name)
    {
        $product = $this->productBuilder
            ->withName($name)
            ->build()
        ;

        $this->productRepository->addAndSave($product);
    }

    /**
     * @Given a :quantity of product
     */
    public function aProductQuantityOfProducts(int $quantity)
    {
        for ($i=0; $i<$quantity; $i++) {
            $this->productRepository->add(
                $this->productBuilder
                ->build()
            );
        }

        $this->productRepository->save();
    }
}
