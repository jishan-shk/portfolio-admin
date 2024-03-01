<div class="dlabnav">
    <div class="dlabnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="dropdown header-profile">
                <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
{{--                    @if(!empty(\App\Models\PersonalInfoModel::pluck('profile_logo')->first()))--}}
{{--                        <img src="{{ asset(UPLOAD_PATH).'/'.\App\Models\PersonalInfoModel::pluck('profile_logo')->first() }}" width="20" alt="">--}}
{{--                    @else--}}
                        <img src="https://v2.medibhai.co.in/assets/img/default_profile_logo.png" width="20" alt="">
{{--                    @endif--}}
                    <div class="header-info ms-3">
                        <span class="font-w600 ">Hi,<b>{{Auth::user()->name}}</b></span>
                        <small class="text-end font-w400">{{Auth::user()->gmail}}</small>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="javascript:void(0)" class="dropdown-item ai-icon">
                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span class="ms-2">Profile </span>
                    </a>
                    <a href="javascript:void(0)" class="dropdown-item ai-icon">
                        <svg id="icon-inbox" xmlns="http://www.w3.org/2000/svg" class="text-success" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span class="ms-2">Inbox </span>
                    </a>
                    <a href="{{ route('logout') }}" class="dropdown-item ai-icon">
                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        <span class="ms-2">Logout </span>
                    </a>
                </div>
            </li>
            <li>
                <a class="ai-icon" href="{{ route('dashboard') }}" aria-expanded="false">
                    <i class="flaticon-025-dashboard"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a class="ai-icon" href="{{ route('about_me') }}" aria-expanded="false">
                    <i class="flaticon-025-dashboard"></i>
                    <span class="nav-text">About Me</span>
                </a>
            </li>
            <li>
                <a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
                    <i class="flaticon-025-dashboard"></i>
                    <span class="nav-text">Skills</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('skills_category') }}">Category</a></li>
                    <li><a href="{{ route('skills_page') }}">Skills</a></li>
                </ul>
            </li>
            <li>
                <a class="ai-icon" href="{{ route('experience') }}" aria-expanded="false">
                    <i class="flaticon-025-dashboard"></i>
                    <span class="nav-text">Experience</span>
                </a>
            </li>
            <li>
                <a class="ai-icon" href="{{ route('education_page') }}" aria-expanded="false">
                    <i class="flaticon-025-dashboard"></i>
                    <span class="nav-text">Education</span>
                </a>
            </li>
            <li>
                <a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
                    <i class="flaticon-025-dashboard"></i>
                    <span class="nav-text">Projects</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('project_category_page') }}">Category</a></li>
                    <li><a href="{{ route('projects_page') }}">Project</a></li>
                </ul>
            </li>
        </ul>
        <div class="copyright">
            <p><strong>Dompet Payment Admin Dashboard</strong> © 2022 All Rights Reserved</p>
            <p class="fs-12">Made with <span class="heart"></span> by DexignLab</p>
        </div>
    </div>
</div>
