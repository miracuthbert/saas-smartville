// ADMIN CUSTOM JS

// Core UI js
require('../admin/core');
// require('@coreui/coreui');

// simplemde
window.SimpleMDE = require('simplemde')

// intro.js
const introJs = require('intro.js/intro');

// bootstrap 4 popover
$(function () {
    $('[data-toggle="popover"]').popover()
});

// bootstrap 4 tooltip
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

// flatpickr
const flatpickr = require("flatpickr");

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

// table checkboxes
$('table input:checkbox#selectAll').on('change', function (event) {
    var checkboxes = $(this).parents('table').find('tbody td:first-of-type input:checkbox');
    var $state = $(this).prop('checked');

    if ($state == true) {
        $(checkboxes).prop('checked', true);
        return;
    }

    $(checkboxes).prop('checked', false);
});

$('table tbody td:first-of-type input:checkbox').on('change', function (event) {
    var selectBox = $(this).parents('table').find('thead input:checkbox#selectAll');
    var checkboxes = $(this).parents('table').find('tbody td:first-of-type input:checkbox');
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
