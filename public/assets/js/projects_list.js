$(function (){
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    $('#project-list-table').DataTable({
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
            url: ProjectListApi,
            method: 'get',
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'category',
                name: 'category'
            },
            {
                data: 'image',
                name: 'image'
            },
            {
                data: 'title',
                name: 'title'
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
                data: 'language_used',
                name: 'language_used'
            },
            {
                data: 'description',
                name: 'description',
                className:'overflow_text'
            },
            {
                data: 'github',
                name: 'github'
            },
            {
                data: 'live_link',
                name: 'live_link'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'action',
                name: 'action'
            },
        ]
    });

    $(document).on('click','.delete_project',function (e) {
        e.preventDefault();

        var url = $(this).attr('href');

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
                    url: url,
                    success: function (response) {
                        if (response.status == true) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            })

                            $('#project-list-table').DataTable().draw();
                        }

                        if (response.status == false) {
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
