
@if (!empty($webinars))
    @foreach ($webinars as $item)
    <div class="row mt-30">
        <div class="col-12">
            <div class="webinar-card webinar-list d-flex">
                <div class="image-box">
                    <img src="{{ $item->getImage() }}" class="img-cover" alt="">
                </div>
                <div class="webinar-card-body w-100 d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="{{ $item->getUrl() }}">
                            <h3 class="webinar-title font-weight-bold font-16 text-dark-blue">
                                {{ $item->title }}
                            </h3>
                        </a>
                        <div class="btn-group dropdown table-actions">
                            <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i data-feather="more-vertical" height="20"></i>
                            </button>
                        </div>
                    </div>

                    @include(getTemplate() . '.includes.webinar.rate',['rate' => $item->getRate()])
                    <div class="webinar-price-box mt-15">
                        @if($item->price > 0)
                            @if($item->bestTicket() < $item->price)
                                <span class="real">{{ handlePrice($item->bestTicket(), true, true, false, null, true) }}</span>
                                <span class="off ml-10">{{ handlePrice($item->price, true, true, false, null, true) }}</span>
                            @else
                                <span class="real">{{ handlePrice($item->price, true, true, false, null, true) }}</span>
                            @endif
                        @else
                            <span class="real">{{ trans('public.free') }}</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-auto">
                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                            <span class="stat-title">{{ trans('public.item_id') }}:</span>
                            <span class="stat-value">{{ $item->id }}</span>
                        </div>
                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                            <span class="stat-title">{{ trans('public.category') }}:</span>
                            <span class="stat-value">{{ !empty($item->category_id) ?$item->category->title : '' }}</span>
                        </div>
                        @if($item->type == 'webinar')
                            @if($item->isProgressing() and !empty($nextSession))
                                <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                    <span class="stat-title">{{ trans('webinars.next_session_duration') }}:</span>
                                    <span class="stat-value">{{ convertMinutesToHourAndMinute($nextSession->duration) }} Hrs</span>
                                </div>

                                <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                    <span class="stat-title">{{ trans('webinars.next_session_start_date') }}:</span>
                                    <span class="stat-value">{{ dateTimeFormat($nextSession->date,'j M Y') }}</span>
                                </div>
                            @else
                                <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                    <span class="stat-title">{{ trans('public.duration') }}:</span>
                                    <span class="stat-value">{{ convertMinutesToHourAndMinute($item->duration) }} Hrs</span>
                                </div>

                                <div class="d-flex align-items-start flex-column mt-20 mr-15">
                                    <span class="stat-title">{{ trans('public.start_date') }}:</span>
                                    <span class="stat-value">{{ dateTimeFormat($item->start_date,'j M Y') }}</span>
                                </div>
                            @endif
                        @endif

                        <div class="d-flex align-items-start flex-column mt-20 mr-15">
                            <span class="stat-title">{{ trans('public.instructor') }}:</span>
                            <span class="stat-value">{{ $item->teacher->full_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    @include(getTemplate() . '.includes.no-result',[
        'file_name' => 'student.png',
        'title' => trans('panel.no_result_purchases') ,
        'hint' => "Please contact your principal to set your targets"
    ])
@endif