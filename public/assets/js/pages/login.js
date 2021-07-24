$(document).ready(function(){
    var formid = "<?php echo $formid ?>"

    var options = {
        submitError: function(response){
            endLoading();
            var responseText  = JSON.parse(response.responseText)
            $('#alert_danger').text(responseText.message).show();
            $('#btn-login').prop('disabled', false);
        },
        sebelumSubmit: function(input, ){
            showLoading();
            $('#alert_danger').text('').hide();
            $('#btn-login').prop('disabled', true);
        }, 
        submitSuccess: function(){
            endLoading();
            setTimeout(function(){
                location.href = path + 'admin/dashboard';
            }, 50)
        }
    }
    $("#" + formid).initFormAjax(options)
});