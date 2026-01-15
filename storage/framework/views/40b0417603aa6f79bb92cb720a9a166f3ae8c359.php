<?php echo e(Form::model($subscription, array('route' => array('subscriptions.update', $subscription->id), 'method' => 'PUT'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group">
            <?php echo e(Form::label('title',__('Title'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter subscription title'),'required'=>'required'))); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('interval', __('Interval'),array('class'=>'form-label'))); ?>

            <?php echo Form::select('interval', $intervals, null,array('class' => 'form-control hidesearch','required'=>'required')); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('package_amount',__('Package Amount'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('package_amount',null,array('class'=>'form-control','placeholder'=>__('Enter package amount'),'step'=>'0.01'))); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('user_limit',__('User Limit'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('user_limit',null,array('class'=>'form-control','placeholder'=>__('Enter user limit'),'required'=>'required'))); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('property_limit',__('Property Limit'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('property_limit',null,array('class'=>'form-control','placeholder'=>__('Enter property limit'),'required'=>'required'))); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('tenant_limit',__('Tenant Limit'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::number('tenant_limit',null,array('class'=>'form-control','placeholder'=>__('Enter tenant limit'),'required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-6">
            <div class="form-check form-switch custom-switch-v1 mb-2">
                <input type="checkbox" class="form-check-input input-secondary" name="enabled_logged_history" id="enabled_logged_history" <?php echo e($subscription->enabled_logged_history==1?'checked':''); ?>>
                <?php echo e(Form::label('enabled_logged_history',__('Show User Logged History'),array('class'=>'form-label'))); ?>

              </div>
        </div>

    </div>
</div>
<div class="modal-footer">

    <?php echo e(Form::submit(__('Update'),array('class'=>'btn btn-secondary btn-rounded'))); ?>

</div>
<?php echo e(Form::close()); ?>



<?php /**PATH /home/paulawit/malipos.co.ke/resources/views/subscription/edit.blade.php ENDPATH**/ ?>