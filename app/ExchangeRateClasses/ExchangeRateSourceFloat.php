<?php

namespace App\ExchangeRateClasses;

use App\AbstractClasses\ExchangeRateSourceAbstract;

class ExchangeRateSourceFloat extends ExchangeRateSourceAbstract
{
  public function __construct ($url)
  {
    parent::__construct ($url);
  }

  public function getConversionOptions ()
  {
    $json=json_encode ($this->extractOptionTitles ($this->loadXMLSource()));
    return $json;
  }

  protected function extractOptionTitles ($xml)
  {
    $return_options=[];

    $array_json=json_decode (json_encode($xml));

    $source_node=$array_json->item;

    for ($a=0;$a<sizeof ($source_node);$a++)
    {
      array_push ($return_options,($source_node[$a]->baseName."/".$source_node[$a]->targetName));
    }

    return $return_options;
  }

  public function convertAmount ($selectedRate,$amount)
  {

    $exchange_rate_json=$this->getSelectedExchangeRate($selectedRate);
    $exchange_rate_array=json_decode($exchange_rate_json);
    $exchange_rate=$exchange_rate_array->exchangeRate;

    $conversion=(float)$exchange_rate*(float)$amount;
    return $conversion;
  }

  public function getSelectedExchangeRate ($selectedRate)
  {
    $json=json_encode ($this->extractSelectedOption ($this->loadXMLSource(),$selectedRate));
    return $json;
  }

  protected function extractSelectedOption ($data,$selection)
  {
    $source_node=null;

    $options_array=json_decode(json_encode($data));
    $items_node=$options_array->item;
    $source_node=$items_node[$selection];

    return $source_node;
  }


}

 ?>
