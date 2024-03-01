$(function () {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    $('.work_date').pickadate({
        max: new Date(),
        selectMonths: true,
        selectYears: true
    });


    $('.add_file').click(function (e){
        e.preventDefault();

        var document_html = `<div class="row">
                                <div class="col-md-8 mb-3 mt-2">
                                    <label class="form-label"><strong>Document</strong><sup class="text-danger red">*</sup></label>
                                    <input type="file" name="document[]" class="mt-3 dropify" data-allowed-file-extensions='["jpg", "png","jpeg","gif"]' data-height="100">
                                </div>
                                <div class="col-md-4 mb-3 mt-2">
                                    <button class="btn btn-danger mt-4" onclick="$(this).parent().parent('.row').remove()">Remove</button>
                                </div>
                            </div>`;

        $('.append_document').append(document_html);
        $('.dropify').dropify();
    });


    $("#experience_form").submit(function(e){
        e.preventDefault();
        $('.remove_custome_div').remove();

        if($(this).parsley().validate()) {
            var formData = new FormData(this);
            let button = $(this).find('button[type="submit"]');

            $.ajax({
                url: SaveExperienceApi,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    button.attr('disabled', 'true').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please wait...')
                },
                complete: function (xhr) {
                    button.removeAttr('disabled').text('Save changes')
                },
                success: function (response) {
                    if(response.success){
                        button.attr('disabled', 'true').html('<span>Redirecting...</span>')

                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })

                        setTimeout(() => {
                            window.location.href = '/experience'
                        },100)
                    }else{
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        })
                    }
                },
                error: function (xhr, status, error) {
                    var e = JSON.parse(xhr.responseText);

                    var errorCode = e.errors_fields;

                    Toast.fire({
                        icon: 'error',
                        title: xhr.responseJSON.message
                    })

                    for (x in errorCode) {
                        $("#experience_form").find("input#" + x).after(
                            "<div  class='error remove_custome_div text-danger'  id=" + x +
                            "-error' class='error'>" + errorCode[x] + "</div>");
                    }
                },
            });
        }
    });

    $(document).on('click','.delete_document', function (e) {
        e.preventDefault();
        var doc_id = $(this).data('document-id');

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
                    url: window.origin + '/experience/delete-experience-document/' + doc_id,
                    success: function (response) {
                        if (response.success == true) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            })

                            location.reload();

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
