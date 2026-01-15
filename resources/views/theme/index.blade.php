@extends('theme.main')
@section('content')

    <!-- Home Banner Style V1 -->
    @php
        $Section_0 = App\Models\FrontHomePage::where('section', 'Section 0')->first();
        $Section_0_content_value = !empty($Section_0->content_value)
            ? json_decode($Section_0->content_value, true)
            : [];
    @endphp
    @if (empty($Section_0_content_value['section_enabled']) || $Section_0_content_value['section_enabled'] == 'active')
        <section class="hero-home11 ">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-6 col-xl-6 mb30-md">
                        <div class="home11-hero-content">
                            <h2 class="title animate-up-2"> {{ $Section_0_content_value['title'] }}</h2>
                            <p class="text animate-up-3 h4 text-muted mt-3">{{ $Section_0_content_value['sub_title'] }}</p>
                        </div>


                    </div>
                    <div class="col-lg-6">
                        <div class="home11-hero-img text-center text-xxl-end">
                            <img class="bdrs20 ban-img"
                                src="{{ asset(Storage::url($Section_0_content_value['banner_image1_path'])) }}"
                                alt="">

                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Need something -->
    @php
        $Section_1 = App\Models\FrontHomePage::where('section', 'Section 1')->first();
        $Section_1_content_value = !empty($Section_1->content_value)
            ? json_decode($Section_1->content_value, true)
            : [];
    @endphp
    @if (empty($Section_1_content_value['section_enabled']) || $Section_1_content_value['section_enabled'] == 'active')
        <section class="our-features pb90">
            <div class="container wow fadeInUp">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title text-center">
                            <h2>{{ $Section_1_content_value['Sec1_title'] }}</h2>
                            <p class="text">{{ $Section_1_content_value['Sec1_info'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @php
                        $is4_check = 0;
                    @endphp
                    @for ($is4 = 1; $is4 <= 4; $is4++)
                        @if (
                            !empty($Section_1_content_value['Sec1_box' . $is4 . '_enabled']) &&
                                $Section_1_content_value['Sec1_box' . $is4 . '_enabled'] == 'active')
                            @php $is4_check++; @endphp <div class="col-sm-6 col-lg-3">
                                <div class="iconbox-style1 border-less p-0">
                                    <div class="icon before-none">
                                        <img src="{{ asset(Storage::url($Section_1_content_value['Sec1_box' . $is4 . '_image_path'])) }}"
                                            alt="img" class="activity-img" />
                                    </div>
                                    <div class="details">
                                        <h4 class="title mt10 mb-3">
                                            {{ $Section_1_content_value['Sec1_box' . $is4 . '_title'] }}</h4>
                                        <p class="text">
                                            {{ $Section_1_content_value['Sec1_box' . $is4 . '_info'] }} </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endfor

                </div>
            </div>
        </section>
    @endif

    <!-- Funfact -->
    @php
        $Section_2 = App\Models\FrontHomePage::where('section', 'Section 2')->first();
        $Section_2_content_value = !empty($Section_2->content_value)
            ? json_decode($Section_2->content_value, true)
            : [];
    @endphp
    @if (empty($Section_2_content_value['section_enabled']) || $Section_2_content_value['section_enabled'] == 'active')
        <section class="home11-funfact bdrs12 mx-auto maxw1700">
            <div class="container">
                <div class="row">
                    <div class="col-xl-10 mx-auto">
                        <div class="row justify-content-center wow fadeInUp" data-wow-delay="300ms">
                            <div class="col-6 col-md-3">
                                <div class="funfact_one mb20-sm text-center">
                                    <span class="icon text-white flaticon-working"></span>
                                    <div class="details">
                                        <ul class="ps-0 mb-1 d-flex justify-content-center">
                                            <li>
                                                <div class="timer text-white">
                                                    {{ $Section_2_content_value['Box1_number'] }}</div>
                                            </li>
                                        </ul>
                                        <p class="text text-white mb-0">
                                            {{ $Section_2_content_value['Box1_title'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="funfact_one mb20-sm text-center">
                                    <span class="icon text-white flaticon-star-2"></span>
                                    <div class="details">
                                        <ul class="ps-0 mb-1 d-flex justify-content-center">
                                            <li>
                                                <div class="timer text-white">
                                                    {{ $Section_2_content_value['Box2_number'] }}</div>
                                            </li>
                                        </ul>
                                        <p class="text text-white mb-0">
                                            {{ $Section_2_content_value['Box2_title'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="funfact_one mb20-sm text-center">
                                    <span class="icon text-white flaticon-file"></span>
                                    <div class="details">
                                        <ul class="ps-0 mb-1 d-flex justify-content-center">
                                            <li>
                                                <div class="timer text-white">
                                                    {{ $Section_2_content_value['Box3_number'] }}</div>
                                            </li>
                                        </ul>
                                        <p class="text text-white mb-0">
                                            {{ $Section_2_content_value['Box3_title'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="funfact_one mb20-sm text-center">
                                    <span class="icon text-white flaticon-rocket-1"></span>
                                    <div class="details">
                                        <ul class="ps-0 mb-1 d-flex justify-content-center">
                                            <li>
                                                <div class="timer text-white">
                                                    {{ $Section_2_content_value['Box4_number'] }}</div>
                                            </li>
                                        </ul>
                                        <p class="text text-white mb-0">
                                            {{ $Section_2_content_value['Box4_title'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    <!-- category -->
    @php
        $Section_3 = App\Models\FrontHomePage::where('section', 'Section 3')->first();
        $Section_3_content_value = !empty($Section_3->content_value)
            ? json_decode($Section_3->content_value, true)
            : [];
    @endphp

    @if (empty($Section_3_content_value['section_enabled']) || $Section_3_content_value['section_enabled'] == 'active')
        <section class="pb80">
            <div class="container">
                <div class="row align-items-center wow fadeInUp" data-wow-delay="300ms">
                    <div class="col-lg-9">
                        <div class="main-title2">
                            <h2 class="title">{{ $Section_3_content_value['Sec3_title'] }}</h2>
                            <p class="paragraph">{{ $Section_3_content_value['Sec3_info'] }}</p>
                        </div>
                    </div>
                    <div class="col-lg-3">

                    </div>
                </div>
                @if (isset($allAmenities) && count($allAmenities) > 0)
                    <div class="row">
                        <div class="col-lg-12 wow fadeInUp" data-wow-delay="300ms">
                            <div class="dots_none slider-dib-sm slider-5-grid vam_nav_style owl-theme owl-carousel">
                                @foreach ($allAmenities as $amenity)
                                    @if (!empty($amenity->image) && !empty($amenity->image))
                                        @php $image= $amenity->image; @endphp
                                    @else
                                        @php $image= 'default.png'; @endphp
                                    @endif
                                    <div class="item">
                                        <div class="feature-style1 mb30 bdrs16">
                                            <div class="feature-img bdrs16 overflow-hidden"><img class="loc-img"
                                                    src="{{ asset(Storage::url('upload/amenity/')) . '/' . $image }}"
                                                    alt=""></div>
                                            <div class="feature-content">
                                                <div class="top-area">
                                                    <h4 class="title mb-1">{{ ucfirst($amenity->name) }}</h4>
                                                    <h5 class="text">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($amenity->description), 50, '...') }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <p class="text-center">{{ __('No Emenities Available') }}</p>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <!-- CTA Banner -->
    @php
        $Section_4 = App\Models\FrontHomePage::where('section', 'Section 4')->first();
        $Section_4_content_value = !empty($Section_4->content_value)
            ? json_decode($Section_4->content_value, true)
            : [];
    @endphp
    @if (empty($Section_4_content_value['section_enabled']) || $Section_4_content_value['section_enabled'] == 'active')
        <section class="cta-banner-about2 at-home10-2 mx-auto position-relative pt60-lg pb60-lg">
            <div class="container">
                <div class="row align-items-center wow fadeInDown" data-wow-delay="400ms">
                    <div class="col-lg-7 col-xl-5 offset-xl-1 wow fadeInRight mb60-xs mb100-md">
                        <div class="mb30">
                            <div class="main-title">
                                <h2 class="title">{{ $Section_4_content_value['Sec4_title'] ?? '' }}
                                </h2>
                            </div>
                        </div>
                        <div class="why-chose-list">
                            @if (!empty($Section_4_content_value['Sec4_Box_title']))
                                @foreach ($Section_4_content_value['Sec4_Box_title'] as $sec4_key => $sec4_item)
                                    <div class="list-one d-flex align-items-start mb30">
                                        <span class="list-icon flex-shrink-0 flaticon-badge"></span>
                                        <div class="list-content flex-grow-1 ml20">
                                            <h4 class="mb-1">{{ $sec4_item ?? '' }}</h4>
                                            <p class="text mb-0 fz15">
                                                {{ $Section_4_content_value['Sec4_Box_subtitle'][$sec4_key] ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            <img class="home10-cta-img bdrs24 d-sm-none d-lg-inline-block"
                src="{{ asset(Storage::url($Section_4_content_value['about_image_path'])) }}" alt="">
        </section>
    @endif

    <!-- Popular Services -->
    @php
        $Section_5 = App\Models\FrontHomePage::where('section', 'Section 5')->first();
        $Section_5_content_value = !empty($Section_5->content_value)
            ? json_decode($Section_5->content_value, true)
            : [];
    @endphp
    @if (empty($Section_5_content_value['section_enabled']) || $Section_5_content_value['section_enabled'] == 'active')
        <section class="pb90 pb20-md">
            <div class="container">
                <div class="row align-items-center wow fadeInUp">
                    <div class="col-xl-3">
                        <div class="main-title mb30-lg">
                            <h2 class="title">{{ $Section_5_content_value['Sec5_title'] }}</h2>
                            <p class="paragraph">{{ $Section_5_content_value['Sec5_info'] }}</p>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div class="navpill-style2 at-home6 mb50-lg">
                            <ul class="nav nav-pills mb20 justify-content-xl-end" id="pills-tab" role="tablist">
                                @foreach ($listingTypes as $key => $type)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $key == 0 ? 'active' : '' }} fw500 dark-color"
                                            id="pills-{{ $type }}-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-{{ $type }}" type="button" role="tab"
                                            aria-controls="pills-{{ $type }}"
                                            aria-selected="{{ $key == 0 ? 'true' : 'false' }}">
                                            {{ ucfirst($type) }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @if (!empty($propertiesByType))
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tab-content ha" id="pills-tabContent">
                                @foreach ($listingTypes as $key => $type)
                                    <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}"
                                        id="pills-{{ $type }}" role="tabpanel"
                                        aria-labelledby="pills-{{ $type }}-tab">
                                        <div class="row">
                                            @forelse ($propertiesByType[$type] as $property)
                                                @if (!empty($property->thumbnail) && !empty($property->thumbnail->image))
                                                    @php $thumbnail= $property->thumbnail->image; @endphp
                                                @else
                                                    @php $thumbnail= 'default.jpg'; @endphp
                                                @endif
                                                <div class="col-md-6">
                                                    <div
                                                        class="listing-style1 list-style d-block d-xl-flex align-items-center">
                                                        <div class="list-thumb flex-shrink-0">
                                                            <a
                                                                href="{{ route('property.detail', ['code' => $user->code, \Crypt::encrypt($property->id)]) }}">
                                                                <img class="package-front-img"
                                                                    src="{{ asset(Storage::url('upload/property/thumbnail/' . $thumbnail)) }}"
                                                                    alt="{{ $property->name }}">
                                                            </a>
                                                        </div>
                                                        <div class="list-content flex-grow-1 ms-0">
                                                            <p class="list-text body-color fz14 mb-1">
                                                                <a
                                                                    href="{{ route('property.detail', ['code' => $user->code, \Crypt::encrypt($property->id)]) }}">
                                                                    {{ ucfirst($property->name) }}
                                                                </a>
                                                            </p>
                                                            <h5 class="list-title">
                                                                {{ \Illuminate\Support\Str::limit(strip_tags($property->description), 50, '...') }}
                                                            </h5>
                                                            <hr class="my-2">

                                                            <div
                                                                class="list-meta d-flex justify-content-between align-items-center mt15">
                                                                <ul class="list-unstyled">
                                                                    <li class="mb-2 d-flex align-items-center">
                                                                        <i class="fas fa-list-ul text-secondary me-2"></i>
                                                                        <strong>{{ __('Type') }}: </strong>
                                                                        {{ \App\Models\Property::$Type[$property->type] }}
                                                                    </li>
                                                                    <li class="mb-2 d-flex align-items-center">
                                                                        <i
                                                                            class="fas fa-sort-amount-up text-secondary me-2"></i>
                                                                        <strong>{{ __('Price') }}: </strong>
                                                                        {{ priceformat($property->price) }}
                                                                    </li>
                                                                    <li class="mb-2 d-flex align-items-center">
                                                                        <i
                                                                            class="fas fa-address-book text-secondary me-2"></i>
                                                                        <strong>{{ __('Address') }}: </strong>
                                                                        {{ $property->address }}
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-center">{{ __('No Properties Available') }}</p>
                                            @endforelse
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <p class="text-center">{{ __('No Properties Available') }}</p>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <!-- Banner 2 -->
    @php
        $Section_6 = App\Models\FrontHomePage::where('section', 'Section 6')->first();
        $Section_6_content_value = !empty($Section_6->content_value)
            ? json_decode($Section_6->content_value, true)
            : [];
    @endphp
    @if (empty($Section_6_content_value['section_enabled']) || $Section_6_content_value['section_enabled'] == 'active')
        <section class="home11-cta-3 at-home13">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-6 col-lg-8 wow fadeInLeft">
                        <div class="cta-style3">
                            <h2 class="cta-title"> {{ $Section_6_content_value['Sec6_title'] }}
                            </h2>
                            <p class="cta-text">{{ $Section_6_content_value['Sec6_info'] }}</p>
                            <a href="{{ $Section_6_content_value['sec6_btn_link'] }}"
                                class="ud-btn btn-dark default-box-shadow1">{{ $Section_6_content_value['sec6_btn_name'] }}
                                <i class="fal fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 wow fadeIn">
                        <img class="home11-ctaimg-v3 d-none d-md-block"
                            src="{{ asset(Storage::url($Section_6_content_value['banner_image2_path'])) }}"
                            alt="">
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!--  Testimonials -->
    @php
        $Section_7 = App\Models\FrontHomePage::where('section', 'Section 7')->first();
        $Section_7_content_value = !empty($Section_7->content_value)
            ? json_decode($Section_7->content_value, true)
            : [];
    @endphp
    @if (empty($Section_7_content_value['section_enabled']) || $Section_7_content_value['section_enabled'] == 'active')
        @php
            $testimonials = [];
            foreach ($Section_7_content_value as $key => $value) {
                if (Str::startsWith($key, 'Sec7_box') && Str::endsWith($key, '_Enabled') && $value === 'active') {
                    $boxNumber = str_replace(['Sec7_box', '_Enabled'], '', $key);
                    $testimonials[] = $boxNumber;
                }
            }
        @endphp

        <section class="our-testimonial">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInUp" data-wow-delay="300ms">
                        <div class="main-title text-center">
                            <h2>{{ $Section_7_content_value['Sec7_title'] ?? '' }}</h2>
                            <p class="paragraph">{{ $Section_7_content_value['Sec7_info'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 m-auto wow fadeInUp" data-wow-delay="500ms">
                        <div class="testimonial-style2">

                            {{-- Testimonial Content --}}
                            <div class="tab-content" id="pills-tabContent">
                                @foreach ($testimonials as $index => $num)
                                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                        id="testimonial-{{ $num }}" role="tabpanel"
                                        aria-labelledby="testimonial-{{ $num }}-tab">
                                        <div class="testi-content text-md-center">
                                            <span class="icon fas fa-quote-left"></span>
                                            <h4 class="testi-text">
                                                {{ $Section_7_content_value["Sec7_box{$num}_review"] ?? '' }}
                                            </h4>
                                            <h6 class="name">
                                                {{ $Section_7_content_value["Sec7_box{$num}_name"] ?? '' }}
                                            </h6>
                                            <p class="design">
                                                {{ $Section_7_content_value["Sec7_box{$num}_tag"] ?? '' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Navigation Thumbnails --}}
                            <div class="tab-list">
                                <ul class="nav nav-pills justify-content-center gap-2" id="testimonial-tab"
                                    role="tablist">
                                    @foreach ($testimonials as $index => $num)
                                        @php
                                            $imagePath = $Section_7_content_value["Sec7_box{$num}_image_path"] ?? '';
                                        @endphp
                                        <li class="nav-item" role="presentation">
                                            <button
                                                class="nav-link p-1 rounded-circle border {{ $index === 0 ? 'active' : '' }}"
                                                id="testimonial-{{ $num }}-tab" data-bs-toggle="pill"
                                                data-bs-target="#testimonial-{{ $num }}" type="button"
                                                role="tab" aria-controls="testimonial-{{ $num }}"
                                                aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                                <img src="{{ !empty($imagePath) ? asset(Storage::url($imagePath)) : asset('images/default-avatar.png') }}"
                                                    alt="testimonial {{ $num }}" class="rounded-circle"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif







@endsection


@push('script-page')
    <script>
        $(document).ready(function() {
            $('#search_button').on('click', function(e) {
                if (!$('#location-id').val()) {
                    e.preventDefault(); // stop form
                    alert('Please select a location from suggestions.');
                }
            });

            // Typing and suggestions (same as before)
            $('#search-query').on('keyup', function() {
                let query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: "{{ route('search.location', $user->code) }}",
                        type: 'GET',
                        data: {
                            query: query
                        },
                        success: function(response) {
                            $('#search-results').html(response.html).show();
                        }
                    });
                } else {
                    $('#search-results').hide();
                }
            });

            // Selecting suggestion
            $(document).on('click', '.suggestion-item', function() {
                let title = $(this).data('title');

                let slug = $(this).data('slug');

                $('#search-query').val(title); // show name
                $('#location-id').val(slug);
                $('#search-results').hide();
            });
        });
    </script>
@endpush
