<?php
/**
 * Get the client
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Define configuration
 */

/* Username, password and endpoint used for server to server web-service calls */
Lyra\Client::setDefaultUsername("33579951");
Lyra\Client::setDefaultPassword("prodpassword_4ilkgruCODRrAY0vR4wzJCR3ijTM4liACLVhULNehmXtq");
Lyra\Client::setDefaultEndpoint("https://api.micuentaweb.pe");

/* publicKey and used by the javascript client */
Lyra\Client::setDefaultPublicKey("33579951:publickey_T5ZyLlzUMQp5RToOoicWbSavFXCDi3eaKeR3HGQgtbxWc");

/* SHA256 key */
Lyra\Client::setDefaultSHA256Key("3CcdOJEhAJmgJGZQGbVWfAEVdugefDtAtvLnovsjSQxcH");