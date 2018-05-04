<a
		href="{$data.product._imbaf_affiliate_links.product_page.url}"
		title="{$data.product.product_name}"
		target="{$args.target}"
		rel="nofollow"
		class="affilipus-button buy-button {if $args.fa_icon != false}with-icon{/if}">

	{if $args.fa_icon != false}<i class="fa {$args.fa_icon}"></i>{/if} {$args.button_text}

</a>