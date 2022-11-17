@php $containerNav = $containerNav ?? 'container-fluid'; $navbarDetached = ($navbarDetached ?? ''); @endphp

<style>
    .dropdown-divider {
        height: 0;
        margin: 0 0;
        overflow: hidden;
        border-top: 1px solid #d9dee3;
    }

    .dropdown-item {
        line-height: 1.54;
        padding: 10px 10px;
        position: relative;
        display: block;
        width: 100%;
        text-align: inherit;
    }

    .unread {
        background-color: #9f9f9f33;
    }

    .unread:hover {
        background-color: #9f9f9f1b;
    }
</style>
<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
    @endif @if(isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{$containerNav}}">
            @endif

            <!--  Brand demo (display only for navbar-full and hide on below xl) -->
            @if(isset($navbarFull))
            <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
                <a href="{{url('/')}}" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo">
                        @include('_partials.macros',["width"=>25,"withbg"=>'#696cff'])
                    </span>
                    <span class="app-brand-text demo menu-text fw-bolder">{{config('variables.templateName')}}</span>
                </a>
            </div>
            @endif

            <!-- ! Not required for layout-without-menu -->
            @if(!isset($navbarHideToggle))
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
                <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                    <i class="bx bx-menu bx-sm"></i>
                </a>
            </div>
            @endif

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                <!-- Search -->
                <div class="navbar-nav align-items-center">
                    <div class="nav-item d-flex align-items-center">
                        <i class="bx bx-search fs-4 lh-0"></i>
                        <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search...">
                    </div>
                </div>

                <ul class="navbar-nav flex-row align-items-center justify-content-between ms-auto">

                    <!-- Place this tag where you want the button to render. -->
                    <!-- <li class="nav-item lh-1 me-3">
                        <a class="github-button" href="https://github.com/themeselection/sneat-html-laravel-admin-template-free" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star themeselection/sneat-html-laravel-admin-template-free on GitHub">Star</a>
                    </li> -->

                    <!-- Notifivation -->
                    <li class="nav-item lh-1 me-3">

                        <a href="" class="text-dark" style="position: relative;" data-bs-toggle="dropdown">
                            <i class="bx {{(count(auth()->user()->unreadNotifications)>0) ? 'bxs-bell' :'bx-bell'}}" style="font-size: 1.4rem;color: #697a8d;"></i>
                            <span class="badge rounded-pill badge-notification bg-danger {{(count(auth()->user()->unreadNotifications) > 0) ? '' :'invisible'}}" style="position: absolute;top: -15%;right: -15%;font-size: 0.66em;">{{count(auth()->user()->unreadNotifications)}}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end col-12 col-md-6" style="position: absolute;">
                            <li>
                                <span class="d-flex align-items-center align-middle justify-content-between" style="padding-right: 0.532rem;">

                                    <h3 class="dropdown-header text-uppercase">Notifications

                                    </h3>
                                    @if (count(auth()->user()->unreadNotifications)>0)
                                    <span class="badge rounded-pill bg-danger" style="padding: 0.3em 0.53em;">New {{count(auth()->user()->unreadNotifications)}}</span>
                                    @endif
                                </span>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                            @if (count(auth()->user()->notifications)>0)

                            @foreach (auth()->user()->notifications as $notification)
                            <li>
                                <x-notification title="{{ $notification->data['data']['title'] }}" description="{{$notification->data['data']['description']}}" state="{{($notification->read_at == null) ? 'unread' : ''}}" time="{{time_elapsed_string($notification->created_at)}}" />
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                            @endforeach
                            @else
                            <div class="container-fluid d-flex flex-md-row flex-row justify-content-center align-items-md-center gap-1 container-p-x py-3">
                                <p class="text-muted mb-0">There is no notifications</p>
                            </div>
                            @endif

                        </ul>
                    </li>
                    <!-- User -->
                    <li class="nav-item navbar-dropdown dropdown-user dropdown">
                        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                            <!-- <div class="avatar avatar-online">
              </div> -->
                            <img style="width: 40px;
    height: 40px;
    object-fit: cover;" src="{{(auth()->user()->photo != null) ? asset('public/Image/'.auth()->user()->photo) : asset('assets/img/avatars/profile.jpg')}}" alt class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar avatar-online">
                                                <img style="width: 40px;
    height: 40px;
    object-fit: cover;" src="{{(auth()->user()->photo != null) ? asset('public/Image/'.auth()->user()->photo) : asset('assets/img/avatars/profile.jpg')}}" alt class="rounded-circle">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="fw-semibold d-block">{{auth()->user()->first_name}}</span>
                                            <small class="text-muted text-uppercase">{{auth()->user()->roles->pluck('name')[0]}}</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="bx bx-user me-2"></i>
                                    <span class="align-middle">My Profile</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class='bx bx-cog me-2'></i>
                                    <span class="align-middle">Settings</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <span class="d-flex align-items-center align-middle">
                                        <i class="flex-shrink-0 bx bx-credit-card me-2 pe-1"></i>
                                        <span class="flex-grow-1 align-middle">Billing</span>
                                        <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class='bx bx-power-off me-2'></i>
                                    <span class="align-middle">Log Out</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!--/ User -->
                </ul>
            </div>

            @if(!isset($navbarDetached))
        </div>
        @endif
    </nav>
    <!-- / Navbar -->