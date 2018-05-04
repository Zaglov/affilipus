{* Vergleichstabelle *}

<div class="imbaf-element-container shadow1">


    <div class="comptable-big-container" id="comptable-{$args.table_id}">


        <table class="imbaf_table imbaf-comptable imbaf-comptable-desktop">


            <thead>
            <tr>

                <th scope="row" class="product-picture">Abbildung</th>


                {foreach item=product from=$data.products}
                    <th class="product-picture border-top nopadding nomargin {if $product.id|in_array:$args.highlight}table-cell-highlight{/if}">

                        <div class="image-container" style="background-image:url('{$product.product_picture}');">

                            {if $args.link_product_picture == 'review' && $product.permalink != null}
                            <a href='{$product.permalink}'>
                                {elseif $args.link_product_picture == 'product'}


                                <a href="{$product._imbaf_affiliate_links.product_page.url}"
                                   target="_blank"
                                   title="{$product.product_name}"
                                   rel="nofollow"
                                >


                                    {/if}

                                    <img src="{$IMBAF_IMAGES}/misc/square.png">

                                    {if ($args.link_product_picture == 'review' && $product.permalink != null) || $args.link_product_picture == 'product'}

                                </a>

                                {/if}


                        </div>

                    </th>
                {/foreach}


            </tr>


            </thead>


            <tbody>


            <tr>

                <th scope="row">Produkt</th>
                {foreach item=product from=$data.products}
                    <th scope="col"
                        class="product-name {if $product.id|in_array:$args.highlight}table-cell-highlight{/if} text-centered">{$product.product_name}</th>
                {/foreach}
            </tr>


            {foreach item=feature from=$data.features}
                <tr>

                    <th scope="row" class="text-right">


                        <div class="imbaf-feature-description {if $feature.meta.imbaf_property_icon != ''}with-icon{/if}">


                            {if $feature.meta.imbaf_property_icon != ''}
                                <div class="the-icon">

                                    <i class="fa {$feature.meta.imbaf_property_icon} fa-pull-left property-icon"></i>

                                </div>
                            {/if}

                            <div class="the-meta">



							<span class="the-name">

								{$feature.name}

							</span>
                                {if $feature.description != null}
                                    <div class="the-description">
                                        {$feature.description}
                                    </div>
                                {/if}

                            </div>


                        </div>

                    </th>

                    {foreach item=product from=$data.products}
                        <td class="imbaf-feature text-centered imbaf-feature-type-{$feature.meta.imbaf_property_type}  {if $product.id|in_array:$args.highlight}table-cell-highlight{/if}">


                            {if isset($product._imbaf_custom_property_values[$feature.slug])}

                                {if $feature.meta.imbaf_property_prefix != null}{$feature.meta.imbaf_property_prefix}{/if}


                                {if $feature.meta.imbaf_property_type == 'bool'}
                                    <div class="bool bool-{$product._imbaf_custom_property_values[$feature.slug].value}"></div>
                                {elseif $feature.meta.imbaf_property_type == 'rating'}
                                    <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating"
                                         class="imbaf-star-rating rating-{$product._imbaf_custom_property_values[$feature.slug].value}"
                                         title="Bewertung: {$data.stars} von 5">

										<span><strong
                                                    itemprop="ratingValue">{$product._imbaf_custom_property_values[$feature.slug].value}</strong> Sterne</span>

                                    </div>
                                    <div class="star-rating-text">
                                        {$product._imbaf_custom_property_values[$feature.slug].value2} von 5 Sternen

                                        <br>{$product._imbaf_custom_property_values[$feature.slug].value3} Bewertungen
                                    </div>
                                {elseif $feature.meta.imbaf_property_type == 'features'}
                                    <ul class="feature-list">
                                        {foreach item=product_feature from=$product._imbaf_custom_property_values[$feature.slug].value}
                                            <li>{$product_feature}</li>
                                        {/foreach}
                                    </ul>
                                {elseif $feature.meta.imbaf_property_type == 'fromto'}


                                    {$product._imbaf_custom_property_values[$feature.slug].value} - {$product._imbaf_custom_property_values[$feature.slug].value2}


                                {elseif $feature.meta.imbaf_property_type == 'grade'}
                                    <div class="imbaf-grade grade-{$product._imbaf_custom_property_values[$feature.slug].value|number_format:1:"":""}">{$product._imbaf_custom_property_values[$feature.slug].value|number_format:1:",":""}</div>
                                    <div class="imbaf-grade-hr">{$product._imbaf_custom_property_values[$feature.slug].value2}</div>
                                {else}

                                    {$product._imbaf_custom_property_values[$feature.slug].value}

                                {/if}

                                {if $feature.meta.imbaf_property_suffix != null}{$feature.meta.imbaf_property_suffix}{/if}

                            {else}
                                <div class="text-center">-</div>
                            {/if}


                        </td>
                    {/foreach}

                </tr>
            {/foreach}


            {if $args.display_price == true}

            <tr>

                <th scope="row" rowspan="2">Preis</th>

                {foreach item=product from=$data.products}
                    <td class="text-centered imbaf-price-container {if $product.id|in_array:$args.highlight}table-cell-highlight{/if}">


                        <div>
                            <span class="price price-big">{$product._imbaf_display_price.price}{$product._imbaf_display_price.currency_symbol}</span>
                        </div>

                        {if $product._imbaf_product_shipping_detail.IsEligibleForPrime == 1 && $args.display_prime_logo == 1}

                        {if $args.link_prime_logo == 'review'}

                        <a href='{$product.permalink}'>

                            {elseif $args.link_prime_logo == 'product'}

                            <a href="{$product._imbaf_affiliate_links.product_page.url}"
                               target="_blank"
                               title="{$product.product_name}"
                               rel="nofollow"
                               class="imbaf-prime-link"
                            >


                                {/if}

                                <img src="{$IMBAF_IMAGES}/misc/prime.png" class="prime">


                                {if $args.link_prime_logo == 'review' || $args.link_prime_logo == 'product'}

                            </a>

                            {/if}

                            {/if}

                            {if $product._imbaf_display_price.savings_percent > 0}
                                <div>
                                    <span class="label">Sie sparen:</span>
                                    <span class="price">{$product._imbaf_display_price.savings}{$product._imbaf_display_price.currency_symbol}</span>
                                    <span class="percent">({$product._imbaf_display_price.savings_percent}%)</span>
                                </div>
                            {else}

                            {/if}

                            <div>&nbsp;</div>
                            <span class="info">Zuletzt aktualisiert am<br>{$product._imbaf_last_price_update|date_format: '%d.%m.%Y %H:%M'}</span>

                    </td>
                {/foreach}

            <tr>

                {else}

            <tr>

                <th scope="row" rowspan="2">Mehr</th>

                {/if}


                {foreach item=product from=$data.products}
                    <td class="nopadding buttons {if $product.id|in_array:$args.highlight}table-cell-highlight{/if}">

                        {if $args.display_buy_button == true}
                            <a class="affilipus-button affilipus-button-block text-centered"
                               href='{$product._imbaf_affiliate_links.product_page.url}'
                               title="{$args.button_text}" target="_blank" rel="nofollow">
                                {if $args.buy_button_icon != false}<i
                                    class="fa {$args.buy_button_icon}"></i>{/if} {$args.buy_button_text}
                            </a>
                        {/if}


                        {if $args.display_review_button == true}
                            <a class="affilipus-button affilipus-button-block affilipus-button-secondary text-centered"
                               rel="{$product.permalink_rel}" href='{$product.permalink}'
                               title="{$args.button_text}">
                                {if $args.product_button_icon != false}<i
                                    class="fa {$args.product_button_icon}"></i>{/if}
                                {$args.product_button_text}
                            </a>
                        {/if}

                    </td>
                {/foreach}

            </tr>

            </tbody>


        </table>

    </div>

</div>