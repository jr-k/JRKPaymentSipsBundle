Getting started with JRKPaymentSipsBundle
======================================

Setup
-----
JRKPaymentSipsBundle requires [Foo](http://paymentsips.fr)


- Using the vendors script

Add jrk/paymentsips-bundle as a dependency in your project's composer.json file:

```
{
    "require": {
        "jrk/paymentsips-bundle": "dev-master"
    }
}
```

Or add to your deps

```
[JRKPaymentSipsBundle]
    git=git://github.com/jreziga/JRKPaymentSipsBundle.git
    target=bundles/JRK/PaymentSipsBundle
```

... and run php bin/vendors install

... and add the JRK namespace to autoloader

``` php
<?php
   // app/autoload.php
   $loader->registerNamespaces(array(
    // ...
    'JRK' => __DIR__.'/../vendor/bundles',
  ));
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
jrk_paymentsips:
    key: yourKey # Required
    login: yourLogin # Required
    useless: null
```

Usage
-----

 - Using service

Open your controller and call the service.

``` php
<?php
    $paymentsips = $this->get('JRKPaymentSips');
?>
```

Then you can use one of the methods of JRKPaymentSips class

``` php
<?php
    $result = $paymentsips->paymentsips();
?>
```