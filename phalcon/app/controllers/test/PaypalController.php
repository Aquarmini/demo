<?php

namespace App\Controllers\Test;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Phalcon\Http\Client\Provider\Exception;

class PaypalController extends Controller
{
    private $apiContext;

    public function initialize()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                env('PAYPAL_ID'),     // ClientID
                env('PAYPAL_SECRET')      // ClientSecret
            )
        );

        // Step 2.1 : Between Step 2 and Step 3
        $log_path = BASE_PATH . '/storage/log/' . date("Ymd") . '/PayPal.log';
        $this->apiContext->setConfig(
            [
                'log.LogEnabled' => true,
                'log.FileName' => $log_path,
                'log.LogLevel' => 'DEBUG'
            ]
        );
    }

    public function indexAction()
    {
        $creditCard = new \PayPal\Api\CreditCard();
        $creditCard->setType("visa")
            ->setNumber("4417119669820331")
            ->setExpireMonth("11")
            ->setExpireYear("2019")
            ->setCvv2("012")
            ->setFirstName("Joe")
            ->setLastName("Shopper");

        // After Step 3
        try {
            $creditCard->create($this->apiContext);
            echo $creditCard;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            echo $ex->getData();
        }
    }

    public function createPaymentAction()
    {
        // Create new payer and method
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // Set payment amount
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(0.01);

        // Set transaction object
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription("Payment description");

        // Set redirect urls
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl('http://phalcon.app/test/paypal/process')
            ->setCancelUrl('http://phalcon.app/test/paypal/cancel');

        // Create the full payment object
        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction))
            ->setNoteToPayer("我是支付消息");

        try {
            $payment->create($this->apiContext);
            logger($payment->getId());
            // Get PayPal redirect URL and redirect user
            $approvalUrl = $payment->getApprovalLink();
            $this->response->redirect($approvalUrl);

            // REDIRECT USER TO $approvalUrl
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    }

    public function processAction()
    {
        $paymentId = $this->request->get('paymentId');
        $payment = Payment::get($paymentId, $this->apiContext);
        $payerId = $this->request->get('PayerID');

        // Execute payment with payer id
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            // Execute payment
            $result = $payment->execute($execution, $this->apiContext);
            if ($result->getState() == 'approved') {
                echo "SUCCESS";
            }
            dump($result);
            dump($result->getTransactions()[0]->getAmount()->getTotal());
            dump($result->getId());
            //var_dump($result);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    }

    public function cancelAction()
    {
        echo "CANCEL";
    }

}

