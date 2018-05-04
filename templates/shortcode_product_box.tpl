{*Affilipus Product Box Template*}
<div class="imbaf-element-container shadow1">
	<div class="imbaf-product-box {if $args.display_product_picture == 1 && $data.post_thumbnail != null}with-thumbnail{else}without-thumbnail{/if} {if $args.show_all_prices}preisvergleich-active{/if}" id="product-box-{$args.uniqid}">

		{if $args.display_product_picture == true && $data.post_thumbnail != null}

			<div class="imbaf-product-thumbnail-container">

				{if $args.link_product_picture == 'review'}
						<a href='{$data.product.permalink}'>
				{elseif $args.link_product_picture == 'product'}


						<a href="{$data.product._imbaf_affiliate_links.product_page.url}"
						   target="_blank"
						   title="{$data.product.product_name}"
						   rel="nofollow"
						>

				{/if}

				{$data.post_thumbnail}

				{if $args.link_product_picture == 'review' || $args.link_product_picture == 'product'}

					</a>

				{/if}


			</div>



		{/if}


		<div class="imbaf-product-meta">


			{if $args.title_to_review == true && $data.product.permalink != null}

				<a href="{$data.product.permalink}"
				   title="{$data.product.product_name}"
				   rel="{$data.product.permalink_rel}"
				   class="imbaf-product-name">{$data.product.product_name}
				</a>

			{else}

				<a href="{$data.product._imbaf_affiliate_links.product_page.url}"
				   target="_blank"
				   title="{$data.product.product_name}"
				   rel="nofollow"
				   class="imbaf-product-name">{$data.product.product_name}
				</a>

			{/if}


			{if

			$data.product._imbaf_review_star_rating != null &&
			$data.product._imbaf_review_star_rating != 0 &&
			$args.display_product_rating != false

			}

				<div class="star-rating-container">

					<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating rating-{$data.product._imbaf_review_star_rating*10}" title="Bewertung: {$data.product._imbaf_review_star_rating} von 5">

						<span><strong itemprop="ratingValue">{$data.product._imbaf_review_star_rating}</strong> Sterne</span>

					</div>

					<div class="star-rating-text">
						{$data.product._imbaf_review_star_rating} von 5 Sternen
						{if $data.product._imbaf_review_count > 0}<br>  {$data.product._imbaf_review_count} Bewertungen{/if}
					</div>

				</div>

			{/if}


			<div class="imbaf-price-container">

				<div>
					<span class="label label-big">Preis:</span>
				<span class="price price-big">
					{$data.product._imbaf_display_price.price}{$data.product._imbaf_display_price.currency_symbol}
				</span>
					{if $data.product._imbaf_product_shipping_detail.IsEligibleForPrime == 1}

						{if $args.link_prime_logo == 'review'}

							<a href='{$data.product.permalink}'>

						{elseif $args.link_prime_logo == 'product'}

							<a href="{$data.product._imbaf_affiliate_links.product_page.url}"
							   target="_blank"
							   title="{$data.product.product_name}"
							   rel="nofollow"
							   >


						{/if}

							<img src="{$IMBAF_IMAGES}/misc/prime.png" class="prime">


						{if $args.link_prime_logo == 'review' || $args.link_prime_logo == 'product'}

							</a>

						{/if}

					{/if}
				</div>

				{if $data.product._imbaf_display_price.savings_percent > 0}
					<div>
						<span class="label">Sie sparen:</span>
						<span class="price">{$data.product._imbaf_display_price.savings}{$data.product._imbaf_display_price.currency_symbol}</span>
						<span class="percent">({$data.product._imbaf_display_price.savings_percent}%)</span>
					</div>
				{/if}

                {if $data.product.children|@count > 0}

					<a href="javascript:void();" class="preisvergleich_toggle_hide hidden" onclick="imbaf_preisvergleich.togglePreisvergleich('product-box-{$args.uniqid}');">weitere Angebote ausblenden <i class="fa fa-chevron-up"></i></a>

					<a href="javascript:void();" class="preisvergleich_toggle_show" onclick="imbaf_preisvergleich.togglePreisvergleich('product-box-{$args.uniqid}');">{$data.product.children|@count} weitere Angebote ansehen <i class="fa fa-chevron-down"></i></a>

					<div class="preisvergleich_display">


						<table class="preisvergleich-table">

							<tbody>

                            {foreach from=$data.product.children item=child}

								<tr>

									<td class="logo-cell">
										<a href="{$child._imbaf_affiliate_links.product_page.url}" target="_blank" title="{$args.button_text}" class="" rel="nofollow">
										<img class="partner_logo" src="{$child._imbaf_partner_logo_url}">
										</a>
									</td>
									<td class="price-cell" ><span class="price price-small">{$child._imbaf_display_price.price}{$child._imbaf_display_price.currency_symbol}</span></td>

								</tr>

                            {/foreach}

							</tbody>

						</table>


					</div>



                {/if}

				<div>
					<span class="info">Zuletzt aktualisiert am {$data.product._imbaf_last_price_update|date_format: '%d.%m.%Y'}</span>
				</div>




			</div>


			<div class="imbaf-buy-button-container">



				{if $args.display_buy_button == true}

					<a href="{$data.product._imbaf_affiliate_links.product_page.url}" target="_blank" title="{$args.button_text}" class="affilipus-button button-buy {if $args.buy_button_icon != false}with-icon{/if}" rel="nofollow">

						{if $args.buy_button_icon != false}
						<i class="fa {$args.buy_button_icon}"></i>
						{/if}


						{if $data.product.children|@count != 0}

							Jetzt bei {$data.product._imbaf_affiliate_display_name} ansehen

						{else}

                            {$args.buy_button_text}

						{/if}





					</a>


				{/if}

				{if $data.product.permalink != null && $args.display_review_button == true}

					<a class="affilipus-button affilipus-button-secondary {if $args.review_button_icon != false}with-icon{/if}" href='{$data.product.permalink}' title="{$args.review_button_text}">
						{if $args.review_button_icon != false}<i class="fa {$args.review_button_icon}"></i>{/if} {$args.review_button_text}
					</a>

				{/if}

			</div>

			{if $data.product._imbaf_description != null && $args.display_product_description == true}
				<p class="imbaf-product-description">{$data.product._imbaf_description}</p>
			{/if}

			{if $data.product._imbaf_features|count > 1 && $args.display_product_features == true}
				<ul class="imbaf-product-features">

					{foreach item=feature from=$data.product._imbaf_features}
						<li>{$feature}</li>
					{/foreach}

				</ul>
			{/if}

		</div>




		<div class="imbaf-product-footer"></div>


	</div>
</div>