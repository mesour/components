/**
 * Mesour Editable Component - mesour.editable.FieldEditor.js
 * @author Matous Nemec (http://mesour.com)
 */
var mesour = !mesour ? {} : mesour;
mesour.dateTimePicker = !mesour.dateTimePicker ? {} : mesour.dateTimePicker;

(function($) {

	var DateTimePicker = function(options) {

		var _this = this;

		this.create = function(input, dateFormat) {
			input.datetimepicker({
				format: dateFormat,
				focusOnShow: false,
				hide: function() {
					input.focus();
				},
				useCurrent: false
			});
		};

		this.show = function(input) {
			input.data("DateTimePicker").show();
		};

		this.hide = function(input) {
			input.data("DateTimePicker").hide();
		};

		this.destroy = function(input) {
			if (input.data("DateTimePicker")) {
				input.data("DateTimePicker").destroy();
			}
		};

	};

	mesour.core.createWidget('dateTimePicker', new DateTimePicker());
})(jQuery);