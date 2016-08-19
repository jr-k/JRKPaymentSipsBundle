<?php


/*
    Copyright 2014 Jessym Reziga https://github.com/jreziga/JRKPaymentSipsBundle

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace JRK\PaymentSipsBundle\Services;


use Symfony\Component\DependencyInjection\ContainerInterface as Container;


/*
*  	Powered by the WCE Community
*	Worst code ever presents... The JRKPaymentSips service !
*/
class JRKPaymentSips {



    public $container;

    public $datas_request;

    public function __construct(Container $container = null) {
        $this->container = $container;
    }

    public function p($str){
        return $this->container->getParameter($str);
    }

    public function defset(&$arr,$str,$url = false){
        if (!array_key_exists($str,$arr)){
            if ($url){
                $val = $this->container->get('router')->generate($this->p("jrk_sips_".$str),array(),true);
                $this->datas_request[$str] = $val;
                return $str = $str."=".$val." ";
            }
            $val = $this->p("jrk_sips_".$str);
            if ($str == "currency_code") $val = $this->getCurrencySipsCode($val);
            $this->datas_request[$str] = $val;
            return $str = $str."=".$val." ";
        }
        else if ($url) {
            $val = $this->container->get('router')->generate("jrk_sips_".$arr[$str],array(),true);
            $arr[$str] = $val;
            $this->datas_request[$str] = $val;
            return "";
        }
    }

    public function get_sips_request($attrbs = array(),$transaction = null, $tag = null){

		$path_bin = $this->p("jrk_sips_request");


		if (empty($path_bin)) {
			throw new \Exception('jrk_payment_sips.files.sips_request is not set');
		}
        if (count($attrbs) <= 0){
            throw new \Exception('Specify an amount : array("amount" => 1) for 1.00€ for example');
        }

        $attrbs["normal_return_url"] = "payment_response";
        $attrbs["automatic_response_url"] = "payment_auto_response";
        $attrbs["cancel_return_url"] = "payment_cancel";
        $attrbs["amount"]*=100;

        $datas = array();
        $parm = "";
        $parm .= $this->defset($attrbs,"merchant_id");
        $parm .= $this->defset($attrbs,"merchant_country");
        $parm .= $this->defset($attrbs,"currency_code");
        $parm .= $this->defset($attrbs,"pathfile");
        $parm .= $this->defset($attrbs,"normal_return_url",true);
        $parm .= $this->defset($attrbs,"cancel_return_url",true);
        $parm .= $this->defset($attrbs,"automatic_response_url",true);
        $parm .= $this->defset($attrbs,"language");
        $parm .= $this->defset($attrbs,"payment_means");
        $parm .= $this->defset($attrbs,"header_flag");
        $parm .= $this->defset($attrbs,"order_id");

        foreach($attrbs as $k => $v){ $parm .= $k."=".$v." "; $this->datas_request[$k] = $v; }

        $parm = escapeshellcmd($parm);
        $result = exec("$path_bin $parm");

                if (empty($result)) {
			throw new \Exception('Empty request from sips_request file, result '.$result);
		}
        $tableau = explode("!", "$result");
        $this->datas_request["code"] = $tableau[1];
        $this->datas_request["error"] = $tableau[2];
        $this->datas_request["render"] = $tableau[3];
        $this->datas_request["path_bin"] = $path_bin;

		if (( $this->datas_request["code"] == "" ) && ( $this->datas_request["error"] == "" ) ) {
            throw new \Exception("call to $path_bin failed");
        }
        elseif ($this->datas_request["code"] != 0) {
            throw new \Exception("SIPS returns the following error: \n ".$this->datas_request["error"]);
        }

        if (!array_key_exists(3, $tableau)) {
            throw new \Exception("No message returned by SIPS \n Error: ".$this->datas_request["error"]);
        }

        return $this->datas_request["render"];
    }

	public function getCurrencySipsCode($currency_iso)
    {
		if (preg_match('#^([0-9]+)$#',$currency_iso))
			return $currency_iso;

        $currencies = array(
            "EUR" => "978","USD" => "840",'EUR' => '978', 'USD' => '840','CHF' => '756','GBP' => '826',
            'CAD' => '124','JPY' => '392', 'MXP' => '484','TRL' => '792','AUD' => '036','NZD' => '554',
            'NOK' => '578', 'BRC' => '986','ARP' => '032','KHR' => '116','TWD' => '901','SEK' => '752',
            'DKK' => '208','KRW' => '410','SGD' => '702',
        );
        if (! array_key_exists($currency_iso, $currencies)) {
            throw new \Exception("Uknown currency $currency_iso, Known currencies: ".implode(',', array_keys($currencies)));
        }
        return $currencies[$currency_iso];
    }

}
