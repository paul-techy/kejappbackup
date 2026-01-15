<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Tenant History Report')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Tenant History Report')); ?></li>
<?php $__env->stopSection(); ?>

<?php
    $profile = asset(Storage::url('upload/profile'));

?>

<?php $__env->startPush('script-page'); ?>
    <script>
        $('#property_id').on('change', function() {
            "use strict";
            var property_id = $(this).val();
            var url = '<?php echo e(route('property.unit', ':id')); ?>';
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
                    $('.unit').empty();
                    var unit =
                        `<select class="form-control hidesearch unit" id="unit_id" name="unit_id"></select>`;
                    $('.unit_div').html(unit);

                    $.each(data, function(key, value) {
                        $('.unit').append('<option value="' + key + '">' + value + '</option>');
                    });
                    $(".hidesearch").each(function() {
                        var basic_select = new Choices(this, {
                            searchEnabled: false,
                            removeItemButton: true,
                        });
                    });
                },

            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('css-page'); ?>
    <style>
        .cust-pro {
            width: 230px;
        }

        .choices__list--dropdown .choices__item--selectable:after {
            content: '';
        }

        .choices__list--dropdown .choices__item--selectable {
            padding-right: 10px;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">

                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h5 class="mb-0"><?php echo e(__('Tenant History Report')); ?></h5>
                        </div>

                        <form action="<?php echo e(route('report.tenant')); ?>" method="get">
                            <div class="row gx-2 gy-1 align-items-end">


                                <div class="cust-pro">
                                    <?php echo e(Form::label('tenant_id', __('Tenant'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::select('tenant_id', $tenant_options, request('tenant_id'), [
                                        'class' => 'form-control hidesearch',
                                        'id' => 'tenant_id',
                                    ])); ?>

                                </div>
                                <div class="cust-pro">
                                    <?php echo e(Form::label('property_id', __('Property'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::select('property_id', $property, request('property_id'), [
                                        'class' => 'form-control hidesearch',
                                        'id' => 'property_id',
                                    ])); ?>

                                </div>

                                <div class="cust-pro">
                                    <?php echo e(Form::label('unit_id', __('Unit'), ['class' => 'form-label'])); ?>


                                    <div class="unit_div">
                                        <select class="form-control hidesearch unit" id="unit_id" name="unit_id">
                                            <option value=""><?php echo e(__('Select Unit')); ?></option>
                                            <?php if(!empty($units)): ?>
                                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($id); ?>"
                                                        <?php echo e(request('unit_id') == $id ? 'selected' : ''); ?>>
                                                        <?php echo e($name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="cust-pro">
                                    <?php echo e(Form::label('status', __('Status'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::select('status', $status, request('status'), [
                                        'class' => 'form-control',
                                    ])); ?>

                                </div>

                                <div class="col-auto">
                                    <button type="submit" class="btn btn-light-secondary px-3">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </div>

                                <div class="col-auto">
                                    <a href="<?php echo e(route('report.tenant')); ?>" class="btn btn-light-dark px-3">
                                        <i class="ti ti-refresh"></i>
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>


                <div class="card-body pt-0">
                    <div class="dt-responsive table-responsive">
                        <table class="table table-hover advance-datatable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Tenant')); ?></th>
                                    <th><?php echo e(__('Email')); ?></th>
                                    <th><?php echo e(__('Contact')); ?></th>
                                    <th><?php echo e(__('Property')); ?></th>
                                    <th><?php echo e(__('Unit')); ?></th>
                                    <th><?php echo e(__('Paid Amount')); ?></th>
                                    <th><?php echo e(__('Due Amount')); ?></th>
                                    <th><?php echo e(__('Total Amount')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>

                                         <td class="table-user">
                                            <img src="<?php echo e(!empty($tenant->user->profile) ? fetch_file($tenant->user->profile, 'upload/profile/') : $profile); ?>"
                                                alt="" class="mr-2 avatar-sm rounded-circle user-avatar">
                                            <a href="#"
                                                class="text-body font-weight-semibold"><?php echo e(ucfirst(!empty($tenant->user) ? $tenant->user->first_name : '') . ' ' . ucfirst(!empty($tenant->user) ? $tenant->user->last_name : '')); ?></a>
                                        </td>


                                        <td><?php echo e(!empty($tenant->user) ? $tenant->user->email : '-'); ?></td>
                                        <td><?php echo e(!empty($tenant->user) ? $tenant->user->phone_number : '-'); ?></td>
                                        <td> <?php echo e(!empty($tenant->properties) ? $tenant->properties->name : '-'); ?></td>
                                        <td> <?php echo e(!empty($tenant->units) ? $tenant->units->name : '-'); ?></td>
                                        <?php
                                            $invoice = \App\Models\Tenant::invoiceDetail($tenant->id);
                                        ?>

                                        <td><?php echo e(priceformat($invoice['paid'] ?? 0)); ?></td>
                                        <td><?php echo e(priceformat($invoice['due'] ?? 0)); ?></td>
                                        <td><?php echo e(priceformat($invoice['total'] ?? 0)); ?></td>

                                        <td>

                                            <?php if($invoice['status'] == 'open'): ?>
                                                <span class="badge bg-light-info">
                                                    <?php echo e(__($invoice['status'] ?? '-')); ?></span>
                                            <?php elseif($invoice['status'] == 'paid'): ?>
                                                <span class="badge bg-light-success">
                                                    <?php echo e(__($invoice['status'] ?? '-')); ?></span>
                                            <?php elseif($invoice['status'] == 'partial_paid'): ?>
                                                <span class="badge bg-light-warning">
                                                    <?php echo e(__($invoice['status'] ?? '-')); ?></span>
                                            <?php else: ?>
                                                <span><?php echo e(__('No Invoice Found')); ?></span>
                                            <?php endif; ?>

                                        </td>



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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/report/tenant.blade.php ENDPATH**/ ?>