<?php
/*
Plugin Name: Store Locator WPAPI by Alec
Plugin URI: http://localhost
Description: Plugin per la creazione di una tabella su WP, lettura e scrittura di dati tramite APIRest
Author: Alec
Version: 1.0
*/

// function create_table() {
//     //Verifica la presenza della tabella

//     //se non presente la crea

//     /* SQL QUERY 
//     CREATE TABLE `wp_store_locator`.`wp_sl_stores` (`ID` INT NOT NULL , `address` TEXT NULL , `bookable` BOOLEAN NOT NULL , `countryIso` VARCHAR(2) NULL , `email` TEXT NOT NULL , `hasPickupInStore` BOOLEAN NOT NULL , `hasTailorBooking` BOOLEAN NOT NULL , `lat` FLOAT NULL , `lng` FLOAT NULL , UNIQUE `ID` (`ID`)) ENGINE = InnoDB;
//     */

//     return;
// }

add_action('rest_api_init', function () {
    register_rest_route('storelocator/v1', '/stores/', array(
        'methods' => 'GET',
        'callback' => 'api_get_stores',
    ));

    register_rest_route('storelocator/v1', '/store/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'api_get_stores',
    ));

    register_rest_route('storelocator/v1', '/add-store/', array(
        'methods' => 'POST',
        'callback' => 'api_create_store',
    ));
});

function api_get_stores($data)
{
    $all_stores = array();
    global $wpdb, $table_prefix;
    $query = null;
    if ($data['id'] != null) {
        $query = $wpdb->prepare("SELECT * FROM wp_sl_stores WHERE ID = %d", $data['id']);
    } else {
        $query = $wpdb->prepare("SELECT * FROM wp_sl_stores");
    }
    // Turn on error reporting, so you can see if there's an issue with the query
    $wpdb->show_errors();

    $all_stores = $wpdb->get_results($query);

    return $all_stores;
}

function api_create_store($data)
{
    global $wpdb, $table_prefix;
    $wpdb->insert(
        'wp_sl_stores',
        array(
            'ID' => $data['ID'],
            'address' => $data['address'],
            'bookable' => $data['bookable'],
            'countryIso' => $data['countryIso'],
            'email' => $data['email'],
            'hasPickupInStore' => $data['hasPickupInStore'],
            'hasTailorBooking' => $data['hasTailorBooking'],
            'lat' => $data['lat'],
            'lng' => $data['lng'],
        ),
        array(
            '%d',
            '%s',
            '%d',
            '%s',
            '%s',
            '%d',
            '%d',
            '%f',
            '%f'
        )
    );
    return $wpdb->insert_id;
}

?>