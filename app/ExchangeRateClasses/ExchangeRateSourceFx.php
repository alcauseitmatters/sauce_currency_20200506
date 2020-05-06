<?php

namespace App\ExchangeRateClasses;

use App\AbstractClasses\ExchangeRateSourceAbstract;

class ExchangeRateSourceFx extends ExchangeRateSourceAbstract
{

  public function __construct ($url)
  {
    parent::__construct ($url);
  }

  public function getConversionOptions (){
    $json=json_encode ($this->extractOptionTitles ($this->loadXMLSource()));

    return $json;
  }

  protected function extractOptionTitles ($xml){
    $return_options=[];

    $json=json_encode($xml);

    $array_json=json_decode (json_encode($xml));

    $source_node=$array_json->channel->item;

    for ($a=0;$a<sizeof ($source_node);$a++)
    {
      array_push ($return_options,$source_node[$a]->title);
    }

    return $return_options;

  }

  public function convertAmount ($selectedRate,$amount){

    $exchange_rate_json=json_encode($this->getSelectedExchangeRate($selectedRate));
    $exchange_rate_array=json_decode(json_decode($exchange_rate_json));
    $exchange_rate=$exchange_rate_array->exchangeRate;

    $conversion=(float)$exchange_rate*(float)$amount;
    return $conversion;

  }

  public function getSelectedExchangeRate ($selectedRate){
    $json=json_encode ($this->extractSelectedOption ($this->loadXMLSource(),$selectedRate));
    return $json;
  }

  protected function extractSelectedOption ($data,$selection){
    $source_node=null;
    $rearrange_node=[];
    $options_array=json_decode(json_encode($data));
    $items_node=$options_array->channel->item;
    $source_node=$items_node[$selection];

    $rearrange_node['title']=$source_node->description;

    $the_rate=$this->extractExchangeRate ($rearrange_node['title']);

    $rearrange_node['exchangeRate']=$the_rate;

    return $rearrange_node;

  }

  private function extractExchangeRate ($exchange_str)
  {
    $amount=null;

    $split_str_stage1=null;
    $split_str_stage2=null;

    //"description": "1 British Pound = 1.93319 Australian Dollar",
    $split_str_stage1=explode ('=',$exchange_str);
    // [1 British Pound ]=[ 1.93319 Australian Dollar]
    $split_str_stage2=explode (' ',$split_str_stage1[1]);

    $amount=$split_str_stage2[1];

    return $amount;
  }

}

 ?>
