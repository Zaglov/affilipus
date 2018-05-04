{*Affilipus Topseller List Template *}
<div class="imbaf-element-container shadow1">
	<ol class="imbaf-topseller-list">


		{foreach item=item key=number from = $data}
		<li class="imbaf-topseller-element">


			<div class="imbaf-topseller-list-box {if $args.show_all_prices}preisvergleich-active{/if}" id="product-box-{$args.uniqid}">



				{if $args.display_bestseller_label == 1}
				<div class="imbaf-bestseller-indicator">Bestseller Nr. {$number+1}</div>
				{/if}


				{if {$item.post_thumbnail} != null && {$item.post_thumbnail} != null}
					<div class="imbaf-product-thumbnail-container">

						{if $args.link_product_picture == 'review' && $item.product.permalink != null}

							<a href='{$item.product.permalink}'>

							{elseif $args.link_product_picture == 'product'}


							<a href="{$item.product._imbaf_affiliate_links.product_page.url}"
							   target="_blank"
							   title="{$item.product.product_name}"
							   rel="nofollow"
							>

						{/if}


						{$item.post_thumbnail}


						{if ($args.link_product_picture == 'review' && $item.product.permalink != null) ||
							 $args.link_product_picture == 'product'

						}

							</a>

						{/if}

					</div>
				{/if}


				<div class="imbaf-product-meta">



					{if $args.title_to_review == 1 && $item.product.permalink != null}

						<a href="{$item.product.permalink}"
						   title="{$item.product.product_name}"
						   rel="{$item.product.permalink_rel}"
						   class="imbaf-product-name">{$item.product.product_name}
						</a>


					{else}

						<a href="{$item.product._imbaf_affiliate_links.product_page.url}"
						   target="_blank"
						   title="{$item.product.product_name}"
						   rel="nofollow"
						   class="imbaf-product-name">{$item.product.product_name}
						</a>

					{/if}

					{if $item.product._imbaf_review_star_rating != null && $item.product._imbaf_review_star_rating != 0 && $args.display_product_rating != false}


						<div class="star-rating-container">

							<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating rating-{$item.product._imbaf_review_star_rating*10}" title="Bewertung: {$item.product._imbaf_review_star_rating} von 5">

								<span><strong itemprop="ratingValue">{$item.product._imbaf_review_star_rating}</strong> Sterne</span>

							</div>

							<div class="star-rating-text">{$item.product._imbaf_review_star_rating} von 5 Sternen

								{if $item.product._imbaf_review_count > 0}<br>  {$item.product._imbaf_review_count} Bewertungen{/if}

							</div>

						</div>

					{/if}



					<div class="imbaf-price-container">

						<div>
							<span class="label label-big">Preis:</span>
							<span class="price price-big">{$item.product._imbaf_display_price.price}{$item.product._imbaf_display_price.currency_symbol}</span>


							{if $item.product._imbaf_product_shipping_detail.IsEligibleForPrime == 1}

								{if $args.link_prime_logo == 'review' && $item.product.permalink != null}

									<a href='{$item.product.permalink}'>

								{elseif $args.link_prime_logo == 'product'}

									<a href="{$item.product._imbaf_affiliate_links.product_page.url}" target="_blank" title="{$item.product.product_name}" rel="nofollow">

								{/if}

									<img src="{$IMBAF_IMAGES}/misc/prime.png" class="prime">


								{if ($args.link_prime_logo == 'review' && $item.product.permalink != null) || $args.link_prime_logo == 'product'}


									</a>

								{/if}





							{/if}
						</div>

						{if $item.product._imbaf_display_price.savings_percent > 0}


						<div>
							<span class="label">Sie sparen:</span>
							<span class="price">{$item.product._imbaf_display_price.savings}{$item.product._imbaf_display_price.currency_symbol}</span>
							<span class="percent">({$item.product._imbaf_display_price.savings_percent}%)</span>
						</div>

						{/if}

						<span class="info">Zuletzt aktualisiert am {$item.product._imbaf_last_price_update|date_format: '%d.%m.%Y'}</span>

                        {if $item.product.children|@count > 0}

							<a href="javascript:void();" class="preisvergleich_toggle_hide hidden" onclick="imbaf_preisvergleich.togglePreisvergleich('product-box-{$args.uniqid}');">weitere Angebote ausblenden <i class="fa fa-chevron-up"></i></a>

							<a href="javascript:void();" class="preisvergleich_toggle_show" onclick="imbaf_preisvergleich.togglePreisvergleich('product-box-{$args.uniqid}');">{$item.product.children|@count} weitere Angebote ansehen <i class="fa fa-chevron-down"></i></a>

							<div class="preisvergleich_display">


								<table class="preisvergleich-table">

									<tbody>

                                    {foreach from=$item.product.children item=child}

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

					</div>


					<div class="imbaf-buy-button-container">


						{if $args.display_buy_button != 0}

						<a href="{$item.product._imbaf_affiliate_links.product_page.url}" target="_blank" title="{$args.buy_button_text}" class="affilipus-button button-buy {if $args.buy_button_icon != null}with-icon{/if}" rel="nofollow">

							{if $args.buy_button_icon != null}<i class="fa {$args.buy_button_icon}"></i>{/if}

                            {if $item.product.children|@count > 0}

								Jetzt bei {$item.product._imbaf_affiliate_display_name} ansehen

							{else}

                                {$args.buy_button_text}

							{/if}



						</a>

						{/if}


						{if $item.product.permalink != null && $args.display_review_button == 1}

						<a class="affilipus-button affilipus-button-secondary {if $args.review_button_icon != null}with-icon{/if}" href='{$item.product.permalink}' title="{$args.review_button_text}">
							{if $args.review_button_icon != null}<i class="fa {$args.review_button_icon}"></i>{/if}{$args.review_button_text}
						</a>

						{/if}





					</div>

					{if $item.product._imbaf_description != null}
						<p class="imbaf-product-description">{$item.product._imbaf_description}</p>
					{/if}


					{if $item.product._imbaf_features|count > 1 && $args.display_product_features == true}
						<ul class="imbaf-product-features">

							{foreach item=feature from=$item.product._imbaf_features}
								<li>{$feature}</li>
							{/foreach}

						</ul>
					{/if}

				</div>

				<div class="imbaf-product-footer">

				</div>

			</div>


		</li>
		{/foreach}



	</ol>
</div>

