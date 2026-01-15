<?php echo e(Form::open(array('route'=>array('unit.store',$property_id),'method'=>'post'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('name',__('Name'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter unit name')))); ?>

        </div>
        <div class="form-group  col-md-4">
            <?php echo e(Form::label('bedroom',__('Bedroom'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('bedroom',null,array('class'=>'form-control','placeholder'=>__('Enter number of bedroom')))); ?>

        </div>
        <div class="form-group  col-md-4">
            <?php echo e(Form::label('kitchen',__('Kitchen'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('kitchen',null,array('class'=>'form-control','placeholder'=>__('Enter number of kitchen')))); ?>

        </div>
        <div class="form-group  col-md-4">
            <?php echo e(Form::label('baths',__('Bath'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('baths',null,array('class'=>'form-control','placeholder'=>__('Enter number of bath')))); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('rent',__('Rent'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('rent',null,array('class'=>'form-control','placeholder'=>__('Enter unit rent')))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('rent_type',__('Rent Type'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::select('rent_type',$rentTypes,null,array('class'=>'form-control hidesearch','id'=>'rent_type'))); ?>

        </div>
        <div class="form-group  col-md-12 rent_type monthly ">
            <?php echo e(Form::label('rent_duration',__('Rent Duration'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('rent_duration',null,array('class'=>'form-control','placeholder'=>__('Enter day of month between 1 to 30')))); ?>

        </div>
        <div class="form-group  col-md-12 rent_type yearly d-none">
            <?php echo e(Form::label('rent_duration',__('Rent Duration'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('rent_duration',null,array('class'=>'form-control','placeholder'=>__('Enter month of year between 1 to 12'),'disabled'))); ?>

        </div>
        <div class="form-group  col-md-4 rent_type custom d-none">
            <?php echo e(Form::label('start_date',__('Start Date'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::date('start_date',null,array('class'=>'form-control','disabled'))); ?>

        </div>
        <div class="form-group  col-md-4 rent_type custom d-none">
            <?php echo e(Form::label('end_date',__('End Date'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::date('end_date',null,array('class'=>'form-control','disabled'))); ?>

        </div>
        <div class="form-group  col-md-4 rent_type custom d-none">
            <?php echo e(Form::label('payment_due_date',__('Payment Due Date'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::date('payment_due_date',null,array('class'=>'form-control','disabled'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('deposit_type',__('Deposit Type'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::select('deposit_type',$types,null,array('class'=>'form-control hidesearch'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('deposit_amount',__('Deposit Amount'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('deposit_amount',null,array('class'=>'form-control','placeholder'=>__('Enter deposit amount')))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('late_fee_type',__('Late Fee Type'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::select('late_fee_type',$types,null,array('class'=>'form-control hidesearch'))); ?>

        </div>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('late_fee_amount',__('Late Fee Amount'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('late_fee_amount',null,array('class'=>'form-control','placeholder'=>__('Enter late fee amount')))); ?>

        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('incident_receipt_amount',__('Incident Receipt Amount'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('incident_receipt_amount',null,array('class'=>'form-control','placeholder'=>__('Enter incident receipt amount')))); ?>

        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('notes',__('Notes'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::textarea('notes',null,array('class'=>'form-control','rows'=>2,'placeholder'=>__('Enter notes')))); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <?php echo e(Form::submit(__('Create'),array('class'=>'btn btn-secondary btn-rounded'))); ?>

</div>
<?php echo e(Form::close()); ?>

<script>
    $('#rent_type').on('change', function() {
        "use strict";
        var type=this.value;
        $('.rent_type').addClass('d-none')
        $('.'+type).removeClass('d-none')

        var input1= $('.rent_type').find('input');
        input1.prop('disabled', true);
        var input2= $('.'+type).find('input');
        input2.prop('disabled', false);
    });
</script>

<?php /**PATH /home/paulawit/malipos.co.ke/resources/views/unit/create.blade.php ENDPATH**/ ?>