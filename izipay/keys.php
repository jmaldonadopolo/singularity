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
Lyra\Client::setDefaultPassword("testpassword_VoDSURb2xE8CpZja6VjAlW0696KxZk86nwZG0yOfLFpto");
Lyra\Client::setDefaultEndpoint("https://api.micuentaweb.pe");

/* publicKey and used by the javascript client */
Lyra\Client::setDefaultPublicKey("33579951:testpublickey_b2VTJTYoweTFBgV02WqIyESQ37jFj46B4P5qEQCukSuMV");

/* SHA256 key */
Lyra\Client::setDefaultSHA256Key("o74uq4emlPDTKxXiZdvgxYJsvtdUSsF5zQurSHOneFowD");