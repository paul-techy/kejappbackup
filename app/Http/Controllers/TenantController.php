<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantDocument;
use App\Models\User;
use App\Services\ZettatelSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TenantController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage tenant')) {
            $tenants = Tenant::where('parent_id', parentId())->orderBy('id', 'desc')->get();
            return view('tenant.index', compact('tenants'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create tenant')) {
            $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);
            return view('tenant.create', compact('property'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create tenant')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required',
                    'phone_number' => 'required',
                    'family_member' => 'required',
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',
                    'property' => 'required',
                    'unit' => 'required',
                    'lease_start_date' => 'required',
                    'lease_end_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),
                ]);
            }
            $ids = parentId();
            $authUser = \App\Models\User::find($ids);
            $totalTenant = $authUser->totalTenant();
            $subscription = Subscription::find($authUser->subscription);
            if ($totalTenant >= $subscription->tenant_limit && $subscription->tenant_limit != 0) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('Your tenant limit is over, please upgrade your subscription.'),
                    'id' => 0,
                ]);
            }

            $userRole = Role::where('parent_id', parentId())->where('name', 'tenant')->first();
            $setting = settings();

            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = \Hash::make($request->password);
            $user->phone_number = $request->phone_number;
            $user->type = $userRole->name;
            $user->email_verified_at = now();
            $user->profile = 'avatar.png';
            $user->lang = 'english';
            $user->parent_id = parentId();
            $user->save();
            $user->assignRole($userRole);


            if ($request->hasFile('profile')) {
                $uploadResult = handleFileUpload($request->file('profile'), 'upload/profile/');
                if ($uploadResult['flag'] == 0) {
                    return redirect()->back()->with('error', $uploadResult['msg']);
                }
                $user->profile = $uploadResult['filename'];
                $user->save();
            }

            $tenant = new Tenant();
            $tenant->user_id = $user->id;
            $tenant->family_member = $request->family_member;
            $tenant->country = $request->country;
            $tenant->state = $request->state;
            $tenant->city = $request->city;
            $tenant->zip_code = $request->zip_code;
            $tenant->address = $request->address;
            $tenant->property = $request->property;
            $tenant->unit = $request->unit;
            $tenant->lease_start_date = $request->lease_start_date;
            $tenant->lease_end_date = $request->lease_end_date;
            $tenant->parent_id = parentId();
            $tenant->save();


            $pro_unit = PropertyUnit::find($tenant->unit);
            $pro_unit->is_occupied = 1;
            $pro_unit->save();


            if ($request->hasFile('tenant_images')) {
                foreach ($request->file('tenant_images') as $image) {
                    $uploadResult = handleFileUpload($image, 'upload/tenant/');

                    if ($uploadResult['flag'] == 1) {
                        TenantDocument::create([
                            'property_id' => $request->property,
                            'tenant_id'   => $tenant->id,
                            'document'    => $uploadResult['filename'],
                            'parent_id'   => parentId(),
                        ]);
                    } else {
                        return redirect()->back()->with('error', $uploadResult['msg']);
                    }
                }
            }

            $module = 'tenant_create';
            $notification = Notification::where('parent_id', parentId())->where('module', $module)->first();
            $notification->password = $request->password;
            $errorMessage = '';
            if (!empty($notification)) {
                $notification_responce = MessageReplace($notification, $user->id);
                $datas['subject'] = $notification_responce['subject'];
                $datas['message'] = $notification_responce['message'];
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
                        send_twilio_msg($user->phone_number, $notification_responce['sms_message']);
                    }
                    
                    // Also try Zettatel if configured
                    $zettatel_sender_id = getSettingsValByName('zettatel_sender_id');
                    if (!empty($zettatel_sender_id) && !empty($user->phone_number)) {
                        try {
                            send_zettatel_sms($user->phone_number, $notification_responce['sms_message']);
                        } catch (\Exception $e) {
                            \Log::error('Zettatel SMS failed in tenant creation: ' . $e->getMessage());
                        }
                    }
                }
            }


            return response()->json([
                'status' => 'success',
                'msg' => __('Tenant successfully created.') . '</br>' . $errorMessage,

            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function show($ids)
    {

        if (\Auth::user()->can('show tenant')) {

            $id = Crypt::decrypt($ids);
            $tenant = Tenant::where('id', $id)->first();
            $invoices = Invoice::where('parent_id', parentId())->where('property_id', $tenant->property)->where('unit_id', $tenant->unit)->orderBy('id', 'desc')->get();

            return view('tenant.show', compact('tenant', 'invoices'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function edit($ids)
    {
        if (\Auth::user()->can('edit tenant')) {

            $id = Crypt::decrypt($ids);
            $tenant = Tenant::where('id', $id)->first();
            $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);

            $user = User::find($tenant->user_id);
            return view('tenant.edit', compact('property', 'tenant', 'user'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
    public function update(Request $request, Tenant $tenant)
    {
        if (!\Auth::user()->can('edit tenant')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        $validator = \Validator::make($request->all(), [
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|email|unique:users,email,' . $tenant->user_id,
            'phone_number'      => 'required',
            'family_member'     => 'required',
            'country'           => 'required',
            'state'             => 'required',
            'city'              => 'required',
            'zip_code'          => 'required',
            'address'           => 'required',
            'property'          => 'required',
            'unit'              => 'required',
            'lease_start_date'  => 'required',
            'lease_end_date'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'msg'    => $validator->getMessageBag()->first(),
            ]);
        }

        $user = User::find($tenant->user_id);
        $user->update([
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        if ($request->hasFile('profile')) {
            $uploadResult = handleFileUpload($request->file('profile'), 'upload/profile/');

            if ($uploadResult['flag'] == 0) {
                return redirect()->back()->with('error', $uploadResult['msg']);
            }

            if (!empty($user->profile)) {
                deleteOldFile($user->profile, 'upload/profile/');
            }

            $user->profile = $uploadResult['filename'];
            $user->save();
        }

        // Store old unit ID before update
        $old_unit_id = $tenant->unit;

        // Update tenant info
        $tenant->update([
            'family_member'    => $request->family_member,
            'country'          => $request->country,
            'state'            => $request->state,
            'city'             => $request->city,
            'zip_code'         => $request->zip_code,
            'address'          => $request->address,
            'property'         => $request->property,
            'unit'             => $request->unit,
            'lease_start_date' => $request->lease_start_date,
            'lease_end_date'   => $request->lease_end_date,
        ]);

        // Set previous unit as vacant if changed
        if ($old_unit_id != $request->unit) {
            $old_unit = PropertyUnit::find($old_unit_id);
            if ($old_unit) {
                $old_unit->is_occupied = 0;
                $old_unit->save();
            }
        }

        // Set new unit as occupied
        $new_unit = PropertyUnit::find($request->unit);
        if ($new_unit) {
            $new_unit->is_occupied = 1;
            $new_unit->save();
        }



        // Handle tenant images
        if ($request->hasFile('tenant_images')) {
            foreach ($request->file('tenant_images') as $image) {
                $uploadResult = handleFileUpload($image, 'upload/tenant/');

                if ($uploadResult['flag'] == 0) {
                    return redirect()->back()->with('error', $uploadResult['msg']);
                }

                TenantDocument::create([
                    'property_id' => $request->property,
                    'tenant_id'   => $tenant->id,
                    'document'    => $uploadResult['filename'],
                    'parent_id'   => parentId(),
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'msg'    => __('Tenant successfully updated.'),
        ]);
    }


    public function destroy(Tenant $tenant)
    {
        if (\Auth::user()->can('delete tenant')) {

            $documents = TenantDocument::where('tenant_id', $tenant->id)->get();
            foreach ($documents as $doc) {
                if (!empty($doc->document)) {
                    deleteOldFile($doc->document, 'upload/tenant/');
                }
                $doc->delete();
            }

            $user = User::find($tenant->user_id);
            if ($user && !empty($user->profile)) {
                deleteOldFile($user->profile, 'upload/profile/');
            }

            if ($user) {
                $user->delete();
            }

            $tenant->delete();

            return redirect()->back()->with('success', 'Tenant successfully deleted.');
        }

        return redirect()->back()->with('error', __('Permission Denied!'));
    }


    public function fileDestroy($id)
    {
        $document = TenantDocument::findOrFail($id);

        // Delete file from storage
        if (!empty($document->document)) {
            deleteOldFile($document->document, 'upload/tenant/');
        }

        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }


    public function getPropertyUnit($property_id)
    {

        // dd($property_id);
        $units = PropertyUnit::where('property_id', $property_id)
            ->select('id', 'name', 'is_occupied')
            ->get();

        $formatted = $units->mapWithKeys(function ($unit) {
            return [
                $unit->id => [
                    'name' => $unit->name,
                    'is_occupied' => $unit->is_occupied,
                ],
            ];
        });

        return response()->json($formatted);
    }

    public function getUnitDetails($id)
    {
        $unit = PropertyUnit::find($id);

        if (!$unit) {
            return response()->json(['error' => 'Unit not found'], 404);
        }

        return response()->json([
            'name' => ucfirst($unit->name),
            'is_occupied' => $unit->is_occupied,
            'bedroom' => $unit->bedroom,
            'kitchen' => $unit->kitchen,
            'baths' => $unit->baths,
            'rent_type' => $unit->rent_type,
            'rent' => priceFormat($unit->rent),
            'start_date' => $unit->start_date,
            'end_date' => $unit->end_date,
            'payment_due_date' => $unit->payment_due_date,
            'rent_duration' => $unit->rent_duration,
            'deposit_type' => $unit->deposit_type,
            'deposit_amount' => priceFormat($unit->deposit_amount),
            'late_fee_type' => $unit->late_fee_type,
            'late_fee_amount' => priceFormat($unit->late_fee_amount),
            'incident_receipt_amount' => priceFormat($unit->incident_receipt_amount),
            'notes' => $unit->notes,
        ]);
    }

    public function tenantExit($id)
    {
        if (\Auth::user()->can('create tenant')) {


            $tenant = Tenant::where('id', $id)->first();

            return view('tenant.exit', compact('tenant'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function tenantExitUpdate(Request $request, $tid)
    {
        if (\Auth::user()->can('create tenant')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'exit_amount' => 'required',
                    'extra_charge' => 'required',
                    'exit_date' => 'required',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $tenant = Tenant::find($tid);
            if (!$tenant) {
                return redirect()->back()->with('error', __('Tenant not found.'));
            }

            $pro_unit = PropertyUnit::find($tenant->unit);
            $pro_unit->is_occupied = 0;
            $pro_unit->save();


            $tenant->unit = 0;
            $tenant->exit_amount = $request->exit_amount;
            $tenant->extra_charge = $request->extra_charge;
            $tenant->exit_date = $request->exit_date;
            // $tenant->lease_end_date = $request->lease_end_date;
            $tenant->reason = $request->reason;
            $tenant->save();




            return redirect()->back()->with('success', __('Tenant exit details updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Show SMS send form for tenants
     */
    public function showSendSms()
    {
        if (\Auth::user()->can('manage tenant')) {
            $tenants = Tenant::where('parent_id', parentId())
                ->with('user')
                ->orderBy('id', 'desc')
                ->get();
            
            return view('tenant.send-sms', compact('tenants'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Send SMS to selected tenants using Zettatel file upload method
     */
    public function sendSms(Request $request)
    {
        if (!\Auth::user()->can('manage tenant')) {
            return response()->json([
                'status' => 'error',
                'msg' => __('Permission Denied!'),
            ]);
        }

        $validator = \Validator::make(
            $request->all(),
            [
                'tenant_ids' => 'required|array|min:1',
                'message' => 'required|string|max:1600',
                'msgType' => 'nullable|in:text,unicode',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'msg' => $validator->getMessageBag()->first(),
            ]);
        }

        try {
            // Get selected tenants with their users
            $tenantIds = $request->tenant_ids;
            $tenants = Tenant::where('parent_id', parentId())
                ->whereIn('id', $tenantIds)
                ->with('user')
                ->get();

            if ($tenants->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('No valid tenants selected.'),
                ]);
            }

            // Collect mobile numbers
            $mobileNumbers = [];
            foreach ($tenants as $tenant) {
                if ($tenant->user && !empty($tenant->user->phone_number)) {
                    $phoneNumber = preg_replace('/[^0-9+]/', '', $tenant->user->phone_number);
                    if (!empty($phoneNumber)) {
                        $mobileNumbers[] = $phoneNumber;
                    }
                }
            }

            if (empty($mobileNumbers)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('No valid phone numbers found for selected tenants.'),
                ]);
            }

            // Prepare options
            $options = [
                'msgType' => $request->msgType ?? 'text',
                'duplicatecheck' => $request->duplicatecheck ?? 'true',
                'output' => 'json',
            ];

            if ($request->has('scheduleTime') && !empty($request->scheduleTime)) {
                $options['scheduleTime'] = $request->scheduleTime;
            }

            if ($request->has('testMessage')) {
                $options['testMessage'] = $request->testMessage === 'true' ? 'true' : 'false';
            }

            // Send SMS using Zettatel
            $response = send_zettatel_sms_file($mobileNumbers, $request->message, $options);

            if ($response['status'] === 'success') {
                return response()->json([
                    'status' => 'success',
                    'msg' => __('SMS sent successfully to :count tenant(s). Transaction ID: :transactionId', [
                        'count' => count($mobileNumbers),
                        'transactionId' => $response['transactionId'] ?? 'N/A',
                    ]),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'msg' => $response['message'] ?? __('Failed to send SMS.'),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Send SMS Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'msg' => __('An error occurred while sending SMS: :error', ['error' => $e->getMessage()]),
            ]);
        }
    }

    /**
     * Send SMS to all tenants for the current agent
     */
    public function sendSmsToAll(Request $request)
    {
        if (!\Auth::user()->can('manage tenant')) {
            return response()->json([
                'status' => 'error',
                'msg' => __('Permission Denied!'),
            ]);
        }

        $validator = \Validator::make(
            $request->all(),
            [
                'message' => 'required|string|max:1600',
                'msgType' => 'nullable|in:text,unicode',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'msg' => $validator->getMessageBag()->first(),
            ]);
        }

        try {
            // Get all tenants for current agent
            $tenants = Tenant::where('parent_id', parentId())
                ->with('user')
                ->get();

            if ($tenants->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('No tenants found.'),
                ]);
            }

            // Collect mobile numbers
            $mobileNumbers = [];
            foreach ($tenants as $tenant) {
                if ($tenant->user && !empty($tenant->user->phone_number)) {
                    $phoneNumber = preg_replace('/[^0-9+]/', '', $tenant->user->phone_number);
                    if (!empty($phoneNumber)) {
                        $mobileNumbers[] = $phoneNumber;
                    }
                }
            }

            if (empty($mobileNumbers)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('No valid phone numbers found.'),
                ]);
            }

            // Prepare options
            $options = [
                'msgType' => $request->msgType ?? 'text',
                'duplicatecheck' => $request->duplicatecheck ?? 'true',
                'output' => 'json',
            ];

            if ($request->has('scheduleTime') && !empty($request->scheduleTime)) {
                $options['scheduleTime'] = $request->scheduleTime;
            }

            if ($request->has('testMessage')) {
                $options['testMessage'] = $request->testMessage === 'true' ? 'true' : 'false';
            }

            // Send SMS using Zettatel
            $response = send_zettatel_sms_file($mobileNumbers, $request->message, $options);

            if ($response['status'] === 'success') {
                return response()->json([
                    'status' => 'success',
                    'msg' => __('SMS sent successfully to :count tenant(s). Transaction ID: :transactionId', [
                        'count' => count($mobileNumbers),
                        'transactionId' => $response['transactionId'] ?? 'N/A',
                    ]),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'msg' => $response['message'] ?? __('Failed to send SMS.'),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Send SMS to All Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'msg' => __('An error occurred while sending SMS: :error', ['error' => $e->getMessage()]),
            ]);
        }
    }
}
