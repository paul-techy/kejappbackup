<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\PropertyUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaPaymentController extends Controller
{
    /**
     * Handle M-Pesa payment webhook/callback
     * This endpoint receives payment notifications from M-Pesa
     */
    public function webhook(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('M-Pesa Webhook Received', ['data' => $request->all()]);

            // Handle different M-Pesa callback formats
            $data = $request->all();
            
            // Extract data from different possible formats
            // Format 1: Direct fields (C2B API)
            $accountNumber = $request->input('AccountNumber') 
                ?? $request->input('BillRefNumber') 
                ?? $request->input('account_number')
                ?? null;
            
            $amount = $request->input('Amount') 
                ?? $request->input('TransAmount')
                ?? $request->input('amount')
                ?? null;
            
            $transactionId = $request->input('TransactionID') 
                ?? $request->input('TransID')
                ?? $request->input('transaction_id')
                ?? $request->input('MpesaReceiptNumber')
                ?? null;
            
            $phoneNumber = $request->input('PhoneNumber') 
                ?? $request->input('MSISDN')
                ?? $request->input('phone_number')
                ?? null;

            // Validate required fields
            if (empty($accountNumber) || strlen($accountNumber) != 6) {
                Log::error('M-Pesa Webhook: Invalid or missing AccountNumber', ['accountNumber' => $accountNumber]);
                return response()->json([
                    'ResultCode' => 1,
                    'ResultDesc' => 'Invalid or missing account number'
                ], 400);
            }

            if (empty($amount) || $amount < 1) {
                Log::error('M-Pesa Webhook: Invalid or missing Amount', ['amount' => $amount]);
                return response()->json([
                    'ResultCode' => 1,
                    'ResultDesc' => 'Invalid or missing amount'
                ], 400);
            }

            if (empty($transactionId)) {
                Log::error('M-Pesa Webhook: Missing TransactionID');
                return response()->json([
                    'ResultCode' => 1,
                    'ResultDesc' => 'Missing transaction ID'
                ], 400);
            }

            $amount = floatval($amount);

            // Find the property unit by unit_id
            $unit = PropertyUnit::where('unit_id', $accountNumber)->first();

            if (!$unit) {
                Log::warning('M-Pesa Payment: Unit not found', ['unit_id' => $accountNumber]);
                return response()->json([
                    'ResultCode' => 1,
                    'ResultDesc' => 'Unit not found'
                ], 404);
            }

            // Find unpaid invoices for this unit matching the amount
            // Prioritize the oldest invoice (first generated) and match by due amount first
            $invoices = Invoice::where('unit_id', $unit->id)
                ->where('status', '!=', 'paid')
                ->orderBy('created_at', 'asc')
                ->get();

            // First, try to match by due amount (prioritize partially paid invoices)
            $invoice = $invoices->filter(function ($inv) use ($amount) {
                $dueAmount = $inv->getInvoiceDueAmount();
                // Match if payment amount equals the due amount (allowing for small rounding differences)
                return abs($dueAmount - $amount) < 0.01;
            })->first();

            // If no match by due amount, try matching by total invoice amount
            if (!$invoice) {
                $invoice = $invoices->filter(function ($inv) use ($amount) {
                    $invoiceTotal = $inv->getInvoiceSubTotalAmount();
                    // Match if payment amount equals the total amount (allowing for small rounding differences)
                    return abs($invoiceTotal - $amount) < 0.01;
                })->first();
            }

            if (!$invoice) {
                Log::warning('M-Pesa Payment: No matching invoice found', [
                    'unit_id' => $accountNumber,
                    'amount' => $amount
                ]);
                return response()->json([
                    'ResultCode' => 1,
                    'ResultDesc' => 'No matching invoice found for this amount'
                ], 404);
            }

            // Check if this transaction has already been processed
            $existingPayment = InvoicePayment::where('transaction_id', $transactionId)->first();
            if ($existingPayment) {
                Log::info('M-Pesa Payment: Transaction already processed', ['transaction_id' => $transactionId]);
                return response()->json([
                    'ResultCode' => 0,
                    'ResultDesc' => 'Transaction already processed'
                ], 200);
            }

            // Create payment record
            $paymentData = [
                'invoice_id' => $invoice->id,
                'transaction_id' => $transactionId,
                'payment_type' => 'M-Pesa',
                'amount' => $amount,
                'payment_date' => date('Y-m-d'),
                'receipt' => '',
                'notes' => "M-Pesa payment from {$phoneNumber} for unit {$accountNumber}",
                'parent_id' => $invoice->parent_id,
            ];

            Invoice::addPayment($paymentData);

            Log::info('M-Pesa Payment: Successfully processed', [
                'transaction_id' => $transactionId,
                'invoice_id' => $invoice->id,
                'amount' => $amount
            ]);

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Payment processed successfully'
            ], 200);

        } catch (\Exception $e) {
            Log::error('M-Pesa Webhook Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle M-Pesa STK Push callback (if using STK Push)
     */
    public function stkCallback(Request $request)
    {
        try {
            Log::info('M-Pesa STK Callback Received', ['data' => $request->all()]);

            $body = $request->all();
            
            // M-Pesa STK Push callback structure
            if (isset($body['Body']['stkCallback'])) {
                $callbackData = $body['Body']['stkCallback'];
                $resultCode = $callbackData['ResultCode'] ?? null;
                $resultDesc = $callbackData['ResultDesc'] ?? '';
                $checkoutRequestID = $callbackData['CheckoutRequestID'] ?? '';

                if ($resultCode == 0) {
                    // Payment successful
                    $callbackMetadata = $callbackData['CallbackMetadata']['Item'] ?? [];
                    
                    $amount = 0;
                    $mpesaReceiptNumber = '';
                    $phoneNumber = '';
                    $accountNumber = '';

                    foreach ($callbackMetadata as $item) {
                        if ($item['Name'] == 'Amount') {
                            $amount = floatval($item['Value']);
                        } elseif ($item['Name'] == 'MpesaReceiptNumber') {
                            $mpesaReceiptNumber = $item['Value'];
                        } elseif ($item['Name'] == 'PhoneNumber') {
                            $phoneNumber = $item['Value'];
                        }
                    }

                    // Extract account number from the merchant request ID or metadata
                    // This depends on how you structure your STK Push request
                    // For now, we'll try to get it from the request
                    if (isset($body['Body']['stkCallback']['MerchantRequestID'])) {
                        // You may need to store MerchantRequestID -> unit_id mapping
                        // For now, we'll use a different approach
                    }

                    // If account number is in the callback, use it
                    // Otherwise, you may need to store the mapping when initiating STK Push
                    if (!empty($accountNumber)) {
                        return $this->processPayment($accountNumber, $amount, $mpesaReceiptNumber, $phoneNumber);
                    } else {
                        Log::warning('M-Pesa STK Callback: Account number not found');
                        return response()->json(['status' => 'error', 'message' => 'Account number not found'], 400);
                    }
                } else {
                    Log::warning('M-Pesa STK Callback: Payment failed', [
                        'result_code' => $resultCode,
                        'result_desc' => $resultDesc
                    ]);
                    return response()->json(['status' => 'error', 'message' => $resultDesc], 400);
                }
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('M-Pesa STK Callback Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Process payment for a unit
     */
    private function processPayment($accountNumber, $amount, $transactionId, $phoneNumber)
    {
        // Find the property unit by unit_id
        $unit = PropertyUnit::where('unit_id', $accountNumber)->first();

        if (!$unit) {
            Log::warning('M-Pesa Payment: Unit not found', ['unit_id' => $accountNumber]);
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Unit not found'
            ], 404);
        }

        // Find unpaid invoices for this unit matching the amount
        // Prioritize the oldest invoice (first generated) and match by due amount first
        $invoices = Invoice::where('unit_id', $unit->id)
            ->where('status', '!=', 'paid')
            ->orderBy('created_at', 'asc')
            ->get();

        // First, try to match by due amount (prioritize partially paid invoices)
        $invoice = $invoices->filter(function ($inv) use ($amount) {
            $dueAmount = $inv->getInvoiceDueAmount();
            // Match if payment amount equals the due amount (allowing for small rounding differences)
            return abs($dueAmount - $amount) < 0.01;
        })->first();

        // If no match by due amount, try matching by total invoice amount
        if (!$invoice) {
            $invoice = $invoices->filter(function ($inv) use ($amount) {
                $invoiceTotal = $inv->getInvoiceSubTotalAmount();
                // Match if payment amount equals the total amount (allowing for small rounding differences)
                return abs($invoiceTotal - $amount) < 0.01;
            })->first();
        }

        if (!$invoice) {
            Log::warning('M-Pesa Payment: No matching invoice found', [
                'unit_id' => $accountNumber,
                'amount' => $amount
            ]);
            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'No matching invoice found for this amount'
            ], 404);
        }

        // Check if this transaction has already been processed
        $existingPayment = InvoicePayment::where('transaction_id', $transactionId)->first();
        if ($existingPayment) {
            Log::info('M-Pesa Payment: Transaction already processed', ['transaction_id' => $transactionId]);
            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Transaction already processed'
            ], 200);
        }

        // Create payment record
        $paymentData = [
            'invoice_id' => $invoice->id,
            'transaction_id' => $transactionId,
            'payment_type' => 'M-Pesa',
            'amount' => $amount,
            'payment_date' => date('Y-m-d'),
            'receipt' => '',
            'notes' => "M-Pesa payment from {$phoneNumber} for unit {$accountNumber}",
            'parent_id' => $invoice->parent_id,
        ];

        Invoice::addPayment($paymentData);

        Log::info('M-Pesa Payment: Successfully processed', [
            'transaction_id' => $transactionId,
            'invoice_id' => $invoice->id,
            'amount' => $amount
        ]);

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Payment processed successfully'
        ], 200);
    }
}
