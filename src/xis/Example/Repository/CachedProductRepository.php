<?php
namespace xis\Example\Repository;

use xis\Example\Cache\Cache;
use xis\Example\Entity\Product;

class CachedProductRepository implements ProductRepository
{
    /** @var ProductRepository */
    private $repository;
    /** @var Cache */
    private $cache;
    private $keyPrefix = 'products_';

    /**
     * @param ProductRepository $repository
     * @param Cache $cache
     */
    function __construct(ProductRepository $repository, Cache $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @return Product[]
     */
    public function findAll()
    {
        $key = $this->getKey('all');
        return $this->findOrCreate($key, function (ProductRepository $repository) {
            return $repository->findAll();
        });
    }

    /**
     * @param int $id
     * @return Product
     */
    public function findById($id)
    {
        $key = $this->getKey('byId:' . $id);
        return $this->findOrCreate($key, function (ProductRepository $repository) use ($id) {
            return $repository->findById($id);
        });
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function save(Product $product)
    {
        $ret = $this->repository->save($product);
        $this->onProductUpdate($product);
        return $ret;
    }

    protected function onProductUpdate(Product $product)
    {
        $key = $this->getKey('byId:' . $product->getId());
        $this->cache->remove($key);
        $key = $this->getKey('all');
        $this->cache->remove($key);
    }

    /**
     * @param $key
     * @return string
     */
    private function getKey($key)
    {
        return $this->keyPrefix . $key;
    }

    /**
     * @param $key
     * @param $callback
     * @return mixed
     */
    private function findOrCreate($key, $callback)
    {
        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }
        $ret = $callback($this->repository);
        $this->cache->set($key, $ret);
        return $ret;
    }
}