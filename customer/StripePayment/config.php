<?php
	require_once "stripe-php-master/init.php";
	require_once "products.php";

$stripeDetails = array(
		"secretKey" => "sk_test_51Q0OQGJ5O8qGLLSCACqJTuD427GjfOMLQMn62zil61CqqgAFheaN8DNGaF1h8NKGjjPDLvu9IpAX106EXEgyfD8a00Op35U4D9",  //Your Stripe Secret key
		"publishableKey" => "pk_test_51Q0OQGJ5O8qGLLSCWzyJBTGG6GKb2YewYqW8vpUkWvic4bo8ZGPc90dWSFDtSm1DC2xJLJeJJfNTmcBlxvIWYzxA00fIjpKsPH"  //Your Stripe Publishable key
	);

	// Set your secret key: remember to change this to your live secret key in production
	// See your keys here: https://dashboard.stripe.com/account/apikeys
	\Stripe\Stripe::setApiKey($stripeDetails['secretKey']);

	
?>
