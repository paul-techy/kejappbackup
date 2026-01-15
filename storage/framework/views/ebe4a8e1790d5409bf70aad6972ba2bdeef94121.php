<?php echo e(Form::model($maintainer, ['route' => ['maintainer.update', $maintainer->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('first_name', __('First Name'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('first_name', $user->first_name, ['class' => 'form-control', 'placeholder' => __('Enter First Name')])); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('last_name', __('Last Name'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('last_name', $user->last_name, ['class' => 'form-control', 'placeholder' => __('Enter Last Name')])); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6 ">
            <?php echo e(Form::label('email', __('Email'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('email', $user->email, ['class' => 'form-control', 'placeholder' => __('Enter Email')])); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('phone_number', __('Phone Number'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('phone_number', $user->phone_number, ['class' => 'form-control', 'placeholder' => __('Enter Phone Number')])); ?>

            <small class="form-text text-muted">
                <?php echo e(__('Please enter the number with country code. e.g., +91XXXXXXXXXX')); ?>

            </small>
        </div>
        <div class="form-group">
            <?php echo e(Form::label('property_id', __('Property'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('property_id[]', $property, explode(',', $maintainer->property_id), ['class' => 'form-control hidesearch', 'id' => 'property', 'multiple'])); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('type_id', __('Type'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::select('type_id', $types, null, ['class' => 'form-control hidesearch'])); ?>

        </div>
        <div class="form-group">
            <?php echo e(Form::label('profile', __('Profile'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::file('profile', ['class' => 'form-control'])); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <?php echo e(Form::submit(__('Update'), ['class' => 'btn btn-secondary btn-rounded'])); ?>

</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /home/paulawit/malipos.co.ke/resources/views/maintainer/edit.blade.php ENDPATH**/ ?>