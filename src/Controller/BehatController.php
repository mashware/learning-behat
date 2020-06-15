<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BehatController extends AbstractController
{
    /**
     * @Route("/category", name="create_category", methods={"POST"})
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function createCategory(Request $request): JsonResponse
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        $category = new Category(
            $request->get('name'),
            $request->get('description')
        );

        $em->persist($category);
        $em->flush($category);

        return new JsonResponse([], 201);
    }

    /**
     * @Route("/category/{id}", name="get_category", methods={"GET"})
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws Exception
     */
    public function getCategory(Request $request): JsonResponse
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        /** @var Product $category */
        $category = $em->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c')
            ->where('c.id = :id')
            ->setParameter('id', $request->attributes->get('id'))
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $category) {
            throw new Exception('Category not found');
        }

        return new JsonResponse($category->toArray(), 200);
    }

    /**
     * @Route("/category/{id}", name="delete_category", methods={"DELETE"})
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function deleteCategory(Request $request): JsonResponse
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        /** @var Product $category */
        $category = $em->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c')
            ->where('c.id = :id')
            ->setParameter('id', $request->attributes->get('id'))
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $category) {
            throw new Exception('Category not found');
        }

        $em->remove($category);
        $em->flush($category);

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/category", name="list_categories", methods={"GET"})
     *
     * @throws Exception
     */
    public function listCategory(Request $request): JsonResponse
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        /** @var Product $product */
        $categories = $em->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c')
            ->getQuery()
            ->getResult();

        $response = [];
        foreach ($categories as $category) {
            $response []= $category->toArray();
        }

        return new JsonResponse($response, 200);
    }

    /**
     * @Route("/product", name="create_product", methods={"POST"})
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function createProduct(Request $request): JsonResponse
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        $category = $em->createQueryBuilder()
            ->select('c')
            ->from(Category::class, 'c')
            ->where('c.id = :id')
            ->setParameter('id', $request->get('category_id'))
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $category) {
            throw new Exception('Category not found');
        }

        $product = new Product(
            $request->get('name'),
            $request->get('description'),
            $category
        );

        $em->persist($product);
        $em->flush($product);

        return new JsonResponse([], 201);
    }

    /**
     * @Route("/product/{id}", name="get_product", methods={"GET"})
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws Exception
     */
    public function getProduct(Request $request): JsonResponse
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        /** @var Product $product */
        $product = $em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->where('p.id = :id')
            ->setParameter('id', $request->attributes->get('id'))
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $product) {
            throw new Exception('Product not found');
        }

        return new JsonResponse($product->toArray(), 200);
    }

    /**
     * @Route("/product/{id}", name="delete_product", methods={"DELETE"})
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function deleteProduct(Request $request): JsonResponse
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        /** @var Product $product */
        $product = $em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->where('p.id = :id')
            ->setParameter('id', $request->attributes->get('id'))
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $product) {
            throw new Exception('Product not found');
        }

        $em->remove($product);
        $em->flush($product);

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/product", name="list_products", methods={"GET"})
     *
     * @throws Exception
     */
    public function listProduct(Request $request): JsonResponse
    {
        $em = $this->get('doctrine.orm.default_entity_manager');

        /** @var Product $product */
        $products = $em->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->getQuery()
            ->getResult();

        $response = [];
        foreach ($products as $product) {
            $response []= $product->toArray();
        }

        return new JsonResponse($response, 200);
    }
}
