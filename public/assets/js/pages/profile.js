$(document).ready(function () {
    setTimeout(function () {
        $('#pass').val('')
    }, 1000);
    var data = persiapan();
    add_eventlistener(data);
    inisialisasi(data);
});

function persiapan(){
    var data = {};
    data.readURL = function(input, prevEl) {
        console.log(input);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $(prevEl).attr('src', e.target.result);
            }
    
            reader.readAsDataURL(input.files[0]);
        }
    }

    return data;
}
function add_eventlistener(data) {
    $('#pp').hover(function () {
        $('#file').show();
    }, function () {
        $('#file').hide();
    });

    $("#n-pp").change(function () {
        data.readURL(this, '#pp-preview');
    })
};

function inisialisasi(data) {
    var options = {
        submitError: function (response) {
            endLoading();
            var responseText = JSON.parse(response.responseText)
            $('#alert_danger strong').html(responseText.message).parent().show();
            $('#btn-submit').prop('disabled', false);
        },
        sebelumSubmit: function (input,) {
            showLoading();
            $('#alert_danger strong').html('').parent().hide();
            $('#btn-submit').prop('disabled', true);
        },
        submitSuccess: function (res) {
            endLoading();
            setTimeout(function(){location.reload()}, 2000);
        }
    }
    $("#form-edit-user").initFormAjax(options);
}

