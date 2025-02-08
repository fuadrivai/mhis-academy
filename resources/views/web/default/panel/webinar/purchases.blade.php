@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')

@endpush

@section('content')
    <section>
        <div class="webinar-progress d-block d-lg-flex align-items-center p-15 panel-shadow bg-white rounded-sm mb-20">

            <div class="progress-item d-flex align-items-center">
                @if ($query['tab']!=null)
                    <a href="/panel?tab=target" class="progress-icon p-10 d-flex align-items-center justify-content-center rounded-circle {{$query['tab']=='target'?'active':''}}" data-toggle="tooltip" data-placement="top">
                        <img src="/assets/default/img/icons/basic-info.svg" class="img-cover" alt="">
                    </a>
                @else
                    <a href="/panel?tab=target" class="progress-icon p-10 d-flex align-items-center justify-content-center rounded-circle active" data-toggle="tooltip" data-placement="top">
                        <img src="/assets/default/img/icons/basic-info.svg" class="img-cover" alt="">
                    </a>
                @endif
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
