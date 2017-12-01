(function($){
    $(document).ready(function(){
        var offset = $(".navbar").offset().top;
        $(document).scroll(function(){
            var scrollTop = $(document).scrollTop();
            if(scrollTop > offset){
                $(".navbar").addClass("navbar-fixed-top");
            }
            else {
                $(".navbar").removeClass("navbar-fixed-top");
            }
        });
    });
})(jQuery);