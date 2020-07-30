<?php declare (strict_types = 1);

namespace Vrerabek;

use Exception;
use GuzzleHttp\Client;

/**
 * Reads the supplied array of contents and returns readable array
 *
 */

class TrelloReader
{
    private $client;
    private $boardContents;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Make HTTP Request from trello, then format the results and return it as an array
     *
     * @return Array
     */
    public function getBoardContents()
    {
        $params = '&cards=visible&card_fields=id,desc,url,name';
        $url = $_ENV['TRELLO_BOARD_URL'] . '.json?key=' . $_ENV['TRELLO_API_KEY'] . '&token=' . $_ENV['TRELLO_API_TOKEN'] . $params;

        $response = $this->client->request('GET', $url, ['http_errors' => false]);

        if ($response->getStatusCode() < 400) {
            $contents = $response->getBody()->getContents();
            $this->boardContents = json_decode($contents, true);
            $cards = $this->getContentCards();
            return $cards;
        } else {
            return null;
        }

    }

    /**
     * Format Trello board array
     * 
     * @return Array cards from Trello board in multidimensional array. ['nameofcard' => 'description']
     */
    private function getContentCards()
    {
        $formattedContent = [];
        if (is_array($this->boardContents) && count($this->boardContents['cards']) >= 1) {
            foreach ($this->boardContents['cards'] as $card) {
                $formattedContent[$card['name']] = $card['desc'];
            }
        } else {
            throw new Exception("Cannot fetch any data from this board. Either bad URL in .env file or there are no visible cards to fetch.");
        }

        return $formattedContent;
    }
}
