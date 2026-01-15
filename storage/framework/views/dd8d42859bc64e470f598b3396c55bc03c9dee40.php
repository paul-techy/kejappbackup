<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Edit')); ?>

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
        $('#property-update').on('click', function() {
            "use strict";
            $('#property-update').attr('disabled', true);
            var fd = new FormData();
            var file = document.getElementById('thumbnail').files[0];

            var files = $('#demo-upload').get(0).dropzone.getAcceptedFiles();
            $.each(files, function(key, file) {
                fd.append('property_images[' + key + ']', $('#demo-upload')[0].dropzone
                    .getAcceptedFiles()[key]); // attach dropzone image element
            });
            if (file == undefined) {
                fd.append('thumbnail', '');
            } else {
                fd.append('thumbnail', file);
            }

            var other_data = $('#property_form').serializeArray();
            $.each(other_data, function(key, input) {
                fd.append(input.name, input.value);
            });
            fd.append('description', $('.ck-content').html());
            $.ajax({
                url: "<?php echo e(route('property.update', $property->id)); ?>",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data) {
                    if (data.status == "success") {
                        $('#property-update').attr('disabled', true);
                        toastrs('success', data.msg, 'success');
                        var url = '<?php echo e(route('property.show', ':id')); ?>';
                        url = url.replace(':id', data.id);
                        setTimeout(() => {
                            window.location.href = url;
                        }, "1000");

                    } else {
                        toastrs('Error', data.msg, 'error');
                        $('#property-update').attr('disabled', false);
                    }
                },
                error: function(data) {
                    $('#property-update').attr('disabled', false);
                    if (data.error) {
                        toastrs('Error', data.error, 'error');
                    } else {
                        toastrs('Error', data, 'error');
                    }
                },
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('property.index')); ?>"><?php echo e(__('Property')); ?></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Edit')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">

            <?php echo e(Form::model($property, ['route' => ['property.update', $property->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'property_form'])); ?>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="info-group">
                                <div class="form-group ">
                                    <?php echo e(Form::label('type', __('Type'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::select('type', $types, null, ['class' => 'form-control hidesearch'])); ?>

                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Property Name')])); ?>

                                </div>
                                <div class="form-group ">
                                    <?php echo e(Form::label('description', __('Description'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::textarea('description', null, ['class' => 'form-control', 'id' => 'classic-editor','rows' => 8, 'placeholder' => __('Enter Property Description')])); ?>

                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('thumbnail', __('Thumbnail Image'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::file('thumbnail', ['class' => 'form-control'])); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="info-group">
                                <div class="form-group">
                                    <?php echo e(Form::label('country', __('Country'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Enter Property Country')])); ?>

                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('state', __('State'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Enter Property State')])); ?>

                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('city', __('City'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('Enter Property City')])); ?>

                                </div>
                                <div class="form-group">
                                    <?php echo e(Form::label('zip_code', __('Zip Code'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('Enter Property Zip Code')])); ?>

                                </div>
                                <div class="form-group ">
                                    <?php echo e(Form::label('address', __('Address'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Property Address')])); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <?php echo e(Form::label('demo-upload', __('Property Images'), ['class' => 'form-label'])); ?>

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
                    <div class="card">
                        <div class="card-header">
                            <?php echo e(Form::label('amenities[]', __('Amenities'), ['class' => 'form-label'])); ?>

                        </div>
                        <div class="card-body">
                            <div class="row">

                                <?php $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 col-xl-4 mb-3">
                                        <div class="border rounded shadow-sm p-3 h-100 d-flex align-items-start gap-3">
                                            <input type="checkbox" name="amenities[]" value="<?php echo e($amenity->id); ?>"
                                                id="amenity_<?php echo e($amenity->id); ?>" class="form-check-input mt-1"
                                                <?php echo e(in_array($amenity->id, $selectedAmenities) ? 'checked' : ''); ?>>

                                            <?php if($amenity->image): ?>
                                                <img src="<?php echo e(fetch_file('upload/amenity/' . $amenity->image)); ?>"
                                                    alt="<?php echo e($amenity->name); ?>"
                                                    style="width: 50px; height: 50px; object-fit: cover;"
                                                    class="rounded shadow-sm">
                                            <?php else: ?>
                                                <i class="ti ti-building text-muted fs-3 mt-1"></i>
                                            <?php endif; ?>
                                            <label for="amenity_<?php echo e($amenity->id); ?>" class="form-check-label">
                                                <strong><?php echo e($amenity->name); ?></strong><br>
                                                <small class="text-muted"><?php echo e($amenity->description); ?></small>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <?php echo e(Form::label('advantages[]', __('Advantages'), ['class' => 'form-label'])); ?>

                        </div>
                        <div class="card-body">
                            <div class="row">

                                <?php $__currentLoopData = $advantages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advantage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 col-xl-4 mb-3">
                                        <div class="border rounded shadow-sm p-3 h-100 d-flex align-items-start gap-3">
                                            <input type="checkbox" name="advantages[]" value="<?php echo e($advantage->id); ?>"
                                                id="advantage_<?php echo e($advantage->id); ?>" class="form-check-input mt-1"
                                                <?php echo e(in_array($advantage->id, $selectedAdvantages) ? 'checked' : ''); ?>>


                                            <label for="advantage_<?php echo e($advantage->id); ?>" class="form-check-label">
                                                <strong><?php echo e($advantage->name); ?></strong><br>
                                                <small class="text-muted"><?php echo e($advantage->description); ?></small>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mb-4">
                    <div class="group-button text-end">
                        <?php echo e(Form::submit(__('Update'), ['class' => 'btn btn-secondary btn-rounded', 'id' => 'property-update'])); ?>

                    </div>
                </div>
            </div>

            <?php echo e(Form::close()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/property/edit.blade.php ENDPATH**/ ?>