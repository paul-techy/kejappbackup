<div id="page" class="mobilie_header_nav stylehome1">
    <div class="mobile-menu">
        <div class="header bdrb1">
            <div class="menu_and_widgets">
                <div class="mobile_menu_bar d-flex justify-content-between align-items-center">
                    <a class="mobile_logo" href="#">
                        <img src="{{ asset(Storage::url('upload/logo/')) . '/' . (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png') }}"
                            alt="" class="img-fluid" style="width: 200px;">
                    </a>



                    <div class="right-side text-end">
                        {{-- <a class=""
                            href="{{ route('login.home', ['code' => $user->code]) }}">{{ __('Login') }}</a> --}}
                        <a class="menubar ml30" href="#menu"><img
                                src="{{ asset('assets/web/images/mobile-dark-nav-icon.svg') }}" alt=""></a>

                    </div>
                </div>
            </div>
            <div class="posr">
                <div class="mobile_menu_close_btn"><span class="far fa-times"></span></div>
            </div>
        </div>
    </div>
    <!-- /.mobile-menu -->
    <nav id="menu" class="">
        <ul>

            <li><a href="{{ route('web.page', $user->code) }}">{{ __('Home') }}</a>

            </li>

            <li><a href="{{ route('property.home', ['code' => $user->code]) }}">{{ __('Properties') }}</a>

            </li>
            <li><a href="{{ route('blog.home', ['code' => $user->code]) }}">{{ __('Blog') }}</a>

            </li>
              <li><a href="{{ route('contact.home', ['code' => $user->code]) }}">{{ __('Contact') }}</a>

            </li>
            <!-- Only for Mobile View -->
        </ul>
    </nav>
</div>
