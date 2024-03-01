@extends('layouts.app')

@section('page')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Project Details</h4>
                    <a href="{{ route('project_details') }}" class="btn btn-rounded btn-primary add_project"><span class="btn-icon-start text-info"><i class="fa fa-plus color-info"></i>
                    </span>Add</a>
                </div>
                <div class="card-body">
                    <form id="ProjectDetailsForm" enctype="multipart/form-data" data-validate="parsley">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Project Image</strong><sup class="text-danger red">*</sup></label>
                                <div class="main-img-user profile-user" style="display: inline-block;position: relative;width: 36px;height: 36px;border-radius: 100%;text-align: center;width: 120px;height: 120px;margin-bottom: 20px;">
                                    @if(isset($project_info['image']) && !empty($project_info['image']))
                                        <img alt="" id="web_image" src="{{asset(PROJECTS_PATH).'/'.$project_info['image'] }}" class="image-picker-preview">
                                    @else
                                        <img alt="" id="web_image" src="" class="image-picker-preview">
                                    @endif
                                    <a href="JavaScript:void(0);"><label for="image" class="fas fa-camera profile-edit" style="position: absolute;width: 30px;height: 30px;border-radius: 50%;line-height: 30px;right: 0;background: #d5d4e0;margin: 0 auto;text-align: center;"></label></a>
                                    <input type="file" class="form-control" id="image" name="image" onchange="document.getElementById('web_image').src = window.URL.createObjectURL(this.files[0])" accept="image/*" style="display: none">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                            </div>
                            <div class="col-md-6 mb-3">
                                @include('components.dropdown-field',[
                                    'label' => 'Project Category',
                                    'name' => 'project_category',
                                    'id' => 'project_category',
                                    'extClass' => '',
                                    'options' => $ProjectCategory,
                                    'placeholder' => 'Select Category',
                                    'is_required' => true,
                                ])
                            </div>
                            <div class="col-md-6 mb-3">
                                @include('components.text-field',[
                                    'label' => 'Title',
                                    'name' => 'title',
                                    'id' => 'title',
                                    'value' => @$project_info['title'],
                                    'placeholder' => 'Enter Project Title',
                                    'is_required' => true,
                                ])
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Started</strong><sup class="text-danger red">*</sup></label>
                                <input name="started" class="form-control" id="started" value="{{@$project_info['started']}}" data-parsley-required="true">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><strong>Ended</strong></label>
                                <input name="ended" class="form-control" id="ended" value="{{@$project_info['ended']}}">
                            </div>
                            <div class="col-md-6 mb-3">
                                @include('components.dropdown-field',[
                                    'label' => 'Language Used',
                                    'name' => 'language_used[]',
                                    'id' => 'language_used',
                                    'extClass' => 'select2-multiple',
                                    'options' => $Skills,
                                    'selected_value' => (isset($project_info['language_used']) && !empty($project_info['language_used'])) ? explode(',',$project_info['language_used']) : '',
                                    'is_required' => true,
                                    'is_multiple' => true,

                                ])
                            </div>
                            <div class="col-md-6 mb-3 mt-2">
                                <label class="form-label"><strong>Description</strong><sup class="text-danger red">*</sup></label>
                                <textarea name="description" id="description" class="form-control bg-transparent" data-parsley-required="true">{{ @$project_info['description']}}</textarea>
                            </div>
                            <h4 class="mb-3 mt-2">Links : </h4>
                            <div class="row">
                                <div class="col-md-6 mb-3 mt-2">
                                    <div class="row">
                                        <div class="col-md-2 col-3">
                                            <a class="btn-social facebook" href="javascript:void(0)"><i class="fa fa-link"></i></a>
                                        </div>
                                        <div class="col-md-9 col-9">
                                            @include('components.text-field',[
                                                'name' => 'live',
                                                'id' => 'live',
                                                'value' => '',
                                                'placeholder' => 'Live',
                                                'value' => @$project_info['webapp']
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
                                                'name' => 'github',
                                                'id' => 'github',
                                                'value' => '',
                                                'placeholder' => 'Github',
                                                'value' => @$project_info['github']
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-3">
                                    @if(isset($project_info) && !empty($project_info))
                                        <input type="hidden" name="project_id" id="project_id" value="{{ $project_info['id'] }}">
                                    @endif
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
        var SAVE_API = '{{route("save_project_details")}}';

        $(document).ready(function (){
            @if(isset($project_info) && !empty($project_info['id']))
                var language = "{{ $project_info['language_used'] }}";
                var selectedValues = language.split(',');
                var dropdown = $('#language_used');

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
    <script src="{{ asset('assets/js/projects_details.js?ver=' . SCRIPT_VERSION) }}"></script>
@endsection
