<?php $__env->startPush('css-page'); ?>
    <style>
        .carousel-item {
            height: 400px;
            width: 720px;
            overflow: hidden;
            border-radius: 10px;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <section class="our-blog pt-0">
        <div class="container">
            <div class="row">
            </div>
        </div>
    </section>


    <?php
        $Section_3 = App\Models\Additional::where('section', 'Section 3')->first();
        $Section_3_content_value = !empty($Section_3->content_value)
            ? json_decode($Section_3->content_value, true)
            : [];
    ?>
    <?php if(empty($Section_3_content_value['section_enabled']) || $Section_3_content_value['section_enabled'] == 'active'): ?>
        <section class="breadcumb-section pt-0">
            <div class="cta-service-v6 cta-banner mx-auto maxw1700 pt120 pt60-sm pb120 pb60-sm bdrs16 position-relative d-flex align-items-center"
                style="background-image: url('<?php echo e(asset(Storage::url($Section_3_content_value['sec3_banner_image_path']))); ?>'); background-position: bottom;">
                <div class="container">
                    <div class="row wow fadeInUp">
                        <div class="col-xl-7">
                            <div class="position-relative">
                                <h2 class="text-dark"><?php echo e($Section_3_content_value['sec3_title']); ?></h2>
                                <p class="text mb30 text-dark"><?php echo e($Section_3_content_value['sec3_sub_title']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>


    <sections class="our-blog pt40">
        <div class="container mt-5">
            <div class="wow fadeInUp" data-wow-delay="300ms">
                <div class="row property-page mt-3">
                    <div class="col-sm-12">
                        <div class="card border">
                            <div class="card-body">
                                <div class="row d-flex justify-content-between align-items-center mb-3">
                                    <div class="col">
                                        <h3 class="form-title mb-3"><a href="#" class="text-secondary">
                                                <?php echo e(ucfirst($property->name)); ?></a></h3>
                                    </div>
                                    <div class="col text-end">

                                        <p class="list-text body-color fz16 mb-1"><span class="badge bg-light-secondary">
                                                <?php echo e(\App\Models\Property::$Type[$property->type]); ?></span></p>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class=" product-sticky">
                                            <div id="carouselExampleCaptions" class="carousel slide carousel-fade"
                                                data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php $__currentLoopData = $property->propertyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $img = !empty($image->image)
                                                                ? $image->image
                                                                : 'default.jpg';
                                                        ?>
                                                        <div class="carousel-item <?php echo e($key === 0 ? 'active' : ''); ?>">
                                                            <img src="<?php echo e(asset(Storage::url('upload/property/image/') . $img)); ?>"
                                                                class="d-block w-100 rounded" alt="Package image" />
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                                <ol
                                                    class="carousel-indicators position-relative product-carousel-indicators my-sm-3 mx-0">
                                                    <?php $__currentLoopData = $property->propertyImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $img = !empty($image->image)
                                                                ? $image->image
                                                                : 'default.jpg';
                                                        ?>
                                                        <li data-bs-target="#carouselExampleCaptions"
                                                            data-bs-slide-to="<?php echo e($key); ?>"
                                                            class="<?php echo e($key === 0 ? 'active' : ''); ?> w-25 h-auto">
                                                            <img src="<?php echo e(asset(Storage::url('upload/property/image/') . $img)); ?>"
                                                                class="d-block wid-100 rounded" alt="Package image" />
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="default-box-shadow1 bdrs8 bdr1 p-4 mb-4 bgc-white">
                                            <div class="row align-items-center mb-3">
                                                <div class="col">
                                                    <h4 class="form-title mb-0"><?php echo e(__('Property Detail')); ?></h4>
                                                </div>
                                                <div class="col-auto">

                                                    <?php if(!empty($property->price) && $property->listing_type == 'rent'): ?>
                                                        <?php echo e(__('Rent Price')); ?> :
                                                        <span class="fw-semibold fs-20 text-primary">
                                                            <?php echo e(priceformat($property->price)); ?>/ Monthly
                                                        </span>
                                                    <?php else: ?>
                                                        <?php echo e(__('Sell Price')); ?>:
                                                        <span class="fw-semibold fs-20 text-primary">
                                                            <?php echo e(priceformat($property->price)); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="property-description">
                                                <?php echo $property->description; ?>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="mb-3"><?php echo e(__('Included Amenities')); ?>:</h4>
                                                <hr class="my-3" />
                                                <?php if($selectedAmenities->count()): ?>
                                                    <div class="row">
                                                        <?php $__currentLoopData = $selectedAmenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="col-md-2 col-xl-2 mb-4">
                                                                <div
                                                                    class="border rounded p-2 shadow-sm h-100 position-relative d-flex align-items-center">
                                                                    <i class="ti ti-circle-check text-success fs-5 position-absolute"
                                                                        style="top: 10px; right: 10px;"></i>

                                                                    <?php if($amenity->image): ?>
                                                                        <img src="<?php echo e(fetch_file('upload/amenity/' . $amenity->image)); ?>"
                                                                            alt="<?php echo e($amenity->name); ?>"
                                                                            class="rounded shadow-sm me-2"
                                                                            style="width: 100px; height: 60px; object-fit: cover;">
                                                                    <?php endif; ?>

                                                                    <h6 class="mb-0 text-start"><?php echo e($amenity->name); ?></h6>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php else: ?>
                                                    <p class="text-muted"><?php echo e(__('No amenities selected.')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="mb-3"><?php echo e(__('Advantages')); ?>:</h4>
                                                <hr class="my-3" />
                                                <?php if($selectedAdvantages->count()): ?>
                                                    <div class="row">
                                                        <ul class="list-unstyled">
                                                            <?php $__currentLoopData = $selectedAdvantages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $advantage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li class="mb-2">
                                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                                    <?php echo e($advantage->name); ?>

                                                                </li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ul>
                                                    </div>
                                                <?php else: ?>
                                                    <p class="text-muted"><?php echo e(__('No advantages selected.')); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="mb-3"><?php echo e(__('Address')); ?>:</h4>
                                                <hr class="my-3" />
                                                <div class="row">
                                                    <ul class="list-unstyled">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        <?php echo e($property->address); ?>, <?php echo e($property->city); ?>,
                                                        <?php echo e($property->state); ?>, <?php echo e($property->country); ?> -
                                                        <?php echo e($property->zip_code); ?>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>


                                        <?php if($units->isNotEmpty()): ?>
                                            <div class="row mt-3">
                                                <h3 class="mb-2"><?php echo e(__('Property Unit')); ?>:</h3>
                                                <hr class="my-3" />
                                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-xxl-3 col-xl-4 col-md-6">
                                                        <div class="card follower-card ">
                                                            <div class="card-body p-3">
                                                                <div class="d-flex align-items-start mb-3">
                                                                    <div class="flex-grow-1 ">
                                                                        <h2 class="mb-1 text-truncate">
                                                                            <?php echo e(ucfirst($unit->name)); ?></h2>
                                                                    </div>

                                                                </div>
                                                                <hr class="my-3" />
                                                                <ul class="list-unstyled mb-0">
                                                                    <li class="mb-1">
                                                                        <strong><?php echo e(__('Bedroom')); ?>:</strong>
                                                                        <span
                                                                            class="text-muted"><?php echo e($unit->bedroom); ?></span>
                                                                    </li>
                                                                    <li class="mb-1">
                                                                        <strong><?php echo e(__('Kitchen')); ?>:</strong>
                                                                        <span
                                                                            class="text-muted"><?php echo e($unit->kitchen); ?></span>
                                                                    </li>
                                                                    <li class="mb-1">
                                                                        <strong><?php echo e(__('Bath')); ?>:</strong>
                                                                        <span
                                                                            class="text-muted"><?php echo e($unit->baths); ?></span>
                                                                    </li>

                                                                    <?php if($property->listing_type == 'rent'): ?>
                                                                        <li class="mb-1">
                                                                            <strong><?php echo e(__('Rent Type')); ?>:</strong>
                                                                            <span
                                                                                class="text-muted"><?php echo e($unit->rent_type); ?></span>
                                                                        </li>
                                                                        <li class="mb-1">
                                                                            <strong><?php echo e(__('Rent')); ?>:</strong>
                                                                            <span
                                                                                class="text-muted"><?php echo e(priceFormat($unit->rent)); ?></span>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                </ul>


                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </sections>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('theme.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/theme/detail.blade.php ENDPATH**/ ?>