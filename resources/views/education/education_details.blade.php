@extends('layouts.app')

@section('page')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Education Details</h4>
                </div>
                <div class="card-body">
                    <form id="education_form" enctype="multipart/form-data" data-validate="parsley">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                @include('components.text-field',[
                                    'label' => 'Institute Name',
                                    'name' => 'institute_name',
                                    'id' => 'institute_name',
                                    'value' => @$education_data['institute_name'],
                                    'placeholder' => 'Enter Institute Name',
                                    'is_required' => true,
                                ])
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Institute Logo</strong><sup class="text-danger red">*</sup></label>
                                <div class="main-img-user profile-user" style="display: inline-block;position: relative;width: 36px;height: 36px;border-radius: 100%;text-align: center;width: 120px;height: 120px;margin-bottom: 20px;">
                                    @if(isset($education_data['logo']) && !empty($education_data['logo']))
                                        <img alt="" id="institude_img" src="{{ $education_data['logo'] }}" class="image-picker-preview">
                                    @else
                                        <img alt="" id="institude_img" class="image-picker-preview">
                                    @endif
                                    <a href="JavaScript:void(0);"><label for="logo" class="fas fa-camera profile-edit" style="position: absolute;width: 30px;height: 30px;border-radius: 50%;line-height: 30px;right: 0;background: #d5d4e0;margin: 0 auto;text-align: center;"></label></a>
                                    <input type="file" class="form-control" id="logo" name="logo" onchange="document.getElementById('institude_img').src = window.URL.createObjectURL(this.files[0])" accept="image/*" style="display: none" @if(!isset($education_data['logo'])) data-parsley-required="true" @endif>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Started</strong><sup class="text-danger red">*</sup></label>
                                <input name="started" class="form-control education_date" id="ended" value="{{@$education_data['started']}}" data-parsley-required="true">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Ended</strong><sup class="text-danger red">*</sup></label>
                                <input name="ended" class="form-control education_date" id="ended" value="{{@$education_data['ended']}}">
                            </div>
                            <div class="col-md-6 mb-3">
                                @include('components.text-field',[
                                    'label' => 'Degree',
                                    'name' => 'degree',
                                    'id' => 'degree',
                                    'value' => @$education_data['degree'],
                                    'placeholder' => 'Enter Degree',
                                    'is_required' => true,
                                ])
                            </div>
                            <div class="col-md-6 mb-3">
                                @include('components.text-field',[
                                    'label' => 'Grade',
                                    'name' => 'grade',
                                    'id' => 'grade',
                                    'value' => @$education_data['grade'],
                                    'placeholder' => 'Enter Grade',
                                    'is_required' => true,
                                ])
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Description</strong><sup class="text-danger red">*</sup></label>
                                <textarea name="description" id="description" class="form-control bg-transparent" data-parsley-required="true">{{ @$education_data['description']}}</textarea>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-3">
                                @if(isset($education_data) && !empty($education_data['id']))
                                    <input type="hidden" name="education_id" id="education_id" value="{{$education_data['id']}}">
                                @endif
                                <button type="submit" class="btn btn-primary mt-4">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var SaveEducationApi = '/save-education';
    </script>
    <script src="{{ '/assets/js/education_details.js?ver=' . SCRIPT_VERSION }}"></script>
@endsection
