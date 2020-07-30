<?php declare (strict_types = 1);

namespace Vrerabek;

use Exception;

/**
 * Bread and butter of this library.
 *
 * Manages communication with trello API.
 * You preferably want to keep instance of this is some kind of DI.
 */
class VreClient
{
    private $cards;
    private $rootPath;
    private $trelloReader;
    private $cacher;
    private $cacheSeconds;

    private $devMode;

    /**
     * @param Array $content Array containing the trello cards
     * @param string $root Optional parameter containing path to file where .env file is located
     */
    public function __construct(string $root = null)
    {

        $this->devMode = false;

        if ($root === null) {
            $this->rootPath = $_SERVER['DOCUMENT_ROOT'];
            $dotenv = \Dotenv\Dotenv::createImmutable($this->rootPath);
            $dotenv->load();
        } else {
            $this->rootPath = $root;
            $dotenv = \Dotenv\Dotenv::createImmutable($this->rootPath);
            $dotenv->load();
        }

        // Check if all env variables are set
        if (!isset($_ENV['TRELLO_API_KEY']) || !isset($_ENV['TRELLO_API_TOKEN']) || !isset($_ENV['TRELLO_BOARD_URL'])) {
            throw new Exception('Some .env variables not set');
        }

        // Default cache time 6000 seconds
        $this->cacheSeconds = 6000;

        $this->trelloReader = new TrelloReader($this->devMode);
        $this->cacher = new Cacher($this->rootPath);
    }

    /**
     * Fetch board from Trello and return formatted cards
     *
     * @return Array
     */
    public function getCards()
    {
        if ($this->devMode) {
            $this->cards = $this->trelloReader->getBoardContents();
            $this->cacher->cacheContent($this->cards);
            return $this->cards;
        }

        if ($this->cacher->shouldCache($this->cacheSeconds)) {
            $this->cards = $this->trelloReader->getBoardContents();
            $this->cacher->cacheContent($this->cards);
        } else {
            $cached = $this->cacher->getCachedContent();

            if ($cached !== null) {
                $this->cards = $cached;
            } else {
                $this->cards = $this->trelloReader->getBoardContents();
                $this->cacher->cacheContent($this->cards);
            }
        }

        return $this->cards;
    }

    /**
     * Using this method, vre won't cache.
     * Recommended for development, since we must cache in production to fit into Trello API limits
     */
    public function setDev(bool $mode)
    {
        $this->devMode = $mode;
    }

    /**
     * Set how long before cache file expires
     * @param int $seconds cache expire time in seconds
     */
    public function setCacheSeconds(int $seconds)
    {
        $this->cacheSeconds = $seconds;
    }

}
