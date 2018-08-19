$(function () {

        $( ".profile-usermenu ul li a" ).each(function( index ) {
            //alert(window.location.pathname);
            if(window.location.pathname === $( this ).attr("href")){
                $( this ).addClass("active");
                $( this ).parent().addClass("active");
            }
        });

    }
);