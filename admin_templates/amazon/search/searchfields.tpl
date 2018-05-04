<h1>Produkte importieren</h1>

<div class="tablenav top">

    <form data-bind="submit:startSearch">

        <div class="alignleft actions bulkactions">
            <input type="text" placeholder="Produktname, ASIN, EAN" data-bind="value: searchTerm, valueUpdate: 'afterkeydown'">


            <!-- ko if: allowSearch() == true -->
            <input type="button" class="button action" value="Suchen" data-bind="click: startSearch">
            <!-- /ko -->

            <select data-bind="value: $data.country">

                <option value="de">Deutschland</option>
                <option value="co.uk">England</option>
                <option value="fr">Frankreich</option>
                <option value="es">Spanien</option>
                <option value="com">USA</option>

            </select>

        </div>

    </form>



</div>


<div style="clear:both;"></div>