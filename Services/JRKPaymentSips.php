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
		else if ($url && $str != "cancel_return_url") {
			$val = $this->container->get('router')->generate("jrk_sips_".$arr[$str],array(),true);
			$arr[$str] = $val;
			$this->datas_request[$str] = $val;
			return "";
		}
    }

    public function get_sips_response(){
        $details = $this->container->get('session')->getFlashBag()->get('sips_request_details');
        if (array_key_exists("0",$details))
            return $details[0];
        return array();
    }

    public function get_sips_auto_response(){
        $details = $this->container->get('session')->getFlashBag()->get('sips_request_details_auto');
        if (array_key_exists("0",$details))
            return $details[0];
        return array();
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



        foreach($attrbs as $k => $v){ $parm .= $k."=".$v." "; $this->datas_request[$k] = $v; }
        /*
		$parm="$parm capture_day="; $parm="$parm capture_mode="; $parm="$parm bgcolor="; $parm="$parm order_id=";
		$parm="$parm block_align="; $parm="$parm block_order="; $parm="$parm textcolor="; $parm="$parm transaction_id=";
		$parm="$parm receipt_complement="; $parm="$parm caddie=mon_caddie"; $parm="$parm customer_id=";
		$parm="$parm customer_ip_address="; $parm="$parm data="; $parm="$parm return_context="; $parm="$parm target=";
        */

        /*
        $parm .=";";
        foreach($user_data as $k => $v) { $parm.=$k."=".$v.";";}
        $parm = substr($parm,0,-1);
        */

        $parm = escapeshellcmd($parm);



        $result = exec("$path_bin $parm");
		//echo $path_bin." ".$parm;
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


        $this->sips_save_entity($transaction, true, $tag);

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

    public function sips_clear() {
        $this->container->get('session')->remove('sips_entity');
        $this->container->get('session')->remove('sips_entity_tag');
    }

    public function sips_save_entity($item,$clear = true, $tag = null){
        if ($clear) $this->sips_clear();
        $this->container->get('session')->set('sips_entity', $item);

        if ($tag != null) {
            $this->container->get('session')->set('sips_entity_tag', $tag);
        }
    }

    public function sips_load_entity($clear = true){
        $entity = $this->container->get('session')->get('sips_entity');

        if ($this->container->get('session')->has('sips_entity_tag')) {
            $tag = $this->container->get('session')->get('sips_entity_tag');
            if ($clear) $this->sips_clear();
            return array($entity, $tag);
        }

        if ($clear) $this->sips_clear();
        return $entity;
    }

}
