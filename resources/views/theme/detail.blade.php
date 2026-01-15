@extends('theme.main')
@push('css-page')
    <style>
        .carousel-item {
            height: 400px;
            width: 720px;
            overflow: hidden;
            border-radius: 10px;
        }
    </style>
@endpush
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
                <div class="container">
                    <div class="row wow fadeInUp">
                        <div class="col-xl-7">
                            <div class="position-relative">
                                <h2 class="text-dark">{{ $Section_3_content_value['sec3_title'] }}</h2>
                                <p class="text mb30 text-dark">{{ $Section_3_content_value['sec3_sub_title'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    <sections class="our-blog pt40">
        <div class="container mt-5">
            <div class="wow fadeInUp" data-wow-delay="300ms">
                <div class="row property-page mt-3">
                    <div class="col-sm-12">
                        <div class="card border">
                            <div class="card-body">
                                <div class="row d-flex justify-content-between align-items-center mb-3">
                                    <div class="col">
                                        <h3 class="form-title mb-3"><a href="#" class="text-secondary">
                                                {{ ucfirst($property->name) }}</a></h3>
                                    </div>
                                    <div class="col text-end">

                                        <p class="list-text body-color fz16 mb-1"><span class="badge bg-light-secondary">
                                                {{ \App\Models\Property::$Type[$property->type] }}</span></p>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class=" product-sticky">
                                            <div id="carouselExampleCaptions" class="carousel slide carousel-fade"
                                                data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    @foreach ($property->propertyImages as $key => $image)
                                                        @php
                                                            $img = !empty($image->image)
                                                                ? $image->image
                                                                : 'default.jpg';
                                                        @endphp
                                                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                                            <img src="{{ asset(Storage::url('upload/property/image/') . $img) }}"
                                                                class="d-block w-100 rounded" alt="Package image" />
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <ol
                                                    class="carousel-indicators position-relative product-carousel-indicators my-sm-3 mx-0">
                                                    @foreach ($property->propertyImages as $key => $image)
                                                        @php
                                                            $img = !empty($image->image)
                                                                ? $image->image
                                                                : 'default.jpg';
                                                        @endphp
                                                        <li data-bs-target="#carouselExampleCaptions"
                                                            data-bs-slide-to="{{ $key }}"
                                                            class="{{ $key === 0 ? 'active' : '' }} w-25 h-auto">
                                                            <img src="{{ asset(Storage::url('upload/property/image/') . $img) }}"
                                                                class="d-block wid-100 rounded" alt="Package image" />
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="default-box-shadow1 bdrs8 bdr1 p-4 mb-4 bgc-white">
                                            <div class="row align-items-center mb-3">
                                                <div class="col">
                                                    <h4 class="form-title mb-0">{{ __('Property Detail') }}</h4>
                                                </div>
                                                <div class="col-auto">

                                                    @if (!empty($property->price) && $property->listing_type == 'rent')
                                                        {{ __('Rent Price') }} :
                                                        <span class="fw-semibold fs-20 text-primary">
                                                            {{ priceformat($property->price) }}/ Monthly
                                                        </span>
                                                    @else
                                                        {{ __('Sell Price') }}:
                                                        <span class="fw-semibold fs-20 text-primary">
                                                            {{ priceformat($property->price) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="property-description">
                                                {!! $property->description !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="mb-3">{{ __('Included Amenities') }}:</h4>
                                                <hr class="my-3" />
                                                @if ($selectedAmenities->count())
                                                    <div class="row">
                                                        @foreach ($selectedAmenities as $amenity)
                                                            <div class="col-md-2 col-xl-2 mb-4">
                                                                <div
                                                                    class="border rounded p-2 shadow-sm h-100 position-relative d-flex align-items-center">
                                                                    <i class="ti ti-circle-check text-success fs-5 position-absolute"
                                                                        style="top: 10px; right: 10px;"></i>

                                                                    @if ($amenity->image)
                                                                        <img src="{{ fetch_file('upload/amenity/' . $amenity->image) }}"
                                                                            alt="{{ $amenity->name }}"
                                                                            class="rounded shadow-sm me-2"
                                                                            style="width: 100px; height: 60px; object-fit: cover;">
                                                                    @endif

                                                                    <h6 class="mb-0 text-start">{{ $amenity->name }}</h6>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-muted">{{ __('No amenities selected.') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="mb-3">{{ __('Advantages') }}:</h4>
                                                <hr class="my-3" />
                                                @if ($selectedAdvantages->count())
                                                    <div class="row">
                                                        <ul class="list-unstyled">
                                                            @foreach ($selectedAdvantages as $advantage)
                                                                <li class="mb-2">
                                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                                    {{ $advantage->name }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @else
                                                    <p class="text-muted">{{ __('No advantages selected.') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="mb-3">{{ __('Address') }}:</h4>
                                                <hr class="my-3" />
                                                <div class="row">
                                                    <ul class="list-unstyled">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        {{ $property->address }}, {{ $property->city }},
                                                        {{ $property->state }}, {{ $property->country }} -
                                                        {{ $property->zip_code }}
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>


                                        @if($units->isNotEmpty())
                                            <div class="row mt-3">
                                                <h3 class="mb-2">{{ __('Property Unit') }}:</h3>
                                                <hr class="my-3" />
                                                @foreach ($units as $unit)
                                                    <div class="col-xxl-3 col-xl-4 col-md-6">
                                                        <div class="card follower-card ">
                                                            <div class="card-body p-3">
                                                                <div class="d-flex align-items-start mb-3">
                                                                    <div class="flex-grow-1 ">
                                                                        <h2 class="mb-1 text-truncate">
                                                                            {{ ucfirst($unit->name) }}</h2>
                                                                    </div>

                                                                </div>
                                                                <hr class="my-3" />
                                                                <ul class="list-unstyled mb-0">
                                                                    <li class="mb-1">
                                                                        <strong>{{ __('Bedroom') }}:</strong>
                                                                        <span
                                                                            class="text-muted">{{ $unit->bedroom }}</span>
                                                                    </li>
                                                                    <li class="mb-1">
                                                                        <strong>{{ __('Kitchen') }}:</strong>
                                                                        <span
                                                                            class="text-muted">{{ $unit->kitchen }}</span>
                                                                    </li>
                                                                    <li class="mb-1">
                                                                        <strong>{{ __('Bath') }}:</strong>
                                                                        <span
                                                                            class="text-muted">{{ $unit->baths }}</span>
                                                                    </li>

                                                                    @if ($property->listing_type == 'rent')
                                                                        <li class="mb-1">
                                                                            <strong>{{ __('Rent Type') }}:</strong>
                                                                            <span
                                                                                class="text-muted">{{ $unit->rent_type }}</span>
                                                                        </li>
                                                                        <li class="mb-1">
                                                                            <strong>{{ __('Rent') }}:</strong>
                                                                            <span
                                                                                class="text-muted">{{ priceFormat($unit->rent) }}</span>
                                                                        </li>
                                                                    @endif
                                                                </ul>


                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </sections>
@endsection


@push('script-page')
@endpush
