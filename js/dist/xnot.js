var il;
(function (il) {
    var plugins;
    (function (plugins) {
        var xnot;
        (function (xnot) {
            var UI = (function () {
                function UI() {
                }
                UI.init = function () {
                    this.initClasses();
                    this.register();
                    this.adaptLeftNav();
                };
                UI.initClasses = function () {
                    $('.xnot-alert').each(function () {
                        var $strong = $(this).find('strong.xnot-title');
                        var $xnot_body = $(this).find('span.xnot-body');
                        var $xnot_body_p = $(this).find('span.xnot-body p');
                        if ($strong.length && $strong[0].scrollWidth > $strong.innerWidth()) {
                            $(this).addClass('xnot-content-hidden');
                        }
                        if ($xnot_body.length && $xnot_body[0].scrollWidth > $xnot_body.innerWidth()) {
                            $(this).addClass('xnot-content-hidden');
                        }
                        if ($xnot_body_p.length && $xnot_body_p[0].scrollWidth > $xnot_body_p.innerWidth()) {
                            $(this).addClass('xnot-content-hidden');
                        }
                    });
                };
                UI.register = function () {
                    var self = this;
                    $(window).on('resize', function () {
                        self.initClasses();
                    });
                    $(document).on('click', '.xnot-content-hidden', function () {
                        $(this).removeClass('xnot-content-hidden');
                        $(this).addClass('xnot-content-shown');
                    });
                    $(document).on('click', '.xnot-content-shown', function () {
                        $(this).removeClass('xnot-content-shown');
                        $(this).addClass('xnot-content-hidden');
                    });
                };
                UI.adaptLeftNav = function () {
                    var e = $("#left_nav");
                    if (e.length !== 0) {
                        $('#xnot_container').addClass("ilLeftNavSpace");
                    }
                    else {
                        $("#xnot_container").removeClass("ilLeftNavSpace");
                    }
                };
                return UI;
            }());
            xnot.UI = UI;
        })(xnot = plugins.xnot || (plugins.xnot = {}));
    })(plugins = il.plugins || (il.plugins = {}));
})(il || (il = {}));
$(document).ready(function () {
    il.plugins.xnot.UI.init();
});
