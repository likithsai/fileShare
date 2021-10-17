$(function () {
    $('#addMultiFiles').click(function (e) {
        e.preventDefault();
        $('ol.filelist').append('<li class="d-flex align-items-center justify-content-between my-1"><div><input type="file" name="addFiles[]" /></div><button type="button" class="btn btn-danger btn-sm deleteMultiFile text-uppercase">Delete</button></li>');
    });

    //  file dialog for upload list
    $('#addMultiUploadFiles').click(function (e) {
        e.preventDefault();
        $('ol.uploadList').append('<li class="d-flex align-items-center justify-content-between my-1"><div><input type="file" name="addFiles[]" /></div><button type="button" class="btn btn-danger btn-sm deleteMultiFile text-uppercase">Delete</button></li>');
    });

    //  Delete files 
    $(document).on('click', 'button.deleteMultiFile, button.deleteMultiUploadFile', function (e) {
        e.preventDefault();
        $(this).parent().remove();
    });

    $(".file-search").on("keyup", function () {
        var target = $(this).attr('data-table-target');
        var value = $(this).val().toLowerCase();

        $('#' + target + ' tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $('.js-example-basic-multiple').select2({
        placeholder: 'Select user',
        width: '100%'
    });
});