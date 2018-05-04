jQuery(document).ready(function(){

    
    function imbaf_properties_manager(){



        var self = this;



        self.allProperties = ko.viewmodel.fromModel([]);


        var data = {

            'action': 'imbaf_property_gen_get_list',
            'post_id':localize.post_id

        };


        self.getProperties = function(){

            jQuery.post(ajax_object.ajax_url, data, function(response) {

                console.log(response);

                ko.viewmodel.updateFromModel(self.allProperties, response.properties);

            });

        };



        self.addProperty = function(property){


            property.value(null);
            property.selected(true);


        };


        self.deleteProperty = function(property){

            property.value(null);
            property.selected(false);

        };


        self.getProperties();


    }



    ko.applyBindings(new imbaf_properties_manager(),jQuery('#imbaf_product_properties')[0]);


});