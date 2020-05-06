<?php

namespace App\Http\Controllers\CurrencyExchange;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ExchangeRateClasses\ExchangeRateSourceFloat;
use App\ExchangeRateClasses\ExchangeRateSourceFx;

class CurrencyExchangeController extends Controller
{
    private $data_source;

    public function __construct ()
    {
      $url=env ('CURRENCY_EXCHANGE_SOURCE');
      if ($url=="http://www.floatrates.com/daily/gbp.xml"){
        $this->data_source=new ExchangeRateSourceFloat ($url);
      }
      else if ($url=="https://gbp.fxexchangerate.com/rss.xml"){
          $this->data_source=new ExchangeRateSourceFx ($url);
      }
    }

    public function getExchangeRateOptions (Request $request)
    {
      if (isset($this->data_source)){
        return $this->data_source->getConversionOptions ();
      }
    }

    public function getSelectedExchangeRate (Request $request)
    {
      $data=$request->validate ([
        'selectedRate'=>'required|not_regex:[^0-9\.]',
      ]);

      //if ($request->selectedRate){
        return $this->data_source->getSelectedExchangeRate ($request->selectedRate);
      //}

    }


    public function convertAmount (Request $request)
    {
      $data=$request->validate ([
        'selectedRate'=>'required|not_regex:[^0-9\.]',
        'amount'=>'required|not_regex:[^0-9\.]',
      ]);
//      if ($request->selectedRate && $request->amount){
        return $this->data_source->convertAmount ($request->selectedRate,$request->amount);
//      }
    }
}
