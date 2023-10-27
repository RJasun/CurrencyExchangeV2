<?php

namespace App;

use GuzzleHttp\Client;

class FreeCurrencyApiConversion
{
    private Client $client;
    private const FREECURRENCY_API_URL = 'https://api.freecurrencyapi.com/v1/latest';

    public function __construct()
    {
        $this->client = new Client();
    }

    private function getUrl(Currency $baseCurrency, CurrencyCollection $currencies): string
    {
        $params = [
            'apikey' => $_ENV['FREECURRENCY_API_KEY'],
            'currencies' => implode($currencies->getIsoCodes()),
            'base_currency' => $baseCurrency->getIsoCode(),
        ];
        return self::FREECURRENCY_API_URL . '?' . http_build_query($params);
    }
    public function exchange(Currency $baseCurrency, CurrencyCollection $currencies, float $amount): array
    {
        $url = $this->getUrl($baseCurrency, $currencies);

        $result = $this->client->get($url);
        $result = (string)$result->getBody();
        $result = json_decode($result);
        $results = [];
        foreach ($result->data as $isoCode => $rate)
        {
            $results[$isoCode]['value'] = $rate * $amount;
            $results[$isoCode]['rate'] = $rate;
        }
        return $results;
    }

}