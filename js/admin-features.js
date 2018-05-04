jQuery(document).ready(function(){


    
    function bind_buttons(){


        jQuery('#imbaf_feature_list input[data-action="delete_imbaf_feature"]').bind('click',function(){


            var parent = jQuery(this).parent().parent().parent().parent();
            parent.remove();



        });


    }


    jQuery('#imbaf_feature_list #new_feature_add').bind('click',function(e){

        var template = jQuery('#imbaf_feature_list #imbaf_feature_template').html();



        jQuery('#imbaf_feature_list .feature_list').append(template);

        jQuery('#imbaf_feature_list input[data-action="delete_imbaf_feature"]').unbind('click');

        bind_buttons();



    });









    bind_buttons();



});