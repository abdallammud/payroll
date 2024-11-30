function isNumberKey(e) {
    var charCode = (e.which) ? e.which : e.keyCode;
    if (charCode !== 8 && charCode !== 46 && !/\d/.test(String.fromCharCode(charCode))) {
    return false;
    }
    return true;
}
function isNumberOrCommaKey(e) {
    // Allow only numbers, period, comma, and delete keys
    var charCode = (e.which) ? e.which : e.keyCode;
    if (charCode !== 8 && charCode !== 46 && charCode !== 188 && !/[0-9,]/.test(String.fromCharCode(charCode))) {
    return false;
    }
    return true;
}
function clearErrors() {
    $('input, select, textarea').removeClass('error')
    $('.form-error').css('display', 'none')
}
function showError (msg, id) {
    let span = $('#'+id).parents('.form-group').find('.form-error');
    let span2 = $('#'+id).parents('.form-outline').find('.form-error');
    // let span3 = $('#'+id).parents('div').find('.form-error');
    let span4 = $('#'+id).parents('.div').find('.form-error');
    $(span).html(msg)
    $(span).show();

    $(span2).html(msg)
    $(span2).show();

    $(span4).html(msg)
    $(span4).show();

    $('#'+id).addClass('error')
}
function validateField(value, errorMessage, fieldId) {
    if (!value) {
        showError(errorMessage, fieldId);
        return false;
    }
    return true;
}
function isUserNameValid(username) {
    const res = /^[a-zA-Z0-9_\.]+$/.exec(username);
    const valid = !!res;
    return valid;
}
function isValidPhone(phone) {
    const res = /^[0-9-+]+$/.exec(phone);;
    const valid = !!res;
    return valid;
}
function isNumber(evt)  {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}
function extractEmails ( text ){
    return text.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9_-]+)/gi);
}
function formatDateRange(startDate, endDate) {
    // Parse input dates
    const start = new Date(startDate);
    const end = new Date(endDate);
    
    // Define options for formatting
    const options = { month: 'short', day: 'numeric' };
    const yearOptions = { ...options, year: 'numeric' };
    
    // Format the dates
    const startFormatted = start.toLocaleDateString('en-US', options);
    const endFormatted = end.toLocaleDateString('en-US', yearOptions);

    // Check if the year is the same
    if (start.getFullYear() === end.getFullYear()) {
        // Same year
        return `${startFormatted} - ${endFormatted}`;
    } else {
        // Different years
        const startFormattedWithYear = `${startFormatted} ${start.getFullYear()}`;
        return `${startFormattedWithYear} - ${endFormatted}`;
    }
}
function validateForm(form) {
    let error = false; 
    if(form instanceof jQuery || form instanceof HTMLElement) {
        $(form).find('.validate').each(function() { 
            let input = $(this); 
            let value = input.val(); 
            let message = input.data('msg');
            console.log(form)
            if(!message) message = "This is required";
            let id = input.attr('id');  
            error = !validateField(value, message, id) || error; 
        }); 
    }

    return error;
}
function formatMoney(amount, currencySymbol = '$', decimals = 2) {
    // Ensure the number is a valid value and round to the specified decimals
    amount = parseFloat(amount).toFixed(decimals);

    // Split the amount into integer and decimal parts
    let [integerPart, decimalPart] = amount.split('.');

    // Add thousands separator to the integer part
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    // Combine integer part, decimal part and currency symbol
    return currencySymbol + integerPart + (decimalPart ? '.' + decimalPart : '');
}
