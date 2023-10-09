<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// I decided to disable REST functionality for this, because of 
// unablitiy to have more granular control over what is showed
// to whom in the REST api itself. E.g. I was able to disable
// not-owners to see applications that doesn't belong to them 
// in REST listing: /wp-json/wp/v2/applications
//  but if they brute force /wp-json/wp/v2/application/[0-XYZ]
//  the REST api will reveal the ID's. 
//
//  Somewhere down the road we might revisit this, but for now...
//  I will leave the file empty, yet loaded.
