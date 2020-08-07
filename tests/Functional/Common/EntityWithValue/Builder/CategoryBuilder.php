<?php
declare(strict_types=1);

namespace App\Tests\Functional\Common\EntityWithValue\Builder;

use App\Entity\Category;
use Ramsey\Uuid\Uuid;

class CategoryBuilder
{
    private string $name;
    private string $description;

    public function __construct()
    {
        $this->applyDefaultValues();
    }

    public function build($id): Category
    {
        $category = new Category($id, $this->name, $this->description);

        $this->applyDefaultValues();

        return $category;
    }

    private function applyDefaultValues(): void
    {
        $rand = rand(0, 100);
        $this->name = 'test '.$rand;
        $this->description = 'Product test '.$rand;
    }
}
