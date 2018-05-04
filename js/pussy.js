jQuery(document).ready(function($){


        var postdata = {
            action: 'imbaf_check_license'
        };

        jQuery.post(ajax_object.ajax_url, postdata, function(response) {

           if(response == 'expired'){

               $('.imbaf-template-container').remove();

           }

        });


    }
);