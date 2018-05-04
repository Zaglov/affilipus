<h1>Produkte importieren</h1>

<div class="tablenav top">

    <form data-bind="submit:startSearch">

        <div class="alignleft actions bulkactions">
            <input type="text" placeholder="Produktname" data-bind="value: searchTerm, valueUpdate: 'afterkeydown'">


            <!-- ko if: allowSearch() == true -->
            <input type="button" class="button action" value="Suchen" data-bind="click: startSearch">
            <!-- /ko -->
            <select  data-bind="value: $data.selectedShop">

                {foreach from=$shopList item=shop}

                    <option value="{$shop.ShopId}">{$shop.ShopTitle} #{$shop.ShopId} ({$shop.ProductCount})</option>

                {/foreach}

            </select>

        </div>




    </form>


</div>



{*
<pre data-bind="text: ko.toJSON($data, null, 2)">


</pre>
*}


<div style="clear:both;"></div>