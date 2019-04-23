<?

error_reporting(1);

require_once('currency.class.php');

$currency = new Currency();

$allCurrency = $currency->getAllCurrency();

foreach ($allCurrency as $key => $value) {
  echo $value["full"] . ' = ' . $value["abb"];
}
