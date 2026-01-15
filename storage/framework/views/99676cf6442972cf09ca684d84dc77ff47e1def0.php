<?php $__env->startSection('content'); ?>

    <!-- Home Banner Style V1 -->
    <?php
        $Section_0 = App\Models\FrontHomePage::where('section', 'Section 0')->first();
        $Section_0_content_value = !empty($Section_0->content_value)
            ? json_decode($Section_0->content_value, true)
            : [];
    ?>
    <?php if(empty($Section_0_content_value['section_enabled']) || $Section_0_content_value['section_enabled'] == 'active'): ?>
        <section class="hero-home11 ">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-6 col-xl-6 mb30-md">
                        <div class="home11-hero-content">
                            <h2 class="title animate-up-2"> <?php echo e($Section_0_content_value['title']); ?></h2>
                            <p class="text animate-up-3 h4 text-muted mt-3"><?php echo e($Section_0_content_value['sub_title']); ?></p>
                        </div>


                    </div>
                    <div class="col-lg-6">
                        <div class="home11-hero-img text-center text-xxl-end">
                            <img class="bdrs20 ban-img"
                                src="<?php echo e(asset(Storage::url($Section_0_content_value['banner_image1_path']))); ?>"
                                alt="">

                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Need something -->
    <?php
        $Section_1 = App\Models\FrontHomePage::where('section', 'Section 1')->first();
        $Section_1_content_value = !empty($Section_1->content_value)
            ? json_decode($Section_1->content_value, true)
            : [];
    ?>
    <?php if(empty($Section_1_content_value['section_enabled']) || $Section_1_content_value['section_enabled'] == 'active'): ?>
        <section class="our-features pb90">
            <div class="container wow fadeInUp">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title text-center">
                            <h2><?php echo e($Section_1_content_value['Sec1_title']); ?></h2>
                            <p class="text"><?php echo e($Section_1_content_value['Sec1_info']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php
                        $is4_check = 0;
                    ?>
                    <?php for($is4 = 1; $is4 <= 4; $is4++): ?>
                        <?php if(
                            !empty($Section_1_content_value['Sec1_box' . $is4 . '_enabled']) &&
                                $Section_1_content_value['Sec1_box' . $is4 . '_enabled'] == 'active'): ?>
                            <?php $is4_check++; ?> <div class="col-sm-6 col-lg-3">
                                <div class="iconbox-style1 border-less p-0">
                                    <div class="icon before-none">
                                        <img src="<?php echo e(asset(Storage::url($Section_1_content_value['Sec1_box' . $is4 . '_image_path']))); ?>"
                                            alt="img" class="activity-img" />
                                    </div>
                                    <div class="details">
                                        <h4 class="title mt10 mb-3">
                                            <?php echo e($Section_1_content_value['Sec1_box' . $is4 . '_title']); ?></h4>
                                        <p class="text">
                                            <?php echo e($Section_1_content_value['Sec1_box' . $is4 . '_info']); ?> </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>

                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Funfact -->
    <?php
        $Section_2 = App\Models\FrontHomePage::where('section', 'Section 2')->first();
        $Section_2_content_value = !empty($Section_2->content_value)
            ? json_decode($Section_2->content_value, true)
            : [];
    ?>
    <?php if(empty($Section_2_content_value['section_enabled']) || $Section_2_content_value['section_enabled'] == 'active'): ?>
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
                                                    <?php echo e($Section_2_content_value['Box1_number']); ?></div>
                                            </li>
                                        </ul>
                                        <p class="text text-white mb-0">
                                            <?php echo e($Section_2_content_value['Box1_title']); ?></p>
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
                                                    <?php echo e($Section_2_content_value['Box2_number']); ?></div>
                                            </li>
                                        </ul>
                                        <p class="text text-white mb-0">
                                            <?php echo e($Section_2_content_value['Box2_title']); ?></p>
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
                                                    <?php echo e($Section_2_content_value['Box3_number']); ?></div>
                                            </li>
                                        </ul>
                                        <p class="text text-white mb-0">
                                            <?php echo e($Section_2_content_value['Box3_title']); ?></p>
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
                                                    <?php echo e($Section_2_content_value['Box4_number']); ?></div>
                                            </li>
                                        </ul>
                                        <p class="text text-white mb-0">
                                            <?php echo e($Section_2_content_value['Box4_title']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>


    <!-- category -->
    <?php
        $Section_3 = App\Models\FrontHomePage::where('section', 'Section 3')->first();
        $Section_3_content_value = !empty($Section_3->content_value)
            ? json_decode($Section_3->content_value, true)
            : [];
    ?>

    <?php if(empty($Section_3_content_value['section_enabled']) || $Section_3_content_value['section_enabled'] == 'active'): ?>
        <section class="pb80">
            <div class="container">
                <div class="row align-items-center wow fadeInUp" data-wow-delay="300ms">
                    <div class="col-lg-9">
                        <div class="main-title2">
                            <h2 class="title"><?php echo e($Section_3_content_value['Sec3_title']); ?></h2>
                            <p class="paragraph"><?php echo e($Section_3_content_value['Sec3_info']); ?></p>
                        </div>
                    </div>
                    <div class="col-lg-3">

                    </div>
                </div>
                <?php if(isset($allAmenities) && count($allAmenities) > 0): ?>
                    <div class="row">
                        <div class="col-lg-12 wow fadeInUp" data-wow-delay="300ms">
                            <div class="dots_none slider-dib-sm slider-5-grid vam_nav_style owl-theme owl-carousel">
                                <?php $__currentLoopData = $allAmenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!empty($amenity->image) && !empty($amenity->image)): ?>
                                        <?php $image= $amenity->image; ?>
                                    <?php else: ?>
                                        <?php $image= 'default.png'; ?>
                                    <?php endif; ?>
                                    <div class="item">
                                        <div class="feature-style1 mb30 bdrs16">
                                            <div class="feature-img bdrs16 overflow-hidden"><img class="loc-img"
                                                    src="<?php echo e(asset(Storage::url('upload/amenity/')) . '/' . $image); ?>"
                                                    alt=""></div>
                                            <div class="feature-content">
                                                <div class="top-area">
                                                    <h4 class="title mb-1"><?php echo e(ucfirst($amenity->name)); ?></h4>
                                                    <h5 class="text">
                                                        <?php echo e(\Illuminate\Support\Str::limit(strip_tags($amenity->description), 50, '...')); ?>

                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <p class="text-center"><?php echo e(__('No Emenities Available')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- CTA Banner -->
    <?php
        $Section_4 = App\Models\FrontHomePage::where('section', 'Section 4')->first();
        $Section_4_content_value = !empty($Section_4->content_value)
            ? json_decode($Section_4->content_value, true)
            : [];
    ?>
    <?php if(empty($Section_4_content_value['section_enabled']) || $Section_4_content_value['section_enabled'] == 'active'): ?>
        <section class="cta-banner-about2 at-home10-2 mx-auto position-relative pt60-lg pb60-lg">
            <div class="container">
                <div class="row align-items-center wow fadeInDown" data-wow-delay="400ms">
                    <div class="col-lg-7 col-xl-5 offset-xl-1 wow fadeInRight mb60-xs mb100-md">
                        <div class="mb30">
                            <div class="main-title">
                                <h2 class="title"><?php echo e($Section_4_content_value['Sec4_title'] ?? ''); ?>

                                </h2>
                            </div>
                        </div>
                        <div class="why-chose-list">
                            <?php if(!empty($Section_4_content_value['Sec4_Box_title'])): ?>
                                <?php $__currentLoopData = $Section_4_content_value['Sec4_Box_title']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec4_key => $sec4_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="list-one d-flex align-items-start mb30">
                                        <span class="list-icon flex-shrink-0 flaticon-badge"></span>
                                        <div class="list-content flex-grow-1 ml20">
                                            <h4 class="mb-1"><?php echo e($sec4_item ?? ''); ?></h4>
                                            <p class="text mb-0 fz15">
                                                <?php echo e($Section_4_content_value['Sec4_Box_subtitle'][$sec4_key] ?? ''); ?>

                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
            <img class="home10-cta-img bdrs24 d-sm-none d-lg-inline-block"
                src="<?php echo e(asset(Storage::url($Section_4_content_value['about_image_path']))); ?>" alt="">
        </section>
    <?php endif; ?>

    <!-- Popular Services -->
    <?php
        $Section_5 = App\Models\FrontHomePage::where('section', 'Section 5')->first();
        $Section_5_content_value = !empty($Section_5->content_value)
            ? json_decode($Section_5->content_value, true)
            : [];
    ?>
    <?php if(empty($Section_5_content_value['section_enabled']) || $Section_5_content_value['section_enabled'] == 'active'): ?>
        <section class="pb90 pb20-md">
            <div class="container">
                <div class="row align-items-center wow fadeInUp">
                    <div class="col-xl-3">
                        <div class="main-title mb30-lg">
                            <h2 class="title"><?php echo e($Section_5_content_value['Sec5_title']); ?></h2>
                            <p class="paragraph"><?php echo e($Section_5_content_value['Sec5_info']); ?></p>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div class="navpill-style2 at-home6 mb50-lg">
                            <ul class="nav nav-pills mb20 justify-content-xl-end" id="pills-tab" role="tablist">
                                <?php $__currentLoopData = $listingTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link <?php echo e($key == 0 ? 'active' : ''); ?> fw500 dark-color"
                                            id="pills-<?php echo e($type); ?>-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-<?php echo e($type); ?>" type="button" role="tab"
                                            aria-controls="pills-<?php echo e($type); ?>"
                                            aria-selected="<?php echo e($key == 0 ? 'true' : 'false'); ?>">
                                            <?php echo e(ucfirst($type)); ?>

                                        </button>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php if(!empty($propertiesByType)): ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tab-content ha" id="pills-tabContent">
                                <?php $__currentLoopData = $listingTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="tab-pane fade <?php echo e($key == 0 ? 'show active' : ''); ?>"
                                        id="pills-<?php echo e($type); ?>" role="tabpanel"
                                        aria-labelledby="pills-<?php echo e($type); ?>-tab">
                                        <div class="row">
                                            <?php $__empty_1 = true; $__currentLoopData = $propertiesByType[$type]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php if(!empty($property->thumbnail) && !empty($property->thumbnail->image)): ?>
                                                    <?php $thumbnail= $property->thumbnail->image; ?>
                                                <?php else: ?>
                                                    <?php $thumbnail= 'default.jpg'; ?>
                                                <?php endif; ?>
                                                <div class="col-md-6">
                                                    <div
                                                        class="listing-style1 list-style d-block d-xl-flex align-items-center">
                                                        <div class="list-thumb flex-shrink-0">
                                                            <a
                                                                href="<?php echo e(route('property.detail', ['code' => $user->code, \Crypt::encrypt($property->id)])); ?>">
                                                                <img class="package-front-img"
                                                                    src="<?php echo e(asset(Storage::url('upload/property/thumbnail/' . $thumbnail))); ?>"
                                                                    alt="<?php echo e($property->name); ?>">
                                                            </a>
                                                        </div>
                                                        <div class="list-content flex-grow-1 ms-0">
                                                            <p class="list-text body-color fz14 mb-1">
                                                                <a
                                                                    href="<?php echo e(route('property.detail', ['code' => $user->code, \Crypt::encrypt($property->id)])); ?>">
                                                                    <?php echo e(ucfirst($property->name)); ?>

                                                                </a>
                                                            </p>
                                                            <h5 class="list-title">
                                                                <?php echo e(\Illuminate\Support\Str::limit(strip_tags($property->description), 50, '...')); ?>

                                                            </h5>
                                                            <hr class="my-2">

                                                            <div
                                                                class="list-meta d-flex justify-content-between align-items-center mt15">
                                                                <ul class="list-unstyled">
                                                                    <li class="mb-2 d-flex align-items-center">
                                                                        <i class="fas fa-list-ul text-secondary me-2"></i>
                                                                        <strong><?php echo e(__('Type')); ?>: </strong>
                                                                        <?php echo e(\App\Models\Property::$Type[$property->type]); ?>

                                                                    </li>
                                                                    <li class="mb-2 d-flex align-items-center">
                                                                        <i
                                                                            class="fas fa-sort-amount-up text-secondary me-2"></i>
                                                                        <strong><?php echo e(__('Price')); ?>: </strong>
                                                                        <?php echo e(priceformat($property->price)); ?>

                                                                    </li>
                                                                    <li class="mb-2 d-flex align-items-center">
                                                                        <i
                                                                            class="fas fa-address-book text-secondary me-2"></i>
                                                                        <strong><?php echo e(__('Address')); ?>: </strong>
                                                                        <?php echo e($property->address); ?>

                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <p class="text-center"><?php echo e(__('No Properties Available')); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <p class="text-center"><?php echo e(__('No Properties Available')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Banner 2 -->
    <?php
        $Section_6 = App\Models\FrontHomePage::where('section', 'Section 6')->first();
        $Section_6_content_value = !empty($Section_6->content_value)
            ? json_decode($Section_6->content_value, true)
            : [];
    ?>
    <?php if(empty($Section_6_content_value['section_enabled']) || $Section_6_content_value['section_enabled'] == 'active'): ?>
        <section class="home11-cta-3 at-home13">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-6 col-lg-8 wow fadeInLeft">
                        <div class="cta-style3">
                            <h2 class="cta-title"> <?php echo e($Section_6_content_value['Sec6_title']); ?>

                            </h2>
                            <p class="cta-text"><?php echo e($Section_6_content_value['Sec6_info']); ?></p>
                            <a href="<?php echo e($Section_6_content_value['sec6_btn_link']); ?>"
                                class="ud-btn btn-dark default-box-shadow1"><?php echo e($Section_6_content_value['sec6_btn_name']); ?>

                                <i class="fal fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 wow fadeIn">
                        <img class="home11-ctaimg-v3 d-none d-md-block"
                            src="<?php echo e(asset(Storage::url($Section_6_content_value['banner_image2_path']))); ?>"
                            alt="">
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!--  Testimonials -->
    <?php
        $Section_7 = App\Models\FrontHomePage::where('section', 'Section 7')->first();
        $Section_7_content_value = !empty($Section_7->content_value)
            ? json_decode($Section_7->content_value, true)
            : [];
    ?>
    <?php if(empty($Section_7_content_value['section_enabled']) || $Section_7_content_value['section_enabled'] == 'active'): ?>
        <?php
            $testimonials = [];
            foreach ($Section_7_content_value as $key => $value) {
                if (Str::startsWith($key, 'Sec7_box') && Str::endsWith($key, '_Enabled') && $value === 'active') {
                    $boxNumber = str_replace(['Sec7_box', '_Enabled'], '', $key);
                    $testimonials[] = $boxNumber;
                }
            }
        ?>

        <section class="our-testimonial">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInUp" data-wow-delay="300ms">
                        <div class="main-title text-center">
                            <h2><?php echo e($Section_7_content_value['Sec7_title'] ?? ''); ?></h2>
                            <p class="paragraph"><?php echo e($Section_7_content_value['Sec7_info'] ?? ''); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 m-auto wow fadeInUp" data-wow-delay="500ms">
                        <div class="testimonial-style2">

                            
                            <div class="tab-content" id="pills-tabContent">
                                <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $num): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="tab-pane fade <?php echo e($index === 0 ? 'show active' : ''); ?>"
                                        id="testimonial-<?php echo e($num); ?>" role="tabpanel"
                                        aria-labelledby="testimonial-<?php echo e($num); ?>-tab">
                                        <div class="testi-content text-md-center">
                                            <span class="icon fas fa-quote-left"></span>
                                            <h4 class="testi-text">
                                                <?php echo e($Section_7_content_value["Sec7_box{$num}_review"] ?? ''); ?>

                                            </h4>
                                            <h6 class="name">
                                                <?php echo e($Section_7_content_value["Sec7_box{$num}_name"] ?? ''); ?>

                                            </h6>
                                            <p class="design">
                                                <?php echo e($Section_7_content_value["Sec7_box{$num}_tag"] ?? ''); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            
                            <div class="tab-list">
                                <ul class="nav nav-pills justify-content-center gap-2" id="testimonial-tab"
                                    role="tablist">
                                    <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $num): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $imagePath = $Section_7_content_value["Sec7_box{$num}_image_path"] ?? '';
                                        ?>
                                        <li class="nav-item" role="presentation">
                                            <button
                                                class="nav-link p-1 rounded-circle border <?php echo e($index === 0 ? 'active' : ''); ?>"
                                                id="testimonial-<?php echo e($num); ?>-tab" data-bs-toggle="pill"
                                                data-bs-target="#testimonial-<?php echo e($num); ?>" type="button"
                                                role="tab" aria-controls="testimonial-<?php echo e($num); ?>"
                                                aria-selected="<?php echo e($index === 0 ? 'true' : 'false'); ?>">
                                                <img src="<?php echo e(!empty($imagePath) ? asset(Storage::url($imagePath)) : asset('images/default-avatar.png')); ?>"
                                                    alt="testimonial <?php echo e($num); ?>" class="rounded-circle"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            </button>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>







<?php $__env->stopSection(); ?>


<?php $__env->startPush('script-page'); ?>
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
                        url: "<?php echo e(route('search.location', $user->code)); ?>",
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('theme.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/paulawit/malipos.co.ke/resources/views/theme/index.blade.php ENDPATH**/ ?>