<?php

namespace imbaa\Affilipus\Admin\AdminPages;



class AffilipusPartner {


    public static function page() {

        $partners = array(


            array(

                'partnerName' => 'Amazon',
                'partnerSlug' => 'amazon'

            ),

            array(

                'partnerName' => 'Affilinet',
                'partnerSlug' => 'affilinet'

            ),

            array(

                'partnerName' => 'Zanox',
                'partnerSlug' => 'zanox'

            ),

        );



        if(AFP_DEBUG == true ){

            array_push($partners,[

                'partnerName' => 'Webgains',
                'partnerSlug' => 'webgains'

            ]);

        }

        ?>

        <div class="wrap">
            <h2>Affilipus Partner</h2>

            <p>Wähle einen Partner aus, um Produkte zu importieren oder Einstellungen vorzunehmen.</p>


            <div class="theme-browser rendered">


                <div class="themes">

                    <?php foreach ( $partners as $partner ) { ?>

                        <div class="theme">


                            <div class="theme-screenshot">
                                <img
                                    src="<?php echo IMBAF_IMAGES . '/partner_page/preview_' . $partner['partnerSlug'] . '.png'; ?>"
                                    alt="">
                            </div>

                            <a href="admin.php?page=imbaf_partner_<?php echo $partner['partnerSlug'] ?>">
                                <span class="more-details">Produkte von <?php echo $partner['partnerName'] ?></span>
                            </a>
                            <div class="theme-author">Von dem WordPress-Team</div>


                            <h2 class="theme-name">

                                <?php echo $partner['partnerName'] ?></h2>

                            <div class="theme-actions">


                                <a
                                    class="button button-primary"
                                    href="admin.php?page=imbaf_partner_<?php echo $partner['partnerSlug'] ?>">Zum
                                    Partner</a>


                            </div>


                        </div>


                        <?php


                        ?>

                    <?php } ?>

                </div>
                <br class="clear"></div>

        </div>

        <?php

    }

}