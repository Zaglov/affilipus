{* Template für Produkt Tabelle *}




{if $args.title != null}<h2>{$args.title}</h2>{/if}
<div class="shadow1">
<table class="imbaf_price_list imbaf_table">


	<thead>
		<tr>


			<th>Shop</th>
			<th>Preis</th>


		</tr>
	</thead>


	<tbody>

	{foreach from = $data.products item=product}
		<tr>

			{if $product._imbaf_affiliate != 'imbaf_custom'}

				<th class="nopadding text-centered valign-middle" scope="row">

					<img class="partnerlogo" src="{$product._imbaf_partner_logo_url}">

				</th>

			{else}

				<th class="nopadding text-centered valign-middle" scope="row">
					{$product._imbaf_affiliate_name}
				</th>

			{/if}


			<td class="nopadding">

				<div class="imbaf-price-container">

					<div>
						<span class="label label-big">Preis:</span>
						<span class="price price-big">{$product._imbaf_display_price.price}{$product._imbaf_display_price.currency_symbol}</span>
						{if $product._imbaf_product_shipping_detail.IsEligibleForPrime == 1}<img src="{$IMBAF_IMAGES}/misc/prime.png" class="prime">{/if}

					</div>

					{if $product._imbaf_display_price.savings_percent > 0}

						<div>
							<span class="label">Sie sparen:</span>
							<span class="price">{$product._imbaf_display_price.savings}{$product._imbaf_display_price.currency_symbol}</span>
							<span class="percent">({$product._imbaf_display_price.savings_percent}%)</span>
						</div>

					{/if}

					<span class="info">Zuletzt aktualisiert am {$product._imbaf_last_price_update|date_format: '%d.%m.%Y'}</span>

				</div>

				<a
						href="{$product._imbaf_affiliate_links.product_page.url}"
						target="_blank" title="{$product.product_name} jetzt kaufen"
						rel="nofollow"
						class="affilipus-button affilipus-button-block button-buy with-icon">


					<i class="fa fa-shopping-cart"></i>




					{if $data.products|count > 1}

						Jetzt bei {$product._imbaf_affiliate_display_name} ansehen

						{else}

                        {$args.buy_button_text}

					{/if}

				</a>




			</td>






		</tr>
	{/foreach}


	</tbody>
</table>
</div>