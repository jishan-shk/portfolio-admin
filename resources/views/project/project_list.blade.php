@extends('layouts.app')

@section('page')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Projects</h4>
                    <a href="{{ route('project_details') }}" class="btn btn-rounded btn-primary add_project"><span class="btn-icon-start text-info"><i class="fa fa-plus color-info"></i>
                    </span>Add</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="project-list-table" class="display" style="min-width: 845px; width:100%!important;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Started</th>
                                <th>Ended</th>
                                <th>Languages used</th>
                                <th>Description</th>
                                <th>Code</th>
                                <th>Live</th>
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
    <script>
        var ProjectListApi = '{{route('projects_list')}}';
    </script>
    <script src="{{ asset('assets/js/projects_list.js?ver=' . SCRIPT_VERSION) }}"></script>
@endsection
