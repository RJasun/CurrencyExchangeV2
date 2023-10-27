<?php

namespace App;
class CurrencyCollection
{
    private array $currencies;
    public function __construct(array $currencies = []) {
        foreach ($currencies as $currency) {
            $this->add(new Currency($currency));
        }
    }
    public function add(Currency $currency): void {
        $this->currencies[] = $currency;
    }
    public function getCurrencies(): array {
        return $this->currencies;
    }
    public function getIsoCodes(): array {
        $isoCodes = [];
        foreach ($this->currencies as $currency) {
            /**
             * @var Currency $currencies
             */
            $isoCodes[] = $currency->getIsoCode();
        }
        return $isoCodes;
    }
}