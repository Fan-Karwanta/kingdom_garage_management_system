<?php

namespace App\Http\Controllers;

use App\BranchSetting;
use App\Income;
use App\IncomeHistoryRecord;
use App\Invoice;
use App\Setting;
use App\tbl_payment_records;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Stripe;

class StripePaymentController extends Controller
{
    protected $s_key;

    public function __construct()
    {
        $updatekey = DB::table('updatekey')->first();
        $this->s_key = $updatekey->secret_key;

        if (\Schema::hasTable('payment_gateways')) {
            $stripe_payment_gateway = DB::table('payment_gateways')->where('gateway_name', 'stripe')->first();
            if ($stripe_payment_gateway) {
                $stripedetail = json_decode($stripe_payment_gateway->credentials);
                if (! empty($stripedetail->secret_key)) {
                    $this->s_key = $stripedetail->secret_key;
                }
            }
        }
    }

    public function stripeCheckout(Request $request)
    {
        try {

            $stripe = new \Stripe\StripeClient($this->s_key);

            $stripeamount = $request->invoice_amount;
            $invoice_number = $request->invoice_no;

            // Retrieve the Stripe account details
            $account = $stripe->accounts->retrieve();
            $country = $account->country;

            $setting = Setting::first();
            $currencyCode = $setting->currancy; // Default to USD if currency is not set
            $currancy = DB::table('tbl_currency_records')->find($currencyCode);

            $successUrl = route('stripe.checkout.success').'?session_id={CHECKOUT_SESSION_ID}';
            $cancelUrl = route('stripe.checkout.cancel');

            $paymentMethodTypes = $country === 'IN' ? ['card'] : ['link', 'card'];

            $response = $stripe->checkout->sessions->create([
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'payment_method_types' => $paymentMethodTypes,
                'line_items' => [
                    [
                        'price_data' => [
                            'product_data' => [
                                'name' => 'Invoice Payment',
                            ],
                            'unit_amount' => $stripeamount * 100,
                            'currency' => $currancy->code ?? 'USD',
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'metadata' => [
                    'invoice_number' => $invoice_number,
                ],
            ]);

            // Store the Checkout Session ID in the database or session
            // This step is crucial for verifying the payment status later
            $request->session()->put('stripe_session_id', $response->id);
            $request->session()->put('invoice_number', $invoice_number);

            return redirect($response->url);
        } catch (\Exception $e) {
            if ($e->getMessage() == 'api_key cannot be the empty string') {
                return redirect('invoice/list')->with('message', 'Stripe keys are not set. Contact admin');
            } else {
                return redirect('invoice/list')->with('message', 'Error initiating payment: '.$e->getMessage());
            }

        }
    }

    public function stripeCheckoutSuccess(Request $request)
    {
        $sessionId = $request->session()->get('stripe_session_id');
        $invoiceNumber = $request->session()->get('invoice_number');

        if (! $sessionId || ! $invoiceNumber) {
            return redirect('invoice/list  ')->with('error', 'Invalid session');
        }

        try {
            $stripe = new \Stripe\StripeClient($this->s_key);

            $session = $stripe->checkout->sessions->retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                // Payment was successful, update your database
                $this->updatePaymentStatus($invoiceNumber, $session->amount_total / 100);

                // Clear the session data
                $request->session()->forget(['stripe_session_id', 'invoice_number']);

                return redirect('invoice/list')->with('message', 'Payment Added Successfully');
            } else {
                return redirect('invoice/list  ')->with('error', 'Payment not completed');
            }
        } catch (\Exception $e) {
            return redirect('invoice/list  ')->with('error', 'Error processing payment: '.$e->getMessage());
        }
    }

    public function stripeCheckoutCancel(Request $request)
    {
        // Clear the session data
        $request->session()->forget(['stripe_session_id', 'invoice_number']);

        return redirect('invoice/list  ')->with('message', 'Payment was cancelled');
    }

    private function updatePaymentStatus($invoiceNumber, $stripeAmount)
    {
        // Update the invoice status in the database
        $invoice = Invoice::where('invoice_number', $invoiceNumber)->first();

        // Extract necessary values from the fetched invoice
        $customerId = $invoice->customer_id;
        $invoiceId = $invoice->id;
        $type = $invoice->type;
        $paidAmount = $invoice->paid_amount;
        $newAmount = $paidAmount + $stripeAmount;

        // Determine the type (service or sales)
        $typeLabel = ($type == 0) ? 'service' : (($type == 1) ? 'sales' : '');

        // Get the current date
        $nowDate = date('Y-m-d');

        // Generate a random payment code
        $characters = '0123456789';
        $paymentCode = 'P'.substr(str_shuffle($characters), 0, 6);

        $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
        $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

        $branchId = '';

        if (isAdmin(Auth::User()->role_id)) {
            $branchId = $adminCurrentBranch->branch_id;
        } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
            $branchId = $invoice->branch_id;
        } else {
            $branchId = $currentUser->branch_id;
        }

        if ($invoice) {
            $invoice->paid_amount = $newAmount;
            $invoice->payment_status = '2'; // Assuming '2' means paid
            $invoice->charge_id = '';
            $invoice->save();

            // Create an income record
            $income = new Income;
            $income->invoice_number = $invoiceNumber;
            $income->payment_number = $paymentCode;
            $income->customer_id = $customerId;
            $income->status = '2';
            $income->payment_type = 'Stripe';
            $income->date = $nowDate;
            $income->main_label = $typeLabel;
            $income->branch_id = $branchId;
            $income->save();

            // Get the latest income record
            $latestIncome = DB::table('tbl_incomes')->latest('id')->first();
            $latestIncomeId = $latestIncome->id;

            // Create a new income history record
            $incomeHistory = new IncomeHistoryRecord;
            $incomeHistory->tbl_income_id = $latestIncomeId;
            $incomeHistory->income_amount = $stripeAmount;
            $incomeHistory->income_label = $typeLabel;
            $incomeHistory->branch_id = $branchId;
            $incomeHistory->save();

            // Create a new payment record
            $paymentRecord = new tbl_payment_records;
            $paymentRecord->invoices_id = $invoiceId;
            $paymentRecord->amount = $stripeAmount;
            $paymentRecord->payment_date = $nowDate;
            $paymentRecord->payment_type = 'Stripe';
            $paymentRecord->payment_number = $paymentCode;
            $paymentRecord->branch_id = $branchId;
            $paymentRecord->save();
        }
    }
}
