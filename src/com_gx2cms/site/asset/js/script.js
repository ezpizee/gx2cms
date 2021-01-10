var GX2CMS = function($){

    var that = {};

    if ($ === null) {
        var $intervalCount = 0, $interval = setInterval(function(){
            if ($intervalCount >= 100) {
                clearInterval($interval);
            }
            else if (typeof jQuery !== "undefined") {
                $ = jQuery;
                init();
                clearInterval($interval);
            }
            $intervalCount++;
        }, 100);
    }

    function renderSectionUrl() {
        var basePage = $('input[name="basePage"]').val();
        var layout = $('input[name="layout"]').val();
        var page = $('select[name="page"] option:selected').val();
        var section = $('select[name="section"] option:selected').val();
        if (basePage) {
            if (layout) {
                if (page) {
                    if (section) {
                        return window.location.protocol+'//'+
                            window.location.host+basePage+'?layout='+layout+'&page='+page+'&section='+section;
                    }
                    else {
                        alert("Section is required, but missing");
                        return '#';
                    }
                }
                else {
                    alert("Page is required, but missing");
                    return '#';
                }
            }
            else {
                alert("Layout is required, but missing");
                return '#';
            }
        }
        else {
            alert("Base Page is required, but missing");
            return '#';
        }
    }
    
    function init() {
        $('#render-section').click(function(e){
            //e.preventDefault();
            var url = renderSectionUrl();
            $(this).attr('href', url);
            if (url === '#') {
                return false;
            }
            return true;
        });
    }

    return that;

}(typeof jQuery !== "undefined" ? jQuery : null);