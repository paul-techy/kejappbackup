<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Profit & Loss Report')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item" aria-current="page"> <?php echo e(__('Profit & Loss Report')); ?></li>
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

    <script>
        var options = {
            chart: {
                type: 'area',
                height: 450,
                toolbar: {
                    show: false
                }
            },
            colors: ['#2ca58d', '#0a2342'],
            dataLabels: {
                enabled: false
            },
            legend: {
                show: true,
                position: 'top'
            },
            markers: {
                size: 1,
                colors: ['#fff', '#fff', '#fff'],
                strokeColors: ['#2ca58d', '#0a2342'],
                strokeWidth: 1,
                shape: 'circle',
                hover: {
                    size: 4
                }
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    type: 'vertical',
                    inverseColors: false,
                    opacityFrom: 0.5,
                    opacityTo: 0
                }
            },
            grid: {
                show: false
            },
            series: [{
                    name: "<?php echo e(__('Total Income')); ?>",
                    data: <?php echo json_encode($incomeExpenseByMonth['income']); ?>

                },
                {
                    name: "<?php echo e(__('Total Expense')); ?>",
                    data: <?php echo json_encode($incomeExpenseByMonth['expense']); ?>

                }
            ],
            xaxis: {
                categories: <?php echo json_encode($incomeExpenseByMonth['label']); ?>,
                tooltip: {
                    enabled: false
                },
                labels: {
                    hideOverlappingLabels: true
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            }
        };
        var chart = new ApexCharts(document.querySelector('#incomeExpense'), options);
        chart.render();
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
                            <h5 class="mb-0"><?php echo e(__('Profit & Loss Report')); ?></h5>
                        </div>

                        <form action="<?php echo e(route('report.profit_loss')); ?>" method="get">
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
                                    <?php echo e(Form::label('year', __('Year'), ['class' => 'form-label'])); ?>

                                    <select class="form-control" name="year" id="year">
                                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($yr); ?>"
                                                <?php echo e(request('year', $year) == $yr ? 'selected' : ''); ?>>
                                                <?php echo e($yr); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-light-secondary px-3">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </div>

                                <div class="col-auto">
                                    <a href="<?php echo e(route('report.profit_loss')); ?>" class="btn btn-light-dark px-3">
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

                                    <th><?php echo e(__('Month')); ?></th>
                                    <th><?php echo e(__('Income')); ?></th>
                                    <th><?php echo e(__('Expense')); ?></th>
                                    <th><?php echo e(__('Profit & Loss')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php $__currentLoopData = $report; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($data->month); ?></td>
                                        <td><?php echo e(priceFormat($data->income)); ?></td>
                                        <td><?php echo e(priceFormat($data->expense)); ?></td>
                                        <?php
                                            $profitClass =
                                                $data->profit < 0
                                                    ? 'text-danger'
                                                    : ($data->profit > 0
                                                        ? 'text-success'
                                                        : 'text-muted');
                                        ?>

                                        <td class="<?php echo e($profitClass); ?>">
                                            <?php echo e(priceFormat($data->profit)); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h5 class="mb-1"><?php echo e(__('Analysis Report')); ?></h5>
                            <p class="text-muted mb-2"><?php echo e(__('Income and Expense Overview')); ?></p>
                        </div>

                    </div>
                    <div id="incomeExpense"></div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/report/profit_loss.blade.php ENDPATH**/ ?>