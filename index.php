<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';


use App\Currency;
use App\CurrencyCollection;
use App\ConversionCompare;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$input = readline("Enter conversion: ");
$targetCurrencyIsoCode = explode(' ', readline("Enter currency to convert to: "));
[$amount, $baseCurrencyIsoCode] = explode(' ', $input);

$targetCurrencies = new CurrencyCollection($targetCurrencyIsoCode);

$comparator = new ConversionCompare();
$comparator->compareAndRecommend(
    new Currency($baseCurrencyIsoCode),
    $targetCurrencies,
    (float) $amount
);