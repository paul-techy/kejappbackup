@extends('layouts.app')
@section('page-title')
    {{ __('Tenant Create') }}
@endsection
@push('script-page')
    <script src="{{ asset('assets/js/vendors/dropzone/dropzone.js') }}"></script>
    <script>
        var dropzone = new Dropzone('#demo-upload', {
            previewTemplate: document.querySelector('.preview-dropzon').innerHTML,
            parallelUploads: 10,
            thumbnailHeight: 120,
            thumbnailWidth: 120,
            maxFilesize: 10,
            filesizeBase: 1000,
            autoProcessQueue: false,
            thumbnail: function(file, dataUrl) {
                if (file.previewElement) {
                    file.previewElement.classList.remove("dz-file-preview");
                    var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
                    for (var i = 0; i < images.length; i++) {
                        var thumbnailElement = images[i];
                        thumbnailElement.alt = file.name;
                        thumbnailElement.src = dataUrl;
                    }
                    setTimeout(function() {
                        file.previewElement.classList.add("dz-image-preview");
                    }, 1);
                }
            }

        });

        $('#tenant-submit').on('click', function() {
            "use strict";
            $('#tenant-submit').attr('disabled', true);
            var fd = new FormData();
            var file = document.getElementById('profile').files[0];


            var files = $('#demo-upload').get(0).dropzone.getAcceptedFiles();
            $.each(files, function(key, file) {
                fd.append('tenant_images[' + key + ']', $('#demo-upload')[0].dropzone
                    .getAcceptedFiles()[key]); // attach dropzone image element
            });
            fd.append('profile', file);
            var other_data = $('#tenant_form').serializeArray();
            $.each(other_data, function(key, input) {
                fd.append(input.name, input.value);
            });
            $.ajax({
                url: "{{ route('tenant.store') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data) {
                    if (data.status == "success") {
                        $('#tenant-submit').attr('disabled', true);
                        toastrs(data.status, data.msg, data.status);
                        var url = '{{ route('tenant.index') }}';
                        setTimeout(() => {
                            window.location.href = url;
                        }, "1000");

                    } else {
                        toastrs('Error', data.msg, 'error');
                        $('#tenant-submit').attr('disabled', false);
                    }
                },
                error: function(data) {
                    $('#tenant-submit').attr('disabled', false);
                    if (data.error) {
                        toastrs('Error', data.error, 'error');
                    } else {
                        toastrs('Error', data, 'error');
                    }
                },
            });
        });

        $('#property').on('change', function() {
            "use strict";
            var property_id = $(this).val();
            var url = '{{ route('tenant.unit', ':id') }}';
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
                    $('.unit_div').html(`
                    <select class="form-control hidesearch unit" id="unit" name="unit">
                        <option value="">{{ __('Select Unit') }}</option>
                    </select>
                `);

                    $.each(data, function(key, value) {
                        let statusDot = value.is_occupied == 1 ? '🔴' : '🟢';
                        let displayName = ` ${value.name} ${statusDot}`;
                        $('.unit').append(`<option value="${key}">${displayName}</option>`);
                    });

                    $(".hidesearch").each(function() {
                        new Choices(this, {
                            searchEnabled: false,
                            removeItemButton: true,
                        });
                    });
                },

            });
        });
    </script>

    <script>
        function renderUnitField(label, value, id) {
            if (!value || value === 'null') return ''; // Skip if empty/null

            return `<p><strong>${label}:</strong> <span id="${id}">${value}</span></p>`;
        }

        $(document).on('change', '#unit', function() {
            var unit_id = $(this).val();

            if (unit_id) {
                var url = '{{ route('tenant.unit.details', ':id') }}';
                url = url.replace(':id', unit_id);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        let html = '';

                        $('#unit_name').text(data.name);

                        html += renderUnitField(`{{ __('Status') }}`, data.is_occupied == 1 ?
                            '<span class="text-danger">Occupied 🔴</span>' :
                            '<span class="text-success">Vacant 🟢</span>', 'unit_status');
                        html += renderUnitField(`{{ __('Bedroom') }}`, data.bedroom, 'unit_bedroom');
                        html += renderUnitField(`{{ __('Kitchen') }}`, data.kitchen, 'unit_kitchen');
                        html += renderUnitField(`{{ __('Bath') }}`, data.baths, 'unit_baths');
                        html += renderUnitField(`{{ __('Rent Type') }}`, data.rent_type,
                            'unit_rent_type');
                        html += renderUnitField(`{{ __('Rent') }}`, data.rent_formatted,
                            'unit_rent');
                        html += renderUnitField(`{{ __('Start Date') }}`, data.start_date ?? '-',
                            'unit_start_date');
                        html += renderUnitField(`{{ __('End Date') }}`, data.end_date ?? '-',
                            'unit_end_date');
                        html += renderUnitField(`{{ __('Payment Due Date') }}`, data
                            .payment_due_date ?? '-', 'unit_payment_due_date');
                        html += renderUnitField(`{{ __('Rent Duration') }}`, data.rent_duration,
                            'unit_rent_duration');
                        html += renderUnitField(`{{ __('Deposit Type') }}`, data.deposit_type,
                            'unit_deposit_type');
                        html += renderUnitField(`{{ __('Deposit Amount') }}`, data
                            .deposit_amount_formatted, 'unit_deposit_amount');
                        html += renderUnitField(`{{ __('Late Fee Type') }}`, data.late_fee_type,
                            'unit_late_fee_type');
                        html += renderUnitField(`{{ __('Late Fee Amount') }}`, data
                            .late_fee_amount_formatted, 'unit_late_fee_amount');
                        html += renderUnitField(`{{ __('Incident Receipt Amount') }}`, data
                            .incident_receipt_amount_formatted, 'unit_incident_receipt_amount');
                        html += renderUnitField(`{{ __('Notes') }}`, data.notes ?? 'N/A',
                            'unit_notes');

                        $('#unit-fields').html(html);
                        $('#unit-detail-section').removeClass('d-none');
                    },
                    error: function() {
                        $('#unit-detail-section').addClass('d-none');
                    }
                });
            } else {
                $('#unit-detail-section').addClass('d-none');
            }
        });
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> <a href="{{ route('tenant.index') }}"> {{ __('Tenant') }}</a></li>
    <li class="breadcrumb-item active">
        <a href="#">{{ __('Create') }}</a>
    </li>
@endsection


@section('content')
    <div class="row">

        {{ Form::open(['url' => 'tenant', 'method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'tenant_form']) }}
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Personal Details') }}</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('first_name', __('First Name'), ['class' => 'form-label']) }}
                                {{ Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => __('Enter First Name')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('last_name', __('Last Name'), ['class' => 'form-label']) }}
                                {{ Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Last Name')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                                {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Email')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
                                {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Password')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('phone_number', __('Phone Number'), ['class' => 'form-label']) }}
                                {{ Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => __('Enter Phone Number')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('family_member', __('Total Family Member'), ['class' => 'form-label']) }}
                                {{ Form::number('family_member', null, ['class' => 'form-control', 'placeholder' => __('Enter Total Family Member')]) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('profile', __('Profile'), ['class' => 'form-label']) }}
                                {{ Form::file('profile', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Address Details') }}</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}
                                {{ Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('state', __('State'), ['class' => 'form-label']) }}
                                {{ Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter State')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
                                {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter City')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('zip_code', __('Zip Code'), ['class' => 'form-label']) }}
                                {{ Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code')]) }}
                            </div>
                            <div class="form-group ">
                                {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                                {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 5, 'placeholder' => __('Enter Address')]) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Property Details') }}</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('property', __('Property'), ['class' => 'form-label']) }}
                                {{ Form::select('property', $property, null, ['class' => 'form-control hidesearch', 'id' => 'property']) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('unit', __('Unit'), ['class' => 'form-label']) }}
                                <div class="unit_div">
                                    <select class="form-control hidesearch unit" id="unit" name="unit">
                                        <option value="">{{ __('Select Unit') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('lease_start_date', __('Start Date'), ['class' => 'form-label']) }}
                                {{ Form::date('lease_start_date', null, ['class' => 'form-control', 'placeholder' => __('Enter lease start date')]) }}
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                {{ Form::label('lease_end_date', __('End Date'), ['class' => 'form-label']) }}
                                {{ Form::date('lease_end_date', null, ['class' => 'form-control', 'placeholder' => __('Enter lease end date')]) }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Documents') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="dropzone needsclick" id='demo-upload' action="#">
                            <div class="dz-message needsclick">
                                <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                                <h3>{{ __('Drop files here or click to upload.') }}</h3>
                            </div>
                        </div>
                        <div class="preview-dropzon" style="display: none;">
                            <div class="dz-preview dz-file-preview">
                                <div class="dz-image"><img data-dz-thumbnail="" src="" alt="">
                                </div>
                                <div class="dz-details">
                                    <div class="dz-size"><span data-dz-size=""></span></div>
                                    <div class="dz-filename"><span data-dz-name=""></span></div>
                                </div>
                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="">
                                    </span></div>
                                <div class="dz-success-mark"><i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-12">
                <div id="unit-detail-section" class="card mt-3 d-none">
                    <div class="card-body">
                        <h4 class="card-title" id="unit_name"></h4>
                        <hr>
                        <div id="unit-fields" class="row"></div>
                    </div>
                </div>
            </div>


            <div class="col-lg-12 mb-2">
                <div class="group-button text-end">
                    {{ Form::submit(__('Create'), ['class' => 'btn btn-secondary btn-rounded', 'id' => 'tenant-submit']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@endsection
