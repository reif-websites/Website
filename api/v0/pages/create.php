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
if(defined($_POST["displayName"])) {
    $displayName = $_POST["displayName"];
} else {
    $displayName = $_POST["name"];
}

if(check_user($_POST["username"], $_POST["password"])) {
    if(get_user_type($_POST["username"]) == 0) {
        // This type only has one page, creation is disabled
        return false;
    }
    if(get_user_type($_POST["username"]) == 1) {
        $userinfo = json_decode(file_get_contents($REIF["root"] . "/users/" . hash("sha3-512", hash("sha3-512", $_POST["username"])) . ".json"), true);
        create_page($_POST["name"], $displayName, $userinfo["navGroup"], $_POST["content"], hash("sha3-512", hash("sha3-512", $_POST["username"])));
    } else {
        create_page($_POST["name"], $displayName, $_POST["navGroup"], $_POST["content"], hash("sha3-512", hash("sha3-512", $_POST["username"])));
    }
} else {
    http_response_code(401);
    echo "{\"valid\": false}";
}