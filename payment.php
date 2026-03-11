
<?php
include "connection.php";
session_start();
require_once __DIR__ . '/vendor/autoload.php';
$fee = $_SESSION['fee'];

\Stripe\Stripe::setApiKey('sk_test_51RaIdkPN2E3kM4PrhYoEJDdT8KhX0u91rH0LwjbkVvfNbma6gGAT6Rcjc5pDxyb65sxj78KcU2sc4UPn7AvtZgpa00MhoZLndy'); // Replace with your actual secret key

header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost/Prototype%20PSM';

// Example: Get player/team details dynamically
$teamName = "Blackened Futsal Team"; 
$price = $fee * 100; // Amount in cents (5000 = $50.00 or RM50.00, depending on currency)

// Create a new Checkout Session
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card', 'grabpay'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'myr',
            'product_data' => [
                'name' => 'Team Registration',
            ],
            'unit_amount' => $price, // $50.00
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/payment_success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $YOUR_DOMAIN . '/payment_cancel.php',
]);
header('Location: ' . $session->url);
exit();
