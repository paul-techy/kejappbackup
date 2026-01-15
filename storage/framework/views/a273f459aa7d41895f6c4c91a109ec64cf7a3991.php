<?php echo $__env->make('theme.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



<body class="landing-page"
    data-pc-preset="<?php echo e(!empty($settings['color_type']) && $settings['color_type'] == 'custom' ? 'custom' : $settings['accent_color']); ?>"
    data-pc-sidebar-theme="light" data-pc-sidebar-caption="<?php echo e($settings['sidebar_caption']); ?>"
    data-pc-direction="<?php echo e($settings['theme_layout']); ?>" data-pc-theme="<?php echo e($settings['theme_mode']); ?>">

    <div class="wrapper ovh">
        <div class="preloader"></div>


        <!-- Main Header Nav -->
        <?php echo $__env->make('theme.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


        <div class="hiddenbar-body-ovelay"></div>

        <!-- Mobile Nav  -->
        <?php echo $__env->make('theme.mobile_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



        <div class="body_content">
            <?php echo $__env->yieldContent('content'); ?>

            <a class="scrollToHome" href="#"><i class="fas fa-angle-up"></i></a>
        </div>


    </div>
    <!-- Wrapper End -->


    <?php echo $__env->make('theme.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
<?php /**PATH /home/paulawit/malipos.co.ke/resources/views/theme/main.blade.php ENDPATH**/ ?>