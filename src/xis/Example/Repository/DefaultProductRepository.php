<?php
namespace xis\Example\Repository;

use xis\Example\Entity\Product;

class DefaultProductRepository implements ProductRepository
{
    private $filename;
    private $products = array();
    private $maxId = 0;

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->loadProducts();
    }

    /**
     * @return Product[]
     */
    public function findAll()
    {
        $this->doSomeExpensiveStuff();
        return $this->products;
    }

    /**
     * @param int $id
     * @return Product
     */
    public function findById($id)
    {
        $this->doSomeExpensiveStuff();
        return $this->products[$id];
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function save(Product $product)
    {
        if (!$product->getId()) {
            $product->setId(++$this->maxId);
        }

        $this->products[$product->getId()] = $product;
        $this->saveProducts();

        return $product;
    }

    /**
     * @return null
     */
    private function loadProducts()
    {
        $this->products = array();
        $lines = @file($this->filename);
        if ($lines === false) {
            return;
        }
        foreach ($lines as $line) {
            /** @var Product $p */
            $p = unserialize($line);
            if ($p->getId() > $this->maxId) {
                $this->maxId = $p->getId();
            }
            $this->products[$p->getId()] = $p;
        }
    }

    /**
     * @return null
     */
    private function saveProducts()
    {
        $f = fopen($this->filename, 'w+');
        foreach ($this->products as $product) {
            fwrite($f, serialize($product) . "\n");
        }
        fclose($f);
    }

    /**
     * @return null
     */
    private function doSomeExpensiveStuff()
    {
        sleep(3);
    }
}