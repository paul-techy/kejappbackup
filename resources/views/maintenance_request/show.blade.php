@extends('layouts.app')
@section('page-title')
    {{ __('Maintenance Request Details') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    {{-- <li class="breadcrumb-item" aria-current="page"><a href="{{ route('maintenance-request.pending') }}"> {{ __('Maintenance Request') }}</a></li> --}}
    <li class="breadcrumb-item active">
        <a href="#">{{ __('Maintenance Request Details') }}</a>
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
                                                src= "{{ !empty($maintainer->user->profile) ? fetch_file($maintainer->user->profile, 'upload/profile/') : $profile }}"
                                                alt="User image" />
                                        </div>
                                        <div class="flex-grow-1 mx-3">
                                            <h4 class="mb-2">
                                                {{ ucfirst(!empty($maintainer->user) ? $maintainer->user->first_name : '') . ' ' . ucfirst(!empty($maintainer->user) ? $maintainer->user->last_name : '') }}
                                            </h4>
                                            <h5 class="mt-1"><span
                                                    class="badge bg-light-secondary">{{ ucfirst($maintainer->user->type) }}</span>
                                            </h5>
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
                                                    <small>{{ !empty($maintainer->user) ? $maintainer->user->email : '-' }}</small>
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
                                                    <small>{{ !empty($maintainer->user) ? $maintainer->user->phone_number : '-' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </a>


                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-8 col-xxl-9">
                            <div class="card border">
                                <div class="card-header">
                                    <h5>{{ __('Additional Information') }}</h5>
                                </div>
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td><b class="text-header">{{ __('Property') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ !empty($maintenanceRequest->properties) ? $maintenanceRequest->properties->name : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header">{{ __('Unit') }}</b></td>
                                                    <td>:</td>
                                                    <td> {{ !empty($maintenanceRequest->units) ? $maintenanceRequest->units->name : '-' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header">{{ __('Issue') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ !empty($maintenanceRequest->types) ? $maintenanceRequest->types->title : '-' }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><b class="text-header">{{ __('Request Date') }}</b></td>
                                                    <td>:</td>
                                                    <td>{{ dateFormat($maintenanceRequest->request_date) }}</td>
                                                </tr>
                                                @if (!empty($maintenanceRequest->fixed_date))
                                                    <tr>
                                                        <td><b class="text-header">{{ __('Fixed Date') }}</b></td>
                                                        <td>:</td>
                                                        <td>{{ dateFormat($maintenanceRequest->fixed_date) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($maintenanceRequest->amount != 0)
                                                    <tr>
                                                        <td><b class="text-header">{{ __('Amount') }}</b></td>
                                                        <td>:</td>
                                                        <td>{{ priceFormat($maintenanceRequest->amount) }}</td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <td><b class="text-header">{{ __('Status') }}</b></td>
                                                    <td>:</td>
                                                    <td>
                                                        @if ($maintenanceRequest->status == 'pending')
                                                            <span class="badge bg-light-warning">
                                                                {{ \App\Models\MaintenanceRequest::$status[$maintenanceRequest->status] }}</span>
                                                        @elseif($maintenanceRequest->status == 'in_progress')
                                                            <span class="badge bg-light-info">
                                                                {{ \App\Models\MaintenanceRequest::$status[$maintenanceRequest->status] }}</span>
                                                        @else
                                                            <span class="badge bg-light-primary">
                                                                {{ \App\Models\MaintenanceRequest::$status[$maintenanceRequest->status] }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                @if (!empty($maintenanceRequest->invoice))
                                                    <tr>
                                                        <td><b class="text-header">{{ __('Invoice') }}</b></td>
                                                        <td>:</td>
                                                        <td><a href="{{ !empty($maintenanceRequest->invoice) ? fetch_file($maintenanceRequest->invoice, 'upload/invoice/') : '#' }}"
                                                                download="download"><i class="fa fa-download"></i></a></td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <td><b class="text-header">{{ __('Attachment') }}</b></td>
                                                    <td>:</td>
                                                    <td>
                                                        @if (!empty($maintenanceRequest->issue_attachment))
                                                            <a href="{{ !empty($maintenanceRequest->issue_attachment) ? fetch_file($maintenanceRequest->issue_attachment, 'upload/issue_attachment/') : '#' }}"
                                                                download="download"><i class="fa fa-download"></i></a>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td><b class="text-header">{{ __('Notes') }}</b></td>
                                                    <td>:</td>
                                                    <td> {{ $maintenanceRequest->notes }}</td>
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

        <div class="col-sm-12">

            @if ($maintenanceRequest->comments->count() > 0)
                <div class="card border">
                    <div class="card-header">
                        <h5>{{ __('Comments') }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($maintenanceRequest->comments as $comment)
                            @php
                                $user = App\Models\User::find($comment->user_id);
                            @endphp

                            <div class="bg-light rounded p-2 mb-3 position-relative">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <img class="img-radius img-fluid wid-40"
                                            src="{{ !empty($user->profile)
                                                ? asset(Storage::url('upload/profile/' . $user->profile))
                                                : asset(Storage::url('upload/profile/avatar.png')) }}"
                                            alt="profile" />
                                    </div>

                                    <div class="flex-grow-1 mx-3">
                                        <div class="d-flex align-items-center">
                                            <h5 class="mb-0 me-3">{{ $user->name ?? '-' }}</h5>
                                            <span class="text-body text-opacity-50 d-flex align-items-center">
                                                <i class="fas fa-circle f-8 me-2"></i>
                                                {{ dateFormat($comment->created_at) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-header">{{ $comment->comment }}</p>

                                @if (\Auth::user()->id === $comment->user_id || \Auth::user()->type === 'owner')
                                    <div class="position-absolute top-0 end-0 m-2">
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['maintenance-request.comment.destroy', $comment->id],
                                            'id' => 'delete-comment-' . $comment->id,
                                        ]) !!}
                                        <button type="button"
                                            class="btn btn-sm btn-light text-danger border-0 p-1 confirm_dialog"
                                            data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card border">
                <div class="card-header">
                    <h5>{{ __('Add Comment') }}</h5>
                </div>

                <div class="card-body">
                    {{ Form::open(['route' => ['maintenance-request.comment', $maintenanceRequest->id], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                    <div class="d-flex align-items-center mt-3">
                        <div class="flex-shrink-0">
                            <img class="img-radius d-none d-sm-inline-flex me-3 img-fluid wid-35"
                                src="{{ asset(Storage::url('upload/profile/' . $maintainer->user->profile)) }}"
                                alt="{{ $maintainer->user->first_name }}" />
                        </div>
                        <div class="flex-grow-1 me-3">
                            <textarea class="form-control" rows="1" name="comment" placeholder="{{ __('Write a comment...') }}"></textarea>

                        </div>

                        <div class="flex-shrink-0">
                            <button type="submit" class="btn btn-secondary">{{ __('Send') }}</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>


    </div>
@endsection
