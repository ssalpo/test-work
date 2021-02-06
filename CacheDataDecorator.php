<?php

namespace src\Integration;

/**
 * Class CacheDecorator
 *
 * @package src\Integration
 */
class CacheDataDecorator implements DataProvideable
{
    /** @var DataProvideable */
    private $provider;

    /** @var CacheItemPoolInterface **/
    private $cache;

    /**
     * CacheDecorator constructor.
     *
     * @param DataProvider           $provider
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(DataProvideable $provider, CacheItemPoolInterface $cache)
    {
        $this->provider = $provider;
        $this->cache = $cache;
    }

    /**
     * @param array $request
     *
     * @return array
     */
    public function get(array $request): array
    {
        $cacheKey = $this->getCacheKey($request);
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return (array)$cacheItem->get();
        }

        $result = $this->provider->get($request);

        $cacheItem
            ->set($result)
            ->expiresAt(
                (new DateTime())->modify('+1 day')
            );

        $this->cache->save($cacheItem);

        return $result;
    }

    public function getCacheKey(array $input): string
    {
        return md5(serialize($input));
    }
}
