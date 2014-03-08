<?php
namespace xis;

use xis\Example\Cache\SimpleFileCache;
use xis\Example\Repository\CachedProductRepository;
use xis\Example\Repository\DefaultProductRepository;

class DecoratorExample
{
    private $useCache;

    public function __construct($useCache = true)
    {
        $this->useCache = $useCache;
    }

    public function doSomeCoolStuff()
    {
        $productRepository = $this->createProductRepository($this->useCache);

        echo '1) getting product[1]<br/>';
        $p = $productRepository->findById(1);
        echo 'product[1].name=' . $p->getName() . '<br/>';

        echo '2) getting product[1] again...<br/>';
        $p = $productRepository->findById(1);
        echo 'product[1].name=' . $p->getName() . '<br/>';

        echo '3) setting product[1] new name...<br/>';
        $p->setName('Some new name [' . time() . ']');
        $productRepository->save($p);

        echo '4) getting product[1] one more time...<br/>';
        $p = $productRepository->findById(1);
        echo 'product[1].name=' . $p->getName() . '<br/>';
    }

    function createProductRepository($useCache)
    {
        $dir = __DIR__ . '/../../';
        $productRepository = new DefaultProductRepository($dir . '/data/products.txt');

        if ($useCache) {
            $cache = new SimpleFileCache($dir . '/cache');
            $cache->clear();
            $productRepository = new CachedProductRepository($productRepository, $cache);
        }

        return $productRepository;
    }
}