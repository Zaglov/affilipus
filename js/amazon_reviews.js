//alert( settings.some_string);

jQuery(document).ready(function($){


        var postdata = {
            action: 'imbaf_amazon_reviews_link',
            product_id: settings.product_id
        };


        jQuery.post(settings.ajax_url, postdata, function(response) {

            $('#'+settings.container_id).html(response);

        });






    }
);