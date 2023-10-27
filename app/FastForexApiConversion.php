<?php

namespace App;

use GuzzleHttp\Client;

class FastForexApiConversion
{
    private Client $client;
    private const FASTFOREX_API_URL = 'https://api.fastforex.io/fetch-multi';

    public function __construct()
    {
        $this->client = new Client();
    }

    private function getUrl(Currency $baseCurrency, CurrencyCollection $currencies): string
    {
        $params = [
            'api_key' => $_ENV['FASTFOREX_API_KEY'],
            'from' => $baseCurrency->getIsoCode(),
            'to' => implode(',', $currencies->getIsoCodes())
        ];
        return self::FASTFOREX_API_URL . '?' . http_build_query($params);
    }

    public function exchange(Currency $baseCurrency, CurrencyCollection $currencies, float $amount): array
    {
        $url = $this->getUrl($baseCurrency, $currencies);

        $result = $this->client->get($url);
        $result = (string)$result->getBody();
        $result = json_decode($result);

        $results = [];
        foreach ($result->results as $isoCode => $rate) {
            $results[$isoCode]['value'] = $rate * $amount;
            $results[$isoCode]['rate'] = $rate;
        }
        return $results;
    }

}