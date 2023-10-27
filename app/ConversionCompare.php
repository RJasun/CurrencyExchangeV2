<?php
declare(strict_types=1);

namespace App;

class ConversionCompare
{
    private FreeCurrencyApiConversion $freeApi;
    private FastForexApiConversion $fastApi;

    public function __construct()
    {
        $this->freeApi = new FreeCurrencyApiConversion();
        $this->fastApi = new FastForexApiConversion();
    }

    public function compareAndRecommend(Currency $baseCurrency, CurrencyCollection $currencies, float $amount): void
    {
        $freeResults = $this->freeApi->exchange($baseCurrency, $currencies, $amount);
        $fastResults = $this->fastApi->exchange($baseCurrency, $currencies, $amount);

        foreach ($freeResults as $isoCode => $freeData) {
            if (isset($fastResults[$isoCode])) {
                $freeRate = $freeData['rate'];
                $fastRate = $fastResults[$isoCode]['rate'];

                $freeVal = number_format($freeData['value'], 2, '.', '');
                $fastVal = number_format($fastResults[$isoCode]['value'], 2, '.', '');

                echo "Conversion outcome for {$isoCode}:" . PHP_EOL;
                echo "From FreeCurrencyApi: {$amount} {$baseCurrency->getIsoCode()} = {$freeVal} {$isoCode}" . PHP_EOL;
                echo "From FastForexApi: {$amount} {$baseCurrency->getIsoCode()} = {$fastVal} {$isoCode}" . PHP_EOL;

                if ($freeRate > $fastRate) {
                    echo "Recommendation: FastForexApi offers a better rate of {$fastRate} for {$isoCode}." . PHP_EOL;
                } else {
                    echo "Recommendation: FreeCurrencyApi offers a smaller rate of {$freeRate} for {$isoCode}." . PHP_EOL;
                }
                echo "---------------------------------------------------" . PHP_EOL;
            }
        }
    }
}