@extends('layouts.app')

@section('page')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Skills Category</h4>
                    <button type="button" class="btn btn-rounded btn-primary add_skill_category"><span class="btn-icon-start text-info"><i class="fa fa-plus color-info"></i>
                    </span>Add</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="category-table" class="display" style="min-width: 845px">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
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
    <div class="modal fade" id="skillsCategoryModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Skills Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <form name="skills_category_frm" id="skills_category_frm"  data-validate="parsley">
                    <div class="modal-body">
                        @include('components.text-field',[
                                'label' => 'Category',
                                'name' => 'category_name',
                                'id' => 'category_name',
                                'value' => '',
                                'placeholder' => 'Enter Skills Category',
                                'is_required' => true,
                        ])
                        <input type="hidden" id="skills_category_id" name="skills_category_id">
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
        var SkillCategoryListApi = '{{route('skills_category_list')}}';
        var SaveSkillCategoryApi = '{{route('save_skill_category')}}';
    </script>
    <script src="{{ asset('assets/js/skill_category.js?ver=' . SCRIPT_VERSION) }}"></script>
@endsection
