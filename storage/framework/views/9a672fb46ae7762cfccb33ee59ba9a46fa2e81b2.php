



    <!-- Footer -->
    <?php
        $Section_8 = App\Models\FrontHomePage::where('section', 'Section 8')->first();
        $Section_8_content_value = !empty($Section_8->content_value)
            ? json_decode($Section_8->content_value, true)
            : [];
    ?>
    <?php if(empty($Section_8_content_value['section_enabled']) || $Section_8_content_value['section_enabled'] == 'active'): ?>
        <section class="footer-style1 pt25 pb-0">

            <div class="container white-bdrt1 py-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="text-center text-lg-start">
                            <p class="copyright-text mb-2 mb-md-0 text-white-light ff-heading">
                                <?php echo e($Section_8_content_value['Sec8_info']); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="social-widget text-center text-md-end">
                            <div class="social-style1">
                                <a class="text-white me-2 fw500 fz17" href=""><?php echo e(__('Follow us')); ?></a>
                                <a href="<?php echo e($Section_8_content_value['fb_link']); ?>"><i
                                        class="fab fa-facebook-f list-inline-item"></i></a>
                                <a href="<?php echo e($Section_8_content_value['twitter_link']); ?>"><i
                                        class="fab fa-twitter list-inline-item"></i></a>
                                <a href="<?php echo e($Section_8_content_value['insta_link']); ?>"><i
                                        class="fab fa-instagram list-inline-item"></i></a>
                                <a href="<?php echo e($Section_8_content_value['linkedin_link']); ?>"><i
                                        class="fab fa-linkedin-in list-inline-item"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

<script src="<?php echo e(asset('js/jquery.js')); ?>"></script>
<script src="<?php echo e(asset('assets/web/js/jquery-migrate-3.5.2.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/web/js/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/web/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/web/js/bootstrap-select.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/web/js/jquery.mmenu.all.js')); ?>"></script>
<script src="<?php echo e(asset('assets/web/js/ace-responsive-menu.js')); ?>"></script>
<script src="<?php echo e(asset('assets/web/js/jquery-scrolltofixed-min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/web/js/wow.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/web/js/isotop.js')); ?>"></script>

<script src="<?php echo e(asset('assets/web/js/owl.js')); ?>"></script>

<script src="<?php echo e(asset('assets/js/plugins/feather.min.js')); ?>"></script>

<script src="<?php echo e(asset('assets/js/plugins/notifier.js')); ?>"></script>
<script src="<?php echo e(asset('assets/web/js/script.js')); ?>"></script>
<?php echo $__env->yieldPushContent('script-page'); ?>

<script>
    var successImg='<?php echo e(asset("assets/images/notification/ok-48.png")); ?>';
    var errorImg='<?php echo e(asset("assets/images/notification/high_priority-48.png")); ?>';
</script>
<script src="<?php echo e(asset('js/custom.js')); ?>"></script>
<?php if($statusMessage = Session::get('success')): ?>
    <script>
        notifier.show('Success!', '<?php echo $statusMessage; ?>', 'success',
        successImg, 4000);
    </script>
<?php endif; ?>
<?php if($statusMessage = Session::get('error')): ?>
    <script>
         notifier.show('Error!', '<?php echo $statusMessage; ?>', 'error',
         errorImg, 4000);
    </script>
<?php endif; ?>

<?php /**PATH /home/paulawit/malipos.co.ke/resources/views/theme/footer.blade.php ENDPATH**/ ?>