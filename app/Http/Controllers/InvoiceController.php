<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use App\Models\Notification;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class InvoiceController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage invoice')) {
            if (\Auth::user()->type == 'tenant') {
                $tenant = Tenant::where('user_id', \Auth::user()->id)->first();
                $invoices = Invoice::where('property_id', $tenant->property)->where('unit_id', $tenant->unit)->where('parent_id', parentId())->orderBy('id', 'desc')->get();
            } else {
                $invoices = Invoice::where('parent_id', parentId())->orderBy('id', 'desc')->get();
            }

            return view('invoice.index', compact('invoices'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create invoice')) {
            $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), '');
            $types = Type::where('parent_id', parentId())->where('type', 'invoice')->get()->pluck('title', 'id');
            $types->prepend(__('Select Type'), '');

            $invoiceNumber = $this->invoiceNumber();
            return view('invoice.create', compact('types', 'property', 'invoiceNumber'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create invoice')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_id' => 'required',
                    'unit_id' => 'required',
                    'invoice_month' => 'required',
                    'end_date' => 'required',
                    'types' => 'required|array',
                    'types.*.invoice_type' => 'required',
                    'types.*.amount' => 'required|numeric',
                    'invoice_type' => 'required|in:one_time,recurring',
                    'recurring_interval' => 'required_if:invoice_type,recurring|in:daily,monthly,yearly',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $invoice = new Invoice();
            $invoice->invoice_id = $request->invoice_id;
            $invoice->property_id = $request->property_id;
            $invoice->unit_id = $request->unit_id;
            $invoice->invoice_month = $request->invoice_month . '-01';
            $invoice->end_date = $request->end_date;
            $invoice->notes = $request->notes;
            $invoice->status = 'open';
            $invoice->parent_id = parentId();
            
            // Handle recurring invoice fields
            if ($request->invoice_type === 'recurring') {
                $invoice->is_recurring = true;
                $invoice->recurring_interval = $request->recurring_interval;
                $invoice->recurring_start_date = $request->invoice_month . '-01';
                $invoice->recurring_end_date = $request->recurring_end_date ?? null;
                $invoice->recurring_parent_id = null; // This is the parent recurring invoice
                $invoice->last_generated_date = $request->invoice_month . '-01';
            } else {
                $invoice->is_recurring = false;
                $invoice->recurring_interval = null;
                $invoice->recurring_start_date = null;
                $invoice->recurring_end_date = null;
                $invoice->recurring_parent_id = null;
                $invoice->last_generated_date = null;
            }
            
            $invoice->save();
            $types = $request->types;

            for ($i = 0; $i < count($types); $i++) {
                $invoiceItem = new InvoiceItem();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->invoice_type = $types[$i]['invoice_type'];
                $invoiceItem->amount = $types[$i]['amount'];
                $invoiceItem->description = $types[$i]['description'];
                $invoiceItem->save();
            }

            $module = 'invoice_create';
            $notification = Notification::where('parent_id', parentId())->where('module', $module)->first();
            $setting = settings();
            $errorMessage = '';
            if ($notification) {
                $notification_responce = MessageReplace($notification, $invoice->id);
                $datas['subject'] = $notification_responce['subject'];
                $datas['message'] = $notification_responce['message'];
                $datas['module'] = $module;
                $datas['logo'] =  $setting['company_logo'];
                
                $tenant = Tenant::where('property', $invoice->property_id)->where('unit', $invoice->unit_id)->first();
                if ($tenant) {
                    $user = User::find($tenant->user_id);
                    if ($user) {
                        $to = $user->email;
                        if ($notification->enabled_email == 1) {
                            $response = commonEmailSend($to, $datas);
                            if ($response['status'] == 'error') {
                                $errorMessage = $response['message'];
                            }
                        }

                        if ($notification->enabled_sms == 1) {
                            // Try Twilio first if configured
                            $twilio_sid = getSettingsValByName('twilio_sid');
                            if (!empty($twilio_sid)) {
                                send_twilio_msg($user->phone_number, $notification_responce['sms_message']);
                            }
                            
                            // Also try Zettatel if configured
                            $zettatel_sender_id = getSettingsValByName('zettatel_sender_id');
                            if (!empty($zettatel_sender_id) && !empty($user->phone_number)) {
                                try {
                                    send_zettatel_sms($user->phone_number, $notification_responce['sms_message']);
                                } catch (\Exception $e) {
                                    \Log::error('Zettatel SMS failed in invoice creation: ' . $e->getMessage());
                                }
                            }
                        }
                    }
                }
            }

            return redirect()->route('invoice.index')->with('success', __('Invoice successfully created.') . '</br>' . $errorMessage);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function show($ids)
    {
        if (\Auth::user()->can('show invoice')) {

            $id = Crypt::decrypt($ids);
            $invoice = Invoice::where('id', $id)->first();
            $invoiceNumber = $invoice->invoice_id;
            $tenant = Tenant::where('property', $invoice->property_id)->where('unit', $invoice->unit_id)->first();

            $invoicePaymentSettings = invoicePaymentSettings($invoice->parent_id);

            $notification = Notification::where('parent_id', parentId())->where('module', 'payment_reminder')->first();
            return view('invoice.show', compact('invoiceNumber', 'invoice', 'tenant', 'invoicePaymentSettings', 'notification'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function edit($ids)
    {
        if (\Auth::user()->can('edit invoice')) {
            $id = Crypt::decrypt($ids);
            $invoice = Invoice::where('id', $id)->first();
            $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), '');
            $types = Type::where('parent_id', parentId())->where('type', 'invoice')->get()->pluck('title', 'id');
            $types->prepend(__('Select Type'), '');

            $invoiceNumber = $invoice->invoice_id;
            return view('invoice.edit', compact('types', 'property', 'invoiceNumber', 'invoice'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function update(Request $request, Invoice $invoice)
    {
        if (\Auth::user()->can('edit invoice')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_id' => 'required',
                    'unit_id' => 'required',
                    'invoice_month' => 'required',
                    'end_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $invoice->property_id = $request->property_id;
            $invoice->unit_id = $request->unit_id;
            $invoice->invoice_month = $request->invoice_month . '-01';
            $invoice->end_date = $request->end_date;
            $invoice->notes = $request->notes;
            $invoice->save();
            $types = $request->types;

            for ($i = 0; $i < count($types); $i++) {
                $invoiceItem = InvoiceItem::find($types[$i]['id']);
                if ($invoiceItem == null) {
                    $invoiceItem = new InvoiceItem();
                    $invoiceItem->invoice_id = $invoice->id;
                }

                $invoiceItem->invoice_type = $types[$i]['invoice_type'];
                $invoiceItem->amount = $types[$i]['amount'];
                $invoiceItem->description = $types[$i]['description'];
                $invoiceItem->save();
            }
            return redirect()->route('invoice.index')->with('success', __('Invoice successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function destroy(Invoice $invoice)
    {
        if (\Auth::user()->can('delete invoice')) {
            InvoiceItem::where('invoice_id', $invoice->id)->delete();
            InvoicePayment::where('invoice_id', $invoice->id)->delete();
            $invoice->delete();
            return redirect()->route('invoice.index')->with('success', __('Invoice successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function invoiceNumber()
    {
        $latest = Invoice::where('parent_id', parentId())->latest()->first();
        if ($latest == null) {
            return 1;
        } else {
            return $latest->invoice_id + 1;
        }
    }

    public function invoiceTypeDestroy(Request $request)
    {
        if (\Auth::user()->can('delete invoice type')) {
            $invoiceType = InvoiceItem::find($request->id);
            $invoiceType->delete();

            return response()->json([
                'status' => 'success',
                'msg' => __('Property successfully updated.'),
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function invoicePaymentCreate($invoice_id)
    {
        $invoice = Invoice::find($invoice_id);
        return view('invoice.payment', compact('invoice_id', 'invoice'));
    }

    public function invoicePaymentStore(Request $request, $invoice_id)
    {

        if (\Auth::user()->can('create invoice payment')) {
            $invoice = Invoice::find($invoice_id);
            $dueAmount = $invoice->getInvoiceDueAmount();

            $validator = \Validator::make(
                $request->all(),
                [
                    'payment_date' => 'required',
                    'amount' => 'required|numeric|min:1|max:' . $dueAmount,
                ],

            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $payment = new InvoicePayment();
            $payment->invoice_id = $invoice_id;
            $payment->transaction_id = md5(time());
            $payment->payment_type = __('Manually');
            $payment->amount = $request->amount;
            $payment->payment_date = $request->payment_date;

            if ($request->hasFile('receipt')) {
                $uploadResult = handleFileUpload($request->file('receipt'), 'upload/receipt/');

                if ($uploadResult['flag'] == 0) {
                    return redirect()->back()->with('error', $uploadResult['msg']);
                }
                $payment->receipt = $uploadResult['filename'];
            }

            $payment->notes = $request->notes;
            $payment->parent_id = parentId();
            $payment->save();
            $invoice = Invoice::find($invoice_id);
            if ($invoice->getInvoiceDueAmount() <= 0) {
                $status = 'paid';
            } else {
                $status = 'partial_paid';
            }
            Invoice::statusChange($invoice->id, $status);
            return redirect()->back()->with('success', __('Invoice payment successfully added.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function invoicePaymentDestroy($invoice_id, $id)
    {
        if (\Auth::user()->can('delete invoice payment')) {
            $payment = InvoicePayment::find($id);
            $payment->delete();

            $invoice = Invoice::find($invoice_id);
            if ($invoice->getInvoiceDueAmount() <= 0) {
                $status = 'paid';
            } elseif ($invoice->getInvoiceDueAmount() == $invoice->getInvoiceSubTotalAmount()) {
                $status = 'open';
            } else {
                $status = 'partial_paid';
            }
            Invoice::statusChange($invoice->id, $status);
            return redirect()->back()->with('success', __('Invoice payment successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function invoicePaymentRemind($id)
    {
        $notification = Notification::where('parent_id', parentId())->where('module', 'payment_reminder')->first();
        $short_code = $notification->short_code;
        $notification->short_code = json_decode($notification->short_code);

        $Notifications = Notification::$modules;
        $notification_option = [];
        foreach ($Notifications as $key => $value) {
            $notification_option[$key] = $value['name'];
        }
        return view('invoice.remind', compact('notification', 'notification_option', 'Notifications', 'id'));
    }

    public function invoicePaymentRemindData(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        $tenant = Tenant::where('property', $invoice->property_id)->where('unit', $invoice->unit_id)->first();
        $user = User::find($tenant->user_id);

        $notification = Notification::where('parent_id', parentId())->where('module', 'payment_reminder')->first();
        $module = 'payment_reminder';

        $setting = settings();
        $errorMessage = '';

        $return['subject'] = $request->subject;
        $return['message'] = $request->message;
        $settings = settings();

        if (!empty($request->subject) && !empty($request->message)) {
            $search = [];
            $replace = [];

            $invoice = Invoice::find($id);
            $user_name = $invoice->tenants()->user->name;
            $invoice_number = invoicePrefix() . $invoice->invoice_id;
            $search = ['{company_name}', '{company_email}', '{company_phone_number}', '{company_address}', '{company_currency}', '{user_name}', '{invoice_number}', '{invoice_date}', '{invoice_due_date}', '{amount}', '{invoice_description}'];
            $replace = [$settings['company_name'], $settings['company_email'], $settings['company_phone'], $settings['company_address'], $settings['CURRENCY_SYMBOL'], $user_name, $invoice_number, $invoice->created_at, $invoice->end_date, priceFormat($invoice->getInvoiceDueAmount()), $invoice->notes];

            $return['subject'] = str_replace($search, $replace, $request->subject);
            $return['message'] = str_replace($search, $replace, $request->message);
            $return['sms_message'] = str_replace($search, $replace, $request->sms_message);
        }

        $datas['subject'] = $return['subject'];
        $datas['message'] = $return['message'];
        $datas['module'] = $module;
        $datas['logo'] =  $setting['company_logo'];
        $to = $user->email;

        if ($notification->enabled_email == 1) {
            $response = commonEmailSend($to, $datas);
            if ($response['status'] == 'error') {
                $errorMessage = $response['message'];
            }
        }
        if ($notification->enabled_sms == 1) {
            // Try Twilio first if configured
            $twilio_sid = getSettingsValByName('twilio_sid');
            if (!empty($twilio_sid)) {
                send_twilio_msg($user->phone_number, $return['sms_message']);
            }
            
            // Also try Zettatel if configured
            $zettatel_sender_id = getSettingsValByName('zettatel_sender_id');
            if (!empty($zettatel_sender_id) && !empty($user->phone_number)) {
                try {
                    send_zettatel_sms($user->phone_number, $return['sms_message']);
                } catch (\Exception $e) {
                    \Log::error('Zettatel SMS failed in payment reminder: ' . $e->getMessage());
                }
            }
        }

        return redirect()->back()->with('success', __('Email successfully sent.') . '</br>' . $errorMessage);
    }
}
