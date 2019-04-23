<?php

class Currency{
  function Currency() {
    $this->apiURL = 'https://www.google.com/finance/converter';
  }

  public function convert($convertFrom, $convertTo, $amount){
    $rawHTML = file_get_contents("$this->apiURL?a=$amount&from=$convertFrom&to=$convertTo");
    preg_match_all("/bld>([0-9\\.]+) ([A-Z]+)<\\//", $rawHTML, $output_array);
    return $output_array[1][0];
  }

  public function getAllCurrency(){
    $rawHTML = file_get_contents($this->apiURL);
    preg_match_all("/value=\"([A-Z]+)\">(.+?)<\\/option>/", $rawHTML, $output_array);
    $array = array();
    foreach ($output_array[0] as $key => $value)
    array_push($array, array("full" => $output_array[2][$key], "abb" => $output_array[1][$key]));

    return $array;
  }
}

