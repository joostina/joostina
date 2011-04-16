$(document).ready(function() {


    var uploader = new qq.FileUploader({
        element: $('#file-uploader')[0],
        multiple: false,
        action:  'ajax.index.php?option=installer' ,
        button_label: 'Загрузить архив',
        params: {
            task: 'upload'
        },
        debug: true,
        allowedExtensions: ['zip'],
        onComplete: function(id, fileName, responseJSON) {
            $('#installer_result').html(responseJSON.message);
        }
    });


});	