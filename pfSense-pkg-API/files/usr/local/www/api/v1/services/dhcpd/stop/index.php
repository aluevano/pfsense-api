<?php

// IMPORTS--------------------------------------------------------------------------------------------------------------
require_once("config.inc");
require_once("auth.inc");
require_once("functions.inc");
require_once("api.inc");

// HEADERS--------------------------------------------------------------------------------------------------------------
api_runtime_allowed();    // Check that our configuration allows this API call to run first
header('Content-Type: application/json');
header("Referer: no-referrer");

// VARIABLES------------------------------------------------------------------------------------------------------------
global $config, $api_resp, $client_params;
$read_only_action = true;    // Set whether this action requires read only access
$req_privs = array("page-all", "page-services-dhcp");    // Array of privs allowed
$http_method = $_SERVER['REQUEST_METHOD'];    // Save our HTTP method

// RUN TIME-------------------------------------------------------------------------------------------------------------
// Check that client is authenticated and authorized
if (api_authorized($req_privs, $read_only_action)) {
    // Check that our HTTP method is POST (UPDATE)
    if ($http_method === 'POST') {
        $stop_serv = service_control_stop("dhcpd", []);    // Stop our service
        // Print our JSON response
        $api_resp = array("status" => "ok", "code" => 200, "return" => 0, "message" => "service dhcpd has been stopped");
        http_response_code(200);
        echo json_encode($api_resp) . PHP_EOL;
        die();
    } else {
        $api_resp = array("status" => "bad request", "code" => 400, "return" => 2, "message" => "invalid http method");
        http_response_code(400);
        echo json_encode($api_resp) . PHP_EOL;
        die();
    }
} else {
    http_response_code(401);
    echo json_encode($api_resp) . PHP_EOL;
    die();
}