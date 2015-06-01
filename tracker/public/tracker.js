(function (window, document) {
    "use strict";

    var data = [],
        gif  = '//localhost:4000/tracker/public/index.php/img.gif',
        xhr,
        queryString = '';

    // 1. gather data
    data['screen_width']  = window.screen.width;
    data['screen_height'] = window.screen.height;

    var _d = [];
    for (var name in data) {
        _d.push([ name, '=', encodeURIComponent(data[name]) ].join(''));
    }

    queryString = _d.join('&');

    // 2. trigger a GET request
    // See: https://themouette.github.io/slides-edu-frontend/lesson3.html#/2/6
    function create_xhr_object() {
        if (window.XMLHttpRequest) {
            return new XMLHttpRequest();
        }

        if (window.ActiveXObject) {
            var names = [
                "Msxml2.XMLHTTP.6.0", "Msxml2.XMLHTTP.3.0",
                "Msxml2.XMLHTTP", "Microsoft.XMLHTTP"
            ];

            for(var i in names) {
                try {
                    return new ActiveXObject(names[i]);
                } catch (e) {}
            }
        }

        return null;
    }

    xhr = create_xhr_object();
    xhr.open('GET', [ gif, queryString ].join('?'));
    xhr.send();
})(window, document);
