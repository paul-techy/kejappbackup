<?php
    $routeName = \Request::route()->getName();
    $admin_logo = getSettingsValByName('company_logo');
?>



<header class="header-nav nav-homepage-style at-home3 stricky main-menu border-0 ">
    <!-- Ace Responsive Menu -->
    <nav class="posr">
        <div class="container posr">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto px-0 px-xl-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="logos">
                            <a class="header-logo logo1 landing-logo" href="#">
                                <img src="<?php echo e(asset(Storage::url('upload/logo/')) . '/' . (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png')); ?>" class="img-fluid"
                                    style="width: 240px;" alt="Header Logo">
                            </a>
                            <a class="header-logo logo2 landing-logo" href="#">
                                <img src="<?php echo e(asset(Storage::url('upload/logo/')) . '/' . (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png')); ?>" class="img-fluid"
                                    alt="Header Logo">
                            </a>

                        </div>
                    </div>
                </div>


                <div class="col-auto pe-0 pe-xl-3">
                    <div class="d-flex align-items-center">
                        <ul id="respMenu" class="ace-responsive-menu" data-menu-style="horizontal">

                            <li>
                                <a class="list-item mr5 <?php echo e(in_array($routeName, ['web.page']) ? 'active custom-active-style' : ''); ?>"
                                    href="<?php echo e(route('web.page', $user->code)); ?>"><?php echo e(__('Home')); ?></a>
                            </li>

                            <li>
                                <a class="list-item mr5 <?php echo e(in_array($routeName, ['property.home','property.detail']) ? 'active custom-active-style' : ''); ?>"
                                    href="<?php echo e(route('property.home', ['code' => $user->code])); ?>"><?php echo e(__('Properties')); ?></a>
                            </li>
                            <li>
                                <a class="list-item mr5 <?php echo e(in_array($routeName, ['blog.home','blog.detail']) ? 'active custom-active-style' : ''); ?>"
                                    href="<?php echo e(route('blog.home', ['code' => $user->code])); ?>"><?php echo e(__('Blog')); ?></a>
                            </li>
                            <li>
                                <a class="list-item mr5 <?php echo e(in_array($routeName, ['contact.home']) ? 'active custom-active-style' : ''); ?>"
                                    href="<?php echo e(route('contact.home', ['code' => $user->code])); ?>"><?php echo e(__('Contact')); ?></a>
                            </li>
                        </ul>

                    </div>
                </div>

            </div>
        </div>
    </nav>
</header>
<?php /**PATH /home/paulawit/malipos.co.ke/resources/views/theme/header.blade.php ENDPATH**/ ?>