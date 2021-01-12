<?php

/**
 * Plugin Name: Islam Daily Hadith (The Prophet Muhammad (SMAWA) and all 12 Imam Speech)
 * Plugin URI: https://islamplus.net
 * Description: This Plugin Developed for Free To showing Daily Shia Hadith On Wordpress Website
 * Version: 0.0.1
 * Author: Saber tabatabaee yazdi
 * Author URI: https://github.com/saber13812002
 */

add_shortcode('islamplus_hadith', 'islamplusHadithController');

function islamplusHadithController($attr)
{
    $context = isset($attr['context']) ? $attr['context'] : "hadith";
    $language = isset($attr['language']) ? $attr['language'] : "arabic";
    $theme = isset($attr['theme']) ? $attr['theme'] : "default";

    $shortcode = getHaditByCallApi($context, $language, $theme);



    /**
     * To use it in php file (e.g. theme) use do_shortcode
     * Usage example: echo do_shortcode("[sample_shortcode input_1='Windows XP']");
     */

    return $shortcode;
}

function getHaditByCallApi($context, $language, $theme)
{
    // create & initialize a curl session
    $curl = curl_init();

    // set our url with curl_setopt()
    curl_setopt($curl, CURLOPT_URL, "http://localhost:3000/api/" . $context);

    // return the transfer as a string, also with setopt()
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    global $wp;
    $current_url = home_url(add_query_arg(array(), $wp->request));

    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'random: true',
        'category: ethics',
        'type: ' . $context,
        'language: ' . $language,
        'redirect_url: ' . $current_url,
        'Content-Type: application/json',
    ));

    // curl_exec() executes the started curl session
    // $output contains the output string
    $output = curl_exec($curl);

    // close curl resource to free up system resources
    // (deletes the variable made by curl_init)
    curl_close($curl);

    $islamplusHadith = json_decode($output);
    $shortcode  = "<p>";
    $shortcode .= "$islamplusHadith->text <br>";
    $shortcode .= "<a href=$islamplusHadith->author_link>$islamplusHadith->author </a> <br>";
    $shortcode .= "<a href=$islamplusHadith->resource_link/?redirect_url=$islamplusHadith->redirect_url>$islamplusHadith->resource  </a><br>";
    $shortcode .= "</p>";

    return $shortcode;
}
