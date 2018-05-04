

jQuery(document).ready(function($) {


    function imbaf_shortcode_gen(){


        var self = this;



        self.products = ko.viewmodel.fromModel([]);
        self.shortcodes = ko.viewmodel.fromModel([]);
        self.templates = ko.viewmodel.fromModel([]);
        self.shortcodeParams = ko.viewmodel.fromModel([]);
        self.shortcodeInfo = ko.observable(null);

        self.selectedProduct = ko.observable(null);
        self.selectedTemplate = ko.observable(null);
        self.selectedShortcode = ko.observable(null);



        self.listProducts = function(){

            var data = {

                'action': 'imbaf_codegen_list_products',
                'context': 'api'

            };


            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(ajax_object.ajax_url, data, function(response) {


                ko.viewmodel.updateFromModel(self.products, response.products);





            });

        };

        self.getShortcodes = function(){

            var data = {

                'action': 'imbaf_codegen_get_shortcodes',
                'product': self.selectedProduct(),
                'context': 'api'

            };


            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(ajax_object.ajax_url, data, function(response) {


                ko.viewmodel.updateFromModel(self.shortcodes, response.shortcodes);


            });

        };


        self.getTemplates = function(){

            var data = {

                'action': 'imbaf_codegen_get_templates',
                'context': 'api',
                'shortcode': self.selectedShortcode()

            };


            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(ajax_object.ajax_url, data, function(response) {


                if(response.templates.length == 0){

                    response.templates = [];

                }

                ko.viewmodel.updateFromModel(self.templates, response.templates);
                ko.viewmodel.updateFromModel(self.shortcodeParams, response.shortcodeInfo.args);
                ko.viewmodel.updateFromModel(self.shortcodeParams, response.shortcodeInfo.args);


                self.shortcodeInfo(response.shortcodeInfo.description);


            });

        };


        self.resetShortcode = function(){


            self.selectedShortcode(null);

        };


        self.shortcode = ko.computed(function(){



            var params = ko.viewmodel.toModel(self.shortcodeParams);
            var template = self.selectedTemplate();
            var code_name = self.selectedShortcode();


            if(code_name != undefined){


                var code = '['+code_name;


                if(params != undefined){

                   for(var i = 0; i<params.length;i++){


                       var value = params[i].value.value;
                       var type = params[i].value.type;
                       var default_value = params[i].value.default_value;
                       var name = params[i].name;

                       if(value != default_value){
                           
                           if(value == false && type != 'input'){value = 'false';}

                           if(value != null && value != ''){
                               code += ' '+name+'="'+value+'"';
                           }

                       }



                   }

                }


                if(template != undefined){


                    code += ' template="'+template+'"';

                }


                code += ']';

            } else {


                code = '';

            }



            return code;

        });

        self.selectedShortcode.subscribe(function(newVal){

            self.getTemplates();

        });

        self.listProducts();
        self.getShortcodes();


        new Clipboard('#imbaf_shortcode_generator_copy');
        

    }



    ko.applyBindings(new imbaf_shortcode_gen(),jQuery('#imbaf_shortcode_generator')[0]);

    jQuery("#imbaf_shortcode_generator textarea.the_shortcode").on("click", function () {
        jQuery(this).select();
    });



});