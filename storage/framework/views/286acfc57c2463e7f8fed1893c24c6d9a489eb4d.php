<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Income Report')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Income Report')); ?></li>
<?php $__env->stopSection(); ?>

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
                            <h5 class="mb-0"><?php echo e(__('Income Report')); ?></h5>
                        </div>

                        <form action="<?php echo e(route('report.income')); ?>" method="get">
                            <div class="row gx-2 gy-1 align-items-end">
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
                                    <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'form-label'])); ?>

                                    <input class="form-control" name="start_date" type="date"
                                        value="<?php echo e(request('start_date')); ?>">
                                </div>

                                <div class="cust-pro">
                                    <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-label'])); ?>

                                    <input class="form-control" name="end_date" type="date"
                                        value="<?php echo e(request('end_date')); ?>">
                                </div>

                                <div class="col-auto">
                                    <button type="submit" class="btn btn-light-secondary px-3">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </div>

                                <div class="col-auto">
                                    <a href="<?php echo e(route('report.income')); ?>" class="btn btn-light-dark px-3">
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
                                        <td><?php echo e(!empty($invoice->properties) ? $invoice->properties->name : '-'); ?> </td>
                                        <td><?php echo e(!empty($invoice->units) ? $invoice->units->name : '-'); ?> </td>
                                        <td><?php echo e(date('F Y', strtotime($invoice->invoice_month))); ?> </td>
                                        <td><?php echo e(dateFormat($invoice->end_date)); ?> </td>
                                        <td><?php echo e(priceFormat($invoice->getInvoiceSubTotalAmount())); ?></td>
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
                                                            data-bs-original-title="<?php echo e(__('View')); ?>"> <i
                                                                data-feather="eye"></i></a>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/report/income.blade.php ENDPATH**/ ?>