<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Property')); ?></li>
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
        $('#property-submit').on('click', function() {
            "use strict";
            $('#property-submit').attr('disabled', true);
            var fd = new FormData();
            var file = document.getElementById('thumbnail').files[0];

            var files = $('#demo-upload').get(0).dropzone.getAcceptedFiles();
            $.each(files, function(key, file) {
                fd.append('property_images[' + key + ']', $('#demo-upload')[0].dropzone
                    .getAcceptedFiles()[key]); // attach dropzone image element
            });
            fd.append('thumbnail', file);
            var other_data = $('#property_form').serializeArray();
            $.each(other_data, function(key, input) {
                fd.append(input.name, input.value);
            });
            $.ajax({
                url: "<?php echo e(route('property.store')); ?>",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data) {
                    if (data.status == "success") {
                        $('#property-submit').attr('disabled', true);
                        toastrs(data.status, data.msg, data.status);
                        var url = '<?php echo e(route('property.show', ':id')); ?>';
                        url = url.replace(':id', data.id);
                        setTimeout(() => {
                            window.location.href = url;
                        }, "1000");

                    } else {
                        toastrs('Error', data.msg, 'error');
                        $('#property-submit').attr('disabled', false);
                    }
                },
                error: function(data) {
                    $('#property-submit').attr('disabled', false);
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


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5><?php echo e(__('Property List')); ?></h5>
                        </div>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create property')): ?>
                            <div class="col-auto">
                                <a class="btn btn-secondary" href="<?php echo e(route('property.create')); ?>" data-size="md"> <i
                                        class="ti ti-circle-plus align-text-bottom "></i>
                                    <?php echo e(__('Create Property')); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if(!empty($property->thumbnail) && !empty($property->thumbnail->image)): ?>
                            <?php $thumbnail= $property->thumbnail->image; ?>
                        <?php else: ?>
                            <?php $thumbnail= 'default.jpg'; ?>
                        <?php endif; ?>
                        <div class="col-sm-6 col-md-4 col-xxl-3">
                            <div class="card product-card">
                                <div class="card-img-top">
                                    <img src="<?php echo e(fetch_file($thumbnail, 'upload/property/thumbnail/')); ?>"
                                        alt="<?php echo e($property->name); ?>" class="img-prod" />

                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a href="<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show property')): ?> <?php echo e(route('property.show', \Crypt::encrypt($property->id))); ?>  <?php endif; ?>"
                                            class="fw-semibold mb-0 text-truncate">
                                            <h4><?php echo e($property->name); ?></h4>
                                        </a>
                                        <?php if(Gate::check('edit property') || Gate::check('delete property') || Gate::check('show property')): ?>
                                            <div class="dropdown">
                                                <a class="dropdown-toggle text-primary opacity-50 arrow-none" href="#"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots f-16"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <?php echo Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['property.destroy', $property->id],
                                                        'id' => 'property-' . $property->id,
                                                    ]); ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit property')): ?>
                                                        <a class="dropdown-item"
                                                            href="<?php echo e(route('property.edit', \Crypt::encrypt($property->id))); ?>">
                                                            <i class="material-icons-two-tone">edit</i>
                                                            <?php echo e(__('Edit Property')); ?>

                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show property')): ?>
                                                        <a class="dropdown-item"
                                                            href="<?php echo e(route('property.show', \Crypt::encrypt($property->id))); ?>">
                                                            <i class="material-icons-two-tone">remove_red_eye</i>
                                                            <?php echo e(__('View property')); ?>

                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete property')): ?>
                                                        <a class="dropdown-item confirm_dialog" href="#">
                                                            <i class="material-icons-two-tone">delete</i>
                                                            <?php echo e(__('Delete Property')); ?>

                                                        </a>
                                                    <?php endif; ?>

                                                    <?php echo Form::close(); ?>

                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex flex-wrap gap-2">
                                            <button type="button" class="btn btn-sm btn-light-info">
                                                <i class="material-icons-two-tone">ad_units</i>
                                                <?php echo e($property->totalUnit()); ?> <?php echo e(__('Unit')); ?>

                                            </button>

                                            <button type="button" class="btn btn-sm btn-light-success">
                                                <i class="material-icons-two-tone">ad_units</i>
                                                <?php echo e($property->vacantUnit()); ?> <?php echo e(__('Vacant')); ?>

                                            </button>

                                            <button type="button" class="btn btn-sm btn-light-danger">
                                                <i class="material-icons-two-tone">ad_units</i>
                                                <?php echo e($property->occupiedUnit()); ?> <?php echo e(__('Occupied ')); ?>

                                            </button>
                                        </div>
                                    </div>


                                    <p class="prod-content my-2 text-muted">
                                        <?php echo e(Str::limit(strip_tags($property->description), 200, '...')); ?>

                                    </p>

                                    <div class="d-flex align-items-center justify-content-between mt-3">

                                        <span class="badge bg-light-secondary" data-bs-toggle="tooltip"
                                            data-bs-original-title="<?php echo e(__('Type')); ?>"><?php echo e(\App\Models\Property::$Type[$property->type]); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/property/index.blade.php ENDPATH**/ ?>