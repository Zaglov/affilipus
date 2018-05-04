jQuery(document).ready(function($) {


    // This is a simple *viewmodel* - JavaScript that defines the data and behavior of your UI
    function imbaf_search() {


        var self = this;


        self.affiliatePartner = ajax_object.partner;
        self.searchTerm = ko.observable();
        self.country = ko.observable();

        self.selectedShop = ko.observable(false);


        self.searched = ko.observable(false);


        self.loading = ko.observable(false);
        self.token = ko.observable();

        self.page = ko.observable(1);
        self.maxPages = ko.observable(1);

        self.allowSearch = ko.observable(false);

        self.productList  = ko.viewmodel.fromModel([]);

        self.resultCount = ko.computed(function(){

            return self.productList().length;

        });


        self.taxonomyList = ko.viewmodel.fromModel([]);
        self.brandList = ko.viewmodel.fromModel([]);
        self.typeList = ko.viewmodel.fromModel([]);
        self.allProducts = ko.viewmodel.fromModel([]);


        self.searchTerm.subscribe(function(newValue) {


            if(newValue.length > 0){

                self.allowSearch(true);

            } else {
                self.allowSearch(false);
            }



            self.searched(false);
            self.page(1);
            self.maxPages(1);

        });


        self.startSearch = function(){


            if(self.allowSearch() == true){


                self.loading(true);




                jQuery('#imbaf_console').html('Suche nach: '+self.searchTerm()+' bei '+self.affiliatePartner);


                var data = {

             'action': 'imbaf_search_product',
             'partner': self.affiliatePartner,
                'page': self.page(),
                'term': self.searchTerm(),
                'country': self.country(),
                'shop': self.selectedShop()

             };


                // We can also pass the url value separately from ajaxurl for front end AJAX implementations
                 jQuery.post(ajax_object.ajax_url, data, function(response) {


                     ko.viewmodel.updateFromModel(self.productList, []);
                     ko.viewmodel.updateFromModel(self.typeList, []);
                     ko.viewmodel.updateFromModel(self.brandList, []);
                     ko.viewmodel.updateFromModel(self.allProducts, []);

                     console.log('Response',response);

                     self.searched(false);

                     jQuery('#imbaf_console').html('Suche abgeschlossen. Token: '+response.token);

                     self.token(response.token);
                     self.maxPages(response.maxPages);


                     ko.viewmodel.updateFromModel(self.brandList, response.taxonomies.brands);
                     ko.viewmodel.updateFromModel(self.typeList, response.taxonomies.types);
                     ko.viewmodel.updateFromModel(self.productList, response.products);
                     ko.viewmodel.updateFromModel(self.allProducts, response.allProducts);

                     self.searched(true);
                     self.loading(false);



                 });

            }





        }

        self.prevPage = function(){


            var page = self.page();


            page = page-1;


            if(page<=0){page = 1;}

            self.page(page);


            self.startSearch();



        }

        self.nextPage = function(){


            var page = self.page();


            page = page+1;

            self.page(page);


            self.startSearch();



        }

        self.importProduct = function(index,product,publish){

            var token = self.token();

            var data = {

                'action': 'imbaf_import_product',
                'partner': self.affiliatePartner,
                'index': index,
                'token': token,
                'product_name': product.product_name,
                'selected_type': product.selected_type,
                'selected_brand': product.selected_brand,
                'selected_price': product.selected_price,
                'product_parent': product.product_parent,
                'product_import_values': product.product_import_values,
                'product_image_name_pattern': product.product_image_name_pattern,
                'publish': publish
            };

            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(ajax_object.ajax_url, data, function(response) {




                console.log('Import Response',response);



            });

            alert('Produkt wird im Hintergrund importiert. Es kann ein wenig dauern, bis das Produkt im Backend erscheint.');
            product.product_processed(true);




        }

        self.getRelatedProducts = function(selected_brand,selected_type,index){


            var products = self.productList();



            var data = {

                'action': 'imbaf_related_products',
                'selected_brand': selected_brand,
                'selected_type': selected_type

            };

            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(ajax_object.ajax_url, data, function(response) {



                console.log(response);

                products[index].product_related(response);

                //product.product_related = 'foobar';


            });


        }

        self.init = function(){

            $('#imbaf_search').css('display','block');


            //self.searchTerm('Bier');


            //self.startSearch();


        }

        self.init();



    }

// Activates knockout.js
    ko.applyBindings(new imbaf_search(),jQuery('#imbaf_search')[0]);


});