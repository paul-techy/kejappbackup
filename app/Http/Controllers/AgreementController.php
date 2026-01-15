<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Property;
use App\Models\Setting;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AgreementController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage agreement')) {
            if (\Auth::user()->type == 'tenant') {
                $tenant = Tenant::where('user_id', \Auth::user()->id)->first();
                $agreements = Agreement::where('property_id', $tenant->property)->where('unit_id', $tenant->unit)
                    ->where('parent_id', parentId())->orderBy('id', 'desc')->get();
            } else {
                $agreements = Agreement::where('parent_id', parentId())->orderBy('id', 'desc')->get();
            }

            return view('agreement.index', compact('agreements'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create agreement')) {
            $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);
            $status = Agreement::$status;
            $setting = settings(\Auth::user()->id);
            return view('agreement.create', compact('property', 'status','setting'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function store(Request $request)
    {

        // dd($request->all());
        if (\Auth::user()->can('create agreement')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_id' => 'required',
                    'unit_id' => 'required',
                    'date' => 'required',
                    'status' => 'required',
                    'terms_condition' => 'required',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $agreement = new Agreement();
            $agreement->agreement_id = ($this)->agreementNumber();
            $agreement->property_id = $request->property_id;
            $agreement->unit_id = $request->unit_id;
            $agreement->date = $request->date;
            $agreement->status = $request->status;
            $agreement->terms_condition = $request->terms_condition;
            $agreement->description = $request->description;
            $agreement->parent_id = parentId();
            $agreement->save();


            return redirect()->back()->with('success', __('Agreement successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function show($ids)
    {

        if (\Auth::user()->can('show agreement')) {

            $id = Crypt::decrypt($ids);
            $agreement = Agreement::where('id', $id)->first();

            return view('agreement.show', compact('agreement'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function edit(Agreement $agreement)
    {
        if (\Auth::user()->can('edit agreement')) {

            $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);

            $status = Agreement::$status;

            return view('agreement.edit', compact('property', 'status', 'agreement'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }



    public function update(Request $request, Agreement $agreement)
    {

        if (\Auth::user()->can('edit agreement')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'property_id' => 'required',
                    'unit_id' => 'required',
                    'date' => 'required',
                    'status' => 'required',
                    'terms_condition' => 'required',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $agreement->agreement_id = ($this)->agreementNumber();
            $agreement->property_id = $request->property_id;
            $agreement->unit_id = $request->unit_id;
            $agreement->date = $request->date;
            $agreement->status = $request->status;
            $agreement->terms_condition = $request->terms_condition;
            $agreement->description = $request->description;
            $agreement->parent_id = parentId();
            $agreement->save();

            if ($request->hasFile('attachment')) {
                $uploadResult = handleFileUpload($request->file('attachment'), 'upload/attachment/');
                if ($uploadResult['flag'] == 0) {
                    return redirect()->back()->with('error', $uploadResult['msg']);
                }
                $agreement->attachment = $uploadResult['filename'];
                $agreement->save();
            }

            return redirect()->back()->with('success', __('Agreement successfully update.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function destroy(Agreement $agreement)
    {
        if (\Auth::user()->can('delete agreement')) {

            if ($agreement) {
                if (!empty($agreement->attachment)) {
                    deleteOldFile($agreement->attachment, 'upload/attachment/');
                }

                $agreement->delete();
            }
            return redirect()->back()->with('success', __('Agreement successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function agreementNumber()
    {
        $latestAgreement = Agreement::where('parent_id', parentId())->latest()->first();
        if ($latestAgreement == null) {
            return 1;
        } else {
            return $latestAgreement->agreement_id + 1;
        }
    }
}
