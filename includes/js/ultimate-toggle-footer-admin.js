//Validate Fields
var filters = {
    validate_url : {
        re : /(http|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/,
        error : 'ERROR: Invalid URL.'
    }
};

var validate = function(klass, str) {
    var valid = true,
        error = '';
    for (var f in filters) {
        var re = new RegExp(f);
        if (re.test(klass)) {
            if (str && !filters[f].re.test(str)) {
                error = filters[f].error;
                valid = false;
            }
            break;
        }
    }
    return {
        error: error,
        valid: valid
    }
};

jQuery( document ).ready(function( $ ) {

	jQuery('.validate').blur(function() {
		var test = validate(
			jQuery(this).attr('class'),
			jQuery(this).val()
		);
		if (!test.valid) {
			//jQuery('#errors').append('<p>' + test.error + '</p>');
			alert (test.error);
		}
	});

});