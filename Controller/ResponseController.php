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
    private function getFormattedData($responseData)
    {
        $paymentData = array();
        if (!empty($responseData)) {
            $message="message=".$responseData;
            $pathfile="pathfile=".$this->p("jrk_sips_pathfile");
            $path_bin = $this->p("jrk_sips_response");
            $message = escapeshellcmd($message);
            $result=exec("$path_bin $pathfile $message");
            $tableau = explode ("!", $result);

            $paymentData["code"]= $tableau[1];
            $paymentData["error"]= $tableau[2];
            $paymentData["merchant_id"]= $tableau[3];
            $paymentData["merchant_country"]= $tableau[4];
            $paymentData["amount"]= $tableau[5];
            $paymentData["transaction_id"]= $tableau[6];
            $paymentData["payment_means"]= $tableau[7];
            $paymentData["transmission_date"]= $tableau[8];
            $paymentData["payment_time"]= $tableau[9];
            $paymentData["payment_date"]= $tableau[10];
            $paymentData["response_code"]= $tableau[11];
            $paymentData["payment_certificate"]= $tableau[12];
            $paymentData["authorisation_id"]= $tableau[13];
            $paymentData["currency_code"]= $tableau[14];
            $paymentData["card_number"]= $tableau[15];
            $paymentData["cvv_flag"]= $tableau[16];
            $paymentData["cvv_response_code"]= $tableau[17];
            $paymentData["bank_response_code"]= $tableau[18];
            $paymentData["complementary_code"]= $tableau[19];
            $paymentData["complementary_info"]= $tableau[20];
            $paymentData["return_context"]= $tableau[21];
            $paymentData["caddie"]= $tableau[22];
            $paymentData["receipt_complement"]= $tableau[23];
            $paymentData["merchant_language"]= $tableau[24];
            $paymentData["language"]= $tableau[25];
            $paymentData["customer_id"]= $tableau[26];
            $paymentData["order_id"]= $tableau[27];
            $paymentData["customer_email"]= $tableau[28];
            $paymentData["customer_ip_address"]= $tableau[29];
            $paymentData["capture_day"]= $tableau[30];
            $paymentData["capture_mode"]= $tableau[31];
            $paymentData["data"]= $tableau[32];
            $paymentData["order_validity"]= $tableau[33];
            $paymentData["transaction_condition"]= $tableau[34];
            $paymentData["statement_reference"]= $tableau[35];
            $paymentData["card_validity"]= $tableau[36];
            $paymentData["score_value"]= $tableau[37];
            $paymentData["score_color"]= $tableau[38];
            $paymentData["score_info"]= $tableau[39];
            $paymentData["score_threshold"]= $tableau[40];
            $paymentData["score_profile"]= $tableau[41];
        }
        return ($paymentData);
    }

    public function addResultInLogFile($dataToStore)
    {
        $path_bin = $this->p("jrk_sips_response");
        $logfile=$this->p("jrk_sips_logs");
        $fp=@fopen($logfile, "a+");

        // Add a text with the payment status
        switch ($dataToStore['response_code']) {
            case '17':
                fwrite( $fp, "--- Cancelled payment.\n");
                break;
            case '05':
                fwrite( $fp, "--- Bad credit card Number.\n");
                break;
            case '00':
                fwrite( $fp, "--- Payment successful.\n");
                break;
            default:
                fwrite( $fp, "--- Unknown status.\n");
                break;
        }

        if (!empty($dataToStore)) {
            if (( $dataToStore["code"] == "" ) && ( $dataToStore["error"] == "" ) )
            {
                fwrite($fp, "erreur appel response\n");
                print ("executable response non trouve $path_bin\n");
            }
            else if ( $dataToStore["code"] != 0 ) {
                fwrite($fp, " API call error.\n");
                fwrite($fp, "Error message :  ".$dataToStore["error"]."\n");
            }
            else {

                fwrite( $fp, "merchant_id : ".$dataToStore['merchant_id']."\n");
                fwrite( $fp, "merchant_country : ".$dataToStore['merchant_country']."\n");
                fwrite( $fp, "amount : ".$dataToStore['amount']."\n");
                fwrite( $fp, "transaction_id : ".$dataToStore["transaction_id"]."\n");
                fwrite( $fp, "transmission_date: ".$dataToStore['transmission_date']."\n");
                fwrite( $fp, "payment_means: ".$dataToStore['payment_means']."\n");
                fwrite( $fp, "payment_time : ".$dataToStore['payment_time']."\n");
                fwrite( $fp, "payment_date : ".$dataToStore['payment_date']."\n");
                fwrite( $fp, "response_code : ".$dataToStore['response_code']."\n");
                fwrite( $fp, "payment_certificate : ".$dataToStore['payment_certificate']."\n");
                fwrite( $fp, "authorisation_id : ".$dataToStore['authorisation_id']."\n");
                fwrite( $fp, "currency_code : ".$dataToStore['currency_code']."\n");
                fwrite( $fp, "card_number : ".$dataToStore['card_number']."\n");
                fwrite( $fp, "cvv_flag: ".$dataToStore['cvv_flag']."\n");
                fwrite( $fp, "cvv_response_code: ".$dataToStore['cvv_response_code']."\n");
                fwrite( $fp, "bank_response_code: ".$dataToStore['bank_response_code']."\n");
                fwrite( $fp, "complementary_code: ".$dataToStore['complementary_code']."\n");
                fwrite( $fp, "complementary_info: ".$dataToStore['complementary_info']."\n");
                fwrite( $fp, "return_context: ".$dataToStore['return_context']."\n");
                fwrite( $fp, "caddie : ".$dataToStore['caddie']."\n");
                fwrite( $fp, "receipt_complement: ".$dataToStore['receipt_complement']."\n");
                fwrite( $fp, "merchant_language: ".$dataToStore['merchant_language']."\n");
                fwrite( $fp, "language: ".$dataToStore['language']."\n");
                fwrite( $fp, "customer_id: ".$dataToStore['customer_id']."\n");
                fwrite( $fp, "order_id: ".$dataToStore['order_id']."\n");
                fwrite( $fp, "customer_email: ".$dataToStore['customer_email']."\n");
                fwrite( $fp, "customer_ip_address: ".$dataToStore['customer_ip_address']."\n");
                fwrite( $fp, "capture_day: ".$dataToStore['capture_day']."\n");
                fwrite( $fp, "capture_mode: ".$dataToStore['capture_mode']."\n");
                fwrite( $fp, "data: ".$dataToStore['data']."\n");
                fwrite( $fp, "order_validity: ".$dataToStore['order_validity']."\n");
                fwrite( $fp, "transaction_condition: ".$dataToStore['transaction_condition']."\n");
                fwrite( $fp, "statement_reference: ".$dataToStore['statement_reference']."\n");
                fwrite( $fp, "card_validity: ".$dataToStore['card_validity']."\n");
                fwrite( $fp, "card_validity: ".$dataToStore['score_value']."\n");
                fwrite( $fp, "card_validity: ".$dataToStore['score_color']."\n");
                fwrite( $fp, "card_validity: ".$dataToStore['score_info']."\n");
                fwrite( $fp, "card_validity: ".$dataToStore['score_threshold']."\n");
                fwrite( $fp, "card_validity: ".$dataToStore['score_profile']."\n");
                fwrite( $fp, "-------------------------------------------\n");
            }

        } else {
            fwrite($fp,"No datas\n");
        }
        fclose($fp);
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
        // Get payment structured data
        $structuredData = $this->getFormattedData($_POST["DATA"]);

        // Add cancel in log file
        $this->addResultInLogFile($structuredData);

        if ($this->hp('jrk_sips_controller_auto_response')) {
            // Add result payment in the request
            $response = $this->forward($this->p("jrk_sips_controller_auto_response"), array('response_data'=>$structuredData));
        } else {
            // Add result payment in the request
            $response = $this->forward($this->routeToControllerName($this->p("jrk_sips_route_auto_response")), array('response_data'=>$structuredData));
        }

        return $response;
    }

    public function responseAction()
    {
        // Get payment structured data
        $structuredData = $this->getFormattedData($_POST["DATA"]);

        // Add cancel in log file
        $this->addResultInLogFile($structuredData);

        if ($this->hp('jrk_sips_controller_response')) {
            // Add result payment in the request
            $response = $this->forward($this->p("jrk_sips_controller_response"), array('response_data'=>$structuredData));
        } else {
            // Add result payment in the request
            $response = $this->forward($this->routeToControllerName($this->p("jrk_sips_route_response")), array('response_data'=>$structuredData));
        }

        return $response;
    }

    //@@ TODO : virer ou corriger le cancel
    public function cancelAction()
    {
        // Get payment structured data
        $structuredData = $this->getFormattedData($_POST["DATA"]);

        // Add cancel in log file
        $this->addResultInLogFile($structuredData);

        if ($this->hp('jrk_sips_controller_response')) {
            // Add result payment in the request
            $response = $this->forward($this->p("jrk_sips_cancel_return_url"), array('response_data'=>$structuredData));
        } else {
            // Add result payment in the request
            $response = $this->forward($this->routeToControllerName($this->p("jrk_sips_cancel_return_url")), array('response_data'=>$structuredData));
        }

        return $response;
    }

}
