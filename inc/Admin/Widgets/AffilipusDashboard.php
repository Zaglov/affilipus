<?php


namespace imbaa\Affilipus\Admin\Widgets;



class AffilipusDashboard {

    public function __construct(){

        add_action( 'wp_dashboard_setup', array($this,'add_dashboard_widgets') );

    }

    function add_dashboard_widgets() {

        wp_add_dashboard_widget(
            'affilipus_stats_dashboard_widget',                     // Widget slug.
            'Affilipus Status',                                      // Title.
            array($this,'affilipus_dashboard_widget_callback')      // Display function.
        );




    }

    function affilipus_dashboard_widget_callback() {


        $s = new \imbaa\Affilipus\Admin\Utilities\imbafStats();

        $stats = array();
        $stats['product'] = $s -> productStats();
        $stats['media'] = $s -> mediaStats();


        ?>

        <table class="wp-list-table widefat fixed striped">


            <tbody>

            <tr>
                <th scope="row"><strong>Importierte Produkte:</strong></th>
                <td><?php echo $stats['product']['total_imported']; ?> (Entwürfe: <?php echo $stats['product']['temporary_count']; ?>)</td>
            </tr>

            <tr>
                <th scope="row"><strong>Temporäre Produkte:</strong></td>
                <td><?php echo $stats['product']['temporary_count']; ?></td>
            </tr>

            <tr>
                <th scope="row"><strong>Importierte Bilder von Amazon:</strong></td>
                <td><?php echo $stats['media']['picture_count_amazon']; ?>/100</td>
            </tr>

            <tr>

                <th scope="row"><strong>Letzter Preis Import (Amazon):</strong></th>
                <td><?php

                    $last_cron = get_option('imbaf_cron_amazon_refetch_prices_status');

                    if($last_cron != false){ echo date('d.m.Y G:i',$last_cron['start']); } else { echo 'noch nie';} ?></td>

            </tr>

            <tr>

                <th scope="row"><strong>Nächster Preis Import (Amazon):</strong></th>
                <td><?php $next_cron = wp_next_scheduled('imbaf_amazon_hourly'); if($next_cron != 0){ echo date('d.m.Y G:i',$next_cron); } else { echo 'nie';} ?></td>

            </tr>

            <tr>

                <th scope="row"><strong>Letzter Preis Import (Affilinet):</strong></th>
                <td><?php

                    $last_cron = get_option('imbaf_cron_affilinet_refetch_prices_status');

                    if($last_cron != false){ echo date('d.m.Y G:i',$last_cron['start']); } else { echo 'noch nie';} ?></td>

            </tr>

            <tr>

                <th scope="row"><strong>Nächster Preis Import (Affilinet):</strong></th>
                <td><?php $next_cron = wp_next_scheduled('imbaf_affilinet_hourly'); if($next_cron != 0){ echo date('d.m.Y G:i',$next_cron); } else { echo 'nie';} ?></td>

            </tr>

            <tr>

                <th scope="row"><strong>Letzter Preis Import (Zanox):</strong></th>
                <td><?php

                    $last_cron = get_option('imbaf_cron_zanox_refetch_prices_status');

                    if($last_cron != false){ echo date('d.m.Y G:i',$last_cron['start']); } else { echo 'noch nie';} ?></td>

            </tr>

            <tr>

                <th scope="row"><strong>Nächster Preis Import (Zanox):</strong></th>
                <td><?php $next_cron = wp_next_scheduled('imbaf_zanox_hourly'); if($next_cron != 0){ echo date('d.m.Y G:i',$next_cron); } else { echo 'nie';} ?></td>

            </tr>




            </tbody>

        </table>

        <?php
    }

}