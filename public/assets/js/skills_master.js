$(function (){
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    append_logo_file()

    $('#skill-table').DataTable({
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
            url: SkillListApi,
            method: 'get',
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'category_name',
                name: 'category_name'
            },
            {
                data: 'skill',
                name: 'skill'
            },
            {
                data: 'logo',
                name: 'logo'
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

    $(document).on('click','.add_skill',function (){
        append_logo_file()
        $('#skills_frm')[0].reset();
        $('#skills_id').val('')
        $('#skill_category').val('').trigger('change');
        $('.dropify,#logo').dropify();
        $('#logo').attr('data-parsley-required',true);
        $('#skillsModal').modal('show');
    })

    $(document).on('click','.edit_skill',function (e){
        e.preventDefault();

        var skill_id = $(this).data('skill-id');

        $.ajax({
            url: `get-skill-details/`+skill_id,
            success: function (res){
                if(res.success){
                    $('#skills_id').val(skill_id);
                    $('#skill_category').val(res.data.skills_category_id).trigger('change');
                    $('#skill_name').val(res.data.name);

                    var imageUrl = res.data.logo;

                    append_logo_file()

                    var logoDropify = $('#logo');
                    logoDropify.dropify({ defaultFile: imageUrl });

                    $('#logo').attr('data-parsley-required',false);

                    $('#skillsModal').modal('show');
                }
            }
        });
    })

    $("#skills_frm").submit(function(e){
        e.preventDefault();
        $('.remove_custome_div').remove();

        if($(this).parsley().validate()) {
            var formData = new FormData(this);
            let button = $(this).find('button[type="submit"]');

            $.ajax({
                url: SaveSkillApi,
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
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })

                        $('#skills_frm')[0].reset();
                        $('#skill-table').DataTable().draw();
                        $('#skillsModal').modal('hide');
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
                        $("#skills_frm").find("input#" + x).after(
                            "<div  class='error remove_custome_div text-danger'  id=" + x +
                            "-error' class='error'>" + errorCode[x] + "</div>");
                    }
                },
            });
        }
    });

    $(document).on('click','.delete_skill',function (e) {
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

                            $('#skill-table').DataTable().draw();
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

function append_logo_file(){
    $('.logo_div').html(`<label class="form-label"><strong>Logo</strong> <sup class="text-danger red">*</sup></label>
        <input type="file" id="logo" name="logo" class="mt-3" value="" data-parsley-required="true" data-allowed-file-extensions='["jpg", "png","jpeg","gif"]' data-height="150" >`)
}
