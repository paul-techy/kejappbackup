{{ Form::open(['url' => 'agreement', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('property_id', __('Property'), ['class' => 'form-label']) }}
            {{ Form::select('property_id', $property, null, ['class' => 'form-control hidesearch', 'id' => 'property_id']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('unit_id', __('Unit'), ['class' => 'form-label']) }}
            <div class="unit_div">
                <select class="form-control hidesearch unit" id="unit_id" name="unit_id">
                    <option value="">{{ __('Select Unit') }}</option>
                </select>
            </div>
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
            {{ Form::date('date', null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
            {{ Form::select('status', $status, null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('terms_condition', __('Terms & Condition'), ['class' => 'form-label']) }}
            {{ Form::textarea('terms_condition', $setting['terms_condition'], ['class' => 'form-control classic-editor']) }}
        </div>

        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {{ Form::textarea('description', $setting['agreement_description'], ['class' => 'form-control classic-editor2']) }}
        </div>

        {{-- <div class="form-group">
            {{ Form::label('profile', __('Profile'), ['class' => 'form-label']) }}
            {{ Form::file('profile', ['class' => 'form-control']) }}
        </div> --}}
    </div>
</div>
<div class="modal-footer">
    {{ Form::submit(__('Create'), ['class' => 'btn btn-secondary btn-rounded']) }}
</div>
{{ Form::close() }}
<script>
    if ($('.classic-editor').length > 0) {
        ClassicEditor.create(document.querySelector('.classic-editor')).catch((error) => {
            console.error(error);
        });
    }
    setTimeout(() => {
        feather.replace();
    }, 500);
    if ($('.classic-editor2').length > 0) {
        ClassicEditor.create(document.querySelector('.classic-editor2')).catch((error) => {
            console.error(error);
        });
    }
    setTimeout(() => {
        feather.replace();
    }, 500);
</script>


<script>
    $('#property_id').on('change', function() {
        "use strict";
        var property_id = $(this).val();
        var url = '{{ route('property.unit', ':id') }}';
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
