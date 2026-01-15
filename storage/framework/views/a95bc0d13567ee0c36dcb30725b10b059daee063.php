<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Tenant Edit')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/vendors/dropzone/dropzone.js')); ?>"></script>

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
            if (file == undefined) {
                fd.append('profile', '');
            } else {
                fd.append('profile', file);
            }
            var files = $('#demo-upload').get(0).dropzone.getAcceptedFiles();
            $.each(files, function(key, file) {
                fd.append('tenant_images[' + key + ']', $('#demo-upload')[0].dropzone
                    .getAcceptedFiles()[key]); // attach dropzone image element
            });

            var other_data = $('#tenant_form').serializeArray();
            $.each(other_data, function(key, input) {
                fd.append(input.name, input.value);
            });

            $.ajax({
                url: "<?php echo e(route('tenant.update', $tenant->id)); ?>",
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
                        var url = '<?php echo e(route('tenant.index')); ?>';
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
            var selectedUnitId = $('#edit_unit').val(); // hidden input or pre-filled unit ID
            var url = '<?php echo e(route('tenant.unit', ':id')); ?>';
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
                    <option value=""><?php echo e(__('Select Unit')); ?></option>
                </select>
            `);

                    $.each(data, function(key, value) {
                        let statusDot = value.is_occupied == 1 ? '🔴' : '🟢';
                        let displayName = `${value.name} ${statusDot}`;
                        let selected = key == selectedUnitId ? 'selected' : '';
                        $('.unit').append(
                            `<option value="${key}" ${selected}>${displayName}</option>`);
                    });

                    $(".hidesearch").each(function() {
                        new Choices(this, {
                            searchEnabled: false,
                            removeItemButton: true,
                        });
                    });
                    $('#unit').trigger('change');
                },
            });
        });

        $('#property').trigger('change');
    </script>

    <script>
        function renderUnitField(label, value, id) {
            if (!value || value === 'null') return ''; // Skip if empty/null

            return `<p><strong>${label}:</strong> <span id="${id}">${value}</span></p>`;
        }

        $(document).on('change', '#unit', function() {
            var unit_id = $(this).val();

            if (unit_id) {
                var url = '<?php echo e(route('tenant.unit.details', ':id')); ?>';
                url = url.replace(':id', unit_id);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        let html = '';

                        $('#unit_name').text(data.name);

                        html += renderUnitField(`<?php echo e(__('Status')); ?>`, data.is_occupied == 1 ?
                            '<span class="text-danger">Occupied 🔴</span>' :
                            '<span class="text-success">Vacant 🟢</span>', 'unit_status');
                        html += renderUnitField(`<?php echo e(__('Bedroom')); ?>`, data.bedroom, 'unit_bedroom');
                        html += renderUnitField(`<?php echo e(__('Kitchen')); ?>`, data.kitchen, 'unit_kitchen');
                        html += renderUnitField(`<?php echo e(__('Bath')); ?>`, data.baths, 'unit_baths');
                        html += renderUnitField(`<?php echo e(__('Rent Type')); ?>`, data.rent_type,
                            'unit_rent_type');
                        html += renderUnitField(`<?php echo e(__('Rent')); ?>`, data.rent_formatted,
                            'unit_rent');
                        html += renderUnitField(`<?php echo e(__('Start Date')); ?>`, data.start_date ?? '-',
                            'unit_start_date');
                        html += renderUnitField(`<?php echo e(__('End Date')); ?>`, data.end_date ?? '-',
                            'unit_end_date');
                        html += renderUnitField(`<?php echo e(__('Payment Due Date')); ?>`, data
                            .payment_due_date ?? '-', 'unit_payment_due_date');
                        html += renderUnitField(`<?php echo e(__('Rent Duration')); ?>`, data.rent_duration,
                            'unit_rent_duration');
                        html += renderUnitField(`<?php echo e(__('Deposit Type')); ?>`, data.deposit_type,
                            'unit_deposit_type');
                        html += renderUnitField(`<?php echo e(__('Deposit Amount')); ?>`, data
                            .deposit_amount_formatted, 'unit_deposit_amount');
                        html += renderUnitField(`<?php echo e(__('Late Fee Type')); ?>`, data.late_fee_type,
                            'unit_late_fee_type');
                        html += renderUnitField(`<?php echo e(__('Late Fee Amount')); ?>`, data
                            .late_fee_amount_formatted, 'unit_late_fee_amount');
                        html += renderUnitField(`<?php echo e(__('Incident Receipt Amount')); ?>`, data
                            .incident_receipt_amount_formatted, 'unit_incident_receipt_amount');
                        html += renderUnitField(`<?php echo e(__('Notes')); ?>`, data.notes ?? 'N/A',
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <a href="<?php echo e(route('tenant.index')); ?>"> <?php echo e(__('Tenant')); ?></a></li>
    <li class="breadcrumb-item active">
        <a href="#"><?php echo e(__('Edit')); ?></a>
    </li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">

        <?php echo e(Form::model($tenant, ['route' => ['tenant.update', $tenant->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'tenant_form'])); ?>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Personal Details')); ?></h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('first_name', __('First Name'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('first_name', $user->first_name, ['class' => 'form-control', 'placeholder' => __('Enter First Name')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('last_name', __('Last Name'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('last_name', $user->last_name, ['class' => 'form-control', 'placeholder' => __('Enter Last Name')])); ?>

                            </div>
                            <div class="form-group ">
                                <?php echo e(Form::label('email', __('Email'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('email', $user->email, ['class' => 'form-control', 'placeholder' => __('Enter Email')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('phone_number', __('Phone Number'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('phone_number', $user->phone_number, ['class' => 'form-control', 'placeholder' => __('Enter Phone Number')])); ?>

                                <small class="form-text text-muted">
                                    <?php echo e(__('Please enter the number with country code. e.g., +91XXXXXXXXXX')); ?>

                                </small>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('family_member', __('Total Family Member'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::number('family_member', null, ['class' => 'form-control', 'placeholder' => __('Enter Total Family Member')])); ?>

                            </div>
                            <div class="form-group">
                                <?php echo e(Form::label('profile', __('Profile'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::file('profile', ['class' => 'form-control'])); ?>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Address Details')); ?></h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('country', __('Country'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('state', __('State'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter State')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('city', __('City'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter City')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('zip_code', __('Zip Code'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code')])); ?>

                            </div>
                            <div class="form-group ">
                                <?php echo e(Form::label('address', __('Address'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::textarea('address', null, ['class' => 'form-control', 'rows' => 5, 'placeholder' => __('Enter Address')])); ?>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Property Details')); ?></h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('property', __('Property'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::select('property', $property, null, ['class' => 'form-control basic-select', 'id' => 'property'])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('unit', __('Unit'), ['class' => 'form-label'])); ?>

                                <input type="hidden" id="edit_unit" value="<?php echo e($tenant->unit); ?>">
                                <div class="unit_div">
                                    <select class="form-control hidesearch unit" id="unit" name="unit">
                                        <option value=""><?php echo e(__('Select Unit')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('lease_start_date', __('Start Date'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::date('lease_start_date', null, ['class' => 'form-control', 'placeholder' => __('Enter lease start date')])); ?>

                            </div>
                            <div class="form-group col-lg-6 col-md-6">
                                <?php echo e(Form::label('lease_end_date', __('End Date'), ['class' => 'form-label'])); ?>

                                <?php echo e(Form::date('lease_end_date', null, ['class' => 'form-control', 'placeholder' => __('Enter lease end date')])); ?>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Documents')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="dropzone needsclick" id='demo-upload' action="#">
                            <div class="dz-message needsclick">
                                <div class="upload-icon"><i class="fa fa-cloud-upload"></i></div>
                                <h3><?php echo e(__('Drop files here or click to upload.')); ?></h3>
                            </div>
                        </div>
                        <div class="preview-dropzon" style="display: none;">
                            <div class="dz-preview dz-file-preview">
                                <div class="dz-image"><img data-dz-thumbnail="" src="" alt=""></div>
                                <div class="dz-details">
                                    <div class="dz-size"><span data-dz-size=""></span></div>
                                    <div class="dz-filename"><span data-dz-name=""></span></div>
                                </div>
                                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""> </span>
                                </div>
                                <div class="dz-success-mark"><i class="fa fa-check" aria-hidden="true"></i></div>
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
                    <?php echo e(Form::submit(__('Update'), ['class' => 'btn btn-secondary btn-rounded', 'id' => 'tenant-submit'])); ?>

                </div>
            </div>
        </div>
        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/tenant/edit.blade.php ENDPATH**/ ?>