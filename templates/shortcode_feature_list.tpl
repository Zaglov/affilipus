{* Template für Affilipus Feature Liste *}


{if $data.product._imbaf_features|count > 1}
<div class="imbaf_feature_list">

	{if $args.title != null}

		<h2>{$args.title}</h2>

	{/if}


		<ul class="imbaf-product-features">

			{foreach item=feature from=$data.product._imbaf_features}

				<li>{$feature}</li>

			{/foreach}

		</ul>

</div>

{/if}