Getting started with JRKPaymentSipsBundle
======================================

Setup
-----
JRKPaymentSipsBundle requires the ATOS api folder


- Using composer

Add jrk/paymentsips-bundle as a dependency in your project's composer.json file:

```
{
    "require": {
        "jrk/paymentsips-bundle": "dev-master"
    }
}
```
Update composer
```
php composer update
or
php composer.phar update
```

- Add JRKPaymentSipsBundle to your application kernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new JRK\PaymentSipsBundle\JRKPaymentSipsBundle(),
    );
}
```


- Yml configuration

``` yml
# app/config/config.yml
jrk_payment_sips:
    files:
        sips_pathfile: "%kernel.root_dir%/config/sips/param/pathfile"
        sips_request: "%kernel.root_dir%/config/sips/bin/static/request"
        sips_response: "%kernel.root_dir%/config/sips/bin/static/response"
        sips_logs: "%kernel.root_dir%/logs/sips.log"
    params:
        sips_merchant_id: "XXXXXXXXXXXXXXXXXX"
        sips_currency_code: "EUR"   # OR use the currency_code provided by ATOS (978=EUR for example)
        sips_language: "fr"
        sips_payment_means: "CB,2,VISA,2,MASTERCARD,2"
        sips_header_flag: "yes"
        sips_merchant_country: "fr"
    links:
        sips_cancel_return_url: "my_homepage_route"     # Route to redirect if the payment is canceled
        sips_route_response: "my_sips_response"         # Route to redirect if the payment is accepted
        sips_route_auto_response: "my_sips_autoresponse" # Route called by the payment server
```

- Routes import

``` yml
# app/config/routing.yml
jrk_payment_sips:
    resource: "@JRKPaymentSipsBundle/Resources/config/routing.yml"
    prefix: /payment
```

- Console usage

> Install assets
```
php app/console assets:install
```

> Generate pathfile assets - You'll have to specify param's path directory (by default use [app/config/sips/param])
```
php app/console jrk:sips:install
```


For example, with default values of the bundle, you can extract the API like this:

    .
    |-- app
    |   `-- config
    |       `-- sips
    |       `-- bin
    |           `-- static
    |              `-- request
    |              `-- response
    |       `-- param
    |           `-- certif.XXXXXXXXXXXX
    |           `-- parmcom.XXXXXXXXXXXX
    |           `-- parmcom.mercanet        # if you are using mercanet for example
    |           `-- pathfile                # generated
    |       `-- Version.txt




Usage
-----


 - Using service

Open your controller and call the service.

``` php
<?php
    $sips_form =  $this->get('jrk_paymentsips')->get_sips_request(
        array(
            "amount" => 10,
            "currency_code" => 978   // Override params if you need
            "order_id" => 12
        ),
        $YourTransactionEntityExample
    );
?>
```

The bundle forward an array with the server response in a multidimensionnal array :

In your response action, if you call this : `$structuredData = $request->attributes->get('response_data')`;
the variable structuredData will contain the following data :

- code
- error
- merchant_id
- merchant_country
- amount
- transaction_id
- payment_means
- transmission_date
- payment_time
- payment_date
- response_code
- payment_certificate
- authorisation_id
- currency_code
- card_number
- cvv_flag
- cvv_response_code
- bank_response_code
- complementary_code
- complementary_info
- return_context
- caddie
- receipt_complement
- merchant_language
- language
- customer_id
- order_id
- customer_email
- customer_ip_address
- capture_day
- capture_mode
- data
- order_validity
- transaction_condition
- statement_reference
- card_validity
- score_value
- score_color
- score_info
- score_threshold
- score_profile

Attributes "code" and "response_code" will give you more informations about the state of the payment.
You can now use the "sips_route_response" method in your controller


``` php
<?php
    // Retrieve the data forwarded by the bundle
    $responseData = $request->attributes->get('response_data');

    $orderId = $responseData['order_id'];

    // Get your order entity
    $order = $this
        ->getDoctrine()
        ->getEntityManager()
        ->getRepository('YourBundle:YourEntity')
        ->find($orderId)
    ;

    // Update your entity data, for exemple order state
    $order->setState("ACCEPTED");

    $em=$this->getDoctrine()->getEntityManager();
    $em->persist($order);
    $em->flush();
?>
```

Controller example

``` php
<?php
    class MyController
    {

        public function paymentpageAction()
        {
        
            // Initialize your order entity or whatever you want
            $order = new OrderExample();

            // Don't forget to set an amount in array
            // You can dynamically override config parameters here like currency_code etc...
            $paymentForm = $this->get('jrk_paymentsips')->get_sips_request(
                array(
                    'amount' => $price,
                    'order_id' => $order->getId()
                ),
                $order
            );

            // Render your payment page, you can render the sips form like that for twig : {{ sips_form }}
            return $this->render('ShopFrontBundle:MyController:paymentpage.html.twig',
                array(
                    "sips_form"=>$sips_form
                )
            );

        }


        // Controller set in your config.yml : my_sips_response parameter
        public function my_sips_responseAction()
        {
            $responseData = $request->attributes->get('response_data');

            $orderId = $responseData['order_id'];

            // Find your order in your database
            $order = $this
                ->getDoctrine()
                ->getEntityManager()
                ->getRepository('YourBundle:YourEntity')
                ->find($orderId)
            ;

            // Store your transaction entity in database for example, or attributes.
            $order->setState("ACCEPTED");
            $em = $this->getEntityManager();
            $em->persist($order);
            $em->flush();

            // Notify the user by mail for example
            /* ... */

            // Redirect the user in his history orders for example
            return $this->redirect($this->generateUrl("user_history_orders"));
        }
    }
?>
```

View (twig example)

```
Order page :
{{ sips_form|raw }}
```
