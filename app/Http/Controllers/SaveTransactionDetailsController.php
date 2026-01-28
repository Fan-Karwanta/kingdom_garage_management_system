<?php

namespace App\Http\Controllers;

use App\BranchSetting;
use App\Income;
use App\IncomeHistoryRecord;
use App\Invoice;
use App\tbl_payment_records;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class SaveTransactionDetailsController extends Controller
{
    public function saveTransaction(Request $request)
    {

        try {
            $transactionId = $request->input('transactionId');
            $amount = $request->input('amount');
            $invoiceNo = $request->input('invoiceNo');
            $paymentType = $request->input('paymentType');

            $request->session()->flash('message', 'Payment Successful but not saved');

            if (! $transactionId || ! $amount || ! $invoiceNo || ! $paymentType) {
                return response()->json(['error' => 'Invalid transaction data.'], 400);
            }

            $this->updatePaymentStatus($invoiceNo, $amount, $transactionId, $paymentType);
            $request->session()->flash('message', 'Payment Successful');

            return response()->json(['success' => true, 'message' => 'Transaction saved successfully.'], 200);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Failed to save transaction.', 'message' => $e->getMessage()], 500);
        }

    }

    private function updatePaymentStatus($invoiceNumber, $paypalAmount, $refNo, $paymentType)
    {

        $invoice = Invoice::where('invoice_number', $invoiceNumber)->first();

        // Extract necessary values from the fetched invoice
        $customerId = $invoice->customer_id;
        $invoiceId = $invoice->id;
        $type = $invoice->type;
        $paidAmount = $invoice->paid_amount;
        $newAmount = $paidAmount + $paypalAmount;

        // Determine the type (service or sales)
        $typeLabel = ($type == 0) ? 'service' : (($type == 1) ? 'sales' : '');

        // Get the current date
        $nowDate = date('Y-m-d');

        $paymentCode = $refNo;

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
            $invoice->payment_status = '2';
            $invoice->charge_id = '';
            $invoice->save();

            // Create an income record
            $income = new Income;
            $income->invoice_number = $invoiceNumber;
            $income->payment_number = $paymentCode;
            $income->customer_id = $customerId;
            $income->status = '2';
            $income->payment_type = $paymentType;
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
            $incomeHistory->income_amount = $paypalAmount;
            $incomeHistory->income_label = $typeLabel;
            $incomeHistory->branch_id = $branchId;
            $incomeHistory->save();

            // Create a new payment record
            $paymentRecord = new tbl_payment_records;
            $paymentRecord->invoices_id = $invoiceId;
            $paymentRecord->amount = $paypalAmount;
            $paymentRecord->payment_date = $nowDate;
            $paymentRecord->payment_type = $paymentType;
            $paymentRecord->payment_number = $paymentCode;
            $paymentRecord->branch_id = $branchId;
            $paymentRecord->save();

        }
    }
}
