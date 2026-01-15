@extends('layouts.app')
@section('page-title')
    {{ __('Unit Details') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('unit.index') }}"> {{ __('Unit') }}</a></li>
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
                <div class="card-header">
                    <h4> {{ __('Unit Detail') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Unit Name') }}</b>
                                <p class="mb-20 text-muted">{{ $unit->name }} </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Status') }}</b>
                                <p class="mb-20 text-muted">
                                    @if ($unit->is_occupied)
                                        <span class=" ms-1 text-danger">{{ __('Occupied') }}</span>
                                    @else
                                        <span class="text-success ms-1">{{ __('Vacant') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Bedroom') }}</b>
                                <p class="mb-20 text-muted">{{ $unit->bedroom }} </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Kitchen') }}</b>
                                <p class="mb-20 text-muted">{{ $unit->kitchen }} </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Bath') }}</b>
                                <p class="mb-20 text-muted">{{ $unit->baths }} </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Rent Type') }}</b>
                                <p class="mb-20 text-muted">{{ $unit->rent_type }} </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Rent') }}</b>
                                <p class="mb-20 text-muted">{{ priceFormat($unit->rent) }}</p>
                            </div>
                        </div>
                        @if ($unit->rent_type == 'custom')
                            <div class="col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <b>{{ __('Start Date') }}</b>
                                    <p class="mb-20 text-muted">{{ dateformat($unit->start_date) }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <b>{{ __('End Date') }}</b>
                                    <p class="mb-20 text-muted">{{ dateformat($unit->end_date) }}</p>
                                </div>
                            </div>
                        @else
                            <div class="col-md-3 col-lg-3">
                                <div class="detail-group">
                                    <b>{{ __('Rent Duration') }}</b>
                                    <p class="mb-20 text-muted">{{ $unit->rent_duration }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Deposit Type') }}</b>
                                <p class="mb-20 text-muted">{{ $unit->deposit_type }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Deposit Amount') }}</b>
                                <p class="mb-20 text-muted">
                                    {{ $unit->deposit_type == 'fixed' ? priceFormat($unit->deposit_amount) : $unit->deposit_amount . '%' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Late Fee Type') }}</b>
                                <p class="mb-20 text-muted">{{ $unit->late_fee_type }}</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Late Fee Amount') }}</b>
                                <p class="mb-20 text-muted">
                                    {{ $unit->late_fee_type == 'fixed' ? priceFormat($unit->late_fee_amount) : $unit->late_fee_amount . '%' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3">
                            <div class="detail-group">
                                <b>{{ __('Incident Receipt Amount') }}</b>
                                <p class="mb-20 text-muted">{{ priceFormat($unit->incident_receipt_amount) }}</p>
                            </div>
                        </div>







                        <div class="col-md-6 col-lg-6">
                            <div class="detail-group">
                                <b>{{ __('Notes') }}</b>
                                <p class="mb-20 text-muted">{{ $unit->notes }} </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

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

        <div class="col-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5>{{ __('Expense List') }}</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-hover advance-datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Expense') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Receipt') }}</th>
                                    @if (Gate::check('edit expense') || Gate::check('delete expense') || Gate::check('show expense'))
                                        <th class="text-right">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenses as $expense)
                                    <tr role="row">
                                        <td>{{ expensePrefix() . $expense->expense_id }} </td>
                                        <td> {{ $expense->title }} </td>
                                        <td> {{ !empty($expense->properties) ? $expense->properties->name : '-' }} </td>
                                        <td> {{ !empty($expense->units) ? $expense->units->name : '-' }} </td>
                                        <td> {{ !empty($expense->types) ? $expense->types->title : '-' }} </td>
                                        <td> {{ dateFormat($expense->date) }} </td>
                                        <td> {{ priceFormat($expense->amount) }} </td>
                                        <td>
                                            @if (!empty($expense->receipt))
                                                <a href="{{ !empty($expense->receipt) ? fetch_file($expense->receipt, 'upload/receipt/') : '#' }}"
                                                    download="download"><i data-feather="download"></i></a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if (Gate::check('edit expense') || Gate::check('delete expense') || Gate::check('show expense'))
                                            <td class="text-right">
                                                <div class="cart-action">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['expense.destroy', $expense->id]]) !!}
                                                    @can('show expense')
                                                        <a class="avtar avtar-xs btn-link-warning text-warning customModal"
                                                            data-size="lg" data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('View') }}" href="#"
                                                            data-url="{{ route('expense.show', $expense->id) }}"
                                                            data-title="{{ __('Expense Details') }}"> <i
                                                                data-feather="eye"></i></a>
                                                    @endcan
                                                    @can('edit expense')
                                                        <a class="avtar avtar-xs btn-link-secondary text-secondary customModal"
                                                            data-size="lg" data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Edit') }}" href="#"
                                                            data-url="{{ route('expense.edit', $expense->id) }}"
                                                            data-title="{{ __('Edit Expense') }}"> <i
                                                                data-feather="edit"></i></a>
                                                    @endcan
                                                    @can('delete expense')
                                                        <a class="avtar avtar-xs btn-link-danger text-danger confirm_dialog"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Detete') }}" href="#"> <i
                                                                data-feather="trash-2"></i></a>
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
