<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/apexcharts/apexcharts.css"/>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h1 class="section-title"><?php echo e(trans('panel.dashboard')); ?></h1>
        </div>

        <h2 class="font-30 text-primary line-height-1">
            <span class="d-block"><?php echo e(trans('panel.hi')); ?> <?php echo e($authUser->full_name); ?>,</span>
        </h2>
    </section>

    <section class="dashboard">
        <div class="row">
            <div class="col-12 col-lg-4 mt-35">
                <div class="bg-white account-balance rounded-sm panel-shadow py-15 py-md-30 px-10 px-md-20">
                    <div class="text-center">
                        <img src="/assets/default/img/activity/36.svg" class="account-balance-icon" alt="">
                        <h3 class="font-16 font-weight-500 text-gray mt-25"><?php echo e(trans('panel.account_balance')); ?></h3>
                        <span class="mt-5 d-block font-30 text-secondary"><?php echo e(handlePrice($authUser->getAccountingBalance())); ?></span>
                    </div>

                    <?php
                        $getFinancialSettings = getFinancialSettings();
                        $drawable = $authUser->getPayout();
                        $can_drawable = ($drawable > ((!empty($getFinancialSettings) and !empty($getFinancialSettings['minimum_payout'])) ? (int)$getFinancialSettings['minimum_payout'] : 0))
                    ?>

                    <div class="mt-20 pt-30 border-top border-gray300 d-flex align-items-center <?php if($can_drawable): ?> justify-content-between <?php else: ?> justify-content-center <?php endif; ?>">
                        <?php if($can_drawable): ?>
                            <span class="font-16 font-weight-500 text-gray"><?php echo e(trans('panel.with_drawable')); ?>:</span>
                            <span class="font-16 font-weight-bold text-secondary"><?php echo e(handlePrice($drawable)); ?></span>
                        <?php else: ?>
                            <a href="/panel/financial/account" class="font-16 font-weight-bold text-dark-blue"><?php echo e(trans('financial.charge_account')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mt-35">
                <a href="<?php if($authUser->isUser()): ?> /panel/webinars/purchases <?php else: ?> /panel/meetings/requests <?php endif; ?>" class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center">
                    <div class="stat-icon requests">
                        <img src="/assets/default/img/icons/request.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span class="font-30 text-secondary"><?php echo e(!empty($pendingAppointments) ? $pendingAppointments : (!empty($webinarsCount) ? $webinarsCount : 0)); ?></span>
                        <span class="font-16 text-gray font-weight-500"><?php echo e($authUser->isUser() ? trans('panel.purchased_courses') : trans('panel.pending_appointments')); ?></span>
                    </div>
                </a>

                <a href="<?php if($authUser->isUser()): ?> /panel/meetings/reservation <?php else: ?> /panel/financial/sales <?php endif; ?>" class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center mt-15 mt-md-30">
                    <div class="stat-icon monthly-sales">
                        <img src="<?php if($authUser->isUser()): ?> /assets/default/img/icons/meeting.svg <?php else: ?> /assets/default/img/icons/monay.svg <?php endif; ?>" alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span class="font-30 text-secondary"><?php echo e(!empty($monthlySalesCount) ? handlePrice($monthlySalesCount) : (!empty($reserveMeetingsCount) ? $reserveMeetingsCount : 0)); ?></span>
                        <span class="font-16 text-gray font-weight-500"><?php echo e($authUser->isUser() ? trans('panel.meetings') : trans('panel.monthly_sales')); ?></span>
                    </div>
                </a>
            </div>

            <div class="col-12 col-lg-4 mt-35">
                <a href="/panel/support" class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center">
                    <div class="stat-icon support-messages">
                        <img src="/assets/default/img/icons/support.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span class="font-30 text-secondary"><?php echo e(!empty($supportsCount) ? $supportsCount : 0); ?></span>
                        <span class="font-16 text-gray font-weight-500"><?php echo e(trans('panel.support_messages')); ?></span>
                    </div>
                </a>

                <a href="<?php if($authUser->isUser()): ?> /panel/webinars/my-comments <?php else: ?> /panel/webinars/comments <?php endif; ?>" class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center mt-15 mt-md-30">
                    <div class="stat-icon comments">
                        <img src="/assets/default/img/icons/comment.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span class="font-30 text-secondary"><?php echo e(!empty($commentsCount) ? $commentsCount : 0); ?></span>
                        <span class="font-16 text-gray font-weight-500"><?php echo e(trans('panel.comments')); ?></span>
                    </div>
                </a>
            </div>
        </div>

        
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>

    <script>
        var offlineSuccess = '<?php echo e(trans('panel.offline_success')); ?>';
        var $chartDataMonths = <?php echo json_encode($monthlyChart['months'], 15, 512) ?>;
        var $chartData = <?php echo json_encode($monthlyChart['data'], 15, 512) ?>;
    </script>

    <script src="/assets/default/js/panel/dashboard.min.js"></script>
<?php $__env->stopPush(); ?>

<?php if(!empty($giftModal)): ?>
    <?php $__env->startPush('scripts_bottom2'); ?>
        <script>
            (function () {
                "use strict";

                handleLimitedAccountModal('<?php echo $giftModal; ?>', 40)
            })(jQuery)
        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php echo $__env->make(getTemplate() .'.panel.layouts.panel_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\fuads\OneDrive\Desktop\academy.mhis.link\resources\views/web/default/panel/dashboard/index.blade.php ENDPATH**/ ?>