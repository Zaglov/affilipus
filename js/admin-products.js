jQuery(document).ready(function(){






    jQuery('#imbaf_products [data-action="delete_product"]').bind('click',function(e){




        var data = {

            'action': 'imbaf_remove_product',
            'id': jQuery(this).attr('data-product-id')

        };

        var parent = jQuery(this).parent().parent();
        parent.remove();

        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(ajax_object.ajax_url, data, function(response) {




        });

    });


    /*function bind_buttons(){


        jQuery('#imbaf_feature_list input[data-action="delete_imbaf_feature"]').bind('click',function(){


            var parent = jQuery(this).parent().parent();
            parent.remove();



        });


    }







    bind_buttons();*/



});