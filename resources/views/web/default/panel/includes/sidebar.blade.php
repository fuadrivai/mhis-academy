@php
    $getPanelSidebarSettings = getPanelSidebarSettings();
@endphp

<div class="xs-panel-nav d-flex d-lg-none justify-content-between py-5 px-15">
    <div class="user-info d-flex align-items-center justify-content-between">
        <div class="user-avatar bg-gray200">
            <img src="{{ $authUser->getAvatar(100) }}" class="img-cover" alt="{{ $authUser->full_name }}">
        </div>
        <div class="user-name ml-15">
            <h3 class="font-16 font-weight-bold">{{ $authUser->full_name }}</h3>
        </div>
    </div>

    <button class="sidebar-toggler btn-transparent d-flex flex-column-reverse justify-content-center align-items-center p-5 rounded-sm sidebarNavToggle" type="button">
        <span>{{ trans('navbar.menu') }}</span>
        <i data-feather="menu" width="16" height="16"></i>
    </button>
</div>

<div class="panel-sidebar pt-50 pb-25 px-25" id="panelSidebar">
    <button class="btn-transparent panel-sidebar-close sidebarNavToggle">
        <i data-feather="x" width="24" height="24"></i>
    </button>

    <div class="user-info d-flex align-items-center flex-row flex-lg-column justify-content-lg-center">
        <a href="/panel" class="user-avatar bg-gray200">
            <img src="{{ $authUser->getAvatar(100) }}" class="img-cover" alt="{{ $authUser->full_name }}">
        </a>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <a href="/panel" class="user-name mt-15">
                <h3 class="font-16 font-weight-bold text-center">{{ $authUser->full_name }}</h3>
            </a>

            @if(!empty($authUser->getUserGroup()))
                <span class="create-new-user mt-10">{{ $authUser->getUserGroup()->name }}</span>
            @endif
        </div>
    </div>

    <div class="row pb-lg-20 mt-15 mt-lg-30">
        <div class="col-12 text-center">
            <div class="d-flex flex-column ">
                <strong class="text-center">{{ $authUser->webinars()->count() }}</strong>
                <span class="font-12">{{ trans('panel.classes') }}</span>
            </div>
        </div>
    </div>

    <ul style="height: calc(100% - 250px) !important;" id="panel-sidebar-scroll" class="sidebar-menu pt-10 @if(!empty($authUser->userGroup)) has-user-group @endif @if(empty($getPanelSidebarSettings) or empty($getPanelSidebarSettings['background'])) without-bottom-image @endif" @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>

        <li class="sidenav-item {{ (request()->is('panel')) ? 'sidenav-item-active' : '' }}">
            <a href="/panel" class="d-flex align-items-center">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.dashboard')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('panel.dashboard') }}</span>
            </a>
        </li>

        @if($authUser->isOrganization())
            <li class="sidenav-item {{ (request()->is('panel/instructors') or request()->is('panel/manage/instructors*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#instructorsCollapse" role="button" aria-expanded="false" aria-controls="instructorsCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.teachers')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('public.instructors') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/instructors') or request()->is('panel/manage/instructors*')) ? 'show' : '' }}" id="instructorsCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/instructors/new')) ? 'active' : '' }}">
                            <a href="/panel/manage/instructors/new">{{ trans('public.new') }}</a>
                        </li>
                        <li class="mt-5 {{ (request()->is('panel/manage/instructors')) ? 'active' : '' }}">
                            <a href="/panel/manage/instructors">{{ trans('public.list') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="sidenav-item {{ (request()->is('panel/students') or request()->is('panel/manage/students*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#studentsCollapse" role="button" aria-expanded="false" aria-controls="studentsCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.students')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('quiz.students') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/students') or request()->is('panel/manage/students*')) ? 'show' : '' }}" id="studentsCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/manage/students/new')) ? 'active' : '' }}">
                            <a href="/panel/manage/students/new">{{ trans('public.new') }}</a>
                        </li>
                        <li class="mt-5 {{ (request()->is('panel/manage/students')) ? 'active' : '' }}">
                            <a href="/panel/manage/students">{{ trans('public.list') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        <li class="sidenav-item {{ (request()->is('panel/webinars') or request()->is('panel/webinars/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#webinarCollapse" role="button" aria-expanded="false" aria-controls="webinarCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.webinars')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('panel.webinars') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/webinars') or request()->is('panel/webinars/*')) ? 'show' : '' }}" id="webinarCollapse">
                <ul class="sidenav-item-collapse">
                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ (request()->is('panel/webinars/new')) ? 'active' : '' }}">
                            <a href="/panel/webinars/new">{{ trans('public.new') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/webinars')) ? 'active' : '' }}">
                            <a href="/panel/webinars">{{ trans('panel.my_classes') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/webinars/invitations')) ? 'active' : '' }}">
                            <a href="/panel/webinars/invitations">{{ trans('panel.invited_classes') }}</a>
                        </li>
                    @endif

                    @if(!empty($authUser->organ_id))
                        <li class="mt-5 {{ (request()->is('panel/webinars/organization_classes')) ? 'active' : '' }}">
                            <a href="/panel/webinars/organization_classes">{{ trans('panel.organization_classes') }}</a>
                        </li>
                    @endif

                    {{-- <li class="mt-5 {{ (request()->is('panel/webinars/purchases')) ? 'active' : '' }}">
                        <a href="/panel/webinars/purchases">{{ trans('panel.my_purchases') }}</a>
                    </li> --}}

                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ (request()->is('panel/webinars/comments')) ? 'active' : '' }}">
                            <a href="/panel/webinars/comments">{{ trans('panel.my_class_comments') }}</a>
                        </li>
                    @endif

                    <li class="mt-5 {{ (request()->is('panel/webinars/my-comments')) ? 'active' : '' }}">
                        <a href="/panel/webinars/my-comments">{{ trans('panel.my_comments') }}</a>
                    </li>

                    <li class="mt-5 {{ (request()->is('panel/webinars/favorites')) ? 'active' : '' }}">
                        <a href="/panel/webinars/favorites">{{ trans('panel.favorites') }}</a>
                    </li>
                </ul>
            </div>
        </li>

        @if(!empty(getFeaturesSettings('upcoming_courses_status')))
            <li class="sidenav-item {{ (request()->is('panel/upcoming_courses') or request()->is('panel/upcoming_courses/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#upcomingCoursesCollapse" role="button" aria-expanded="false" aria-controls="upcomingCoursesCollapse">
                <span class="sidenav-item-icon mr-10">
                    <i data-feather="film" class="img-cover"></i>
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('update.upcoming_courses') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/upcoming_courses') or request()->is('panel/upcoming_courses/*')) ? 'show' : '' }}" id="upcomingCoursesCollapse">
                    <ul class="sidenav-item-collapse">
                        @if($authUser->isOrganization() || $authUser->isTeacher())
                            <li class="mt-5 {{ (request()->is('panel/upcoming_courses/new')) ? 'active' : '' }}">
                                <a href="/panel/upcoming_courses/new">{{ trans('public.new') }}</a>
                            </li>

                            <li class="mt-5 {{ (request()->is('panel/upcoming_courses')) ? 'active' : '' }}">
                                <a href="/panel/upcoming_courses">{{ trans('update.my_upcoming_courses') }}</a>
                            </li>
                        @endif

                        <li class="mt-5 {{ (request()->is('panel/upcoming_courses/followings')) ? 'active' : '' }}">
                            <a href="/panel/upcoming_courses/followings">{{ trans('update.following_courses') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if($authUser->isOrganization() or $authUser->isTeacher())
            <li class="sidenav-item {{ (request()->is('panel/bundles') or request()->is('panel/bundles/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#bundlesCollapse" role="button" aria-expanded="false" aria-controls="bundlesCollapse">
                <span class="sidenav-item-icon assign-fill mr-10">
                    @include('web.default.panel.includes.sidebar_icons.bundles')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('update.bundles') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/bundles') or request()->is('panel/bundles/*')) ? 'show' : '' }}" id="bundlesCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/bundles/new')) ? 'active' : '' }}">
                            <a href="/panel/bundles/new">{{ trans('public.new') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/bundles')) ? 'active' : '' }}">
                            <a href="/panel/bundles">{{ trans('update.my_bundles') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if(!$authUser->isUser())
            <li class="sidenav-item {{ (request()->is('panel/stages*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#stagesCollapse" role="button" aria-expanded="false" aria-controls="stagesCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.teachers')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">Stages</span>
                </a>
                <div class="collapse {{ (request()->is('panel/stages') or request()->is('panel/stages*')) ? 'show' : '' }}" id="stagesCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/stages/teachers')) ? 'active' : '' }}">
                            <a href="/panel/stages/teacher">Teachers</a>
                        </li>
                        <li class="mt-5 {{ (request()->is('panel/stages/webinar')) ? 'active' : '' }}">
                            <a href="/panel/stages/webinar">Courses</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if(getFeaturesSettings('webinar_assignment_status'))
            <li class="sidenav-item {{ (request()->is('panel/assignments') or request()->is('panel/assignments/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#assignmentCollapse" role="button" aria-expanded="false" aria-controls="assignmentCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.assignments')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('update.assignments') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/assignments') or request()->is('panel/assignments/*')) ? 'show' : '' }}" id="assignmentCollapse">
                    <ul class="sidenav-item-collapse">

                        <li class="mt-5 {{ (request()->is('panel/assignments/my-assignments')) ? 'active' : '' }}">
                            <a href="/panel/assignments/my-assignments">{{ trans('update.my_assignments') }}</a>
                        </li>

                        @if($authUser->isOrganization() || $authUser->isTeacher())
                            <li class="mt-5 {{ (request()->is('panel/assignments/my-courses-assignments')) ? 'active' : '' }}">
                                <a href="/panel/assignments/my-courses-assignments">{{ trans('update.students_assignments') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif

        <li class="sidenav-item {{ (request()->is('panel/quizzes') or request()->is('panel/quizzes/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#quizzesCollapse" role="button" aria-expanded="false" aria-controls="quizzesCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.quizzes')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('panel.quizzes') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/quizzes') or request()->is('panel/quizzes/*')) ? 'show' : '' }}" id="quizzesCollapse">
                <ul class="sidenav-item-collapse">
                    @if($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ (request()->is('panel/quizzes/new')) ? 'active' : '' }}">
                            <a href="/panel/quizzes/new">{{ trans('quiz.new_quiz') }}</a>
                        </li>
                        <li class="mt-5 {{ (request()->is('panel/quizzes')) ? 'active' : '' }}">
                            <a href="/panel/quizzes">{{ trans('public.list') }}</a>
                        </li>
                        <li class="mt-5 {{ (request()->is('panel/quizzes/results')) ? 'active' : '' }}">
                            <a href="/panel/quizzes/results">{{ trans('public.results') }}</a>
                        </li>
                    @endif

                    <li class="mt-5 {{ (request()->is('panel/quizzes/my-results')) ? 'active' : '' }}">
                        <a href="/panel/quizzes/my-results">{{ trans('public.my_results') }}</a>
                    </li>

                    <li class="mt-5 {{ (request()->is('panel/quizzes/opens')) ? 'active' : '' }}">
                        <a href="/panel/quizzes/opens">{{ trans('public.not_participated') }}</a>
                    </li>
                </ul>
            </div>
        </li>

        @if($authUser->checkCanAccessToStore())
            <li class="sidenav-item {{ (request()->is('panel/store') or request()->is('panel/store/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#storeCollapse" role="button" aria-expanded="false" aria-controls="storeCollapse">
                <span class="sidenav-item-icon assign-fill mr-10">
                    @include('web.default.panel.includes.sidebar_icons.store')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('update.store') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/store') or request()->is('panel/store/*')) ? 'show' : '' }}" id="storeCollapse">
                    <ul class="sidenav-item-collapse">
                        @if($authUser->isOrganization() || $authUser->isTeacher())
                            <li class="mt-5 {{ (request()->is('panel/store/products/new')) ? 'active' : '' }}">
                                <a href="/panel/store/products/new">{{ trans('update.new_product') }}</a>
                            </li>

                            <li class="mt-5 {{ (request()->is('panel/store/products')) ? 'active' : '' }}">
                                <a href="/panel/store/products">{{ trans('update.products') }}</a>
                            </li>

                            @php
                                $sellerProductOrderWaitingDeliveryCount = $authUser->getWaitingDeliveryProductOrdersCount();
                            @endphp

                            <li class="mt-5 {{ (request()->is('panel/store/sales')) ? 'active' : '' }}">
                                <a href="/panel/store/sales">{{ trans('panel.sales') }}</a>

                                @if($sellerProductOrderWaitingDeliveryCount > 0)
                                    <span class="d-inline-flex align-items-center justify-content-center font-weight-500 ml-15 panel-sidebar-store-sales-circle-badge">{{ $sellerProductOrderWaitingDeliveryCount }}</span>
                                @endif
                            </li>

                        @endif

                        <li class="mt-5 {{ (request()->is('panel/store/purchases')) ? 'active' : '' }}">
                            <a href="/panel/store/purchases">{{ trans('panel.my_purchases') }}</a>
                        </li>

                        @if($authUser->isOrganization() || $authUser->isTeacher())
                            <li class="mt-5 {{ (request()->is('panel/store/products/comments')) ? 'active' : '' }}">
                                <a href="/panel/store/products/comments">{{ trans('update.product_comments') }}</a>
                            </li>
                        @endif

                        <li class="mt-5 {{ (request()->is('panel/store/products/my-comments')) ? 'active' : '' }}">
                            <a href="/panel/store/products/my-comments">{{ trans('panel.my_comments') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if(!$authUser->isUser() or (!empty($referralSettings) and $referralSettings['status'] and $authUser->affiliate) or (!empty(getRegistrationBonusSettings('status')) and $authUser->enable_registration_bonus))
            <li class="sidenav-item {{ (request()->is('panel/marketing') or request()->is('panel/marketing/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#marketingCollapse" role="button" aria-expanded="false" aria-controls="marketingCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.marketing')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('panel.marketing') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/marketing') or request()->is('panel/marketing/*')) ? 'show' : '' }}" id="marketingCollapse">
                    <ul class="sidenav-item-collapse">
                        @if(!$authUser->isUser())
                            <li class="mt-5 {{ (request()->is('panel/marketing/special_offers')) ? 'active' : '' }}">
                                <a href="/panel/marketing/special_offers">{{ trans('panel.discounts') }}</a>
                            </li>
                            <li class="mt-5 {{ (request()->is('panel/marketing/promotions')) ? 'active' : '' }}">
                                <a href="/panel/marketing/promotions">{{ trans('panel.promotions') }}</a>
                            </li>
                        @endif

                        @if(!empty($referralSettings) and $referralSettings['status'] and $authUser->affiliate)
                            <li class="mt-5 {{ (request()->is('panel/marketing/affiliates')) ? 'active' : '' }}">
                                <a href="/panel/marketing/affiliates">{{ trans('panel.affiliates') }}</a>
                            </li>
                        @endif

                        @if(!empty(getRegistrationBonusSettings('status')) and $authUser->enable_registration_bonus)
                            <li class="mt-5 {{ (request()->is('panel/marketing/registration_bonus')) ? 'active' : '' }}">
                                <a href="/panel/marketing/registration_bonus">{{ trans('update.registration_bonus') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif

        @if(getFeaturesSettings('forums_status'))
            <li class="sidenav-item {{ (request()->is('panel/forums') or request()->is('panel/forums/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#forumsCollapse" role="button" aria-expanded="false" aria-controls="forumsCollapse">
                <span class="sidenav-item-icon assign-fill mr-10">
                    @include('web.default.panel.includes.sidebar_icons.forums')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('update.forums') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/forums') or request()->is('panel/forums/*')) ? 'show' : '' }}" id="forumsCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('/forums/create-topic')) ? 'active' : '' }}">
                            <a href="/forums/create-topic">{{ trans('update.new_topic') }}</a>
                        </li>
                        <li class="mt-5 {{ (request()->is('panel/forums/topics')) ? 'active' : '' }}">
                            <a href="/panel/forums/topics">{{ trans('update.my_topics') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/forums/posts')) ? 'active' : '' }}">
                            <a href="/panel/forums/posts">{{ trans('update.my_posts') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/forums/bookmarks')) ? 'active' : '' }}">
                            <a href="/panel/forums/bookmarks">{{ trans('update.bookmarks') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif


        @if($authUser->isTeacher())
            <li class="sidenav-item {{ (request()->is('panel/blog') or request()->is('panel/blog/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#blogCollapse" role="button" aria-expanded="false" aria-controls="blogCollapse">
                <span class="sidenav-item-icon assign-fill mr-10">
                    @include('web.default.panel.includes.sidebar_icons.blog')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('update.articles') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/blog') or request()->is('panel/blog/*')) ? 'show' : '' }}" id="blogCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/blog/posts/new')) ? 'active' : '' }}">
                            <a href="/panel/blog/posts/new">{{ trans('update.new_article') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/blog/posts')) ? 'active' : '' }}">
                            <a href="/panel/blog/posts">{{ trans('update.my_articles') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/blog/comments')) ? 'active' : '' }}">
                            <a href="/panel/blog/comments">{{ trans('panel.comments') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if($authUser->isOrganization() || $authUser->isTeacher())
            <li class="sidenav-item {{ (request()->is('panel/noticeboard*') or request()->is('panel/course-noticeboard*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#noticeboardCollapse" role="button" aria-expanded="false" aria-controls="noticeboardCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.noticeboard')
                </span>

                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('panel.noticeboard') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/noticeboard*') or request()->is('panel/course-noticeboard*')) ? 'show' : '' }}" id="noticeboardCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/noticeboard')) ? 'active' : '' }}">
                            <a href="/panel/noticeboard">{{ trans('public.history') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/noticeboard/new')) ? 'active' : '' }}">
                            <a href="/panel/noticeboard/new">{{ trans('public.new') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/course-noticeboard')) ? 'active' : '' }}">
                            <a href="/panel/course-noticeboard">{{ trans('update.course_notices') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/course-noticeboard/new')) ? 'active' : '' }}">
                            <a href="/panel/course-noticeboard/new">{{ trans('update.new_course_notices') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @php
            $rewardSetting = getRewardsSettings();
        @endphp

        @if(!empty($rewardSetting) and $rewardSetting['status'] == '1')
            <li class="sidenav-item {{ (request()->is('panel/rewards')) ? 'sidenav-item-active' : '' }}">
                <a href="/panel/rewards" class="d-flex align-items-center">
                <span class="sidenav-item-icon assign-strock mr-10">
                    @include('web.default.panel.includes.sidebar_icons.rewards')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('update.rewards') }}</span>
                </a>
            </li>
        @endif
        
        @if(!$authUser->isUser())
            <li class="sidenav-item {{ (request()->is('panel/report*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#reportCollapse" role="button" aria-expanded="false" aria-controls="reportCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.support')
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">Report</span>
                </a>
                <div class="collapse {{ (request()->is('panel/report') or request()->is('panel/report*')) ? 'show' : '' }}" id="reportCollapse">
                    <ul class="sidenav-item-collapse">
                            <!--<li class="mt-5 {{ (request()->is('panel/report/user')) ? 'active' : '' }}">-->
                            <!--    <a href="/panel/report/user">Report User</a>-->
                            <!--</li>-->
                            <li class="mt-5 {{ (request()->is('panel/report/division')) ? 'active' : '' }}">
                                <a href="/panel/report/division">Report Division</a>
                            </li>
                    </ul>
                </div>
            </li>
        @endif

        <li class="sidenav-item {{ (request()->is('panel/setting')) ? 'sidenav-item-active' : '' }}">
            <a href="/panel/setting" class="d-flex align-items-center">
                <span class="sidenav-setting-icon sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.setting')
                </span>
                <span class="font-14 text-dark-blue font-weight-500">{{ trans('panel.settings') }}</span>
            </a>
        </li>

        @if($authUser->isOrganization())
            <li class="sidenav-item ">
                <a href="{{ $authUser->getProfileUrl() }}" class="d-flex align-items-center">
                <span class="sidenav-item-icon assign-strock mr-10">
                    <i data-feather="user" stroke="#1f3b64" stroke-width="1.5" width="24" height="24" class="mr-10 webinar-icon"></i>
                </span>
                    <span class="font-14 text-dark-blue font-weight-500">{{ trans('public.my_profile') }}</span>
                </a>
            </li>
        @endif
    </ul>
</div>
