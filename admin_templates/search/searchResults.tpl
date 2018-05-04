{literal}
<!--<pre id="imbaf_console"></pre>-->

<!-- ko if: resultCount() == 0 && searched() == true && loading() == false -->

	<p>Leider konnten keine Ergebnisse gefunden werden.</p>

<!-- /ko -->

<!-- ko if: loading() == true -->

	<p>Lädt... bitte etwas Geduld :)</p>

<!-- /ko -->

<!-- ko if: resultCount() > 0 && loading() == false && searched() == true -->

	<h2 class="screen-reader-text">Suchergebnisse</h2>

	<!--<pre data-bind="text: ko.toJSON($data, null, 2)"></pre>-->

	<table class="wp-list-table widefat fixed striped">
		<thead>
		<tr>



			<th scope="col" class="manage-column column-primary">
				Titel und Beschreibung
			</th>

			<th scope="col" class="manage-column">
				Bild
			</th>


			<th scope="col" class="manage-column">
				Datenimport
			</th>

		</tr>
		</thead>

		<tbody id="the-list" data-bind="foreach: $data.productList">


		<tr class="hentry" data-bind="visible: $data.product_processed() != true">


			<td class="column-primary">


				<h4>Produktbezeichnung</h4>

				<textarea style="width:100%;" rows="5" data-bind="value: product_name" title="Produktbezeichnung"></textarea>


				<h4>Muster für Umbenennung von Bildern</h4>
				<input type="text" data-bind="value: $data.product_image_name_pattern" style="width:100%;">

				<h4>Produktbeschreibung</h4>


				<div data-bind="html: product_description"></div>

				<h4>Produktfeatures</h4>

				<ul data-bind="foreach: product_features()">

					<li data-bind="text: $data"></li>

				</ul>

			</td>


			<td class="column-secondary" style="text-align:center;">


				<!-- ko if: $data.product_pictures() != null && $data.product_pictures().length != 0 -->


				<img data-bind="attr: {src: $data.product_pictures()[0]['large']}" src="" style="width:100%; max-width: 300px; height:auto;">

				<!-- ko if: $data.product_pictures().length > 1 -->

				<div data-bind="foreach: $data.product_pictures">


					<img data-bind="attr: {src: large}" src="" style="max-width:50px;">

				</div>

				<!-- /ko -->

				<!-- /ko -->

				<!-- ko if: $data.product_pictures() == null-->

				<p>Leider keine Bilder vorhanden.</p>

				<!-- /ko -->


			</td>


			<td class="column-secondary">


				<div data-bind="visible: $data.product_already_imported() == true">
					<strong style="color:#fff; background-color:red; text-align:center; font-size: 16px; display:block; padding: 10px 0; margin-top:10px; margin-bottom: 15px;">Produkt bereits importiert</strong>
				</div>
				<!-- ko if: $data.product_parent() == '' || $data.product_parent() == null -->

				<label>Marke</label>
				<select data-bind="options: $root.brandList(), optionsText: 'name', optionsValue: 'name', value: selected_brand" style="width:100%;"></select>



				<label>Typ</label>
				<select data-bind="options: $root.typeList,
							   optionsText: function(item) {
								   return item.name;
							   },
							   value: $data.selected_type,
							   optionsValue: function(item){
									return item.name
							   }
							 "

						style="width:100%;"
				>

				</select>


				<!-- /ko -->

				<label>Preis</label>

				<select data-bind="options: product_prices,
							   optionsText: function(item) {
								   return item.price()+' '+item.currency()+' '+item.display_name();
							   },
							   optionsValue: function(item){return item.name;},
							   value: selected_price,
							 "

						style="width:100%;"
				>

				</select>


				<h4>Vergleichsprodukt für:</h4>


				<select style="width:100%;" data-bind="

							options: $root.allProducts,
							optionsText: function(item) {return item.post_title; },
							optionsValue: function(item) {return item.ID;},
							value: $data.product_parent,
							optionsCaption: 'Vergleichsprodukt wählen'

						">
				</select>

				<h4>Zusätzlich importieren:</h4>

				<ul>

					<!-- ko if: $data.product_parent() == '' || $data.product_parent() == null -->


					<li>
						<input type="checkbox" data-bind="checked: $data.product_import_values.description"> <label>Beschreibung</label>
					</li>

					<li>
						<input type="checkbox" data-bind="checked: $data.product_import_values.features"> <label>Features</label>
					</li>

					<li>
						<input type="checkbox" data-bind="checked: $data.product_import_values.cover_picture"> <label>Cover Bild</label>
					</li>

					<li>
						<input type="checkbox" data-bind="checked: $data.product_import_values.pictures"> <label>Alle Bilder</label>
					</li>

					<!-- /ko -->


				</ul>





				<div style="padding: 10px 0;">

					<!-- ko if: $data.product_parent() == '' || $data.product_parent() == null -->

					<button class="button-primary" style="margin-bottom:10px; display:block;" data-bind="click: function(data, event) { $root.importProduct($index(),$data,0); }">Produkt Entwurf importieren</button>
					<button class="button-primary" data-bind="click: function(data, event) { $root.importProduct($index(),$data,1); }">Produkt Veröffentlicht importieren</button>

					<br><br>

					<!--<a data-bind="attr: {href: $data.product_affiliate_links()[0].url}" target="_blank" href="#">Produkt auf Amazon ansehen</a>-->

					<!-- /ko -->


					<!-- ko if: $data.product_parent() != '' && $data.product_parent() != null -->

					<button class="button-primary" data-bind="click: function(data, event) { $root.importProduct($index(),$data); }">Als Vergleichsprodukt</button>

					<!-- /ko -->

				</div>

			</td>





		</tr>

	<!--
	<tr >

	   <td colspan="3"><pre data-bind="text: ko.toJSON($data.product_image_name_pattern, null, 2)"></pre></td>

	</tr>

	-->






		</tbody>

		<tfoot>

		<!--<tr>


			<td colspan="6">
			  tfoot
			</td>

		</tr>-->

		</tfoot>

	</table>

<!-- /ko -->
{/literal}