/* Global JS */

/* handle click events */
$(document).ready(function() {
    // close alerts
    $('#alert-container').on('click', '.close', function() {
        $(this).closest('.alert').remove();
    });
    
    // enable popovers
    // $('[data-toggle="popover"]').popover();
});

function postFileAjax(url, data, successCB, errorCB) {
    $.ajax({
		method: 'POST',
		url: url,
		data: data,
		error: function(resp) {
            if (errorCB)
                errorCB(resp);
        },
		success: function(resp) {
            if (successCB)
                successCB(resp);
        },
        cache: false,
        contentType: false,
        processData: false
	});
}

function postAjax(url, data, successCB, errorCB, hasFile, loadingParent, hideLoading) {
    if (loadingParent)
        $(loadingParent).addClass('loading');
    let config = {
		method: 'POST',
		url: url,
		data: data,
		error: function(resp) {
            if (errorCB)
                errorCB(resp);
        },
		success: function(resp) {
            if (successCB)
                successCB(resp);
        },
        complete: function() {
            if (hideLoading !== false && loadingParent)
                $(loadingParent).removeClass('loading');
        }
	}
    if (hasFile) {
        config.cache = false;
        config.contentType = false;
        config.processData = false;
    }
    $.ajax(config);
}

function getAjax(url, data, successCB, errorCB, loadingParent, hideLoading) {
    if (loadingParent)
        $(loadingParent).addClass('loading');
    $.ajax({
		method: 'GET',
		url: url,
		data: data,
		error: function(resp) {
            if (errorCB)
                errorCB(resp);
        },
		success: function(resp) {
            if (successCB)
                successCB(resp);
        },
        complete: function() {
            if (hideLoading !== false && loadingParent)
                $(loadingParent).removeClass('loading');
        }
	});
}

function clearFormErrors(form) {
    $.each(form.find('.error-message'), function(i, e) {
        $(e).html('');
    });
}

function showFormErrors(form, error) {
    if (error.status == 400 && error.responseJSON && error.responseJSON.data) {
        // clear all previous errors
        clearFormErrors(form);
        // main form error
        let formError = form.find('.form-error');
        let errors = error.responseJSON.data || {};
        // if (formError.length && errors.error)
        //     formError.html(errors.error);
        for (let name in errors) {
            console.log(name)
            let errorFor = form.find("[data-errorFor='" + name + "']");
            console.log(errorFor)
            if (errorFor.length)
                errorFor.text(errors[name]);
        }
    }
}

function showAlert(type, header, message) {
    let alertType, alertIcon;
	switch (type) {
		case 'error':
			alertType = 'alert-danger';
			alertIcon = 'fa-exclamation-circle';
			break;
		case 'info':
			alertType = 'alert-info';
			alertIcon = 'fa-exclamation-triangle ';
			break;
		case 'warning':
			alertType = 'alert-warning';
			alertIcon = 'fa-exclamation-triangle ';
			break;
		case 'success':
			alertType = 'alert-success';
			alertIcon = 'fa-check';
			break;
	}
	
	if (alertType && alertIcon) {
		let id = new Date().getTime().toString();
		let alertBody = '<div id="' + id + '" class="alert ' + alertType + '">\
			<i type="button" class="close fa fa-close pointer" aria-hidden="true"></i>\
			<div class="mb12"><h4 class="nomar"><i class="icon v-middle fa ' + alertIcon + '"></i> <span class="alert-header">' + header + '</span></h4></div>\
			<div class="alert-message">' + message + '</div>\
		</div>';
		$('#alert-container').append(alertBody);
		setTimeout(function() {
			$('#' + id).remove();
		}, 3000);
	}
}

function showErrorResponseAlert(error) {
    let status = error.responseJSON ? error.responseJSON.status || 'error' : 'error';
	let message = error.responseJSON ? error.responseJSON.message || 'Unknown Error' : 'Unknown Error';
	status = ['error', 'info', 'warning', 'success'].indexOf(status) > -1 ? status : 'error';
	let header = status.charAt(0).toUpperCase() + status.substr(1).toLowerCase();
    showAlert(status, header, message);
}

function showLoading(wpr = 'body') {
    $(wpr).addClass('loading');
}

function hideLoading(wpr = 'body') {
    $(wpr).removeClass('loading');
}