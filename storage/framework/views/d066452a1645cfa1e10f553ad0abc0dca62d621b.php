<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Property Amenity')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#"><?php echo e(__('Property Amenity')); ?></a>
        </li>
    </ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5><?php echo e(__('Property Amenity List')); ?></h5>
                        </div>
                        <?php if(Gate::check('create amenity')): ?>
                            <div class="col-auto">
                                <a class="btn btn-secondary customModal" href="#" data-size="md"
                                    data-url="<?php echo e(route('amenity.create')); ?>" data-title="<?php echo e(__('Create Amenity')); ?>"> <i
                                        class="ti ti-circle-plus align-text-bottom"></i> <?php echo e(__('Create Amenity')); ?></a>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-hover advance-datatable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Image')); ?></th>
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('Description')); ?></th>
                                    <?php if(Gate::check('edit amenity') || Gate::check('delete amenity')): ?>
                                        <th class="text-right"><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr role="row">
                                        <td class="table-user">
                                            <img src="<?php echo e(fetch_file($amenity->image, 'upload/amenity/')); ?>"
                                                alt="" class="mr-2 amenity-img">

                                        </td>
                                        <td>
                                            <?php echo e(ucfirst($amenity->name)); ?>

                                        </td>
                                        <td>
                                             <?php echo e(substr($amenity->description, 0, 200)); ?><?php echo e(!empty($amenity->description) ? '...' : ''); ?>

                                        </td>
                                        <?php if(Gate::check('edit amenity') || Gate::check('delete amenity')): ?>
                                            <td class="text-right">
                                                <div class="cart-action">
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['amenity.destroy', $amenity->id]]); ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit amenity')): ?>
                                                        <a class="avtar avtar-xs btn-link-secondary text-secondary customModal"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="<?php echo e(__('Edit')); ?>" href="#"
                                                            data-url="<?php echo e(route('amenity.edit', $amenity->id)); ?>"
                                                            data-title="<?php echo e(__('Edit Amenity')); ?>"> <i
                                                                data-feather="edit"></i></a>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete amenity')): ?>
                                                        <a class="avtar avtar-xs btn-link-danger text-danger confirm_dialog"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="<?php echo e(__('Detete')); ?>" href="#"> <i
                                                                data-feather="trash-2"></i></a>
                                                    <?php endif; ?>
                                                    <?php echo Form::close(); ?>

                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/amenity/index.blade.php ENDPATH**/ ?>