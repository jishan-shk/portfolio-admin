@extends('layouts.app')

@section('page')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Experience Details</h4>
                </div>
                <div class="card-body">
                    <form id="experience_form" enctype="multipart/form-data" data-validate="parsley">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                @include('components.text-field',[
                                    'label' => 'Company Name',
                                    'name' => 'company_name',
                                    'id' => 'company_name',
                                    'value' => @$experience_data['company_name'],
                                    'placeholder' => 'Enter Company Name',
                                    'is_required' => true,
                                ])
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Company Logo</strong><sup class="text-danger red">*</sup></label>
                                <div class="main-img-user profile-user" style="display: inline-block;position: relative;width: 36px;height: 36px;border-radius: 100%;text-align: center;width: 120px;height: 120px;margin-bottom: 20px;">
                                    @if(isset($experience_data['company_logo']) && !empty($experience_data['company_logo']))
                                        <img alt="" id="company_img" src="{{asset(COMPANY_LOGO_PATH).'/'.$experience_data['company_logo'] }}" class="image-picker-preview">
                                    @else
                                        <img alt="" id="company_img" class="image-picker-preview">
                                    @endif
                                    <a href="JavaScript:void(0);"><label for="company_logo" class="fas fa-camera profile-edit" style="position: absolute;width: 30px;height: 30px;border-radius: 50%;line-height: 30px;right: 0;background: #d5d4e0;margin: 0 auto;text-align: center;"></label></a>
                                    <input type="file" class="form-control" id="company_logo" name="company_logo" onchange="document.getElementById('company_img').src = window.URL.createObjectURL(this.files[0])" accept="image/*" style="display: none" @if(!isset($experience_data['company_logo'])) data-parsley-required="true" @endif>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                @include('components.text-field',[
                                    'label' => 'Role',
                                    'name' => 'role',
                                    'id' => 'role',
                                    'value' => @$experience_data['role'],
                                    'placeholder' => 'Enter Role',
                                    'is_required' => true,
                                ])
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Description</strong><sup class="text-danger red">*</sup></label>
                                <textarea name="description" id="description" class="form-control bg-transparent" data-parsley-required="true">{{ @$experience_data['description']}}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Start Date</strong><sup class="text-danger red">*</sup></label>
                                <input name="start_date" class="form-control work_date" id="start_date" value="{{@$experience_data['start']}}" data-parsley-required="true">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>End Date</strong><sup class="text-danger red">*</sup></label>
                                <input name="end_date" class="form-control work_date" id="end_date" value="{{@$experience_data['end']}}">
                            </div>

                            <div class="col-md-6 mb-3">
                                @include('components.dropdown-field',[
                                        'label' => 'Skills',
                                        'name' => 'skills[]',
                                        'id' => 'skills',
                                        'extClass' => 'select2',
                                        'options' => $skills,
                                        'is_required' => true,
                                        'is_multiple' => true,

                                    ])
                            </div>
                        </div>
                        <hr/>

                        <h5>Documents</h5>
                        @if(isset($document) && !empty($document))
                            <ul>
                                @foreach($document as $file)
                                    <li>
                                        <a href="{{ asset(COMPANY_DOCUMENT_PATH.'/'.$file['file_name']) }}" target="_blank" class="view-document-eye"><i class="fa fa-eye"></i></a>
                                        <button class="btn btn-sm btn-danger delete_document" style="margin-top: -30px;" data-document-id="{{$file['document_id']}}">Delete</button>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="row append_document">
                            <div class="col-md-8 mb-3 mt-2">
                                <input type="file" name="document[]" class="mt-3 dropify" data-allowed-file-extensions='["jpg", "png","jpeg","gif"]' data-height="100">
                            </div>
                            <div class="col-md-4 mb-3 mt-2">
                                <button class="btn btn-info mt-4 add_file">Add</button>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-3">
                                @if(isset($experience_data) && !empty($experience_data['id']))
                                    <input type="hidden" name="experience_id" id="experience_id" value="{{$experience_data['id']}}">
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
        var SaveExperienceApi = '{{ '/save-experience' }}';

        $(document).ready(function (){
            @if(isset($experience_data) && !empty($experience_data['id']))
                var experienceSkills = "{{ $experience_data['skills'] }}";
                var selectedValues = experienceSkills.split(',');
                var dropdown = $('#skills');

                dropdown.find('option').each(function() {
                    var optionValue = $(this).val();

                    if ($.inArray(optionValue, selectedValues) !== -1) {
                        $(this).prop('selected', true);
                    }
                });

                $.each(selectedValues, function(index, value) {
                    if (dropdown.find('option[value="'+value+'"]').length === 0) {
                        dropdown.append($('<option>', {
                            value: value,
                            text: value,
                            selected: true
                        }));
                    }
                });

                dropdown.trigger('change');
            @endif
        })
    </script>
    <script src="{{ '/assets/js/experience_details.js?ver=' . SCRIPT_VERSION }}"></script>
@endsection
