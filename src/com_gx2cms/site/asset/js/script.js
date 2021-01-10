var GX2CMS = function($){

    var that = {};

    if ($ === null) {
        var $intervalCount = 0, $interval = setInterval(function(){
            if ($intervalCount >= 100) {
                clearInterval($interval);
            }
            else if (typeof jQuery !== "undefined") {
                $ = jQuery;
                clearInterval($interval);
            }
            $intervalCount++;
        }, 100);
    }

    that.renderSection = function(){
        var basePage = $('input[name="basePage"]').val();
        var layout = $('input[name="layout"]').val();
        var page = $('select[name="page"] option:selected').val();
        var section = $('select[name="section"] option:selected').val();
        if (basePage) {
            if (layout) {
                if (page) {
                    if (section) {
                        var url = window.location.protocol+'//'+
                            window.location.host+basePage+'?layout='+layout+'&page='+page+'&section='+section;
                        window.location = url;
                    }
                    else {
                        alert("Section is required, but missing")
                    }
                }
                else {
                    alert("Page is required, but missing")
                }
            }
            else {
                alert("Layout is required, but missing")
            }
        }
        else {
            alert("Base Page is required, but missing")
        }
    };

    return that;

}(typeof jQuery !== "undefined" ? jQuery : null);