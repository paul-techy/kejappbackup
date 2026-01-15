@extends('layouts.app')

@section('page-title')
    {{ __('Profit & Loss Report') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Profit & Loss Report') }}</li>
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

    <script>
        var options = {
            chart: {
                type: 'area',
                height: 450,
                toolbar: {
                    show: false
                }
            },
            colors: ['#2ca58d', '#0a2342'],
            dataLabels: {
                enabled: false
            },
            legend: {
                show: true,
                position: 'top'
            },
            markers: {
                size: 1,
                colors: ['#fff', '#fff', '#fff'],
                strokeColors: ['#2ca58d', '#0a2342'],
                strokeWidth: 1,
                shape: 'circle',
                hover: {
                    size: 4
                }
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    type: 'vertical',
                    inverseColors: false,
                    opacityFrom: 0.5,
                    opacityTo: 0
                }
            },
            grid: {
                show: false
            },
            series: [{
                    name: "{{ __('Total Income') }}",
                    data: {!! json_encode($incomeExpenseByMonth['income']) !!}
                },
                {
                    name: "{{ __('Total Expense') }}",
                    data: {!! json_encode($incomeExpenseByMonth['expense']) !!}
                }
            ],
            xaxis: {
                categories: {!! json_encode($incomeExpenseByMonth['label']) !!},
                tooltip: {
                    enabled: false
                },
                labels: {
                    hideOverlappingLabels: true
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            }
        };
        var chart = new ApexCharts(document.querySelector('#incomeExpense'), options);
        chart.render();
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
                            <h5 class="mb-0">{{ __('Profit & Loss Report') }}</h5>
                        </div>

                        <form action="{{ route('report.profit_loss') }}" method="get">
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
                                    {{ Form::label('year', __('Year'), ['class' => 'form-label']) }}
                                    <select class="form-control" name="year" id="year">
                                        @foreach ($years as $yr)
                                            <option value="{{ $yr }}"
                                                {{ request('year', $year) == $yr ? 'selected' : '' }}>
                                                {{ $yr }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-light-secondary px-3">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </div>

                                <div class="col-auto">
                                    <a href="{{ route('report.profit_loss') }}" class="btn btn-light-dark px-3">
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

                                    <th>{{ __('Month') }}</th>
                                    <th>{{ __('Income') }}</th>
                                    <th>{{ __('Expense') }}</th>
                                    <th>{{ __('Profit & Loss') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @dd($report) --}}
                                @foreach ($report as $data)
                                    <tr>
                                        <td>{{ $data->month }}</td>
                                        <td>{{ priceFormat($data->income) }}</td>
                                        <td>{{ priceFormat($data->expense) }}</td>
                                        @php
                                            $profitClass =
                                                $data->profit < 0
                                                    ? 'text-danger'
                                                    : ($data->profit > 0
                                                        ? 'text-success'
                                                        : 'text-muted');
                                        @endphp

                                        <td class="{{ $profitClass }}">
                                            {{ priceFormat($data->profit) }}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h5 class="mb-1">{{ __('Analysis Report') }}</h5>
                            <p class="text-muted mb-2">{{ __('Income and Expense Overview') }}</p>
                        </div>

                    </div>
                    <div id="incomeExpense"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
