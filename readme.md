# vre_trello_cms

vre_trello_cms is extremely simple PHP Library, which let's you get content for your website from your Trello Board. 


# Installation

- Install package by running 
```composer require vrerabek/vre_trello_cms```
- Create .env file in your project root folder (for any other direcory, check Troubleshooting section) and insert following environmental variables (or append this to your .env file if you are already using one)
```
TRELLO_API_KEY="your_trello_api_key_goes_here"
TRELLO_API_TOKEN="your_trello_api_token_goes_here"
TRELLO_BOARD_URL="your_trello_board_url_goes_here"
```
*You can get your api key and token here https://trello.com/app-key*

# How to use
```php
require __DIR__ . '/vendor/autoload.php';

try {
    $vreClient = new \Vrerabek\VreClient();
    $vreClient->setDev(false);
    $vreClient->setCacheSeconds(600);
    $cards = $vreClient->getCards();
    echo $cards['title'];

} catch (Exception $e) {

    echo $e->getMessage();

}
```
All cards from your board are stored in ```Array``` by calling ```->getCards()```. 

Array key is name of the Card. For example ```$cards['title']``` holds description of card in your Trello board named ```title```.

‼️ Accessing cards is case-sensitive. So you can't access card named ```Title``` by calling ```$cards['title']``` and vice-versa!

‼️ If there are more cards with the same name, only one (the newest) will be available. It's planned to improve this in future.

## Methods
```->getCards()``` Returns cards of your trello board formatted for easy use. (see example above)
```php
$vreClient->getCards();
```
```->setDev(true)``` It's recommended to use  while developing. It ignores caching so you can see your results in real time. 
```php
$vreClient->setDev(true);
```

```->setCacheSeconds(60)``` Sets seconds untill cache expires.
```php
$vreClient->setCacheSeconds(60);
```


## Caching
Really simple caching is implemented by storing two files: ```content.json``` and ```lastCache.json```. These files are by default stored in your project root folder (see troubleshooting how to change this directory)


## Troubleshooting
**.env file directory**

By default, library searches for .env file and stores cache in ```$_SERVER['DOCUMENT_ROOT']``` 

If you want to store .env file in different folder or the default is not working, you can supply directory to ```VreClient``` constructor like this 

```$vreClient = new \Vrerabek\VreClient(dirname(__FILE__ ) . '/src');```




🕛 *I made this in one evening and it will deffinitely get better in the future :)* 🕛

