(function($){
    $(document).ready(function(){
        $(".btn-search").on("click", function() {
            if($(this).attr("type") != "submit") {
                $(".search-form").addClass("open");
                $(this).prop("type", "submit");
                $(".search-form .form-control").one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(e) {
                    $(this).focus();
                });
                return false;
            }
        });
    });
})(jQuery);