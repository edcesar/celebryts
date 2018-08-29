<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class Localizador implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $target = $request->getQueryParams()['target'] ?? '';

        $settings = array(
            'oauth_access_token' => "369818935-mHeBebrdFRQwXiRGJjo5qlpoRuFtxtQaejqZVkGQ",
            'oauth_access_token_secret' => "0sxDr67NIz13G5Idj9jHB1oyZ9Jp8I6yBSVXLN5mLp1vW",
            'consumer_key' => "szA2TxAzVy990n5JqSNgHivnP",
            'consumer_secret' => "w00hoDqir7Jhy06HIl2qpjSfxMkaGIeUmwftxYR4MscMGtbQqJ",
        );

        $url = 'https://api.twitter.com/1.1/users/show.json';
        $getfield = "?screen_name={$target}";
        $requestMethod = 'GET';

        $twitter = new \TwitterAPIExchange($settings);
        $twitter = $twitter->setGetfield($getfield)
         ->buildOauth($url, $requestMethod)
         ->performRequest();

        $twitter = json_decode($twitter);

        $client = new \GuzzleHttp\Client();
        $geocode = $client->request('GET', 'https://maps.googleapis.com/maps/api/geocode/json', [
            'query' => [
                'key' => 'AIzaSyBbHhrEnpx8BY25IY2wreK9KpUMhAgzUCY',
                'address' => $twitter->location,
            ]
        ]);

        $geocode = json_decode($geocode->getBody()->getContents());

        $data = [
            'nome' => $twitter->name,
            'endereco' => $geocode->results[0]->formatted_address,
            'googleMaps' => "https://www.google.com/maps/place/?q=place_id:{$geocode->results[0]->place_id}"
        ];

        return new JsonResponse(['data' => $data]);
    }
}
