//  selectTag.js
jQuery.fn.selectTag = function () {
    var selectedTags = [];

    //  hide the first value from dropdown
    $(this).children('option:nth-child(1)').hide();

    //  get the click element of the option
    $(this).change(function () {
        $(this).children('option:nth-child(1)').prop("selected", false);
        var selectedElement = $(this).find(":selected").text();
        selectedTags.push(selectedElement);

        $(this).find(":selected").prop("selected", false);
        
        $(this).children('option:nth-child(1)').html(
            $.map(selectedTags, function (value) {
                return ('<span>' + value + '</span>');
            }).join()
        );
    });
}