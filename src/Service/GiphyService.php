<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class GiphyService
{


    public function __construct(
        private HttpClientInterface $client,
        #[Autowire('%env(GIPHY_API_KEY)%')]
        private string $apiKey) {}
    

    public function queryGiphy(string $keyword): ?string
    {
       // $gifWord = urlencode(trim($keyword));
       $gifWord = strtolower(preg_split('/\s+/', trim($keyword))[0] ?? 'awesome');
        $response = $this->client->request(
            'GET',
            'https://api.giphy.com/v1/gifs/search',
            [
                'query' => [
                    'api_key' => $this->apiKey,
                    'q' => $gifWord,
                    'limit' => 10,
                    'rating' => 'g',
                ]
            ]
        );
       
dump($response);
        if($response->getStatusCode() !== 200)
        {
            return 'https://giphy.com';
        }

        $data = $response->toArray();

        $gif = $data['data'][array_rand($data['data'])];
        return $gif['images']['original']['url']??'';

    }
}