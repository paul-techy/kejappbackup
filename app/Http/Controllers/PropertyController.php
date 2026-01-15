<?php

namespace App\Http\Controllers;

use App\Models\Advantage;
use App\Models\Amenity;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyUnit;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage property')) {
            $properties = Property::where('parent_id', parentId())->where('is_active', 1)->orderBy('id', 'desc')->get();
            return view('property.index', compact('properties'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function create()
    {

        if (\Auth::user()->can('create property')) {
            $types = Property::$Type;
            $unitTypes = PropertyUnit::$Types;
            $rentTypes = PropertyUnit::$rentTypes;
            $amenities = Amenity::where('parent_id', parentId())->orderBy('id', 'desc')->get();
            $advantages = Advantage::where('parent_id', parentId())->orderBy('id', 'desc')->get();

            return view('property.create', compact('types', 'rentTypes', 'unitTypes', 'amenities', 'advantages'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function store(Request $request)
    {

        // dd($request->all());
        if (\Auth::user()->can('create property')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'description' => 'required',
                    'type' => 'required',
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',
                    'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
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
            $totalProperty = $authUser->totalProperty();
            $subscription = Subscription::find($authUser->subscription);
            if ($totalProperty >= $subscription->property_limit && $subscription->property_limit != 0) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('Your property limit is over, please upgrade your subscription.'),
                    'id' => 0,
                ]);
            }


            $property = new Property();
            $property->name = $request->name;
            $property->description = $request->description;
            $property->type = $request->type;
            $property->amenities_id = is_array($request->amenities) ? implode(',', $request->amenities) : null;
            $property->advantage_id = is_array($request->advantages) ? implode(',', $request->advantages) : null;
            $property->country = $request->country;
            $property->state = $request->state;
            $property->city = $request->city;
            $property->zip_code = $request->zip_code;
            $property->listing_type = $request->listing_type;
            $property->price = $request->price;
            $property->address = $request->address;
            $property->parent_id = parentId();
            $property->save();


            if ($request->hasFile('thumbnail')) {
                $uploadResult = handleFileUpload($request->file('thumbnail'), 'upload/property/thumbnail/');
                if ($uploadResult['flag'] == 0) {
                    return redirect()->back()->with('error', $uploadResult['msg']);
                }
                $thumbnail = new PropertyImage();
                $thumbnail->property_id = $property->id;
                $thumbnail->image = $uploadResult['filename'];
                $thumbnail->type = 'thumbnail';
                $thumbnail->save();
            }


            if ($request->hasFile('property_images')) {
                foreach ($request->file('property_images') as $image) {
                    $uploadResult = handleFileUpload($image, 'upload/property/image/');

                    if ($uploadResult['flag'] == 1) {
                        PropertyImage::create([
                            'property_id' => $property->id,
                            'image'    => $uploadResult['filename'],
                            'type'   => 'extra',
                        ]);
                    } else {
                        return redirect()->back()->with('error', $uploadResult['msg']);
                    }
                }
            }


            if (!empty($request->name) && !empty($request->bedroom) && !empty($request->kitchen)) {
                $unit = new PropertyUnit();
                $unit->name = $request->name;
                $unit->bedroom = $request->bedroom;
                $unit->kitchen = $request->kitchen;
                $unit->baths = !empty($request->baths) ? $request->baths : 0;
                $unit->rent = !empty($request->rent) ? $request->rent : 0;
                $unit->rent_type = $request->rent_type;
                if ($request->rent_type == 'custom') {
                    $unit->start_date = $request->start_date;
                    $unit->end_date = $request->end_date;
                    $unit->payment_due_date = $request->payment_due_date;
                } else {
                    $unit->rent_duration = $request->rent_duration;
                }

                $unit->deposit_type = !empty($request->deposit_type) ? $request->deposit_type : null;
                $unit->deposit_amount = !empty($request->deposit_amount) ? $request->deposit_amount : 0;
                $unit->late_fee_type = !empty($request->late_fee_type) ? $request->late_fee_type : null;
                $unit->late_fee_amount = !empty($request->late_fee_amount) ? $request->late_fee_amount : 0;
                $unit->incident_receipt_amount = !empty($request->incident_receipt_amount) ? $request->incident_receipt_amount : 0;
                $unit->notes = $request->notes;
                $unit->property_id = $property->id;
                $unit->is_occupied = 0;
                $unit->parent_id = parentId();
                $unit->save();
            }


            return response()->json([
                'status' => 'success',
                'msg' => __('Property successfully created.'),
                'id' => Crypt::encrypt($property->id),
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function show($ids)
    {
        if (\Auth::user()->can('show property')) {
            $id = Crypt::decrypt($ids);
            $property = Property::where('id', $id)->first();
            $units = PropertyUnit::where('property_id', $property->id)->orderBy('id', 'desc')->get();

            $selectedAmenities = collect();
            if (!empty($property->amenities_id)) {
                $ids = array_filter(explode(',', $property->amenities_id));
                $selectedAmenities = Amenity::whereIn('id', $ids)->get();
            }

            $selectedAdvantages = collect();
            if (!empty($property->advantage_id)) {
                $ids = array_filter(explode(',', $property->advantage_id));
                $selectedAdvantages = Advantage::whereIn('id', $ids)->get();
            }

            return view('property.show', compact('property', 'units', 'selectedAmenities', 'selectedAdvantages'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function edit($ids)
    {
        if (\Auth::user()->can('edit property')) {
            $id = Crypt::decrypt($ids);
            $property = Property::where('id', $id)->first();
            $types = Property::$Type;

            $amenities = Amenity::where('parent_id', parentId())->orderBy('id', 'desc')->get();
            $selectedAmenities = explode(',', $property->amenities_id);

            $advantages = Advantage::where('parent_id', parentId())->orderBy('id', 'desc')->get();
            $selectedAdvantages = explode(',', $property->advantage_id);
            return view('property.edit', compact('types', 'property', 'amenities', 'selectedAmenities', 'advantages', 'selectedAdvantages'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }



    public function update(Request $request, Property $property)
    {

        if (\Auth::user()->can('edit property')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'description' => 'required',
                    'type' => 'required',
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',

                ]

            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),

                ]);
            }

            $property->name = $request->name;
            $property->description = $request->description;
            $property->type = $request->type;
            $property->amenities_id = is_array($request->amenities) ? implode(',', $request->amenities) : null;
            $property->advantage_id = is_array($request->advantages) ? implode(',', $request->advantages) : null;
            $property->country = $request->country;
            $property->state = $request->state;
            $property->city = $request->city;
            $property->zip_code = $request->zip_code;
            $property->address = $request->address;
            $property->save();


            if ($request->hasFile('thumbnail')) {
                $uploadResult = handleFileUpload($request->file('thumbnail'), 'upload/property/thumbnail/');

                if ($uploadResult['flag'] == 0) {
                    return redirect()->back()->with('error', $uploadResult['msg']);
                }
                $oldThumbnails = PropertyImage::where('property_id', $property->id)->where('type', 'thumbnail')->get();
                foreach ($oldThumbnails as $old) {
                    deleteOldFile($old->image, 'upload/property/thumbnail/');
                    $old->delete();
                }

                $thumbnail = new PropertyImage();
                $thumbnail->property_id = $property->id;
                $thumbnail->image = $uploadResult['filename'];
                $thumbnail->type = 'thumbnail';
                $thumbnail->save();
            }

            if ($request->hasFile('property_images')) {
                foreach ($request->file('property_images') as $image) {
                    $uploadResult = handleFileUpload($image, 'upload/property/image/');

                    if ($uploadResult['flag'] == 1) {
                        PropertyImage::create([
                            'property_id' => $property->id,
                            'image'    => $uploadResult['filename'],
                            'type'   => 'extra',
                        ]);
                    } else {
                        return redirect()->back()->with('error', $uploadResult['msg']);
                    }
                }
            }


            return response()->json([
                'status' => 'success',
                'msg' => __('Property successfully updated.'),
                'id' => Crypt::encrypt($property->id),
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function destroy(Property $property)
    {
        if (\Auth::user()->can('delete property')) {

            $documents = PropertyImage::where('property_id', $property->id)->get();
            foreach ($documents as $doc) {
                if (!empty($doc->image)) {
                    deleteOldFile($doc->image, 'upload/property/thumbnail/');
                }
                $doc->delete();
            }
            $property->delete();

            return redirect()->back()->with('success', 'Property successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function units()
    {
        if (\Auth::user()->can('manage unit')) {
            $units = PropertyUnit::where('parent_id', parentId())->get();
            return view('unit.index', compact('units'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function unitCreate($property_id)
    {

        $types = PropertyUnit::$Types;
        $rentTypes = PropertyUnit::$rentTypes;
        return view('unit.create', compact('types', 'property_id', 'rentTypes'));
    }



    public function unitStore(Request $request, $property_id)
    {

        if (\Auth::user()->can('create unit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'bedroom' => 'required',
                    'kitchen' => 'required',
                    'baths' => 'required',
                    'rent' => 'required',
                    'rent_type' => 'required',
                    'deposit_type' => 'required',
                    'deposit_amount' => 'required',
                    'late_fee_type' => 'required',
                    'late_fee_amount' => 'required',
                    'incident_receipt_amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $unit = new PropertyUnit();
            $unit->name = $request->name;
            $unit->bedroom = $request->bedroom;
            $unit->kitchen = $request->kitchen;
            $unit->baths = $request->baths;
            $unit->rent = $request->rent;
            $unit->rent_type = $request->rent_type;
            if ($request->rent_type == 'custom') {
                $unit->start_date = $request->start_date;
                $unit->end_date = $request->end_date;
                $unit->payment_due_date = $request->payment_due_date;
            } else {
                $unit->rent_duration = $request->rent_duration;
            }

            $unit->deposit_type = $request->deposit_type;
            $unit->deposit_amount = $request->deposit_amount;
            $unit->late_fee_type = $request->late_fee_type;
            $unit->late_fee_amount = $request->late_fee_amount;
            $unit->incident_receipt_amount = $request->incident_receipt_amount;
            $unit->notes = $request->notes;
            $unit->property_id = $property_id;
            $unit->is_occupied = 0;
            $unit->parent_id = parentId();
            $unit->save();
            return redirect()->back()->with('success', __('Unit successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function unitdirectCreate()
    {
        $name = Property::all('name', 'id')->pluck('name', 'id');
        $types = PropertyUnit::$Types;
        $rentTypes = PropertyUnit::$rentTypes;
        return view('unit.directcreate', compact('types', 'rentTypes', 'name'));
    }

    public function unitdirectStore(Request $request)
    {
        if (\Auth::user()->can('create unit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'property_id' => 'required',
                    'bedroom' => 'required',
                    'kitchen' => 'required',
                    'baths' => 'required',
                    'rent' => 'required',
                    'rent_type' => 'required',
                    'deposit_type' => 'required',
                    'deposit_amount' => 'required',
                    'late_fee_type' => 'required',
                    'late_fee_amount' => 'required',
                    'incident_receipt_amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $unit = new PropertyUnit();
            $unit->name = $request->name;
            $unit->property_id = $request->property_id;
            $unit->bedroom = $request->bedroom;
            $unit->kitchen = $request->kitchen;
            $unit->baths = $request->baths;
            $unit->rent = $request->rent;
            $unit->rent_type = $request->rent_type;
            if ($request->rent_type == 'custom') {
                $unit->start_date = $request->start_date;
                $unit->end_date = $request->end_date;
                $unit->payment_due_date = $request->payment_due_date;
            } else {
                $unit->rent_duration = $request->rent_duration;
            }

            $unit->deposit_type = $request->deposit_type;
            $unit->deposit_amount = $request->deposit_amount;
            $unit->late_fee_type = $request->late_fee_type;
            $unit->late_fee_amount = $request->late_fee_amount;
            $unit->incident_receipt_amount = $request->incident_receipt_amount;
            $unit->notes = $request->notes;
            $unit->parent_id = parentId();
            $unit->save();
            return redirect()->back()->with('success', __('Unit successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function unitEdit($property_id, $unit_id)
    {
        $unit = PropertyUnit::find($unit_id);
        $types = PropertyUnit::$Types;
        $rentTypes = PropertyUnit::$rentTypes;
        return view('unit.edit', compact('types', 'property_id', 'rentTypes', 'unit'));
    }

    public function unitShow($unit_id)
    {

        if (\Auth::user()->can('show unit')) {

            $uid = Crypt::decrypt($unit_id);
            $unit = PropertyUnit::where('id', $uid)->first();

            $invoices = Invoice::where('parent_id', parentId())->where('unit_id', $unit->id)->orderBy('id', 'desc')->get();
            $expenses = Expense::where('parent_id', parentId())->where('unit_id', $unit->id)->orderBy('id', 'desc')->get();

            return view('unit.show', compact('unit', 'invoices', 'expenses'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function unitUpdate(Request $request, $property_id, $unit_id)
    {
        if (\Auth::user()->can('edit unit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'bedroom' => 'required',
                    'kitchen' => 'required',
                    'baths' => 'required',
                    'rent' => 'required',
                    'rent_type' => 'required',
                    'deposit_type' => 'required',
                    'deposit_amount' => 'required',
                    'late_fee_type' => 'required',
                    'late_fee_amount' => 'required',
                    'incident_receipt_amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $unit = PropertyUnit::find($unit_id);
            $unit->name = $request->name;
            $unit->bedroom = $request->bedroom;
            $unit->kitchen = $request->kitchen;
            $unit->baths = $request->baths;
            $unit->rent = $request->rent;
            $unit->rent_type = $request->rent_type;
            if ($request->rent_type == 'custom') {
                $unit->start_date = $request->start_date;
                $unit->end_date = $request->end_date;
                $unit->payment_due_date = $request->payment_due_date;
            } else {
                $unit->rent_duration = $request->rent_duration;
            }

            $unit->deposit_type = $request->deposit_type;
            $unit->deposit_amount = $request->deposit_amount;
            $unit->late_fee_type = $request->late_fee_type;
            $unit->late_fee_amount = $request->late_fee_amount;
            $unit->incident_receipt_amount = $request->incident_receipt_amount;
            $unit->notes = $request->notes;
            $unit->save();
            return redirect()->back()->with('success', __('Unit successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function unitDestroy($property_id, $unit_id)
    {
        if (\Auth::user()->can('delete unit')) {
            $unit = PropertyUnit::find($unit_id);
            $unit->delete();
            return redirect()->back()->with('success', 'Unit successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function getPropertyUnit($property_id)
    {
        $units = PropertyUnit::where('property_id', $property_id)->get()->pluck('name', 'id');
        return response()->json($units);
    }

    public function fileDestroy($id)
    {
        $images = PropertyImage::findOrFail($id);

        // Delete file from storage
        if (!empty($images->image)) {
            deleteOldFile($images->image, 'upload/property/image/');
        }

        $images->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
