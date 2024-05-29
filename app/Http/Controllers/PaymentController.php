<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Validator;
use URL;
use Session;
use Redirect;

use App\User;
use App\EmployerInfo;

use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\PaymentExecution;


class PaymentController extends Controller
{
    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function payWithpaypal(Request $request)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Item 1') /** item name **/
            ->setCurrency('AUD')
            ->setQuantity(1)
            ->setPrice(69.99);

        /** unit price **/
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        
        $amount = new Amount();
        $amount->setCurrency('AUD')
            ->setTotal(69.99);
        
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');
            
        $redirect_urls = new RedirectUrls();
        if (!in_array(Auth::user()->role, ['3','4'])){
            $redirect_urls->setReturnUrl(URL::route('job-seeker.payment.status')) /** Specify return URL **/
                            ->setCancelUrl(URL::route('job-seeker.payment.status'));
        }else{
            $redirect_urls->setReturnUrl(URL::route('company.payment.status')) /** Specify return URL **/
                            ->setCancelUrl(URL::route('company.payment.status'));
            
        }
        
        
        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        // dd($payment->create($this->_api_context));
        // exit;

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {

            echo "<pre>";
		    echo $ex->getCode(); // Prints the Error Code
		    echo $ex->getData(); // Prints the detailed error message
		    die($ex);

            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');

                return Redirect::route('paywithpaypal');
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');

                return Redirect::route('paywithpaypal');
            }
        }
        
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        
        \Session::put('error', 'Unknown error occurred');

        return Redirect::route('paywithpaypal');
    }

    public function getPaymentStatus(Request $request)
    {

        if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }
        // else{
        // 	if (!in_array(Auth::user()->role, ['3','4'])) {
        // 		return redirect()->back()->with('error', 'You are not Employer!!');
        // 	}
        // }

        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');
 
        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');

        $payment_status = 1;
        $payment_id = isset($_GET['paymentId']) ? $_GET['paymentId'] : '';
        $payer_id = isset($_GET['payerId']) ? $_GET['payerId'] : '';

        if (empty($request->input('PayerID')) || empty($request->input('token'))) {
            // die('failed first');
            if (!in_array(Auth::user()->role, ['3','4'])){
                return Redirect::route('job-seeker.profile')->with('error','Payment Failed');
            }else{
                return Redirect::route('company.subscribe-premium')->with('error','Payment Failed');
                
            }
            
        }
 
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));
 
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
	 
        if ($result->getState() == 'approved') {
        	$pay = (array)$result->getPayer()->getPayerInfo()->getShippingAddress();
			$pay = array_values($pay);

            $payment_status = 1;
            $payer_id = $request->input('PayerID');

            
            $employerArray = [
                                    "premium" => 1,
                                    "payment_status" => $payment_status,
                                    "payment_id" => $payment_id,
                                    "payer_id" => $payer_id
                                ];

            $employer_info = User::where('id', Auth::user()->id)->update($employerArray);
            EmployerInfo::where('user_id', Auth::user()->id)->update(['premium' => 1]);
            // dd($employer_info);
            if (!in_array(Auth::user()->role, ['3','4'])){
                return Redirect::route('job-seeker.profile')->with('success_status', 'Congratulations, You Subsribed as a premium member!');
            }else{
                return Redirect::route('company.profile')->with('success_status', 'Congratulations, You Subsribed as a premium member!');
            }
            
 
        }

        // die('failed');
        return Redirect::route('company.subscribe-premium')->with('error','Payment Failed');
 
    }
}
