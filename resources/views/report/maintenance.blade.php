@extends('layouts.app')

@section('page-title')
    {{ __('Maintenance Report') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Maintenance Report') }}</li>
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
                            <h5 class="mb-0">{{ __('Maintenance Report') }}</h5>
                        </div>

                        <form action="{{ route('report.maintenance') }}" method="get">
                            <div class="row gx-2 gy-1 align-items-end">

                                <div class="cust-pro">
                                    {{ Form::label('tenant_id', __('Tenant'), ['class' => 'form-label']) }}
                                    {{ Form::select('tenant_id', $tenant_options, request('tenant_id'), [
                                        'class' => 'form-control hidesearch',
                                        'id' => 'tenant_id',
                                    ]) }}
                                </div>
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
                                    {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                                    {{ Form::select('status', $status, request('status'), [
                                        'class' => 'form-control',
                                    ]) }}
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-light-secondary px-3">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </div>

                                <div class="col-auto">
                                    <a href="{{ route('report.maintenance') }}" class="btn btn-light-dark px-3">
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
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Tenant Name') }}</th>
                                    <th>{{ __('Maintainer Name') }}</th>
                                    <th>{{ __('Issue') }}</th>
                                    <th>{{ __('Status') }}</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($maintenances as $maintenance)
                                    <tr>
                                        <td>{{ $maintenance->properties->name ?? '-' }}</td>
                                        <td>{{ $maintenance->units->name ?? '-' }}</td>
                                        <td>{{ $maintenance->tenetData()?->user?->first_name . ' ' . $maintenance->tenetData()?->user?->last_name ?? '-' }}
                                        </td>
                                        <td>{{ $maintenance->maintainers->first_name ?? '-' }}</td>
                                        <td>{{ $maintenance->types->title ?? '-' }}</td>

                                        <td>

                                            @if ($maintenance->status== 'in_progress')
                                                <span class="badge bg-light-info">
                                                   {{ __('In Progress') }}</span>
                                            @elseif ($maintenance->status== 'completed')
                                                <span class="badge bg-light-success">
                                                   {{ ucfirst($maintenance->status) }}</span>
                                            @elseif ($maintenance->status== 'pending')
                                                <span class="badge bg-light-warning">
                                                   {{ ucfirst($maintenance->status) }}</span>
                                            @else
                                                <span>{{ __('No Invoice Found') }}</span>
                                            @endif

                                        </td>
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
