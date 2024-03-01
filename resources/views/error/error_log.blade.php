@extends('layouts.app')

@section('page')
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4>Error Logs
                        <button class="delete_log btn btn-danger btn-sm" style="float: right;" data-delete-id="all">Delete All</button>
                    </h4>
                    <hr/>
                    <div class="table-responsive mt-3">
                        <div class="row">
                            <table id="error-log-datatable"
                                   class="border-top-0  table table-bordered border-bottom">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">URL</th>
                                    <th class="border-bottom-0">Line No </th>
                                    <th class="border-bottom-0">Code</th>
                                    <th class="border-bottom-0">File</th>
                                    <th class="border-bottom-0">Error</th>
                                    <th class="border-bottom-0">Created At</th>
                                    <th class="border-bottom-0">Action</th>
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
    </div>
    <script src="{{ '/assets/js/error_list.js?ver=' . SCRIPT_VERSION }}"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click','.delete_log',function (e) {
                e.preventDefault();

                var delete_id = $(this).data('delete-id');

                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                delete_id: delete_id
                            },
                            url: "{{ '/delete-error-logs-list' }}",
                            success: function (response) {
                                if (response.success == true) {
                                    Swal.fire('Deleted!',response.message,'success').then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                }

                                if (response.success == false) {
                                    Swal.fire('Error!',response.message,'error')
                                }

                            },
                            error: function(error,textStatus, errorThrown) {
                                if(error.status == 400){
                                    Swal.fire('Error','Something went wrong. Please try again later.','error')
                                }
                            }
                        });
                    }
                })
            });
        });
    </script>
@endsection
