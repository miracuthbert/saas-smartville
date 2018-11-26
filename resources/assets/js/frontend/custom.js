// intro.js
const introJs = require('intro.js/intro');

// flatpickr
const flatpickr = require("flatpickr");

// bootstrap 4 popover
$(function () {
    $('[data-toggle="popover"]').popover()
});

// bootstrap 4 tooltip
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

// flex menu toggler
$('.flex-menu-toggler').on('click', function () {
    var target = $(this).data('target')

    $(target).toggleClass('d-block')
});

$(".datetimepicker").flatpickr({
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    // wrap: true,
    allowInput: true,
    // static: true,
    altFormat: "l F J, Y H:i K",
    altInput: true,
    altInputClass: "form-control"
});

$(".datepicker").flatpickr({
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "F j, Y",
    altInputClass: "form-control"
});

// input with checkbox toggle
$('input:checkbox.checkbox-toggle').on('change', function () {
    var target = $(this).data('target');
    var checkboxes = $(target).find('input:checkbox[data-parent="' + target + '"]');
    var state = $(this).prop('checked');

    if (state == true) {
        $(checkboxes).prop('checked', true);
        return;
    }

    $(checkboxes).prop('checked', false);
});

// input checkbox
$('input:checkbox').on('change', function (event) {
    var parent = $(this).data('parent');
    var selectBox = $(parent).find('input:checkbox.checkbox-toggle[data-target="' + parent + '"]');
    var checkboxes = $(parent).find('input:checkbox[data-parent="' + parent + '"]');
    var countCheckboxes = checkboxes.length;
    var countChecked = checkboxes.filter(':checked').length;

    if (countCheckboxes == countChecked) {
        $(selectBox).prop('indeterminate', false);
        $(selectBox).prop('checked', true);
        return;
    }

    if (countChecked == 0) {
        $(selectBox).prop('checked', false);
        $(selectBox).prop('indeterminate', false);
        return;
    }

    $(selectBox).prop('indeterminate', true)
});
