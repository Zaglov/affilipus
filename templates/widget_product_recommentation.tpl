<div class="imbaf_widget_product_recommendation {if $args.display_widget_border == true}with-border{/if}">

<div class="imbaf-product-picture-container"><img src="{$data.product.product_picture}"></div>

	{if $args.title_link != 'none'}

	<a

			href="{if $args.title_link == 'product_page'}{$data.product.permalink}{else}{$data.product._imbaf_affiliate_links.product_page.url}{/if}"
			title="{$data.product.product_name}"
			target="{$args.title_link_target}"
			rel="{$args.title_link_rel}"


	>

	{/if}

		<span class="product-title">

	{$data.product.product_name}

			</span>

		{if $args.title_link != 'none'}

	</a>

	{/if}

<div class="imbaf-price-container">



	<div>
		<span class="label label-big">Preis:</span>

		{if $args.price_link != 'none'}

		<a
				href="{if $args.price_link == 'product_page'}{$data.product.permalink}{else}{$data.product._imbaf_affiliate_links.product_page.url}{/if}"
				target="{$args.price_link_rel}"
				rel="{$args.price_link_rel}"
		        class="price-link"
		>

			{/if}


		<span class="price price-big">
			{$data.product._imbaf_display_price.price}{$data.product._imbaf_display_price.currency_symbol}
		</span>

			{if $args.price_link != 'none'}

		</a>

		{/if}

		{if $data.product._imbaf_product_shipping_detail.IsEligibleForPrime == 1}

			{if $args.prime_link != 'none'}

				<a

					href="{if $args.prime_link == 'product_page'}{$data.product.permalink}{else}{$data.product._imbaf_affiliate_links.product_page.url}{/if}"
					target="{$args.prime_link_target}"
					rel="{$args.prime_link_rel}"
				>

			{/if}

			<img src="{$IMBAF_IMAGES}/misc/prime.png" class="prime">

			{if $args.prime_link != 'none'}

				</a>

			{/if}

		{/if}
	</div>

	{if $data.product._imbaf_display_price.savings_percent > 0}
		<div>
			<span class="label">Sie sparen:</span>

			{if $args.price_link != 'none'}

			<a
					href="{if $args.price_link == 'product_page'}{$data.product.permalink}{else}{$data.product._imbaf_affiliate_links.product_page.url}{/if}"
					target="{$args.price_link_rel}"
					rel="{$args.price_link_rel}"
					class="price-link"
			>

				{/if}


			<span class="price">{$data.product._imbaf_display_price.savings}{$data.product._imbaf_display_price.currency_symbol}</span>

			</a>

				<span class="percent">({$data.product._imbaf_display_price.savings_percent}%)</span>
		</div>
	{/if}



	<div>
		<span class="info">Zuletzt aktualisiert am {$data.product._imbaf_last_price_update|date_format: '%d.%m.%Y %H:%M'}</span>
	</div>


</div>


	<div class="cta_button_container">
	{if $args.display_buy_button == true}

		<a

				href="{$data.product._imbaf_affiliate_links.product_page.url}"
				title="{$data.product.product_name}"
				target="{$args.target}"
				rel="nofollow"
				class="affilipus-button buy-button {if $args.buy_button_icon != NULL}with-icon{/if}">

			{if $args.buy_button_icon != null}<i class="fa {$args.buy_button_icon}"></i>{/if} {$args.buy_button_text}

		</a>

	{/if}

	{if $args.display_review_button == true}
		<a

				href="{$data.product.permalink}"
				title="{$data.product.product_name}"
				target="{$args.review_button_target}"
				rel="{$data.product.permalink_rel}"
				class="affilipus-button affilipus-button-secondary review-button {if $args.review_button_icon != NULL}with-icon{/if}">

			{if $args.review_button_icon != null}<i class="fa {$args.review_button_icon}"></i>{/if} {$args.review_button_text}

		</a>
	{/if}

	</div>

</div>