<?php

require __DIR__ . '/vendor/autoload.php';

try {

    $vreClient = new \Vrerabek\VreClient();
    $vreClient->setDev(true);
    $vreClient->setCacheSeconds(5);
    $cards = $vreClient->getCards();
    var_dump($cards);

} catch (Exception $e) {

    echo $e->getMessage();

}
