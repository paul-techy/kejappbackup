<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Agreement')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Agreement')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/plugins/ckeditor/classic/ckeditor.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5><?php echo e(__('Agreement List')); ?></h5>
                        </div>
                        <?php if(Gate::check('create agreement')): ?>
                            <div class="col-auto">
                                <a href="#" class="btn btn-secondary customModal" data-size="lg"
                                    data-url="<?php echo e(route('agreement.create')); ?>" data-title="<?php echo e(__('Create Agreement')); ?>"> <i
                                        class="ti ti-circle-plus align-text-bottom"></i> <?php echo e(__('Create Agreement')); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-hover advance-datatable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Agreement')); ?></th>
                                    <th><?php echo e(__('Property')); ?></th>
                                    <th><?php echo e(__('Unit')); ?></th>
                                    <th><?php echo e(__('Date')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Attachment')); ?></th>
                                    <?php if(Gate::check('edit agreement') || Gate::check('delete agreement') || Gate::check('show agreement')): ?>
                                        <th class="text-right"><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $agreements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agreement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(agreementPrefix() . $agreement->agreement_id); ?> </td>
                                        <td><?php echo e(!empty($agreement->properties) ? $agreement->properties->name : '-'); ?> </td>
                                        <td><?php echo e(!empty($agreement->units) ? $agreement->units->name : '-'); ?> </td>
                                        <td><?php echo e(dateFormat($agreement->date)); ?> </td>
                                        <td>
                                            <?php if($agreement->status == 'Draft'): ?>
                                                <span
                                                    class="badge bg-light-dark"><?php echo e(\App\Models\agreement::$status[$agreement->status]); ?></span>
                                            <?php elseif($agreement->status == 'Pending'): ?>
                                                <span
                                                    class="badge bg-light-warning"><?php echo e(\App\Models\agreement::$status[$agreement->status]); ?></span>
                                            <?php elseif($agreement->status == 'Completed'): ?>
                                                <span
                                                    class="badge bg-light-success"><?php echo e(\App\Models\agreement::$status[$agreement->status]); ?></span>
                                            <?php elseif($agreement->status == 'Active'): ?>
                                                <span
                                                    class="badge bg-light-info"><?php echo e(\App\Models\agreement::$status[$agreement->status]); ?></span>
                                            <?php elseif($agreement->status == 'Cancelled'): ?>
                                                <span
                                                    class="badge bg-light-danger"><?php echo e(\App\Models\agreement::$status[$agreement->status]); ?></span>
                                            <?php elseif($agreement->status == 'Confirmed'): ?>
                                                <span
                                                    class="badge bg-light-secondary"><?php echo e(\App\Models\agreement::$status[$agreement->status]); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if(!empty($agreement->attachment)): ?>
                                                <a href="<?php echo e(!empty($agreement->attachment) ? fetch_file($agreement->attachment, 'upload/attachment/') : '#'); ?>"
                                                    download="download"><i class="ti ti-download"></i></a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <?php if(Gate::check('edit agreement') || Gate::check('delete agreement') || Gate::check('show agreement')): ?>
                                            <td>
                                                <div class="cart-action">
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['agreement.destroy', $agreement->id]]); ?>


                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show agreement')): ?>
                                                        <a class="avtar avtar-xs btn-link-warning text-warning"
                                                            href="<?php echo e(route('agreement.show', \Crypt::encrypt($agreement->id))); ?>"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="<?php echo e(__('View')); ?>"> <i
                                                                data-feather="eye"></i></a>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit agreement')): ?>
                                                        <a class="avtar avtar-xs btn-link-secondary text-secondary customModal"
                                                            data-size="lg" data-bs-toggle="tooltip"
                                                            data-bs-original-title="<?php echo e(__('Edit')); ?>" href="#"
                                                            data-url="<?php echo e(route('agreement.edit', $agreement->id)); ?>"
                                                            data-title="<?php echo e(__('Edit Agreement')); ?>"> <i
                                                                data-feather="edit"></i></a>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete agreement')): ?>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/agreement/index.blade.php ENDPATH**/ ?>