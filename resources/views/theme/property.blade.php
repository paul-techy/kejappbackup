@extends('theme.main')
@section('content')
    <section class="our-blog pt-0">
        <div class="container">
            <div class="row">

            </div>
        </div>
    </section>

    @php
        $Section_3 = App\Models\Additional::where('section', 'Section 3')->first();
        $Section_3_content_value = !empty($Section_3->content_value)
            ? json_decode($Section_3->content_value, true)
            : [];
    @endphp
    @if (empty($Section_3_content_value['section_enabled']) || $Section_3_content_value['section_enabled'] == 'active')
        <section class="breadcumb-section pt-0">
            <div class="cta-service-v6 cta-banner mx-auto maxw1700 pt120 pt60-sm pb120 pb60-sm bdrs16 position-relative d-flex align-items-center"
                style="background-image: url('{{ asset(Storage::url($Section_3_content_value['sec3_banner_image_path'])) }}'); background-position: bottom;">
                @php
                    $userId = $user->id;
                    $countries = \App\Models\Property::where('parent_id', $userId)->distinct()->pluck('country');
                @endphp

                <div class="container">
    <div class="row wow fadeInUp">
        <div class="col-xl-12">
            <div class="position-relative">
                <h2 class="text-dark">{{ $Section_3_content_value['sec3_title'] }}</h2>
                <p class="text mb30 text-dark">{{ $Section_3_content_value['sec3_sub_title'] }}</p>

                {{ Form::open(['route' => ['search.filter', 'code' => $user->code], 'method' => 'GET', 'id' => 'package_filter']) }}
                <div class="advance-search-tab bgc-white p10 bdrs4">
                    <div class="row g-2 align-items-end">
                        {{-- Country Dropdown --}}
                        <div class="col-md-3">
                            <div class="bselect-style1">
                                <label for="country" class="form-label">{{ __('Select Country') }}</label>
                                <select class="form-select" name="country" id="country">
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country }}">{{ $country }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- State Dropdown --}}
                        <div class="col-md-3">
                            <div class="bselect-style1">
                                <label for="state" class="form-label">{{ __('Select State') }}</label>
                                <select class="form-select" name="state" id="state">
                                    <option value="">{{ __('Select State') }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- City Dropdown --}}
                        <div class="col-md-3">
                            <div class="bselect-style1">
                                <label for="city" class="form-label">{{ __('Select City') }}</label>
                                <select class="form-select" name="city" id="city">
                                    <option value="">{{ __('Select City') }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-md-2">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="submit" class="ud-btn btn-thm2 w-100" id="search_button">
                                <i class="fas fa-search me-1"></i> {{ __('Search') }}
                            </button>
                        </div>

                         <div class="col-md-1">
                            <label class="form-label d-block">&nbsp;</label>
                           <a href="{{ route('search.filter', ['code' => $user->code]) }}"
                            class="ud-btn btn-thm3 w-100 d-flex align-items-center justify-content-center" id="reset_button">
                                <i class="fas fa-rotate-left me-1"></i> {{ __('Reset') }}
                            </a>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>


            </div>
        </section>
    @endif
    <section class="our-blog pt40">
        <div class="container">
            <div class="wow fadeInUp" data-wow-delay="300ms">
                <div class="row mb20">
                    <div class="text-center">
                        <p class="h4">{{ __('Find Your Perfect Property') }}</p>
                    </div>

                </div>
                <div class="row" id="package-wrapper">
                    @include('theme.propertybox')
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script-page')
    <script>

        $(document).ready(function() {

            // Pagination via AJAX
            $(document).on('click', '.mbp_pagination .page-link', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        $('#package-wrapper').html(
                            '<div class="text-center py-5">Loading...</div>');
                    },
                    success: function(data) {
                        $('#package-wrapper').html(data);
                        window.history.pushState(null, null, url);
                    },
                    error: function() {
                        alert('Something went wrong.');
                    }
                });
            });
        });




    </script>

    <script>
        $(document).ready(function () {

            // Country -> State
            $('#country').on('change', function () {
                var country = $(this).val();
                $('#state').html('<option>Loading...</option>');
                $('#city').html('<option value="">Select City</option>'); // Reset city

                $.ajax({
                    url: "{{ route('get-states', $user->code) }}",
                    type: 'GET',
                    data: { country: country },
                    success: function (res) {
                        $('#state').empty().append('<option value="">Select State</option>');
                        $.each(res, function (index, value) {
                            $('#state').append('<option value="' + value + '">' + value + '</option>');
                        });
                    },
                    error: function () {
                        alert('Failed to load states.');
                    }
                });
            });

            // State -> City
            $('#state').on('change', function () {
                var state = $(this).val();
                $('#city').html('<option>Loading...</option>');

                $.ajax({
                     url: "{{ route('get-cities', $user->code) }}",
                    type: 'GET',
                    data: { state: state },
                    success: function (res) {
                        $('#city').empty().append('<option value="">Select City</option>');
                        $.each(res, function (index, value) {
                            $('#city').append('<option value="' + value + '">' + value + '</option>');
                        });
                    },
                    error: function () {
                        alert('Failed to load cities.');
                    }
                });
            });

            $('#reset_button').on('click', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        $('#package-wrapper').html('<div class="text-center py-5">Loading...</div>');
                    },
                    success: function(data) {
                        $('#package-wrapper').html(data);
                        window.history.pushState(null, null, url);
                        // Optionally reset dropdowns
                        $('#country, #state, #city').val('');
                    },
                    error: function() {
                        alert('Failed to reset.');
                    }
                });
            });


        });

    </script>
@endpush
