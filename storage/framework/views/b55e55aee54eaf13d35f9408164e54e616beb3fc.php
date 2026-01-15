<?php
$user=\Auth::user();
$tenant=$user->tenants;
?>
<?php echo e(Form::open(array('url'=>'maintenance-request','method'=>'post', 'enctype' => "multipart/form-data"))); ?>

<div class="modal-body">
    <div class="row">
        <?php if($user->type=='tenant'): ?>
            <?php echo e(Form::hidden('property_id',!empty($tenant)?$tenant->property:null,array('class'=>'form-control'))); ?>

            <?php echo e(Form::hidden('unit_id',!empty($tenant)?$tenant->unit:null,array('class'=>'form-control'))); ?>

        <?php else: ?>
            <div class="form-group col-md-6 col-lg-6">
                <?php echo e(Form::label('property_id',__('Property'),array('class'=>'form-label'))); ?>

                <?php echo e(Form::select('property_id',$property,null,array('class'=>'form-control hidesearch','id'=>'property_id'))); ?>

            </div>
            <div class="form-group col-lg-6 col-md-6">
                <?php echo e(Form::label('unit_id',__('Unit'),array('class'=>'form-label'))); ?>

                <div class="unit_div">
                    <select class="form-control hidesearch unit" id="unit_id" name="unit_id">
                        <option value=""><?php echo e(__('Select Unit')); ?></option>
                    </select>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group  col-md-6 col-lg-6">
            <?php echo e(Form::label('request_date',__('Request Date'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::date('request_date',null,array('class'=>'form-control'))); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('maintainer_id',__('Maintainer'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::select('maintainer_id',$maintainers,null,array('class'=>'form-control hidesearch'))); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('issue_type',__('Issue Type'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::select('issue_type',$types,null,array('class'=>'form-control hidesearch'))); ?>

        </div>
        <div class="form-group col-md-6 col-lg-6">
            <?php echo e(Form::label('status',__('Status'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::select('status',$status,null,array('class'=>'form-control hidesearch'))); ?>

        </div>
        <div class="form-group  col-md-12 col-lg-12">
            <?php echo e(Form::label('issue_attachment',__('Issue Attachment'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::file('issue_attachment',array('class'=>'form-control'))); ?>

        </div>
        <div class="form-group  col-md-12 col-lg-12">
            <?php echo e(Form::label('notes',__('Notes'),array('class'=>'form-label'))); ?>

            <?php echo e(Form::textarea('notes',null,array('class'=>'form-control','rows'=>3))); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <?php echo e(Form::submit(__('Create'),array('class'=>'btn btn-secondary btn-rounded'))); ?>

</div>
<?php echo e(Form::close()); ?>

<script>
    $('#property_id').on('change', function () {
        "use strict";
        var property_id=$(this).val();
        var url = '<?php echo e(route("property.unit", ":id")); ?>';
        url = url.replace(':id', property_id);
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                property_id:property_id,
            },
            contentType: false,
            processData: false,
            type: 'GET',
            success: function (data) {
                $('.unit').empty();
                var unit = `<select class="form-control hidesearch unit" id="unit_id" name="unit_id"></select>`;
                $('.unit_div').html(unit);

                $.each(data, function(key, value) {
                    $('.unit').append('<option value="' + key + '">' + value +'</option>');
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

<?php /**PATH /home/paulawit/malipos.co.ke/resources/views/maintenance_request/create.blade.php ENDPATH**/ ?>