@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')

@endpush

@section('content')
    <!--<section style="padding-bottom: 30px">-->
    <!--    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">-->
    <!--        <h1 class="section-title">{{ trans('panel.dashboard') }}</h1>-->
    <!--    </div>-->

    <!--    <h2 class="font-30 text-primary line-height-1">-->
    <!--        <span class="d-block">{{ trans('panel.hi') }} {{ $authUser->full_name }},</span>-->
    <!--    </h2>-->
    <!--</section>-->
    <section>
        <div class="webinar-progress d-block d-lg-flex align-items-center p-15 panel-shadow bg-white rounded-sm mb-20">
            <div class="progress-item d-flex align-items-center">
                <a href="/panel?tab=target" class="progress-icon p-10 d-flex align-items-center justify-content-center rounded-circle {{$query['tab']=='target'?'active':''}}" data-toggle="tooltip" data-placement="top">
                    <img src="/assets/default/img/icons/basic-info.svg" class="img-cover" alt="">
                </a>
                <div class="ml-10">
                    <h4 class="font-16 text-secondary font-weight-bold">Targets</h4>
                </div>
            </div>
            <div class="progress-item d-flex align-items-center">
                <a href="/panel?tab=activity" class="progress-icon p-10 d-flex align-items-center justify-content-center rounded-circle {{$query['tab']=='activity'?'active':''}}" data-toggle="tooltip" data-placement="top">
                    <img src="/assets/default/img/icons/graduate.svg" class="img-cover" alt="">
                </a>
                <div class="ml-10">
                    <h4 class="font-16 text-secondary font-weight-bold">My Activity</h4>
                </div>
            </div>
        </div>
    </section>

    @if (Request::get('tab')=='activity')
        @include('web.default.panel.webinar.my_activity')
    @else
        @include('web.default.panel.webinar.target')
    @endif
@endsection

@push('scripts_bottom')
    <script>
        var undefinedActiveSessionLang = '{{ trans('webinars.undefined_active_session') }}';
    </script>

    <script src="/assets/default/js/panel/join_webinar.min.js"></script>
@endpush
