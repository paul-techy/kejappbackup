@include('theme.head')

{{-- @dd($settings) --}}

<body class="landing-page"
    data-pc-preset="{{ !empty($settings['color_type']) && $settings['color_type'] == 'custom' ? 'custom' : $settings['accent_color'] }}"
    data-pc-sidebar-theme="light" data-pc-sidebar-caption="{{ $settings['sidebar_caption'] }}"
    data-pc-direction="{{ $settings['theme_layout'] }}" data-pc-theme="{{ $settings['theme_mode'] }}">

    <div class="wrapper ovh">
        <div class="preloader"></div>


        <!-- Main Header Nav -->
        @include('theme.header')


        <div class="hiddenbar-body-ovelay"></div>

        <!-- Mobile Nav  -->
        @include('theme.mobile_nav')



        <div class="body_content">
            @yield('content')

            <a class="scrollToHome" href="#"><i class="fas fa-angle-up"></i></a>
        </div>


    </div>
    <!-- Wrapper End -->


    @include('theme.footer')

    <div class="modal fade" id="customModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="body">
                </div>
            </div>
        </div>
    </div>


</body>

</html>
