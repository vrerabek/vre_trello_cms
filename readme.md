# VRE Trello CMS

VRE Trello CMS is extremely lightweight and simple PHP Library, which let's you get content for your website from your Trello Board.

# Installation
WIP 🔨
- Install package by running ```composer install !!!TODO!!!```
- Create .env file in your project root folder (or any other, check Troubleshooting section) and insert following environmental variables (or append this to your .env file if you are already using one)
```
TRELLO_API_KEY="your_trello_api_key_goes_here"
TRELLO_API_TOKEN="your_trello_api_token_goes_here"
TRELLO_BOARD_URL="your_trello_board_url_goes_here"
```

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
That's all. :)

## Methods
```->setDev(true)``` It's recommended to use  while developing. It ignores caching so you can see your results in real time. 
```php
$vreClient->setDev(true);
```

```->setCacheSeconds(60)``` Sets seconds untill cache expires.
```php
$vreClient->setCacheSeconds(60);
```


## Caching
Really simple caching is implemented by storing two files in your root ```content.json``` and ```lastCache.json```
If you change your root folder directory for ```.env``` (check Troubleshooting section), cache files will be stored there also.

## Troubleshooting
**.env file directory**
By default, VRE searches for .env file and stores cache in ```$_SERVER['DOCUMENT_ROOT']``` 
If you want to store .env file in different folder or the default is not working, you can supply directory to ```VreClient``` constructor like this ```$vreClient = new \Vrerabek\VreClient(dirname(__FILE__ ) . '/src');```



