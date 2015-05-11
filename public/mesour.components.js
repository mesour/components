/**
 * Mesour Selection Component - selection.js
 *
 * @author Matous Nemec (http://mesour.com)
 */
var mesour = !mesour ? {
    widgets: {}
} : mesour;

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (obj, start) {
        for (var i = (start || 0), j = this.length; i < j; i++) {
            if (this[i] === obj) {
                return i;
            }
        }
        return -1;
    };
}

(function ($) {
    mesour._core = mesour._core ? mesour._core : function () {

        this.createWidget = function (name, instance) {
            mesour[name] = mesour.widgets[name] = instance;
            return instance;
        };

        this.getQuery = function (full) {
            var query = window.location.search.substr(1);
            var vars = query.split('&');
            var data = {};
            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split('=');
                var key = decodeURIComponent(pair[0]),
                    value = decodeURIComponent(pair[1]);
                if (!full && key.substr(0, 2) === 'm_') {
                    continue;
                }
                data[key] = value;
            }
            return data;
        };

        this.serialize = function (obj, prefix) {
            var str = [];
            for (var p in obj) {
                if (obj.hasOwnProperty(p)) {
                    var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
                    str.push(typeof v == "object" ?
                        this.serialize(v, k) :
                    encodeURIComponent(k) + "=" + encodeURIComponent(v));
                }
            }
            return str.join("&");
        };

        this.createLink = function (link, handle, data) {
            var url = window.location.pathname;
            data = !data ? {} : data;

            var new_args = {};
            $.each(data, function (key, value) {
                new_args['m_' + link + '-' + key] = value;
            });

            var args = $.extend({}, this.getQuery(), new_args);

            args['m_do'] = link + '-' + handle;

            var serialized = this.serialize(args);
            if (serialized && serialized !== '') {
                url += '?' + serialized;
            }
            return url;
        };

        this.redrawCallback = function(r) {
            console.log(r);
        };

    };
    mesour.core = mesour.core ? mesour.core : new mesour._core();

    // console.log(mesour.core.createLink('pager', 'setPage', {page: 3}))

    $(document).on('click', '[data-mesour=ajax]:not(form)', function(e) {
        e.preventDefault();
        $.get($(this).attr('href')).complete(mesour.core.redrawCallback);
    });
})(jQuery);