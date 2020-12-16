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

include(__DIR__ . '/../../../sys/main.php');

global $REIF;

$postData = json_decode(file_get_contents('php://input'), true);

if(defined($postData["displayName"])) {
    $displayName = $postData["displayName"];
} else {
    $displayName = $postData["name"];
}

if(check_user($postData["username"], $postData["password"])) {
    if(get_user_type($postData["username"]) == 0) {
        // This type only has one page, creation is disabled
        return false;
    }
    if(get_user_type($postData["username"]) == 1) {
        $userinfo = json_decode(file_get_contents($REIF["root"] . "/users/" . hash("sha3-512", hash("sha3-512", $postData["username"])) . ".json"), true);
        create_page($postData["name"], $displayName, $userinfo["navGroup"], $postData["content"], hash("sha3-512", hash("sha3-512", $postData["username"])));
    } else {
        create_page($postData["name"], $displayName, $postData["navGroup"], $postData["content"], hash("sha3-512", hash("sha3-512", $postData["username"])));
    }
} else {
    http_response_code(401);
    echo "{\"valid\": false}";
}