<div id="imbaf_shortcode_generator">



	<div>

		<select style="width: 100%;" data-bind="options: $data.shortcodes,
                       optionsText: 'hr_name',
                       value: selectedShortcode,
                       optionsValue: 'code',
                       optionsCaption: 'Shortcode auswählen'"></select>

		<p class="description" data-bind="text: $data.shortcodeInfo"></p>


	</div>

	<hr style="margin: 20px 0;">


	<div>

		<ul>
			<li>

				<p>Template für Darstellung.</p>

				<select style="width:100%;" data-bind="options: $data.templates,
                       optionsText: 'hr_name',
                       value: selectedTemplate,
                       optionsValue: 'basename',
                       optionsCaption: 'Standard Template'"></select>


			</li>
			<li data-bind="foreach: $data.shortcodeParams">

				<p data-bind="text: $data.value.description"></p>

				<!--<label data-bind="text: $data.name" style="display:block; padding: 5px 0;"></label>-->


				<!-- ko if: ko.toJSON($data.value.type, null, 2) == "\"input\""-->


				<input type="text" style="width:100%;"
				       data-bind="attr: {placeholder: $data.value.default_value}, value: $data.value.value, valueUpdate: ['afterkeydown', 'input', 'afterkeyup']">

				<!--/ko-->

				<!-- ko if: ko.toJSON($data.value.type, null, 2) == "\"product\""-->

				<select style="width: 100%;" data-bind="options: $root.products,
                       optionsText: 'post_title',
                       value: $data.value.value,
                       optionsValue: 'ID',
                       optionsCaption: 'Produkt auswählen'"></select>

				<!--/ko-->

				<!-- ko if: ko.toJSON($data.value.type, null, 2) == "\"select\""-->

				<select style="width: 100%;" data-bind="options: $data.value.options,
                       optionsText: 'option_text',
                       value: $data.value.value,
                       optionsValue: 'option_value'
                 "></select>

				<!-- /ko -->



			</li>

		</ul>

	</div>


	<div style="padding: 10px 0;">




	</div>


	<textarea placeholder="Shortcode" class="the_shortcode" data-bind="value: $data.shortcode" rows="7" id="imbaf_shortcode_generator_shortcode" style="width: 100%;" readonly></textarea>

	<input type="button" class="button button-primary" data-clipboard-action="copy" data-clipboard-target="#imbaf_shortcode_generator_shortcode" id="imbaf_shortcode_generator_copy" value="In Zwischenablage">

	<input type="button" data-bind="click: function(data){resetShortcode();}" class="button" value="Zurücksetzen">




	<!--<pre data-bind="text: ko.toJSON($data.shortcodeParams, null, 2)"></pre>-->



</div>