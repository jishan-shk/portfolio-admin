$(function () {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });


    $('#experience-table').DataTable({
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
            url: ExperienceListApi,
            method: 'get',
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'company_logo',
                name: 'company_logo'
            },
            {
                data: 'company_name',
                name: 'company_name'
            },
            {
                data: 'start',
                name: 'start'
            },
            {
                data: 'end',
                name: 'end'
            },
            {
                data: 'role',
                name: 'role'
            },
            {
                data: 'description',
                name: 'description'
            },
            {
                data: 'skills',
                name: 'skills'
            },
            {
                data: 'documents',
                name: 'documents'
            },
            {
                data: 'action',
                name: 'action'
            },
        ]
    });

    $(document).on('click','.delete_experience', function (e) {
        e.preventDefault();
        var experience_id = $(this).data('experience-id');

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
                    url: window.origin + '/experience/delete-experience/' + experience_id,
                    success: function (response) {
                        if (response.success == true) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            })

                            $('#experience-table').DataTable().draw();
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

    $(document).on("click", ".view_document", function (e) {
        e.preventDefault();
        var docRoute = $(this).data("experience-doc-route");

        $.ajax({
            url: docRoute,
            processData: false,
            contentType: false,
            type: "GET",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $("input[name=_token]").val(),
            },
            success: function (response) {
                let doc_html = "";

                $.each(response.data, function (index, item){
                    doc_html += `<div class="col-md-4 mt-3">
                        <a href="${item.file_path}" target="_blank"><img class="pdf-icon" src="${item.file_path}" width="70" alt=""/></a>
<!--                        <span class="pdf-name-text">${item.file_name}</span>-->
                    </div>`;
                });

                $('.document_data').html(doc_html);
                $('#company_doc_modal').modal('show');

            },
            error: function (xhr, status, error) {
                var e = JSON.parse(xhr.responseText);

                Toast.fire({
                    icon: 'error',
                    title: e.message
                })
            },
        });

    });

});
