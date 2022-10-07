$(document).ready(function () {
  // show the alert
  setTimeout(function () {
    $('.alert-success').alert('close');
  }, 5000);
});

// Remove selected option Ajax Call
$('.remove-selected').click(function () {
  let fieldValue = $(this)
    .parent()
    .parent()
    .find('.value-change :selected')
    .val();
  let fieldName = $(this)
    .parent()
    .parent()
    .find('.value-change')
    .attr('field-parent');

  if (fieldValue !== '') {
    $.ajax({
      type: 'POST',
      url: '/create-exclude',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
      data: {
        fieldName: fieldName,
        fieldValue: fieldValue,
      },
      success: function (data) {
        console.log(data);
      },
    });

    $(this).parent().parent().find('.value-change :selected').remove();
  }
});

// // On change of select, place value in form field
// $('.value-change').change(function() {
//     $get_val = $(this).val();
//     $parent = $(this).parent().parent('.form-group');
//     value = $($parent.find('input')).val($get_val);
// });

// Add new factory option
// $('#add-factory-option').click(function() {
//   var factoryAppend =
//       '<div class="row mt-3">' +
//           '<div class="col-md-5">' +
//               '<input type="text" name="factory_option_new[]" id="factory_option" class="form-control" placeholder="e.g. LED Lights" />' +
//           '</div>' +
//           '<div class="col-md-5">' +
//               '<input type="number" name="factory_option_price_new[]" id="factory_option_price" class="form-control" placeholder="e.g. 189.99" step=".01" />' +
//           '</div>' +
//           '<div class="col-md-2">' +
//               '<button class="btn btn-sm btn-danger remove-option" type="button"><i class="fas fa-times" aria-hidden="true"></i></button>' +
//           '</div>' +
//       '</div>';
//   $('.add-factory-con').before(factoryAppend);
// });
//
// // Add new dealer option
// $('#add-dealer-option').click(function() {
//   var dealerAppend =
//       '<div class="row mt-3">' +
//           '<div class="col-md-5">' +
//               '<input type="text" name="dealer_option_new[]" id="dealer_option" class="form-control" placeholder="e.g. LED Lights" />' +
//           '</div>' +
//           '<div class="col-md-5">' +
//               '<input type="number" name="dealer_option_price_new[]" id="dealer_option_price" class="form-control" placeholder="e.g. 20.99" step=".01" />' +
//           '</div>' +
//           '<div class="col-md-2">' +
//               '<button class="btn btn-sm btn-danger remove-option" type="button"><i class="fas fa-times" aria-hidden="true"></i></button>' +
//           '</div>' +
//       '</div>';
//   $('.add-dealer-con').before(dealerAppend);
// });

// Remove option
// $("body").on("click", ".remove-option", function() {
//   $(this).parent().parent().remove();
// });

// Add date picker to inputs
// $('#due_date').datepicker({
//   format: 'dd/mm/yyyy',
//   autoclose: true,
// });
//
// $('#delivery_date').datepicker({
//   format: 'dd/mm/yyyy',
//   autoclose: true,
// });
//
// $('#vehicle_registered_on').datepicker({
//   format: 'dd/mm/yyyy',
//   autoclose: true,
// });
//
// $('#finance_commission_paid').datepicker({
//   format: 'dd/mm/yyyy',
//   autoclose: true,
// });
//
// $('#invoice_broker_paid').datepicker({
//   format: 'dd/mm/yyyy',
//   autoclose: true,
// });
//
// $('#commission_broker_paid').datepicker({
//   format: 'dd/mm/yyyy',
//   autoclose: true,
// });
//
// $.fn.datepicker.dates['qtrs'] = {
//   days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
//   daysShort: ["Sun", "Moon", "Tue", "Wed", "Thu", "Fri", "Sat"],
//   daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
//   months: [".00", ".25", ".50", ".75", "", "", "", "", "", "", "", ""],
//   monthsShort: [".00", ".25", ".50", ".75", "", "", "", "", "", "", "", ""],
//   today: "Today",
//   clear: "Clear",
//   format: "dd/mm/yyyy",
//   titleFormat: "MM yyyy",
//   /* Leverages same syntax as 'format' */
//   weekStart: 0
// };

$('#model_year')
  .datepicker({
    format: 'yyyy MM',
    minViewMode: 1,
    autoclose: true,
    language: 'qtrs',
  })
  .on('click', function (event) {
    $('.month').each(function (index, element) {
      if (index > 3) $(element).remove();
    });
  });

$('.model_year')
  .datepicker({
    format: 'yyyy MM',
    minViewMode: 1,
    autoclose: true,
    language: 'qtrs',
  })
  .on('click', function (event) {
    $('.month').each(function (index, element) {
      if (index > 3) $(element).remove();
    });
  });

// Tooltip init
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
