<?php

namespace JRK\PaymentSipsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


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
		}

        $this->get('session')->getFlashBag()->add('sips_request_details', $datas);
        $response = $this->forward($this->routeToControllerName($this->p("jrk_sips_route_response")));

		return $response;
    }

    public function p($str){
        return $this->container->getParameter($str);
    }


    public function routeToControllerName($routename) {
        $routes = $this->get('router')->getRouteCollection();
        return $routes->get($routename)->getDefaults()['_controller'];
    }


    public function autoresponseAction()
    {

        if (isset($_POST["DATA"])) {
            $message="message=".$_POST["DATA"];
            $pathfile="pathfile=".$this->p("jrk_sips_pathfile");
            $path_bin = $this->p("jrk_sips_response");
            $message = escapeshellcmd($message);
            $result=exec("$path_bin $pathfile $message");
            $tableau = explode ("!", $result);

            $code = $tableau[1];
            $error = $tableau[2];
            $merchant_id = $tableau[3];
            $merchant_country = $tableau[4];
            $amount = $tableau[5];
            $transaction_id = $tableau[6];
            $payment_means = $tableau[7];
            $transmission_date= $tableau[8];
            $payment_time = $tableau[9];
            $payment_date = $tableau[10];
            $response_code = $tableau[11];
            $payment_certificate = $tableau[12];
            $authorisation_id = $tableau[13];
            $currency_code = $tableau[14];
            $card_number = $tableau[15];
            $cvv_flag = $tableau[16];
            $cvv_response_code = $tableau[17];
            $bank_response_code = $tableau[18];
            $complementary_code = $tableau[19];
            $complementary_info= $tableau[20];
            $return_context = $tableau[21];
            $caddie = $tableau[22];
            $receipt_complement = $tableau[23];
            $merchant_language = $tableau[24];
            $language = $tableau[25];
            $customer_id = $tableau[26];
            $order_id = $tableau[27];
            $customer_email = $tableau[28];
            $customer_ip_address = $tableau[29];
            $capture_day = $tableau[30];
            $capture_mode = $tableau[31];
            $data = $tableau[32];
            $order_validity = $tableau[33];
            $transaction_condition = $tableau[34];
            $statement_reference = $tableau[35];
            $card_validity = $tableau[36];
            $score_value = $tableau[37];
            $score_color = $tableau[38];
            $score_info = $tableau[39];
            $score_threshold = $tableau[40];
            $score_profile = $tableau[41];

            $logfile=$this->p("jrk_sips_logs");
            $fp=@fopen($logfile, "a+");

            if (( $code == "" ) && ( $error == "" ) )
            {
                fwrite($fp, "erreur appel response\n");
                print ("executable response non trouve $path_bin\n");
            }
            else if ( $code != 0 ){
                fwrite($fp, " API call error.\n");
                fwrite($fp, "Error message :  $error\n");
            }
            else {
                fwrite( $fp, "merchant_id : $merchant_id\n");
                fwrite( $fp, "merchant_country : $merchant_country\n");
                fwrite( $fp, "amount : $amount\n");
                fwrite( $fp, "transaction_id : $transaction_id\n");
                fwrite( $fp, "transmission_date: $transmission_date\n");
                fwrite( $fp, "payment_means: $payment_means\n");
                fwrite( $fp, "payment_time : $payment_time\n");
                fwrite( $fp, "payment_date : $payment_date\n");
                fwrite( $fp, "response_code : $response_code\n");
                fwrite( $fp, "payment_certificate : $payment_certificate\n");
                fwrite( $fp, "authorisation_id : $authorisation_id\n");
                fwrite( $fp, "currency_code : $currency_code\n");
                fwrite( $fp, "card_number : $card_number\n");
                fwrite( $fp, "cvv_flag: $cvv_flag\n");
                fwrite( $fp, "cvv_response_code: $cvv_response_code\n");
                fwrite( $fp, "bank_response_code: $bank_response_code\n");
                fwrite( $fp, "complementary_code: $complementary_code\n");
                fwrite( $fp, "complementary_info: $complementary_info\n");
                fwrite( $fp, "return_context: $return_context\n");
                fwrite( $fp, "caddie : $caddie\n");
                fwrite( $fp, "receipt_complement: $receipt_complement\n");
                fwrite( $fp, "merchant_language: $merchant_language\n");
                fwrite( $fp, "language: $language\n");
                fwrite( $fp, "customer_id: $customer_id\n");
                fwrite( $fp, "order_id: $order_id\n");
                fwrite( $fp, "customer_email: $customer_email\n");
                fwrite( $fp, "customer_ip_address: $customer_ip_address\n");
                fwrite( $fp, "capture_day: $capture_day\n");
                fwrite( $fp, "capture_mode: $capture_mode\n");
                fwrite( $fp, "data: $data\n");
                fwrite( $fp, "order_validity: $order_validity\n");
                fwrite( $fp, "transaction_condition: $transaction_condition\n");
                fwrite( $fp, "statement_reference: $statement_reference\n");
                fwrite( $fp, "card_validity: $card_validity\n");
                fwrite( $fp, "card_validity: $score_value\n");
                fwrite( $fp, "card_validity: $score_color\n");
                fwrite( $fp, "card_validity: $score_info\n");
                fwrite( $fp, "card_validity: $score_threshold\n");
                fwrite( $fp, "card_validity: $score_profile\n");
                fwrite( $fp, "-------------------------------------------\n");
            }
            fclose ($fp);


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



        }
        else {
            $logfile=$this->p("jrk_sips_logs");
            $fp=@fopen($logfile, "a+");
            fwrite($fp,"No datas\n");
            fclose($fp);
        }

        $this->get('session')->getFlashBag()->add('sips_request_details_auto', $datas);
        $response = $this->forward($this->routeToControllerName($this->p("jrk_sips_route_auto_response")));

        return $response;
    }

}
