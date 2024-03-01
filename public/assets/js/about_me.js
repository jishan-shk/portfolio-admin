$(function (){
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    $('#work_started_from').on('change',function (){
        var currentDate = new Date();

        // Get the given date from the input field
        var startDate = new Date($('#work_started_from').val());

        // Calculate the difference in milliseconds
        var timeDifference = currentDate - startDate;

        // Convert the difference to months and years
        var totalMonths = Math.floor(timeDifference / (1000 * 60 * 60 * 24 * 30.44));
        var totalYears = Math.floor(totalMonths / 12);

        var resultMessage = totalYears == 0 ? "Fresher." : totalYears + " years and " + (totalMonths % 12) + " months.";
        $('.total_exp').text(resultMessage);
        $('#total_experience').val(resultMessage);
    })

    $('#work_started_from,#dob').pickadate({
        max: new Date(),
        selectMonths: true,
        selectYears: true
    });

    $("#PersonalInfoForm").submit(function(e){
        e.preventDefault();
        $('.remove_custome_div').remove();

        if($(this).parsley().validate()) {
            var formData = new FormData(this);
            let button = $(this).find('button[type="submit"]');

            $.ajax({
                url: SAVE_API,
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

                        setTimeout(() => {
                            location.reload();
                        },3000)
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
                        $("#PersonalInfoForm").find("input#" + x).after(
                            "<div  class='error remove_custome_div text-danger'  id=" + x +
                            "-error' class='error'>" + errorCode[x] + "</div>");
                    }
                },
            });
        }
    });
})
