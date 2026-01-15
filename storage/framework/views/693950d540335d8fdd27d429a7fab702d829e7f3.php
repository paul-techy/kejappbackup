<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Tenant Details')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
    </li>
    <li class="breadcrumb-item" aria-current="page"><a href="<?php echo e(route('tenant.index')); ?>"> <?php echo e(__('Tenant')); ?></a></li>
    <li class="breadcrumb-item active">
        <a href="#"><?php echo e(__('Details')); ?></a>
    </li>
<?php $__env->stopSection(); ?>

<?php
    $profile = asset(Storage::url('upload/profile/avatar.png'));
?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-xxl-3">
                            <div class="card border">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img class="img-radius img-fluid wid-80"
                                                src= "<?php echo e(!empty($tenant->user->profile) ? fetch_file($tenant->user->profile, 'upload/profile/') : $profile); ?>"
                                                alt="User image" />
                                        </div>
                                        <div class="flex-grow-1 mx-3">
                                            <h5 class="mb-1">
                                                <?php echo e(ucfirst(!empty($tenant->user) ? $tenant->user->first_name : '') . ' ' . ucfirst(!empty($tenant->user) ? $tenant->user->last_name : '')); ?>

                                            </h5>
                                            <h6 class="mb-0 text-secondary"><?php echo $tenant->LeaseLeftDay(); ?></h6>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body px-2 pb-0">
                                    <div class="list-group list-group-flush">
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="material-icons-two-tone f-20">email</i>
                                                </div>
                                                <div class="flex-grow-1 mx-3">
                                                    <h5 class="m-0"><?php echo e(__('Email')); ?></h5>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <small><?php echo e(!empty($tenant->user) ? $tenant->user->email : '-'); ?></small>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="material-icons-two-tone f-20">phonelink_ring</i>
                                                </div>
                                                <div class="flex-grow-1 mx-3">
                                                    <h5 class="m-0"><?php echo e(__('Phone')); ?></h5>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <small><?php echo e(!empty($tenant->user) ? $tenant->user->phone_number : '-'); ?>

                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        
                        <div class="col-lg-8 col-xxl-9">
                            <div class="card border">
                                <div class="card-header">
                                    <div class="row align-items-center g-2">
                                        <div class="col">
                                            <h5><?php echo e(__('Additional Information')); ?></h5>
                                        </div>

                                        <?php if(\Auth::user()->type == 'owner' && $tenant->units && $tenant->units->is_occupied == 1): ?>
                                            <div class="col-auto">
                                                <a class="btn btn-light-danger customModal" href="#"
                                                    data-url="<?php echo e(route('tenant.exit', $tenant->id)); ?>"
                                                    data-title="<?php echo e(__('Exit Tenant')); ?>">
                                                    <?php echo e(__('Exit Tenant')); ?>

                                                </a>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <tr>
                                                    <td><b class="text-header"><?php echo e(__('Total Family Member')); ?></b></td>
                                                    <td>:</td>
                                                    <td><?php echo e(!empty($tenant->family_member) ? $tenant->family_member : '-'); ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header"><?php echo e(__('Country')); ?></b></td>
                                                    <td>:</td>
                                                    <td><?php echo e(!empty($tenant->country) ? $tenant->country : '-'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header"><?php echo e(__('State')); ?></b></td>
                                                    <td>:</td>
                                                    <td><?php echo e(!empty($tenant->state) ? $tenant->state : '-'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header"><?php echo e(__('City')); ?></b></td>
                                                    <td>:</td>
                                                    <td><?php echo e(!empty($tenant->city) ? $tenant->city : '-'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header"><?php echo e(__('Zip Code')); ?></b></td>
                                                    <td>:</td>
                                                    <td><?php echo e(!empty($tenant->zip_code) ? $tenant->zip_code : '-'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header"><?php echo e(__('Property')); ?></b></td>
                                                    <td>:</td>
                                                    <td><?php echo e(!empty($tenant->properties) ? $tenant->properties->name : '-'); ?>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header"><?php echo e(__('Unit')); ?></b></td>
                                                    <td>:</td>
                                                    <td><?php echo e(!empty($tenant->units) ? $tenant->units->name : '-'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header"><?php echo e(__('Lease Start Date')); ?></b></td>
                                                    <td>:</td>
                                                    <td><?php echo e(dateFormat($tenant->lease_start_date)); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b class="text-header"><?php echo e(__('Lease End Date')); ?></b></td>
                                                    <td>:</td>
                                                    <td><?php echo e(dateFormat($tenant->lease_end_date)); ?></td>
                                                </tr>

                                                <tr>
                                                    <td><b class="text-header"><?php echo e(__('Address')); ?></b></td>
                                                    <td>:</td>
                                                    <td><?php echo e(!empty($tenant->address) ? $tenant->address : '-'); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if(!empty($tenant->documents) && $tenant->documents->count()): ?>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo e(__('Tenant Documents')); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php $__currentLoopData = $tenant->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $folder = 'upload/tenant/';
                                    $filename = $doc->document;
                                    $fileUrl = fetch_file($filename, $folder);

                                    $fileExtension = pathinfo($filename, PATHINFO_EXTENSION); // Use filename, not URL
                                    $isImage = in_array(strtolower($fileExtension), [
                                        'jpg',
                                        'jpeg',
                                        'png',
                                        'gif',
                                        'webp',
                                    ]);
                                ?>

                                <div class="col-md-2 col-sm-4 col-6 mb-2">
                                    <div
                                        class="card gallery-card shadow-sm border rounded text-center d-flex flex-column justify-content-between">
                                        <?php if($isImage): ?>
                                            <a href="<?php echo e($fileUrl); ?>" target="_blank">
                                                <img src="<?php echo e($fileUrl); ?>" alt="Document"
                                                    class="img-fluid img-card-top rounded-top mt-1"
                                                    style="height: 180px; object-fit: cover;">
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo e($fileUrl); ?>" target="_blank"
                                                class="d-flex justify-content-center align-items-center bg-light"
                                                style="height: 180px;">
                                                <i class="ti ti-file-text" style="font-size: 48px;"></i>
                                            </a>
                                        <?php endif; ?>
                                        <hr>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="<?php echo e($fileUrl); ?>" download title="Download"
                                                class="avtar btn-link-success text-success p-0">
                                                <i class="ti ti-download "></i>
                                            </a>

                                            <?php echo Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['tenant.document.delete', $doc->id],
                                                'id' => 'doc-' . $doc->id,
                                            ]); ?>

                                            <a class="avtar  btn-link-danger text-danger confirm_dialog p-0 confirm_dialog"
                                                href="#"><i class="ti ti-trash text-danger"></i>
                                            </a>
                                            <?php echo Form::close(); ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>


                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5><?php echo e(__('Invoice List')); ?></h5>
                        </div>

                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-hover advance-datatable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Invoice')); ?></th>
                                    <th><?php echo e(__('Property')); ?></th>
                                    <th><?php echo e(__('Unit')); ?></th>
                                    <th><?php echo e(__('Invoice Month')); ?></th>
                                    <th><?php echo e(__('End Date')); ?></th>
                                    <th><?php echo e(__('Amount')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <?php if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice')): ?>
                                        <th class="text-right"><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(invoicePrefix() . $invoice->invoice_id); ?> </td>
                                        <td><?php echo e(!empty($invoice->properties) ? $invoice->properties->name : '-'); ?>

                                        </td>
                                        <td><?php echo e(!empty($invoice->units) ? $invoice->units->name : '-'); ?>

                                        </td>
                                        <td><?php echo e(date('F Y', strtotime($invoice->invoice_month))); ?>

                                        </td>
                                        <td><?php echo e(dateFormat($invoice->end_date)); ?> </td>
                                        <td><?php echo e(priceFormat($invoice->getInvoiceSubTotalAmount())); ?>

                                        </td>
                                        <td>
                                            <?php if($invoice->status == 'open'): ?>
                                                <span
                                                    class="badge bg-light-info"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                            <?php elseif($invoice->status == 'paid'): ?>
                                                <span
                                                    class="badge bg-light-success"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                            <?php elseif($invoice->status == 'partial_paid'): ?>
                                                <span
                                                    class="badge bg-light-warning"><?php echo e(\App\Models\Invoice::$status[$invoice->status]); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <?php if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice')): ?>
                                            <td>
                                                <div class="cart-action">
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id]]); ?>


                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show invoice')): ?>
                                                        <a class="avtar avtar-xs btn-link-warning text-warning"
                                                            href="<?php echo e(route('invoice.show', \Crypt::encrypt($invoice->id))); ?>"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="<?php echo e(__('View')); ?>">
                                                            <i data-feather="eye"></i></a>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/tenant/show.blade.php ENDPATH**/ ?>