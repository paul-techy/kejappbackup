@extends('theme.main')

@section('content')
    <section class="our-login contact-background">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 m-auto wow fadeInUp" data-wow-delay="300ms">
                    <div class="main-title text-center">
                        <h2 class="title">{{ __('Get in Touch') }}</h2>
                        <p class="paragraph">
                            {{ __('We’re here to help—reach out to us anytime with your questions or feedback.') }}</p>
                    </div>
                </div>
            </div>

            <div class="row wow fadeInRight" data-wow-delay="300ms">
                <div class="col-xl-6 mx-auto">
                    {{ Form::open(['route' => ['contact-us', 'code' => $user->code], 'method' => 'post']) }}

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                    @endif

                    <div class="log-reg-form search-modal form-style1 bgc-white p50 p30-sm default-box-shadow1 bdrs12">
                        <div class="mb20">
                            <div class="form-group  col-md-12">
                                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter contact name'), 'required' => 'required']) }}
                            </div>
                        </div>
                        <div class="mb20">
                            <div class="form-group  col-md-12">
                                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                                {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter contact email'), 'required' => 'required']) }}
                            </div>
                        </div>
                        <div class="mb20">
                            <div class="form-group  col-md-12">
                                {{ Form::label('contact_number', __('Contact Number'), ['class' => 'form-label']) }}
                                {{ Form::number('contact_number', null, ['class' => 'form-control', 'placeholder' => __('Enter contact number')]) }}
                            </div>
                        </div>
                        <div class="mb20">
                            <div class="form-group  col-md-12">
                                {{ Form::label('subject', __('Subject'), ['class' => 'form-label']) }}
                                {{ Form::text('subject', null, ['class' => 'form-control', 'placeholder' => __('Enter contact subject'), 'required' => 'required']) }}
                            </div>
                        </div>

                        <div class="mb15">
                            <div class="form-group col-md-12">
                                {{ Form::label('message', __('Message'), ['class' => 'form-label']) }}
                                {{ Form::textarea('message', null, [
                                    'class' => 'form-control',
                                    'rows' => 5,
                                    'required' => 'required',
                                    'placeholder' => __('Enter message'),
                                    'style' => 'height:auto; min-height:100px;',
                                ]) }}
                            </div>
                        </div>

                        <div class="d-grid mb20">
                            {{ Form::submit(__('Send Messages'), ['class' => 'ud-btn btn-thm']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@endsection
