<div class="imbaf-grid">

    {foreach item=item key=number from = $data.products}

        <div class="imbaf-grid-col">

            <div class="imbaf-grid-col-inner">

                <div class="imbaf-product-thumbnail-container" style="background-image:url({$item.product_picture});">

                    {if $args.link_product_picture == 'review'}

                        <a href='{$item.permalink}'>{$item.product_name}</a>

                    {elseif $args.link_product_picture == 'product'}


                        <a href="{$item._imbaf_affiliate_links.product_page.url}"
                           target="_blank"
                           title="{$item.product_name}"
                           rel="nofollow">{$item.product_name}</a>


                     {/if}

                </div>

                {if $args.title_to_review == true && $item.permalink != null}

                    <a href="{$item.permalink}"
                       title="{$item.product_name}"
                       rel="{$item.permalink_rel}"
                       class="imbaf-product-name">{$item.product_name}
                    </a>

                {else}

                    <a href="{$item._imbaf_affiliate_links.product_page.url}"
                       target="_blank"
                       title="{$item.product_name}"
                       rel="nofollow"
                       class="imbaf-product-name">{$item.product_name}
                    </a>

                {/if}



                <div class="imbaf-price-container">



                    <div class="price">

                        {if $item._imbaf_display_price.savings_percent > 0}
                            <div class="cross-price"><del>{$item._imbaf_display_price.cross_price}{$item._imbaf_display_price.currency_symbol}</del></div>
                        {/if}

                        {if $item._imbaf_product_shipping_detail.IsEligibleForPrime == 1}

                            {if $args.link_prime_logo == 'review'}
                                <a href='{$item.permalink}'>
                                    <img src="{$IMBAF_IMAGES}/misc/prime.png" class="prime">
                                </a><br>
                            {elseif $args.link_prime_logo == 'product'}
                                <a href="{$item._imbaf_affiliate_links.product_page.url}"
                                   target="_blank"
                                   title="{$item.product_name}"
                                   rel="nofollow"
                                ><img src="{$IMBAF_IMAGES}/misc/prime.png" class="prime"></a><br>
                            {else}
                                <img src="{$IMBAF_IMAGES}/misc/prime.png" class="prime"><br>
                            {/if}

                        {/if}
                        {$item._imbaf_display_price.price}{$item._imbaf_display_price.currency_symbol}
                    </div>
                </div>







                <div class="imbaf-buy-button-container">



                    {if $args.display_buy_button == true}

                        <a href="{$item._imbaf_affiliate_links.product_page.url}" target="_blank" title="{$args.button_text}" class="affilipus-button button-buy {if $args.buy_button_icon != false}with-icon{/if}" rel="nofollow">

                            {if $args.buy_button_icon != false}<i class="fa {$args.buy_button_icon}"></i>{/if} {$args.buy_button_text}

                        </a>

                    {/if}

                    {if $item.permalink != null && $args.display_review_button == true}

                        <a class="affilipus-button affilipus-button-secondary {if $args.review_button_icon != false}with-icon{/if}" href='{$item.permalink}' title="{$args.review_button_text}">
                            {if $args.review_button_icon != false}<i class="fa {$args.review_button_icon}"></i>{/if} {$args.review_button_text}
                        </a>

                    {/if}

                </div>

                <span class="info">Zuletzt aktualisiert am {$item._imbaf_last_price_update|date_format: '%d.%m.%Y'}</span>




            </div>

        </div>

    {/foreach}


</div>