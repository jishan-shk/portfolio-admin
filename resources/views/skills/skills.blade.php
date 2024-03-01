@extends('layouts.app')

@section('page')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Skills</h4>
                    <button type="button" class="btn btn-rounded btn-primary add_skill"><span class="btn-icon-start text-info"><i class="fa fa-plus color-info"></i>
                    </span>Add</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="skill-table" class="display" style="min-width: 845px; width:100%!important;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Skill</th>
                                <th>Logo</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="skillsModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Skills Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form name="skills_frm" id="skills_frm" enctype="multipart/form-data" data-validate="parsley">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                @include('components.dropdown-field',[
                                    'label' => 'Skill Category',
                                    'name' => 'skill_category',
                                    'id' => 'skill_category',
                                    'extClass' => '',
                                    'options' => $skillCategoryList,
                                    'placeholder' => 'Select Category',
                                    'is_required' => true,
                                ])
                            </div>

                            <div class="col-md-12 mt-3">
                                @include('components.text-field',[
                                    'label' => 'Skill',
                                    'name' => 'skill_name',
                                    'id' => 'skill_name',
                                    'value' => '',
                                    'placeholder' => 'Enter Skills',
                                    'is_required' => true,
                                ])
                            </div>
                            <div class="col-md-12 mt-3 logo_div">

                            </div>

                            <input type="hidden" id="skills_id" name="skills_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        var SkillListApi = '{{route('skills_list')}}';
        var SaveSkillApi = '{{route('save_skills')}}';
        var path = '{{asset(SKILL_LOGO_PATH)}}';
    </script>
    <script src="{{ asset('assets/js/skills_master.js?ver=' . SCRIPT_VERSION) }}"></script>

@endsection
