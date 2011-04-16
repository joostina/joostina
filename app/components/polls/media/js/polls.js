$(document).ready(function() {

    $(".poll_form").validate({

        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                $(".poll_errors").show();
            } else {
                $(".poll_errors").hide();
            }
        },

        errorPlacement: function(error, element) {
            error.hide();
        },

        submitHandler: function(form) {
            poll_ajax_submit_handler(form);
        }

    });

    function poll_ajax_submit_handler(form) {

        //alert('123');
        //return;

        $(form).ajaxSubmit({
            clearForm: true,
            success:  function(answ) {
                if (answ.extradata !== undefined) {
                    if (answ.extradata.redirect !== undefined) {
                        //joostina_hashchange(answ.extradata.redirect.url);
                        //$('#poll1_wrap').html(answ.extradata.component);

                        if (answ.extradata.state == 'error') {
                            $().toastmessage('showErrorToast', answ.extradata.redirect.message);
                            return;
                        }
                        else {
                            $.getJSON("polls/1", loadResults);
                            if (answ.extradata.redirect.message) {
                                $().toastmessage('showSuccessToast', answ.extradata.redirect.message);
                                //alert(answ.extradata.redirect.message);
                            }
                        }
                        answ.extradata.redirect = '';
                        return;
                    }
                }
            }//success
        });
    }


});

function loadResults(data) {
    $.colorbox({html:data.component, width:'700px', close: 'Закрыть'})
    //$('#poll1_wrap').html(data.component);
}