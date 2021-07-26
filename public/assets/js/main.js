$(document).ready(function(){
    $(".menu-button .d-none .d-md-block")
    // Add event listener
    $("#logout").click(function(){
        showLoading();
        var data = $(this).data();
        var options = {
            url: data.act,
            method: 'POST',
            success: function(){
                endLoading();
                location.href = data.link
            }, 
            error: function(err){
                var err = JSON.parse(err.responseText);
                endLoading();

                makeToast({
                    wrapper: '.navbar',
                    id: 'toast-error-logout',
                    delay: 1500,
                    autohide: true,
                    show: true,
                    bg: 'bg-danger',
                    textColor: 'text-white',
                    time: waktu(null, 'HH:mm'),
                    toastId: 'logout-error',
                    title: 'Gagal, Terjadi kesalahan',
                    message: err.message,
                    type: 'danger', 
                    hancurkan: true
                });
            }, 
        };

        $.ajax(options)
    });

    $('#akun').click(function(){
        location.href = $(this).data('link')
    })
})