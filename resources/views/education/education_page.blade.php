@extends('layouts.app')

@section('page')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Education</h4>
                    <a href="{{ route('education_details') }}" class="btn btn-rounded btn-primary add_skill"><span class="btn-icon-start text-info"><i class="fa fa-plus color-info"></i>
                    </span>Add</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="education-table" class="display" style="min-width: 845px; width:100%!important;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>Institute Name</th>
                                <th>Degree</th>
                                <th>Started</th>
                                <th>Ended</th>
                                <th>Grade</th>
                                <th>Description</th>
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
        var EducationListApi = '{{route('education_list')}}';
    </script>
    <script src="{{ asset('assets/js/education_list.js?ver=' . SCRIPT_VERSION) }}"></script>
@endsection
