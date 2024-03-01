@extends('layouts.app')

@section('page')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Experience</h4>
                    <a href="{{ '/experience-details' }}" class="btn btn-rounded btn-primary add_skill"><span class="btn-icon-start text-info"><i class="fa fa-plus color-info"></i>
                    </span>Add</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="experience-table" class="display" style="min-width: 845px; width:100%!important;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Company Logo</th>
                                <th>Company Name</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Role</th>
                                <th>Description</th>
                                <th>Skills</th>
                                <th>Documents</th>
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
        <div class="modal fade" id="company_doc_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Document
                        </h6>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">

                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row document_data">

                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var ExperienceListApi = '{{ '/experience-list' }}';
    </script>
    <script src="{{ '/assets/js/experience_list.js?ver=' . SCRIPT_VERSION }}"></script>
@endsection
