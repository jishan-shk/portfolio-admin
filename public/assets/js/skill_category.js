$(function (){
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    $('#category-table').DataTable({
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
            url: SkillCategoryListApi,
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
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'action',
                name: 'action'
            },
        ]
    });

    $(document).on('click','.add_skill_category',function (){
        $('#skills_category_frm')[0].reset();
        $('#skills_category_id').val('')
        $('#skillsCategoryModal').modal('show');
    })

    $(document).on('click','.edit_skill_category',function (e){
        e.preventDefault();

        var category_id = $(this).data('category-id');

        $.ajax({
            url: `get-skill-category-details/`+category_id,
            success: function (res){
                if(res.success){
                    $('#skills_category_id').val(category_id);
                    $('#category_name').val(res.data.category_name);
                    $('#skillsCategoryModal').modal('show');
                }
            }
        });
    })

    $("#skills_category_frm").submit(function(e){
        e.preventDefault();
        $('.remove_custome_div').remove();

        if($(this).parsley().validate()) {
            let button = $(this).find('button[type="submit"]');

            $.ajax({
                url: SaveSkillCategoryApi,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#skills_category_frm").serialize(),
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

                        $('#skills_category_frm')[0].reset();
                        $('#category-table').DataTable().draw();
                        $('#skillsCategoryModal').modal('hide');
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
                        $("#skills_category_frm").find("input#" + x).after(
                            "<div  class='error remove_custome_div text-danger'  id=" + x +
                            "-error' class='error'>" + errorCode[x] + "</div>");
                    }
                },
            });
        }
    });
});
