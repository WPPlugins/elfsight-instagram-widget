(function($, ajaxObj) {
    $(function() {
        window.onmessage = function(e) {
            if (e.data && e.data.params) {
                $.post(ajaxObj.ajaxurl, {
                    action: ajaxObj.action,
                    nonce: ajaxObj.nonce,
                    params: e.data.params
                });
            }
        };
    });
})(window.jQuery || {}, elfsightPortalAjaxObj || {});