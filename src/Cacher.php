<?php declare (strict_types = 1);

namespace Vrerabek;

use Exception;

class Cacher
{

    private $rootPath;

    private $cachedContentFile;
    private $lastCacheFile;

    private $updatedAt;

    public function __construct(string $root)
    {
        $this->rootPath = $root;

        $this->cachedContentFile = $this->rootPath . '/content.json';
        $this->lastCacheFile = $this->rootPath . '/lastCache.json';
    }

    /**
     * Checks if should cache based on time
     * @return bool
     */
    public function shouldCache(int $cacheSeconds)
    {

        if (file_exists($this->lastCacheFile)) {
            $this->updatedAt = json_decode(file_get_contents($this->lastCacheFile), true)['updated_at'];
        } else {
            $this->newUpdateAt();
        }

        $diff = (int) time() - (int) $this->updatedAt;

        // Cache is outdated
        if ($diff > $cacheSeconds) {
            return true;
        }

        return false;

    }

    /**
     * Returns cached content
     * Returns null if nothing cached
     * 
     * @return Array||null
     */
    public function getCachedContent()
    {

        if (file_exists($this->cachedContentFile)) {
            return json_decode(file_get_contents($this->cachedContentFile),true);
        }

        return null;
    }

    /**
     * Caches supplied content
     * @param Array $content
     */
    public function cacheContent($content)
    {
        if ($content === null){
            throw new Exception('HTTP Request failed, please check your Trello credentials');
        }
        file_put_contents($this->cachedContentFile, json_encode($content));
        $this->newUpdateAt();
    }

    /**
     * Updates timestamp of cache
     */
    private function newUpdateAt()
    {
        $time = time();
        file_put_contents($this->lastCacheFile, json_encode(['updated_at' => $time]));
        $this->updatedAt = $time;
    }
}
