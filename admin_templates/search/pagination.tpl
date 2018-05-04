<!-- ko if: loading() != true -->
<div style="clear:both; padding: 15px 0;">

	<!-- ko if: maxPages()>1 || page() > 1-->

	<!-- ko if: page() > 1 -->
	<input type="button" class="button action" value="«" data-bind="click: $root.prevPage;">
	<!-- /ko -->

	<span style="display:inline-block; line-height: 30px; padding-right: 20px; padding-left: 5px;">Seite: <span data-bind="text:page()"></span></span>


	<!-- ko if: maxPages()>page() && page() < 5-->
	<input type="button" class="button action" value="»" data-bind="click: $root.nextPage;">
	<!-- /ko -->

	<!-- /ko -->

</div>
<!-- /ko -->