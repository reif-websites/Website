<?php

/*
Copyright (C) 2020 Aaron Dewes

This file is part of Reif.

Reif is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Reif is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Reif.  If not, see <https://www.gnu.org/licenses/>.
*/

include(__DIR__ . '/../../../../sys/main.php');

global $REIF;
$postData = json_decode(file_get_contents('php://input'), true);

$userfile = $REIF["root"] . "/users/" . hash("sha3-512", hash("sha3-512", $postData["username"])) . ".json";

if((!file_exists($userfile) || json_decode(file_get_contents($userfile), true)["disabled"] == true) && !file_exists($REIF["root"] . "/SETUP_FINISHED")) {
    create_user($postData["username"], $postData["password"], 3);
    $userinfo = json_decode(file_get_contents($userfile), true);
    
    $userinfo["disabled"] = true;
    
    file_put_contents($userfile, json_encode($userinfo, true));
    
    $hash1 = hash("sha3-512",hash("sha3-512", $postData["username"]));
    $hash2 = $userinfo["password"];
    $code = "$hash1:$hash2";
    
    echo "{\"code\": \"$code\"}";
} else {
    http_response_code(401);
    echo "{\"valid\": \"false\"}";
}