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
                };
                UI.initClasses = function () {
                    $('.xnot-alert').each(function () {
                        var $strong = $(this).find('strong.xnot-title');
                        var $xnot_body = $(this).find('span.xnot-body');
                        var $xnot_body_p = $(this).find('span.xnot-body p');
                    });
                };
                UI.register = function () {
                    var self = this;
                    $(window).on('resize', function () {
                        self.initClasses();
                    });
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
