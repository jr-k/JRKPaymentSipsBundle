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

namespace JRK\PaymentSipsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


/* 
*  	Powered by the WCE Community 
*	Worst code ever presents... The ResponseController !
*/

class ResponseController extends Controller
{

    public function responseAction()
    {
        $datas = array();

		
		if (isset($_POST["DATA"])) {
		
			$message="message=".$_POST["DATA"];
			$pathfile="pathfile=".$this->p("jrk_sips_pathfile");
			$path_bin = $this->p("jrk_sips_response");
			$message = escapeshellcmd($message);
			$result=exec("$path_bin $pathfile $message");
			$tableau = explode ("!", $result);
			$datas["code"]= $tableau[1];
			$datas["error"]= $tableau[2];
			$datas["merchant_id"]= $tableau[3];
			$datas["merchant_country"]= $tableau[4];
			$datas["amount"]= $tableau[5];
			$datas["transaction_id"]= $tableau[6];
			$datas["payment_means"]= $tableau[7];
			$datas["transmission_date"]= $tableau[8];
			$datas["payment_time"]= $tableau[9];
			$datas["payment_date"]= $tableau[10];
			$datas["response_code"]= $tableau[11];
			$datas["payment_certificate"]= $tableau[12];
			$datas["authorisation_id"]= $tableau[13];
			$datas["currency_code"]= $tableau[14];
			$datas["card_number"]= $tableau[15];
			$datas["cvv_flag"]= $tableau[16];
			$datas["cvv_response_code"]= $tableau[17];
			$datas["bank_response_code"]= $tableau[18];
			$datas["complementary_code"]= $tableau[19];
			$datas["complementary_info"]= $tableau[20];
			$datas["return_context"]= $tableau[21];
			$datas["caddie"]= $tableau[22];
			$datas["receipt_complement"]= $tableau[23];
			$datas["merchant_language"]= $tableau[24];
			$datas["language"]= $tableau[25];
			$datas["customer_id"]= $tableau[26];
			$datas["order_id"]= $tableau[27];
			$datas["customer_email"]= $tableau[28];
			$datas["customer_ip_address"]= $tableau[29];
			$datas["capture_day"]= $tableau[30];
			$datas["capture_mode"]= $tableau[31];
			$datas["data"]= $tableau[32];
			$datas["order_validity"]= $tableau[33];
			$datas["transaction_condition"]= $tableau[34];
			$datas["statement_reference"]= $tableau[35];
			$datas["card_validity"]= $tableau[36];
			$datas["score_value"]= $tableau[37];
			$datas["score_color"]= $tableau[38];
			$datas["score_info"]= $tableau[39];
			$datas["score_threshold"]= $tableau[40];
			$datas["score_profile"]= $tableau[41];

            $logfile=$this->p("jrk_sips_logs");
            $fp=@fopen($logfile, "a+");

            if (( $datas["code"] == "" ) && ( $datas["error"] == "" ) )
            {
                fwrite($fp, "erreur appel response\n");
                print ("executable response non trouve $path_bin\n");
            }
            else if ( $datas["code"] != 0 ){
                fwrite($fp, " API call error.\n");
                fwrite($fp, "Error message :  ".$datas["error"]."\n");
            }
            else {
                fwrite( $fp, "merchant_id : ".$datas['merchant_id']."\n");
                fwrite( $fp, "merchant_country : ".$datas['merchant_country']."\n");
                fwrite( $fp, "amount : ".$datas['amount']."\n");
                fwrite( $fp, "transaction_id : ".$datas["transaction_id"]."\n");
                fwrite( $fp, "transmission_date: ".$datas['transmission_date']."\n");
                fwrite( $fp, "payment_means: ".$datas['payment_means']."\n");
                fwrite( $fp, "payment_time : ".$datas['payment_time']."\n");
                fwrite( $fp, "payment_date : ".$datas['payment_date']."\n");
                fwrite( $fp, "response_code : ".$datas['response_code']."\n");
                fwrite( $fp, "payment_certificate : ".$datas['payment_certificate']."\n");
                fwrite( $fp, "authorisation_id : ".$datas['authorisation_id']."\n");
                fwrite( $fp, "currency_code : ".$datas['currency_code']."\n");
                fwrite( $fp, "card_number : ".$datas['card_number']."\n");
                fwrite( $fp, "cvv_flag: ".$datas['cvv_flag']."\n");
                fwrite( $fp, "cvv_response_code: ".$datas['cvv_response_code']."\n");
                fwrite( $fp, "bank_response_code: ".$datas['bank_response_code']."\n");
                fwrite( $fp, "complementary_code: ".$datas['complementary_code']."\n");
                fwrite( $fp, "complementary_info: ".$datas['complementary_info']."\n");
                fwrite( $fp, "return_context: ".$datas['return_context']."\n");
                fwrite( $fp, "caddie : ".$datas['caddie']."\n");
                fwrite( $fp, "receipt_complement: ".$datas['receipt_complement']."\n");
                fwrite( $fp, "merchant_language: ".$datas['merchant_language']."\n");
                fwrite( $fp, "language: ".$datas['language']."\n");
                fwrite( $fp, "customer_id: ".$datas['customer_id']."\n");
                fwrite( $fp, "order_id: ".$datas['order_id']."\n");
                fwrite( $fp, "customer_email: ".$datas['customer_email']."\n");
                fwrite( $fp, "customer_ip_address: ".$datas['customer_ip_address']."\n");
                fwrite( $fp, "capture_day: ".$datas['capture_day']."\n");
                fwrite( $fp, "capture_mode: ".$datas['capture_mode']."\n");
                fwrite( $fp, "data: ".$datas['data']."\n");
                fwrite( $fp, "order_validity: ".$datas['order_validity']."\n");
                fwrite( $fp, "transaction_condition: ".$datas['transaction_condition']."\n");
                fwrite( $fp, "statement_reference: ".$datas['statement_reference']."\n");
                fwrite( $fp, "card_validity: ".$datas['card_validity']."\n");
                fwrite( $fp, "card_validity: ".$datas['score_value']."\n");
                fwrite( $fp, "card_validity: ".$datas['score_color']."\n");
                fwrite( $fp, "card_validity: ".$datas['score_info']."\n");
                fwrite( $fp, "card_validity: ".$datas['score_threshold']."\n");
                fwrite( $fp, "card_validity: ".$datas['score_profile']."\n");
                fwrite( $fp, "-------------------------------------------\n");
            }
            fclose ($fp);

		}
        else {
            $logfile=$this->p("jrk_sips_logs");
            $fp=@fopen($logfile, "a+");
            fwrite($fp,"No datas\n");
            fclose($fp);
        }


        $this->get('session')->getFlashBag()->add('sips_request_details', $datas);

        if ($this->hp('jrk_sips_controller_response')) {
            $response = $this->forward($this->p("jrk_sips_controller_response"));
        } else {
            $response = $this->forward($this->routeToControllerName($this->p("jrk_sips_route_response")));
        }

		return $response;
    }

    public function p($str){
        return $this->container->getParameter($str);
    }


    public function hp($str){
        return $this->container->hasParameter($str);
    }


    public function routeToControllerName($routename) {
        $routes = $this->get('router')->getRouteCollection();
		$controllers = $routes->get($routename)->getDefaults();
        return $controllers['_controller'];
    }


    public function autoresponseAction()
    {
        $datas = array();

        if (isset($_POST["DATA"])) {
            $message="message=".$_POST["DATA"];
            $pathfile="pathfile=".$this->p("jrk_sips_pathfile");
            $path_bin = $this->p("jrk_sips_response");
            $message = escapeshellcmd($message);
            $result=exec("$path_bin $pathfile $message");
            $tableau = explode ("!", $result);

            $datas = array();
            $datas["code"]= $tableau[1];
            $datas["error"]= $tableau[2];
            $datas["merchant_id"]= $tableau[3];
            $datas["merchant_country"]= $tableau[4];
            $datas["amount"]= $tableau[5];
            $datas["transaction_id"]= $tableau[6];
            $datas["payment_means"]= $tableau[7];
            $datas["transmission_date"]= $tableau[8];
            $datas["payment_time"]= $tableau[9];
            $datas["payment_date"]= $tableau[10];
            $datas["response_code"]= $tableau[11];
            $datas["payment_certificate"]= $tableau[12];
            $datas["authorisation_id"]= $tableau[13];
            $datas["currency_code"]= $tableau[14];
            $datas["card_number"]= $tableau[15];
            $datas["cvv_flag"]= $tableau[16];
            $datas["cvv_response_code"]= $tableau[17];
            $datas["bank_response_code"]= $tableau[18];
            $datas["complementary_code"]= $tableau[19];
            $datas["complementary_info"]= $tableau[20];
            $datas["return_context"]= $tableau[21];
            $datas["caddie"]= $tableau[22];
            $datas["receipt_complement"]= $tableau[23];
            $datas["merchant_language"]= $tableau[24];
            $datas["language"]= $tableau[25];
            $datas["customer_id"]= $tableau[26];
            $datas["order_id"]= $tableau[27];
            $datas["customer_email"]= $tableau[28];
            $datas["customer_ip_address"]= $tableau[29];
            $datas["capture_day"]= $tableau[30];
            $datas["capture_mode"]= $tableau[31];
            $datas["data"]= $tableau[32];
            $datas["order_validity"]= $tableau[33];
            $datas["transaction_condition"]= $tableau[34];
            $datas["statement_reference"]= $tableau[35];
            $datas["card_validity"]= $tableau[36];
            $datas["score_value"]= $tableau[37];
            $datas["score_color"]= $tableau[38];
            $datas["score_info"]= $tableau[39];
            $datas["score_threshold"]= $tableau[40];
            $datas["score_profile"]= $tableau[41];

            $logfile=$this->p("jrk_sips_logs");
            $fp=@fopen($logfile, "a+");

            if (( $datas["code"] == "" ) && ( $datas["error"] == "" ) )
            {
                fwrite($fp, "erreur appel response\n");
                print ("executable response non trouve $path_bin\n");
            }
            else if ( $datas["code"] != 0 ){
                fwrite($fp, " API call error.\n");
                fwrite($fp, "Error message :  ".$datas["error"]."\n");
            }
            else {
                fwrite( $fp, "merchant_id : ".$datas['merchant_id']."\n");
                fwrite( $fp, "merchant_country : ".$datas['merchant_country']."\n");
                fwrite( $fp, "amount : ".$datas['amount']."\n");
                fwrite( $fp, "transaction_id : ".$datas["transaction_id"]."\n");
                fwrite( $fp, "transmission_date: ".$datas['transmission_date']."\n");
                fwrite( $fp, "payment_means: ".$datas['payment_means']."\n");
                fwrite( $fp, "payment_time : ".$datas['payment_time']."\n");
                fwrite( $fp, "payment_date : ".$datas['payment_date']."\n");
                fwrite( $fp, "response_code : ".$datas['response_code']."\n");
                fwrite( $fp, "payment_certificate : ".$datas['payment_certificate']."\n");
                fwrite( $fp, "authorisation_id : ".$datas['authorisation_id']."\n");
                fwrite( $fp, "currency_code : ".$datas['currency_code']."\n");
                fwrite( $fp, "card_number : ".$datas['card_number']."\n");
                fwrite( $fp, "cvv_flag: ".$datas['cvv_flag']."\n");
                fwrite( $fp, "cvv_response_code: ".$datas['cvv_response_code']."\n");
                fwrite( $fp, "bank_response_code: ".$datas['bank_response_code']."\n");
                fwrite( $fp, "complementary_code: ".$datas['complementary_code']."\n");
                fwrite( $fp, "complementary_info: ".$datas['complementary_info']."\n");
                fwrite( $fp, "return_context: ".$datas['return_context']."\n");
                fwrite( $fp, "caddie : ".$datas['caddie']."\n");
                fwrite( $fp, "receipt_complement: ".$datas['receipt_complement']."\n");
                fwrite( $fp, "merchant_language: ".$datas['merchant_language']."\n");
                fwrite( $fp, "language: ".$datas['language']."\n");
                fwrite( $fp, "customer_id: ".$datas['customer_id']."\n");
                fwrite( $fp, "order_id: ".$datas['order_id']."\n");
                fwrite( $fp, "customer_email: ".$datas['customer_email']."\n");
                fwrite( $fp, "customer_ip_address: ".$datas['customer_ip_address']."\n");
                fwrite( $fp, "capture_day: ".$datas['capture_day']."\n");
                fwrite( $fp, "capture_mode: ".$datas['capture_mode']."\n");
                fwrite( $fp, "data: ".$datas['data']."\n");
                fwrite( $fp, "order_validity: ".$datas['order_validity']."\n");
                fwrite( $fp, "transaction_condition: ".$datas['transaction_condition']."\n");
                fwrite( $fp, "statement_reference: ".$datas['statement_reference']."\n");
                fwrite( $fp, "card_validity: ".$datas['card_validity']."\n");
                fwrite( $fp, "card_validity: ".$datas['score_value']."\n");
                fwrite( $fp, "card_validity: ".$datas['score_color']."\n");
                fwrite( $fp, "card_validity: ".$datas['score_info']."\n");
                fwrite( $fp, "card_validity: ".$datas['score_threshold']."\n");
                fwrite( $fp, "card_validity: ".$datas['score_profile']."\n");
                fwrite( $fp, "-------------------------------------------\n");
            }
            fclose ($fp);

        }
        else {
            $logfile=$this->p("jrk_sips_logs");
            $fp=@fopen($logfile, "a+");
            fwrite($fp,"No datas\n");
            fclose($fp);
        }

        $this->get('session')->getFlashBag()->add('sips_request_details_auto', $datas);

        if ($this->hp('jrk_sips_controller_response')) {
            $response = $this->forward($this->p("jrk_sips_controller_auto_response"));
        } else {
            $response = $this->forward($this->routeToControllerName($this->p("jrk_sips_route_auto_response")));
        }

        return $response;
    }

}
