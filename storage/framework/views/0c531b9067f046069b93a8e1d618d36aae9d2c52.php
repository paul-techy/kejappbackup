<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Maintainer')); ?>

<?php $__env->stopSection(); ?>
<?php
    $profile = asset(Storage::url('upload/profile/avatar.png'));
?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Maintainer')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5><?php echo e(__('Maintainer List')); ?></h5>
                        </div>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create maintainer')): ?>
                            <div class="col-auto">
                                <a class="btn btn-secondary customModal" href="#"
                                    data-url="<?php echo e(route('maintainer.create')); ?>" data-title="<?php echo e(__('Create Maintainer')); ?>"
                                    data-size="lg"> <i class="ti ti-circle-plus align-text-bottom "></i>
                                    <?php echo e(__('Create Maintainer')); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $maintainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $maintainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-xxl-3 col-xl-4 col-md-6">
                                <div class="card follower-card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="flex-grow-1 ms-3 mx-2">
                                                
                                                <img class="img-fluid wid-70"
                                                    src= "<?php echo e(!empty($maintainer->user->profile) ? fetch_file($maintainer->user->profile, 'upload/profile/') : $profile); ?>"
                                                    alt="Image">
                                            </div>
                                            <?php if(Gate::check('edit maintainer') || Gate::check('delete maintainer') || Gate::check('show maintainer')): ?>
                                                <div class="flex-shrink-0">
                                                    <div class="dropdown">
                                                        <a class="dropdown-toggle text-primary opacity-50 arrow-none"
                                                            href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-dots f-16"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit maintainer')): ?>
                                                                <a class="dropdown-item customModal" href="#"
                                                                    data-url="<?php echo e(route('maintainer.edit', $maintainer->id)); ?>"
                                                                    data-title="<?php echo e(__('Edit Maintainer')); ?>">
                                                                    <i class="material-icons-two-tone">edit</i>
                                                                    <?php echo e(__('Edit Maintainer')); ?>

                                                                </a>
                                                            <?php endif; ?>
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete maintainer')): ?>
                                                                <?php echo Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['maintainer.destroy', $maintainer->id],
                                                                    'id' => 'maintainer-' . $maintainer->id,
                                                                ]); ?>

                                                                <a class="dropdown-item confirm_dialog" href="#">
                                                                    <i class="material-icons-two-tone">delete</i>
                                                                    <?php echo e(__('Delete Maintainer')); ?>

                                                                </a>
                                                                <?php echo Form::close(); ?>

                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <a class="customModal" href="#"
                                            data-url="<?php echo e(route('maintainer.edit', $maintainer->id)); ?>"
                                            data-title="<?php echo e(__('Edit Maintainer')); ?>">
                                            <h4><?php echo e(ucfirst(!empty($maintainer->user) ? $maintainer->user->first_name : '') . ' ' . ucfirst(!empty($maintainer->user) ? $maintainer->user->last_name : '')); ?>

                                            </h4>

                                        </a>
                                        <h6 class="text-truncate text-muted d-flex align-items-center mb-2">
                                            <?php echo e(!empty($maintainer->user) ? $maintainer->user->email : '-'); ?></h6>

                                        <div class="row">
                                            
                                            <?php if($maintainer->user->phone_number): ?>
                                                <div class="col-sm-6 mb-3">
                                                    <p class="mb-0 text-muted text-sm"><?php echo e(__('Phone')); ?> :</p>
                                                    <h6 class="mb-0">
                                                        <?php echo e(!empty($maintainer->user) ? $maintainer->user->phone_number : '-'); ?>

                                                    </h6>
                                                </div>
                                            <?php endif; ?>
                                            <div class="col-sm-6 mb-3">
                                                <p class="mb-0 text-muted text-sm"><?php echo e(__('Type')); ?> :</p>
                                                <h6 class="mb-0">
                                                    <?php echo e(!empty($maintainer->types) ? $maintainer->types->title : '-'); ?></h6>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <p class="mb-0 text-muted text-sm"><?php echo e(__('Created Date')); ?> :</p>
                                                <h6 class="mb-0">
                                                    <?php echo e(dateFormat($maintainer->created_at)); ?></h6>
                                            </div>
                                            <div class="col-sm-12 mb-3">
                                                <span><?php echo e(__('Property')); ?> :</span>
                                                <ul>
                                                    <?php $__currentLoopData = $maintainer->properties(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><?php echo e($property->name); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/maintainer/index.blade.php ENDPATH**/ ?>