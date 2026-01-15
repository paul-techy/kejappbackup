@extends('layouts.app')

@section('page-title')
    {{ __('Agreement') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Agreement') }}</li>
@endsection

@push('script-page')
    <script src="{{ asset('assets/js/plugins/ckeditor/classic/ckeditor.js') }}"></script>
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5>{{ __('Agreement List') }}</h5>
                        </div>
                        @if (Gate::check('create agreement'))
                            <div class="col-auto">
                                <a href="#" class="btn btn-secondary customModal" data-size="lg"
                                    data-url="{{ route('agreement.create') }}" data-title="{{ __('Create Agreement') }}"> <i
                                        class="ti ti-circle-plus align-text-bottom"></i> {{ __('Create Agreement') }}</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-hover advance-datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Agreement') }}</th>
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Unit') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Attachment') }}</th>
                                    @if (Gate::check('edit agreement') || Gate::check('delete agreement') || Gate::check('show agreement'))
                                        <th class="text-right">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agreements as $agreement)
                                    <tr>
                                        <td>{{ agreementPrefix() . $agreement->agreement_id }} </td>
                                        <td>{{ !empty($agreement->properties) ? $agreement->properties->name : '-' }} </td>
                                        <td>{{ !empty($agreement->units) ? $agreement->units->name : '-' }} </td>
                                        <td>{{ dateFormat($agreement->date) }} </td>
                                        <td>
                                            @if ($agreement->status == 'Draft')
                                                <span
                                                    class="badge bg-light-dark">{{ \App\Models\agreement::$status[$agreement->status] }}</span>
                                            @elseif($agreement->status == 'Pending')
                                                <span
                                                    class="badge bg-light-warning">{{ \App\Models\agreement::$status[$agreement->status] }}</span>
                                            @elseif($agreement->status == 'Completed')
                                                <span
                                                    class="badge bg-light-success">{{ \App\Models\agreement::$status[$agreement->status] }}</span>
                                            @elseif($agreement->status == 'Active')
                                                <span
                                                    class="badge bg-light-info">{{ \App\Models\agreement::$status[$agreement->status] }}</span>
                                            @elseif($agreement->status == 'Cancelled')
                                                <span
                                                    class="badge bg-light-danger">{{ \App\Models\agreement::$status[$agreement->status] }}</span>
                                            @elseif($agreement->status == 'Confirmed')
                                                <span
                                                    class="badge bg-light-secondary">{{ \App\Models\agreement::$status[$agreement->status] }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($agreement->attachment))
                                                <a href="{{ !empty($agreement->attachment) ? fetch_file($agreement->attachment, 'upload/attachment/') : '#' }}"
                                                    download="download"><i class="ti ti-download"></i></a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if (Gate::check('edit agreement') || Gate::check('delete agreement') || Gate::check('show agreement'))
                                            <td>
                                                <div class="cart-action">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['agreement.destroy', $agreement->id]]) !!}

                                                    @can('show agreement')
                                                        <a class="avtar avtar-xs btn-link-warning text-warning"
                                                            href="{{ route('agreement.show', \Crypt::encrypt($agreement->id)) }}"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('View') }}"> <i
                                                                data-feather="eye"></i></a>
                                                    @endcan
                                                    @can('edit agreement')
                                                        <a class="avtar avtar-xs btn-link-secondary text-secondary customModal"
                                                            data-size="lg" data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Edit') }}" href="#"
                                                            data-url="{{ route('agreement.edit', $agreement->id) }}"
                                                            data-title="{{ __('Edit Agreement') }}"> <i
                                                                data-feather="edit"></i></a>
                                                    @endcan
                                                    @can('delete agreement')
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
