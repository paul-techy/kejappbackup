@extends('layouts.app')

@section('page-title')
    {{ __('Income Report') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Income Report') }}</li>
@endsection

@push('script-page')
    <script>
        $('#property_id').on('change', function() {
            "use strict";
            var property_id = $(this).val();
            var url = '{{ route('property.unit', ':id') }}';
            url = url.replace(':id', property_id);
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    property_id: property_id,
                },
                contentType: false,
                processData: false,
                type: 'GET',
                success: function(data) {
                    $('.unit').empty();
                    var unit =
                        `<select class="form-control hidesearch unit" id="unit_id" name="unit_id"></select>`;
                    $('.unit_div').html(unit);

                    $.each(data, function(key, value) {
                        $('.unit').append('<option value="' + key + '">' + value + '</option>');
                    });
                    $(".hidesearch").each(function() {
                        var basic_select = new Choices(this, {
                            searchEnabled: false,
                            removeItemButton: true,
                        });
                    });
                },

            });
        });
    </script>
@endpush
@push('css-page')
    <style>
        .cust-pro {
            width: 230px;
        }

        .choices__list--dropdown .choices__item--selectable:after {
            content: '';
        }

        .choices__list--dropdown .choices__item--selectable {
            padding-right: 10px;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">

                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h5 class="mb-0">{{ __('Income Report') }}</h5>
                        </div>

                        <form action="{{ route('report.income') }}" method="get">
                            <div class="row gx-2 gy-1 align-items-end">
                                <div class="cust-pro">
                                    {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}
                                    {{ Form::select('property_id', $property, request('property_id'), [
                                        'class' => 'form-control hidesearch',
                                        'id' => 'property_id',
                                    ]) }}
                                </div>

                                <div class="cust-pro">
                                    {{ Form::label('unit_id', __('Unit'), ['class' => 'form-label']) }}
                                    <div class="unit_div">
                                        <select class="form-control hidesearch unit" id="unit_id" name="unit_id">
                                            <option value="">{{ __('Select Unit') }}</option>
                                            @if (!empty($units))
                                                @foreach ($units as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ request('unit_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="cust-pro">
                                    {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                    <input class="form-control" name="start_date" type="date"
                                        value="{{ request('start_date') }}">
                                </div>

                                <div class="cust-pro">
                                    {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                    <input class="form-control" name="end_date" type="date"
                                        value="{{ request('end_date') }}">
                                </div>

                                <div class="col-auto">
                                    <button type="submit" class="btn btn-light-secondary px-3">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </div>

                                <div class="col-auto">
                                    <a href="{{ route('report.income') }}" class="btn btn-light-dark px-3">
                                        <i class="ti ti-refresh"></i>
                                    </a>
                                </div>

                            </div>
                        </form>
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
                                        <td>{{ !empty($invoice->properties) ? $invoice->properties->name : '-' }} </td>
                                        <td>{{ !empty($invoice->units) ? $invoice->units->name : '-' }} </td>
                                        <td>{{ date('F Y', strtotime($invoice->invoice_month)) }} </td>
                                        <td>{{ dateFormat($invoice->end_date) }} </td>
                                        <td>{{ priceFormat($invoice->getInvoiceSubTotalAmount()) }}</td>
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
                                                            data-bs-original-title="{{ __('View') }}"> <i
                                                                data-feather="eye"></i></a>
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
