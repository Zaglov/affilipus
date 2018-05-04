/*
 * jQuery functions for widget controls in the backend
 */
jQuery(document).ready(function($){



    imbaaStorefrontImgSelector = {

        uploader: function( field_id ) {

            var mediaUploader = wp.media({
                title : 'Choose image',
                multiple : false,
                library : {
                    type : 'image'
                },
                button : {
                    text : 'Choose image'
                }
            });

            mediaUploader.on( 'select', function() {

                var attachment = mediaUploader.state().get('selection').first().toJSON();
                imbaaStorefrontImgSelector.save( field_id, attachment)

            });

            mediaUploader.open();
            return false;

        },

        save: function ( field_id, attachment ) {

            $( '#' + field_id + 'image_id' ).val( attachment.id );
            $( '#' + field_id + 'img' ).attr( 'src', attachment.url );
        },

        clear: function(field_id){

            $( '#' + field_id + 'image_id' ).val( '' );
            $( '#' + field_id + 'img' ).attr( 'src','');


        }

    };

});
