$(function () {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });


    $('#education-table').DataTable({
        language: {
            searchPlaceholder: 'Search...',
            scrollX: "100%",
            sSearch: '',
            paginate: {
                next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>'
            }
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: EducationListApi,
            method: 'get',
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'logo',
                name: 'logo'
            },
            {
                data: 'institute_name',
                name: 'institute_name'
            },
            {
                data: 'degree',
                name: 'degree'
            },
            {
                data: 'started',
                name: 'started'
            },
            {
                data: 'ended',
                name: 'ended'
            },
            {
                data: 'grade',
                name: 'grade'
            },
            {
                data: 'description',
                name: 'description'
            },
            {
                data: 'action',
                name: 'action'
            },
        ]
    });

    $(document).on('click','.delete_education', function (e) {
        e.preventDefault();
        var route = $(this).data('education-delete');

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
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: route,
                    success: function (response) {
                        if (response.success == true) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            })

                            $('#education-table').DataTable().draw();
                        }

                        if (response.success == false) {
                            Toast.fire({
                                icon: 'error',
                                title: response.message
                            })
                        }

                    },
                    error: function(error,textStatus, errorThrown) {
                        if(error.status == 400){
                            Toast.fire({
                                icon: 'error',
                                title: 'Something went wrong. Please try again later.'
                            })

                            location.reload();
                        }
                    }
                });
            }
        })
    });
});
