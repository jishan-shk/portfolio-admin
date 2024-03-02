@extends('layouts.app')

@section('page')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">About Me</h4>
                </div>
                <div class="card-body">
                    <form id="PersonalInfoForm" enctype="multipart/form-data" data-validate="parsley">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="main-img-user profile-user" style="display: inline-block;position: relative;width: 36px;height: 36px;border-radius: 100%;text-align: center;width: 120px;height: 120px;margin-bottom: 20px;">
                                    @if(isset($personal_info['profile_logo']) && !empty($personal_info['profile_logo']))
                                        <img alt="" id="profile_img" src="{{ $personal_info['profile_logo'] }}" class="image-picker-preview">
                                    @else
                                        <img alt="" id="profile_img" src="https://v2.medibhai.co.in/assets/img/default_profile_logo.png" class="image-picker-preview">
                                    @endif
                                    <a href="JavaScript:void(0);"><label for="profile_logo" class="fas fa-camera profile-edit" style="position: absolute;width: 30px;height: 30px;border-radius: 50%;line-height: 30px;right: 0;background: #d5d4e0;margin: 0 auto;text-align: center;"></label></a>
                                    <input type="file" class="form-control" id="profile_logo" name="profile_logo" onchange="document.getElementById('profile_img').src = window.URL.createObjectURL(this.files[0])" accept="image/*" style="display: none">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                            </div>
                            <div class="col-md-6 mb-3">
                                @include('components.text-field',[
                                    'label' => 'Full Name',
                                    'name' => 'full_name',
                                    'id' => 'full_name',
                                    'value' => @$personal_info['full_name'],
                                    'placeholder' => 'Enter Full Name',
                                    'is_required' => true,
                                ])
                            </div>
                            <div class="col-md-6 mb-3">
                                @include('components.text-field',[
                                    'label' => 'Email',
                                    'name' => 'email',
                                    'id' => 'email',
                                    'value' => @$personal_info['email'],
                                    'type' => 'email',
                                    'placeholder' => 'Enter Email',
                                    'is_required' => true,
                                ])
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Date of Birth</strong><sup class="text-danger red">*</sup></label>
                                <input name="dob" class="form-control" id="dob" value="{{@$personal_info['date_of_birth']}}" data-parsley-required="true">
                            </div>
                            <div class="col-md-6 mb-3">
                                @include('components.dropdown-field',[
                                    'label' => 'Availability',
                                    'name' => 'availability',
                                    'id' => 'availability',
                                    'extClass' => 'select2',
                                    'options' => [
                                        'Full Time'     => 'Full Time',
                                        'Part Time'     => 'Part Time',
                                        'Freelancer'    => 'Freelancer',
                                        'Part Time (Free Lancer)'   => 'Part Time (Free Lancer)',
                                    ],
                                    'selected_value'  => @$personal_info['Availablity'],
                                    'placeholder' => 'Select availability',
                                    'is_required' => true,
                                ])
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Work Started From</strong><sup class="text-danger red">*</sup></label>
                                <input name="work_started_from" class="form-control" id="work_started_from" value="{{ @$personal_info['work_started'] }}" data-parsley-required="true">
                                <span>Total Experience : <strong class="total_exp"> {{ @$personal_info['total_experience'] }}</strong></span>
                                <input type="hidden" name="total_experience" id="total_experience" value="{{ @$personal_info['total_experience'] }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                @include('components.dropdown-field',[
                                    'label' => 'I am a',
                                    'name' => 'i_am_a[]',
                                    'id' => 'i_am_a[]',
                                    'extClass' => 'select2-multiple',
                                    'options' => [
                                        'Full Stack developer' => 'Full Stack developer',
                                        'Software developer' => 'Software developer',
                                        'Android developer' => 'Android developer',
                                        'Programmer' => 'Programmer',
                                    ],
                                    'selected_value' => (isset($personal_info['position']) && !empty($personal_info['position'])) ? explode(',',$personal_info['position']) : '',
                                    'is_required' => true,
                                    'is_multiple' => true,

                                ])
                            </div>
                            <div class="col-md-6 mb-3 mt-2">
                                <label class="form-label"><strong>About me</strong><sup class="text-danger red">*</sup></label>
                                <textarea name="about_me" id="about_me" cols="30" rows="5" class="form-control bg-transparent" data-parsley-required="true">{{ @$personal_info['about_me']}}</textarea>
                            </div>
                            <div class="col-md-6 mb-3 mt-2">
                                <label class="form-label"><strong>Resume</strong><sup class="text-danger red">*</sup>@if(!empty($personal_info['resume'])) <a href="{{ $personal_info['resume'] }}" target="_blank"><i class="fa fa-eye"></i></a>@endif</label>
                                <input type="file" id="resume" name="resume" class="mt-3 dropify" data-allowed-file-extensions='["jpg", "png","jpeg","gif","pdf"]' data-height="100">
                            </div>
                            <h4 class="mb-3 mt-2">Links : </h4>
                            <div class="row">
                                <div class="col-md-6 mb-3 mt-2">
                                    <div class="row">
                                        <div class="col-md-2 col-3">
                                            <a class="btn-social facebook" href="javascript:void(0)"><i class="fab fa-facebook-f"></i></a>
                                        </div>
                                        <div class="col-md-9 col-9">
                                            @include('components.text-field',[
                                                'name' => 'facebook_link',
                                                'id' => 'facebook_link',
                                                'value' => '',
                                                'placeholder' => 'Facebook link',
                                                'is_required' => true,
                                                'value' => @$personal_info['facebook_link']
                                            ])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 mt-2">
                                    <div class="row">
                                        <div class="col-md-2 col-3">
                                            <a class="btn-social linkedin" href="javascript:void(0)"><i class="fab fa-linkedin"></i></a>
                                        </div>
                                        <div class="col-md-9 col-9">
                                            @include('components.text-field',[
                                                'name' => 'linkedin_link',
                                                'id' => 'linkedin_link',
                                                'value' => '',
                                                'placeholder' => 'Linkedin link',
                                                'is_required' => true,
                                                'value' => @$personal_info['linkedin_link']
                                            ])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 mt-2">
                                    <div class="row">
                                        <div class="col-md-2 col-3">
                                            <a class="btn-social instagram" href="javascript:void(0)"><i class="fab fa-instagram"></i></a>
                                        </div>
                                        <div class="col-md-9 col-9">
                                            @include('components.text-field',[
                                                'name' => 'instagram_link',
                                                'id' => 'instagram_link',
                                                'value' => '',
                                                'placeholder' => 'Instagram link',
                                                'is_required' => true,
                                                'value' => @$personal_info['instagram_link']
                                            ])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 mt-2">
                                    <div class="row">
                                        <div class="col-md-2 col-3">
                                            <a class="btn-social twitter" href="javascript:void(0)"><i class="fab fa-twitter"></i></a>
                                        </div>
                                        <div class="col-md-9 col-9">
                                            @include('components.text-field',[
                                                'name' => 'twitter_link',
                                                'id' => 'twitter_link',
                                                'value' => '',
                                                'placeholder' => 'Twitter link',
                                                'value' => @$personal_info['twitter_link']
                                            ])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 mt-2">
                                    <div class="row">
                                        <div class="col-md-2 col-3">
                                            <a class="btn-social github" href="javascript:void(0)"><i class="fab fa-github"></i></a>
                                        </div>
                                        <div class="col-md-9 col-9">
                                            @include('components.text-field',[
                                                'name' => 'github_link',
                                                'id' => 'github_link',
                                                'value' => '',
                                                'placeholder' => 'Github link',
                                                'is_required' => true,
                                                'value' => @$personal_info['github_link']
                                            ])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3 mt-2">
                                    <div class="row">
                                        <div class="col-md-2 col-3">
                                            <a class="btn-social whatsapp" href="javascript:void(0)"><i class="fab fa-whatsapp"></i></a>
                                        </div>
                                        <div class="col-md-9 col-9">
                                            @include('components.text-field',[
                                                'name' => 'whatsapp_link',
                                                'id' => 'whatsapp_link',
                                                'value' => '',
                                                'placeholder' => 'Whatsapp link',
                                                'value' => @$personal_info['whatshapp_link']
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary mt-4">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var SAVE_API = '/save-personal-details';
    </script>
    <script src="{{ '/assets/js/about_me.js?ver=' . SCRIPT_VERSION }}"></script>
@endsection
