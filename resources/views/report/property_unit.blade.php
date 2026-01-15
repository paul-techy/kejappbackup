@extends('layouts.app')

@section('page-title')
    {{ __('Property Unit Report') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Property Unit Report') }}</li>
@endsection

@push('script-page')
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
                            <h5 class="mb-0">{{ __('Property Unit Report') }}</h5>
                        </div>

                        <form action="{{ route('report.property_unit') }}" method="get">
                            <div class="row gx-2 gy-1 align-items-end">

                                <div class="cust-pro">
                                    {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}
                                    {{ Form::select('property_id', $property, request('property_id'), [
                                        'class' => 'form-control',
                                        'id' => 'property_id',
                                    ]) }}
                                </div>
                              
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-light-secondary px-3">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </div>

                                <div class="col-auto">
                                    <a href="{{ route('report.property_unit') }}" class="btn btn-light-dark px-3">
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
                                    <th>{{ __('Vacant Unit') }}</th>
                                    <th>{{ __('Occupied Unit') }}</th>
                                    <th>{{ __('Total Unit') }}</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($proUnits as $unitGroup)
            <tr>
                <td>{{ $unitGroup->property->name ?? '-' }}</td>
                <td>{{ $unitGroup->vacant }}</td>
                <td>{{ $unitGroup->occupied }}</td>
                <td>{{ $unitGroup->total }}</td>
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
