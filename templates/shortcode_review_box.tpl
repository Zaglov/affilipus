<div class="imbaf_review_box {if $data.stars != null}with_rating{/if} {if $args.title != null}with_title{/if}">


	{if $args.title != null}

	<h2>{$args.title}</h2>

		{else}

		<h2>Review</h2>

	{/if}


	<div class="review_text">
		<p>{$data.text}</p>
	</div>


	{if $data.stars != null && $data.stars != 0}


		<div class="star-rating-container">

	<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="imbaf-star-rating rating-{$data.starclass}" title="Bewertung: {$data.stars} von 5">

		<span><strong itemprop="ratingValue">{$data.stars}</strong> Sterne</span>

	</div>

		<div class="star-rating-text">{$data.stars} von 5 Sternen</div>

		</div>

	{/if}

</div>