@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/apexcharts/apexcharts.css"/>
@endpush

@section('content')
    <section class="">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h1 class="section-title">{{ trans('panel.dashboard') }}</h1>
        </div>

        <h2 class="font-30 text-primary line-height-1">
            <span class="d-block">{{ trans('panel.hi') }} {{ $authUser->full_name }},</span>
        </h2>
    </section>

    <section class="dashboard">
        <div class="row">
            <div class="col-12 col-lg-4 mt-35">
                <div class="bg-white account-balance rounded-sm panel-shadow py-15 py-md-30 px-10 px-md-20">
                    <div class="text-center">
                        <img src="/assets/default/img/activity/36.svg" class="account-balance-icon" alt="">
                        <h3 class="font-16 font-weight-500 text-gray mt-25">{{ trans('panel.account_balance') }}</h3>
                        <span class="mt-5 d-block font-30 text-secondary">{{ handlePrice($authUser->getAccountingBalance()) }}</span>
                    </div>

                    @php
                        $getFinancialSettings = getFinancialSettings();
                        $drawable = $authUser->getPayout();
                        $can_drawable = ($drawable > ((!empty($getFinancialSettings) and !empty($getFinancialSettings['minimum_payout'])) ? (int)$getFinancialSettings['minimum_payout'] : 0))
                    @endphp

                    <div class="mt-20 pt-30 border-top border-gray300 d-flex align-items-center @if($can_drawable) justify-content-between @else justify-content-center @endif">
                        @if($can_drawable)
                            <span class="font-16 font-weight-500 text-gray">{{ trans('panel.with_drawable') }}:</span>
                            <span class="font-16 font-weight-bold text-secondary">{{ handlePrice($drawable) }}</span>
                        @else
                            <a href="/panel/financial/account" class="font-16 font-weight-bold text-dark-blue">{{ trans('financial.charge_account') }}</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mt-35">
                <a href="@if($authUser->isUser()) /panel/webinars/purchases @else /panel/meetings/requests @endif" class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center">
                    <div class="stat-icon requests">
                        <img src="/assets/default/img/icons/request.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span class="font-30 text-secondary">{{ !empty($pendingAppointments) ? $pendingAppointments : (!empty($webinarsCount) ? $webinarsCount : 0) }}</span>
                        <span class="font-16 text-gray font-weight-500">{{ $authUser->isUser() ? trans('panel.purchased_courses') : trans('panel.pending_appointments') }}</span>
                    </div>
                </a>

                <a href="@if($authUser->isUser()) /panel/meetings/reservation @else /panel/financial/sales @endif" class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center mt-15 mt-md-30">
                    <div class="stat-icon monthly-sales">
                        <img src="@if($authUser->isUser()) /assets/default/img/icons/meeting.svg @else /assets/default/img/icons/monay.svg @endif" alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span class="font-30 text-secondary">{{ !empty($monthlySalesCount) ? handlePrice($monthlySalesCount) : (!empty($reserveMeetingsCount) ? $reserveMeetingsCount : 0) }}</span>
                        <span class="font-16 text-gray font-weight-500">{{ $authUser->isUser() ? trans('panel.meetings') : trans('panel.monthly_sales') }}</span>
                    </div>
                </a>
            </div>

            <div class="col-12 col-lg-4 mt-35">
                <a href="/panel/support" class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center">
                    <div class="stat-icon support-messages">
                        <img src="/assets/default/img/icons/support.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span class="font-30 text-secondary">{{ !empty($supportsCount) ? $supportsCount : 0 }}</span>
                        <span class="font-16 text-gray font-weight-500">{{ trans('panel.support_messages') }}</span>
                    </div>
                </a>

                <a href="@if($authUser->isUser()) /panel/webinars/my-comments @else /panel/webinars/comments @endif" class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center mt-15 mt-md-30">
                    <div class="stat-icon comments">
                        <img src="/assets/default/img/icons/comment.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span class="font-30 text-secondary">{{ !empty($commentsCount) ? $commentsCount : 0 }}</span>
                        <span class="font-16 text-gray font-weight-500">{{ trans('panel.comments') }}</span>
                    </div>
                </a>
            </div>
        </div>

        
    </section>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>

    <script>
        var offlineSuccess = '{{ trans('panel.offline_success') }}';
        var $chartDataMonths = @json($monthlyChart['months']);
        var $chartData = @json($monthlyChart['data']);
    </script>

    <script src="/assets/default/js/panel/dashboard.min.js"></script>
@endpush

@if(!empty($giftModal))
    @push('scripts_bottom2')
        <script>
            (function () {
                "use strict";

                handleLimitedAccountModal('{!! $giftModal !!}', 40)
            })(jQuery)
        </script>
    @endpush
@endif
