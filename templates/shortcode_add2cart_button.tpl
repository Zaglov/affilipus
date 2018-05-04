<a
		href="{$data.product._imbaf_affiliate_links.add_to_cart.url}"
		title="{$data.product.product_name}"
		target="{$args.target}"
		class="affilipus-button add2cart-button {if $args.fa_icon != false}with-icon{/if}">

	{if $args.fa_icon != false}<i class="fa {$args.fa_icon}"></i>{/if} {$args.button_text}

</a>