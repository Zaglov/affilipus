jQuery(document).ready(function(){





    jQuery("#imbaf_cdn_pictures input[type='text']").on("click", function () {
        jQuery(this).select();



    });



    jQuery('#imbaf_cdn_pictures [data-action="import_cdn_picture"]').bind('click',function(e){



        event.preventDefault();


        var importingButton = jQuery('#imbaf_cdn_picture_'+jQuery(this).attr('data-picture-id')+' .importing');
        var importActionButton = jQuery('#imbaf_cdn_picture_'+jQuery(this).attr('data-picture-id')+' .importAction');
        var importDialogSuccess = jQuery('#imbaf_cdn_picture_success_'+jQuery(this).attr('data-picture-id'));

        var data = {

            'action': 'imbaf_import_cdn_picture',
            'id': jQuery(this).attr('data-picture-id'),
            'post_id': jQuery(this).attr('data-post-id')

        };



        importingButton.show();
        importActionButton.hide();


        jQuery.post(ajax_object.ajax_url, data, function(response) {






            if(response == '1'){


                importingButton.hide();
                importDialogSuccess.show();

            } else {

                alert('Etwas ist schief gegangen. :('+response);

                importingButton.hide();
                importActionButton.show();
            }


        });








    });







});