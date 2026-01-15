@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Details') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('tenant.index') }}"> {{ __('Tenant') }}</a></li>
    <li class="breadcrumb-item active">
        <a href="#">{{ __('Details') }}</a>
    </li>
@endsection

@php
    $profile = asset(Storage::url('upload/profile/avatar.png'));
@endphp
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-xxl-3">
                            <div class="card border">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img class="img-radius img-fluid wid-80"
                                                src= "{{ !empty($tenant->user->profile) ? fetch_file($tenant->user->profile, 'upload/profile/') : $profile }}"
                                                alt="User image" />
                                        </div>
                                        <div class="flex-grow-1 mx-3">
                                            <h5 class="mb-1">
                                                {{ ucfirst(!empty($tenant->user) ? $tenant->user->first_name : '') . ' ' . ucfirst(!empty($tenant->user) ? $tenant->user->last_name : '') }}
                                            </h5>
                                            <h6 class="mb-0 text-secondary">{!! $tenant->LeaseLeftDay() !!}</h6>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body px-2 pb-0">
                                    <div class="list-group list-group-flush">
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="material-icons-two-tone f-20">email</i>
                                                </div>
                                                <div class="flex-grow-1 mx-3">
                                                    <h5 class="m-0">{{ __('Email') }}</h5>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <small>{{ !empty($tenant->user) ? $tenant->user->email : '-' }}</small>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="material-icons-two-tone f-20">phonelink_ring</i>
                                                </div>
                                                <div class="flex-grow-1 mx-3">
                                                    <h5 class="m-0">{{ __('Phone') }}</h5>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <small>{{ !empty($tenant->user) ? $tenant->user->phone_number : '-' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- @dd($tenant->units) --}}
                        <div class="col-lg-8 col-xxl-9">
                            <div class="card border">
                                <div class="card-header">
                                    <div class="row align-items-center g-2">
                                        <div class="col">
                                            <h5>{{ __('Additional Information') }}</h5>
                                        </div>

                                        @if (\Auth::user()->type == 'owner' && $tenant->units && $tenant->units->is_occupied == 1)
                                            <div class="col-auto">
                                                <a class="btn btn-light-danger customModal" href="#"
                                                    data-url="{{ route('tenant.exit', $tenant->id) }}"
                                                    data-title="{{ __('Exit Tenant') }}">
                                                    {{ __('Exit Tenant') }}
                                                </a>

                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td><b class="text-header">{{ __('Total Family Member') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ !empty($tenant->family_member) ? $tenant->family_member : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header">{{ __('Country') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ !empty($tenant->country) ? $tenant->country : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header">{{ __('State') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ !empty($tenant->state) ? $tenant->state : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header">{{ __('City') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ !empty($tenant->city) ? $tenant->city : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header">{{ __('Zip Code') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ !empty($tenant->zip_code) ? $tenant->zip_code : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header">{{ __('Property') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ !empty($tenant->properties) ? $tenant->properties->name : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header">{{ __('Unit') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ !empty($tenant->units) ? $tenant->units->name : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header">{{ __('Lease Start Date') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ dateFormat($tenant->lease_start_date) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header">{{ __('Lease End Date') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ dateFormat($tenant->lease_end_date) }}</td>
                                                </tr>

                                                <tr>
                                                    <td><b class="text-header">{{ __('Address') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ !empty($tenant->address) ? $tenant->address : '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (!empty($tenant->documents) && $tenant->documents->count())
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Tenant Documents') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($tenant->documents as $doc)
                                @php
                                    $folder = 'upload/tenant/';
                                    $filename = $doc->document;
                                    $fileUrl = fetch_file($filename, $folder);

                                    $fileExtension = pathinfo($filename, PATHINFO_EXTENSION); // Use filename, not URL
                                    $isImage = in_array(strtolower($fileExtension), [
                                        'jpg',
                                        'jpeg',
                                        'png',
                                        'gif',
                                        'webp',
                                    ]);
                                @endphp

                                <div class="col-md-2 col-sm-4 col-6 mb-2">
                                    <div
                                        class="card gallery-card shadow-sm border rounded text-center d-flex flex-column justify-content-between">
                                        @if ($isImage)
                                            <a href="{{ $fileUrl }}" target="_blank">
                                                <img src="{{ $fileUrl }}" alt="Document"
                                                    class="img-fluid img-card-top rounded-top mt-1"
                                                    style="height: 180px; object-fit: cover;">
                                            </a>
                                        @else
                                            <a href="{{ $fileUrl }}" target="_blank"
                                                class="d-flex justify-content-center align-items-center bg-light"
                                                style="height: 180px;">
                                                <i class="ti ti-file-text" style="font-size: 48px;"></i>
                                            </a>
                                        @endif
                                        <hr>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ $fileUrl }}" download title="Download"
                                                class="avtar btn-link-success text-success p-0">
                                                <i class="ti ti-download "></i>
                                            </a>

                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['tenant.document.delete', $doc->id],
                                                'id' => 'doc-' . $doc->id,
                                            ]) !!}
                                            <a class="avtar  btn-link-danger text-danger confirm_dialog p-0 confirm_dialog"
                                                href="#"><i class="ti ti-trash text-danger"></i>
                                            </a>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                    </div>
                </div>
            </div>
        @endif

        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5>{{ __('Invoice List') }}</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-hover advance-datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Invoice') }}</th>
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Invoice Month') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    @if (Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                        <th class="text-right">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ invoicePrefix() . $invoice->invoice_id }} </td>
                                        <td>{{ !empty($invoice->properties) ? $invoice->properties->name : '-' }}
                                        </td>
                                        <td>{{ !empty($invoice->units) ? $invoice->units->name : '-' }}
                                        </td>
                                        <td>{{ date('F Y', strtotime($invoice->invoice_month)) }}
                                        </td>
                                        <td>{{ dateFormat($invoice->end_date) }} </td>
                                        <td>{{ priceFormat($invoice->getInvoiceSubTotalAmount()) }}
                                        </td>
                                        <td>
                                            @if ($invoice->status == 'open')
                                                <span
                                                    class="badge bg-light-info">{{ \App\Models\Invoice::$status[$invoice->status] }}</span>
                                            @elseif($invoice->status == 'paid')
                                                <span
                                                    class="badge bg-light-success">{{ \App\Models\Invoice::$status[$invoice->status] }}</span>
                                            @elseif($invoice->status == 'partial_paid')
                                                <span
                                                    class="badge bg-light-warning">{{ \App\Models\Invoice::$status[$invoice->status] }}</span>
                                            @endif
                                        </td>
                                        @if (Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                            <td>
                                                <div class="cart-action">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id]]) !!}

                                                    @can('show invoice')
                                                        <a class="avtar avtar-xs btn-link-warning text-warning"
                                                            href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('View') }}">
                                                            <i data-feather="eye"></i></a>
                                                    @endcan

                                                    {!! Form::close() !!}
                                                </div>

                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>




    </div>




@endsection
