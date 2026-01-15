<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-sm-6 col-xl-3">
            <div class="listing-style1">
                <div class="list-thumb">

                    <?php if(!empty($property->thumbnail) && !empty($property->thumbnail->image)): ?>
                        <?php $thumbnail= $property->thumbnail->image; ?>
                    <?php else: ?>
                        <?php $thumbnail= 'default.jpg'; ?>
                    <?php endif; ?>


                    <a href="<?php echo e(route('property.detail', ['code' => $user->code, \Crypt::encrypt($property->id)])); ?>">
                        <img class="location-img"
                            src="<?php echo e(asset(Storage::url('upload/property/thumbnail/' . $thumbnail))); ?>" alt="image">
                    </a>
                </div>


                <div class="list-content">
                    <p class="list-text body-color fz16 mb-1"><span class="badge bg-light-secondary">
                            <?php echo e(\App\Models\Property::$Type[$property->type]); ?></span></p>
                    <h5 class="list-title"><a
                            href="<?php echo e(route('property.detail', ['code' => $user->code, \Crypt::encrypt($property->id)])); ?>"><?php echo e(ucfirst($property->name)); ?></a>
                    </h5>
                    <p class="mb-0 body-color">
                        <span class="fz12 ms-1">
                            <?php echo e(\Illuminate\Support\Str::limit(strip_tags($property->description), 50, '...')); ?>

                        </span>
                    </p>
                    <hr class="my-2">
                    <div
                        class="list-meta d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mt-3 gap-3">

                        <div class="w-100 w-md-50">
                            <p class="fz14 mb-0">
                                <i class="fas fa-map-marker-alt me-1"></i> <?php echo e($property->address); ?>

                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <p class="text-center"><?php echo e($noPropertiesMessage); ?></p>
        </div>
    <?php endif; ?>
</div>

<div class="row">
    <div class="mbp_pagination text-center">
        <?php if($properties->hasPages()): ?>
            <ul class="page_navigation">
                <?php if($properties->onFirstPage()): ?>
                    <li class="page-item disabled"><span class="page-link"><span
                                class="fas fa-angle-left"></span></span></li>
                <?php else: ?>
                    <li class="page-item"><a class="page-link" href="<?php echo e($properties->previousPageUrl()); ?>"><span
                                class="fas fa-angle-left"></span></a></li>
                <?php endif; ?>

                <?php $__currentLoopData = $properties->links()->elements[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(is_string($page)): ?>
                        <li class="page-item disabled"><span class="page-link"><?php echo e($page); ?></span></li>
                    <?php else: ?>
                        <li class="page-item <?php echo e($page == $properties->currentPage() ? 'active' : ''); ?>">
                            <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($properties->hasMorePages()): ?>
                    <li class="page-item"><a class="page-link" href="<?php echo e($properties->nextPageUrl()); ?>"><span
                                class="fas fa-angle-right"></span></a></li>
                <?php else: ?>
                    <li class="page-item disabled"><span class="page-link"><span
                                class="fas fa-angle-right"></span></span></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>

        <p class="mt10 mb-0 pagination_page_count text-center">
            <?php echo e(($properties->currentPage() - 1) * $properties->perPage() + 1); ?> –
            <?php echo e(min($properties->currentPage() * $properties->perPage(), $properties->total())); ?>

            of <?php echo e($properties->total()); ?> property available
        </p>
    </div>
</div>
<?php /**PATH /home/paulawit/malipos.co.ke/resources/views/theme/propertybox.blade.php ENDPATH**/ ?>