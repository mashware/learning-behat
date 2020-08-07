<?php
declare(strict_types=1);

namespace App\Tests\Functional\Acceptance\Product\Context;

use App\Entity\CategoryRepository;
use App\Tests\Functional\Common\EntityWithValue\Builder\CategoryBuilder;
use Behat\Behat\Context\Context;

class CategoryCommandContext implements Context
{
    private CategoryBuilder $categoryBuilder;
    private CategoryRepository $categoryRepository;

    public function __construct(
        CategoryBuilder $categoryBuilder,
        CategoryRepository $categoryRepository
    ) {
        $this->categoryBuilder = $categoryBuilder;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Given a category with id :id
     */
    public function aCategoryWithId(string $id)
    {
        $this->categoryRepository->addAndSave($this->categoryBuilder->build($id));
    }
}
