<?php
namespace xis\Example\Repository;

use xis\Example\Entity\Product;

interface ProductRepository
{
    /**
     * @return Product[]
     */
    public function findAll();

    /**
     * @param int $id
     * @return Product
     */
    public function findById($id);

    /**
     * @param  Product $product
     * @return Product
     */
    public function save(Product $product);
} 