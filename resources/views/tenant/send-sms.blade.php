@extends('layouts.app')
@section('page-title')
    {{ __('Send SMS to Tenants') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('tenant.index') }}">{{ __('Tenant') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('Send SMS') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center g-2">
                        <div class="col">
                            <h5>{{ __('Send SMS to Tenants') }}</h5>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-secondary" href="{{ route('tenant.index') }}"> <i
                                    class="ti ti-arrow-left align-text-bottom"></i> {{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="sms_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Select Tenants') }} <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select_all_tenants">
                                        <label class="form-check-label" for="select_all_tenants">
                                            <strong>{{ __('Select All Tenants') }}</strong>
                                        </label>
                                    </div>
                                    <hr>
                                    <div class="row mt-3">
                                        @foreach ($tenants as $tenant)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input tenant-checkbox" type="checkbox"
                                                        name="tenant_ids[]" value="{{ $tenant->id }}"
                                                        id="tenant_{{ $tenant->id }}">
                                                    <label class="form-check-label" for="tenant_{{ $tenant->id }}">
                                                        <strong>{{ ucfirst($tenant->user->first_name ?? '') }}
                                                            {{ ucfirst($tenant->user->last_name ?? '') }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $tenant->user->phone_number ?? __('No Phone') }}
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if ($tenants->isEmpty())
                                        <div class="alert alert-info mt-3">
                                            {{ __('No tenants found.') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Message') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="message" id="message" rows="5"
                                        placeholder="{{ __('Enter your SMS message here...') }}" required></textarea>
                                    <small class="form-text text-muted">
                                        <span id="char_count">0</span> / 1600 {{ __('characters') }}
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Message Type') }}</label>
                                    <select class="form-control" name="msgType" id="msgType">
                                        <option value="text">{{ __('Text (English)') }}</option>
                                        <option value="unicode">{{ __('Unicode (Regional)') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Remove Duplicates') }}</label>
                                    <select class="form-control" name="duplicatecheck" id="duplicatecheck">
                                        <option value="true">{{ __('Yes') }}</option>
                                        <option value="false">{{ __('No') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Schedule Time (Optional)') }}</label>
                                    <input type="datetime-local" class="form-control" name="scheduleTime"
                                        id="scheduleTime">
                                    <small class="form-text text-muted">
                                        {{ __('Format: YYYY-MM-DD HH:MM:SS. Leave empty to send immediately.') }}
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Test Message') }}</label>
                                    <select class="form-control" name="testMessage" id="testMessage">
                                        <option value="false">{{ __('No (Send Real SMS)') }}</option>
                                        <option value="true">{{ __('Yes (Test Mode - No SMS Sent)') }}</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        {{ __('Enable test mode to verify without sending actual SMS.') }}
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle"></i>
                                    <strong>{{ __('Note:') }}</strong>
                                    {{ __('SMS will be sent using Zettatel API via file upload method. Ensure Zettatel credentials are configured in settings.') }}
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="form-group d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" id="btn_cancel">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="btn_send_sms">
                                        <i class="ti ti-send align-text-bottom"></i> {{ __('Send SMS') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            // Character counter
            $('#message').on('input', function() {
                var length = $(this).val().length;
                $('#char_count').text(length);
                if (length > 1600) {
                    $('#char_count').addClass('text-danger');
                } else {
                    $('#char_count').removeClass('text-danger');
                }
            });

            // Select all tenants
            $('#select_all_tenants').on('change', function() {
                $('.tenant-checkbox').prop('checked', $(this).prop('checked'));
            });

            // Uncheck select all if any tenant is unchecked
            $('.tenant-checkbox').on('change', function() {
                if (!$(this).prop('checked')) {
                    $('#select_all_tenants').prop('checked', false);
                }
            });

            // Form submission
            $('#sms_form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var tenantIds = $('.tenant-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (tenantIds.length === 0) {
                    toastrs('Error', '{{ __('Please select at least one tenant.') }}', 'error');
                    return;
                }

                // Add tenant_ids to form data
                formData += '&tenant_ids[]=' + tenantIds.join('&tenant_ids[]=');

                $('#btn_send_sms').attr('disabled', true).html(
                    '<i class="ti ti-loader me-1"></i>{{ __('Sending...') }}');

                $.ajax({
                    url: "{{ route('tenant.send-sms.post') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    type: 'POST',
                    success: function(data) {
                        $('#btn_send_sms').attr('disabled', false).html(
                            '<i class="ti ti-send align-text-bottom"></i> {{ __('Send SMS') }}');
                        if (data.status == "success") {
                            toastrs(data.status, data.msg, data.status);
                            setTimeout(() => {
                                window.location.href = "{{ route('tenant.index') }}";
                            }, 2000);
                        } else {
                            toastrs('Error', data.msg, 'error');
                        }
                    },
                    error: function(data) {
                        $('#btn_send_sms').attr('disabled', false).html(
                            '<i class="ti ti-send align-text-bottom"></i> {{ __('Send SMS') }}');
                        if (data.responseJSON && data.responseJSON.msg) {
                            toastrs('Error', data.responseJSON.msg, 'error');
                        } else {
                            toastrs('Error', '{{ __('An error occurred while sending SMS.') }}', 'error');
                        }
                    },
                });
            });

            // Cancel button
            $('#btn_cancel').on('click', function() {
                window.location.href = "{{ route('tenant.index') }}";
            });

            // Send to all button (optional)
            $(document).on('click', '#btn_send_all', function() {
                if (confirm('{{ __('Are you sure you want to send SMS to all tenants?') }}')) {
                    var formData = $('#sms_form').serialize();
                    formData = formData.replace(/tenant_ids\[\]=/g, '');

                    $('#btn_send_all').attr('disabled', true).html(
                        '<i class="ti ti-loader me-1"></i>{{ __('Sending...') }}');

                    $.ajax({
                        url: "{{ route('tenant.send-sms-all') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        type: 'POST',
                        success: function(data) {
                            $('#btn_send_all').attr('disabled', false).html(
                                '<i class="ti ti-send align-text-bottom"></i> {{ __('Send to All') }}');
                            if (data.status == "success") {
                                toastrs(data.status, data.msg, data.status);
                                setTimeout(() => {
                                    window.location.href = "{{ route('tenant.index') }}";
                                }, 2000);
                            } else {
                                toastrs('Error', data.msg, 'error');
                            }
                        },
                        error: function(data) {
                            $('#btn_send_all').attr('disabled', false).html(
                                '<i class="ti ti-send align-text-bottom"></i> {{ __('Send to All') }}');
                            if (data.responseJSON && data.responseJSON.msg) {
                                toastrs('Error', data.responseJSON.msg, 'error');
                            } else {
                                toastrs('Error', '{{ __('An error occurred while sending SMS.') }}',
                                    'error');
                            }
                        },
                    });
                }
            });
        });
    </script>
@endpush
