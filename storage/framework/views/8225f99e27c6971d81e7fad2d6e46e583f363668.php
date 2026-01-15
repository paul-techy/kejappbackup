<?php echo e(Form::model($notification, ['route' => ['invoice.sendEmail', $id], 'method' => 'post'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            <?php echo e(Form::label('name', __('Module'), ['class' => 'form-label'])); ?>

            <?php echo Form::text('name', null, [
                'class' => 'form-control',
                'required' => 'required',
                'readonly' => 'readonly',
            ]); ?>

        </div>

        <div class="form-group col-md-6">
            <?php echo e(Form::label('subject', __('Subject'), ['class' => 'form-label'])); ?>

            <?php echo e(Form::text('subject', null, ['class' => 'form-control', 'placeholder' => __('Enter Subject'), 'required' => 'required'])); ?>

        </div>

        <div class="form-group col-md-12">
            <?php echo e(Form::label('enabled_sms', __('Enabled SMS Notification'), ['class' => 'form-label'])); ?>

            <input class="form-check-input sms" type="hidden" name="enabled_sms" value="0">
            <div class="form-check form-switch">
                <input class="form-check-input sms" type="checkbox" role="switch" id="flexSwitchCheck"
                    name="enabled_sms" value="1" <?php echo e($notification->enabled_sms == 1 ? 'checked' : ''); ?>>
                <label class="form-check-label" for="flexSwitchCheck"></label>
            </div>
        </div>

        <div class="form-group col-md-12">
            <?php echo e(Form::label('message', __('User Message'), ['class' => 'form-label'])); ?>

            <?php echo Form::textarea('message', $notification->message, [
                'class' => 'form-control',
                'rows' => 5,
                'id' => 'message',
            ]); ?>


        </div>

        <div class="form-group col-md-12 smsMessage <?php echo e($notification->enabled_sms == 1 ? '' : 'd-none'); ?>">
            <?php echo e(Form::label('sms_message', __('User SMS Message'), ['class' => 'form-label'])); ?>

            <?php echo Form::textarea('sms_message', $notification->sms_message, [
                'class' => 'form-control ',
                'rows' => 5,
                'id' => 'sms_message',
            ]); ?>


            <p class="mt-2"> <b><?php echo e(__('Note')); ?></b> :- <?php echo e(__('Maximum 160 characters allowed!')); ?></p>
        </div>

        <div class="form-group col-md-12">
            <h4 class="mb-0"><?php echo e(__('Shortcodes')); ?></h4>
            <p><?php echo e(__('Click to add below shortcodes and insert in your Message')); ?></p>

            <?php
                $i = 0; // Counter for determining the display state of sections
            ?>

            <?php if(!empty($notification->short_code) && is_array($notification->short_code)): ?>
                <section class="sortcode_var" style="display: <?php echo e($i == 0 ? 'block' : 'none'); ?>;">
                    <?php $__currentLoopData = $notification->short_code; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="javascript:void(0);">
                            <span class="badge bg-light-primary rounded-pill f-14 px-2 sort_code_click m-2"
                                data-val="<?php echo e($item); ?>">
                                <?php echo e($item); ?>

                            </span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </section>
            <?php else: ?>
                <p><?php echo e(__('No shortcodes available for this notification.')); ?></p>
            <?php endif; ?>
        </div>


    </div>
</div>
<div class="modal-footer">
    <?php echo e(Form::submit(__('Send Mail'), ['class' => 'btn btn-secondary ml-10'])); ?>

</div>
<?php echo e(Form::close()); ?>



<script>
    $(document).ready(function() {
        $('.module').trigger('change');

    });

    $(document).on('change', '.sms', function() {
        let sms = $(this).is(':checked') ? 1 : 0;
        if (sms == 1) {
            $('.smsMessage').removeClass('d-none')
        } else {
            $('.smsMessage').addClass('d-none')
        }
    });

    $(document).on('change', '.module', function() {
        var modd = $('.module').val();
        $('.sortcode_var').hide();
        $('.sortcode_var.' + modd).show();

        var subject = $('.sortcode_tm.' + modd).attr('data-subject');
        $('.subject').val(subject);

        var templete = $('.sortcode_tm.' + modd).attr('data-templete');
        $('#message').html(templete);
    });



    var CKEDITORsd = ClassicEditor
        .create(document.querySelector('#message'), {}).then(editor => {
            window.editor = editor;
            editorInstance = editor;

            $(document).on('click', '.sort_code_click', function() {
                var val = $(this).attr('data-val');
                editor.model.change(writer => {
                    const viewFragment = editor.data.processor.toView(val);
                    const modelFragment = editor.data.toModel(viewFragment);
                    editor.model.insertContent(modelFragment);
                });
            });

            $(document).on('change', '.module', function() {
                var modd = $('.module').val();
                var templete = $('.sortcode_tm.' + modd).attr('data-templete');
                editorInstance.setData(templete);
            });
        })
        .catch(error => {
            console.log(error);
        });
</script>
<?php /**PATH /home/paulawit/malipos.co.ke/resources/views/invoice/remind.blade.php ENDPATH**/ ?>