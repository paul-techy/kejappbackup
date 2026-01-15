<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Stripe;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class InvoicePaymentController extends Controller
{
    public function paymentSettings()
    {
        $paymentSetting = invoicePaymentSettings(parentId());
        return $paymentSetting;
    }

    public function stripePayment(Request $request, $ids)
    {
        $settings = $this->paymentSettings();
        $id = \Illuminate\Support\Facades\Crypt::decrypt($ids);
        $invoice = Invoice::find($id);
        $amount = $request->amount;
        if ($invoice) {
            try {
                $transactionID = uniqid('', true);
                Stripe\Stripe::setApiKey($settings['STRIPE_SECRET']);
                $data = Stripe\Charge::create(
                    [
                        "amount" => 100 * $amount,
                        "currency" => $settings['CURRENCY'],
                        "source" => $request->stripeToken,
                        "description" => " Invoice - " . invoicePrefix() . $invoice->invoice_id,
                        "metadata" => ["order_id" => $transactionID],
                    ]
                );

                if ($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1) {

                    if ($data['status'] == 'succeeded') {

                        $payment['invoice_id'] = $invoice->id;
                        $payment['transaction_id'] = $transactionID;
                        $payment['payment_type'] = 'Stripe';
                        $payment['amount'] = $amount;
                        $payment['receipt'] = isset($data['receipt_url']) ? $data['receipt_url'] : '';
                        $payment['notes'] = " Invoice - " . invoicePrefix() . $invoice->invoice_id;

                        Invoice::addPayment($payment);
                        return redirect()->back()->with('success', __('Invoice payment successfully completed.'));
                    } else {
                        return redirect()->back()->with('error', __('Your payment has failed.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('Transaction has been failed.'));
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', __('Invoice is deleted.'));
        }
    }


    public function invoicePaypal(Request $request, $id)
    {
        $invoiceId = \Illuminate\Support\Facades\Crypt::decrypt($id);
        $paypalSetting = $this->paymentSettings();

        if ($paypalSetting['paypal_mode'] == 'live') {
            config([
                'paypal.live.client_id' => isset($paypalSetting['paypal_client_id']) ? $paypalSetting['paypal_client_id'] : '',
                'paypal.live.client_secret' => isset($paypalSetting['paypal_secret_key']) ? $paypalSetting['paypal_secret_key'] : '',
                'paypal.mode' => isset($paypalSetting['paypal_mode']) ? $paypalSetting['paypal_mode'] : '',
                'paypal.currency' => isset($paypalSetting['CURRENCY']) ? $paypalSetting['CURRENCY'] : '',
            ]);
        } else {
            config([
                'paypal.sandbox.client_id' => isset($paypalSetting['paypal_client_id']) ? $paypalSetting['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($paypalSetting['paypal_secret_key']) ? $paypalSetting['paypal_secret_key'] : '',
                'paypal.mode' => isset($paypalSetting['paypal_mode']) ? $paypalSetting['paypal_mode'] : '',
                'paypal.currency' => isset($paypalSetting['CURRENCY']) ? $paypalSetting['CURRENCY'] : '',
            ]);
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));

        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('invoice.paypal.status', [$invoiceId, 'success'], ['amount' => $request->amount]),
                "cancel_url" => route('invoice.paypal.status', [$invoiceId, 'cancel'], ['amount' => $request->amount]),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => isset($paypalSetting['CURRENCY']) ? $paypalSetting['CURRENCY'] : '',
                        "value" => $request->amount
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()
                ->back()
                ->with('error', 'Something went wrong.');
        } else {
            return redirect()
                ->back()
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function invoicePaypalStatus(Request $request, $invoiceId, $status)
    {
        if ($status == 'success') {

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $transactionID = uniqid('', true);
            $invoice = Invoice::find($invoiceId);
            $response = $provider->capturePaymentOrder($request['token']);
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                $payment['invoice_id'] = $invoiceId;
                $payment['transaction_id'] = $transactionID;
                $payment['payment_type'] = 'Stripe';
                $payment['amount'] = $request->amount;
                $payment['receipt'] = '';
                $payment['notes'] = " Invoice - " . invoicePrefix() . $invoice->invoice_id;

                Invoice::addPayment($payment);
                return redirect()->back()->with('success', __('Invoice payment successfully completed.'));
            } else {
                return redirect()
                    ->back()
                    ->with('error', $response['message'] ?? __('Something went wrong.'));
            }
        } else {
            return redirect()
                ->back()
                ->with('error', __('Transaction has been failed.'));
        }
    }

    public function banktransferPayment(Request $request, $id)
    {
        $invoiceId = \Illuminate\Support\Facades\Crypt::decrypt($id);
        $validator = \Validator::make(
            $request->all(),
            [
                'receipt' => 'required',
                'amount' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        if ($request->hasFile('payment_receipt')) {
            $uploadResult = handleFileUpload($request->file('payment_receipt'), 'upload/payment_receipt');

            if ($uploadResult['flag'] == 0) {
                return redirect()->back()->with('error', $uploadResult['msg']);
            }
            $payment['receipt']  = $uploadResult['filename'];
        }




        $invoice = Invoice::find($invoiceId);
        $transactionID = uniqid('', true);

        $payment['invoice_id'] = $invoice->id;
        $payment['transaction_id'] = $transactionID;
        $payment['payment_type'] = 'Bank Transfer';
        $payment['amount'] = $request->amount;
        $payment['notes'] = $request->notes;

        Invoice::addPayment($payment);
        return redirect()->back()->with('success', __('Invoice payment successfully completed.'));
    }


    public function invoiceFlutterwave(Request $request, $invoice_id, $pay_id)
    {
        $invoiceID = \Illuminate\Support\Facades\Crypt::decrypt($invoice_id);
        $invoice = Invoice::find($invoiceID);
        $paymentSetting = $this->paymentSettings();

        if ($invoice) {
            try {
                $detail = [
                    'txref' => $pay_id,
                    'SECKEY' => $paymentSetting['flutterwave_secret_key'],
                ];
                $url = "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify";
                $headersData = ['Content-Type' => 'application/json'];
                $bodyData = \Unirest\Request\Body::json($detail);
                $responseData = \Unirest\Request::post($url, $headersData, $bodyData);

                if (!empty($responseData)) {
                    $responseData = json_decode($responseData->raw_body, true);
                }

                if (isset($responseData['status']) && $responseData['status'] == 'success') {
                    $amountPaid = $responseData['data']['amount'];
                    $expectedAmount = $request->query('amount'); // Get amount from request

                    if ($amountPaid < $expectedAmount) {
                        return redirect()->back()->with('error', __('Payment amount mismatch! Expected: ') . $expectedAmount);
                    }

                    $invoiceTransId = uniqid('', true);
                    Invoice::addPayment([
                        'invoice_id' => $invoice->id,
                        'transaction_id' => $invoiceTransId,
                        'payment_type' => 'Flutterwave',
                        'amount' => $amountPaid,
                        'notes' => $request->notes ?? 'Flutterwave Payment',
                    ]);

                    return redirect()->back()->with('success', __('Invoice payment successfully completed.'));
                } else {
                    return redirect()->back()->with('error', __('Transaction failed!'));
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }


    public function invoicePaystack(Request $request, $ids)
    {
        $payment_setting = $this->paymentSettings();
        $currency = $payment_setting['CURRENCY'] ?? 'USD';
        $id = Crypt::decrypt($ids);
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'flag' => 0,
                'message' => __('Invoice not found.')
            ]);
        }

        $amount = $request->amount;
        if ($amount <= 0) {
            return response()->json([
                'flag' => 0,
                'message' => __('Amount must be greater than 0.')
            ]);
        }

        return response()->json([
            'flag' => 1,
            'email' => auth()->user()->email,
            'total_price' => $amount,
            'currency' => $currency,
        ]);
    }


    public function invoicePaystackStatus(Request $request, $pay_id, $invoice_id_encrypted)
    {
        try {
            $invoice = Invoice::find(Crypt::decrypt($invoice_id_encrypted));
            if (!$invoice) {
                return redirect()->back()->with('error', __('Invoice not found.'));
            }

            $secretKey = $this->paymentSettings()['paystack_secret_key'] ?? '';
            $verifyUrl = "https://api.paystack.co/transaction/verify/$pay_id";

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $verifyUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $secretKey],
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            $result = $response ? json_decode($response, true) : [];

            if (!($result['status'] ?? false) || ($result['data']['status'] !== 'success')) {
                return redirect()->back()->with('error', __('Transaction failed or cancelled.'));
            }

            $payment = [
                'invoice_id'     => $invoice->id,
                'transaction_id' => uniqid('', true),
                'payment_type'   => 'Paystack',
                'amount'         => $result['data']['amount'] / 100,
                'receipt'        => '',
                'notes'          => 'Paystack Payment',
            ];

            Invoice::addPayment($payment);

            return redirect()->back()->with('success', __('Invoice payment successfully completed.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong while verifying the payment.'));
        }
    }
}
