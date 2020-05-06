<?php

namespace App\AbstractClasses;

abstract class ExchangeRateSourceAbstract{

  protected $url;

  public function __construct ($url)
  {
    $this->url=$url;
  }

  protected function loadXMLSource ()
  {
    $xml=simplexml_load_file ($this->url) or die ("Can't load URL");
    return $xml;
  }

  abstract public function getConversionOptions ();
  abstract protected function extractOptionTitles ($source);
  abstract public function convertAmount ($selectedRate,$amount);
  abstract public function getSelectedExchangeRate ($selectedRate);
  abstract protected function extractSelectedOption ($data,$selection);
}


 ?>
