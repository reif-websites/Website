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

$postData = json_decode(file_get_contents('php://input'), true);

if(check_user($postData["username"], $postData["password"])) {
    if(get_user_type($postData["username"]) == 2 || $postData["username"] == $postData["changing"]) {
        echo "{\"valid\": true}";
        if(defined($postData["new_name"])) {
            modify_user_name($postData["changing"], $postData["new_name"]);
        }
        if(defined($postData["new_pw"])) {
            modify_user_password($postData["changing"], $postData["new_pw"]);
        }
        if(defined($postData["new_type"]) && get_user_type($postData["username"]) == 2) {
            modify_user_type($postData["changing"], $postData["new_type"]);
        }
    } else {
        http_response_code(401);
        echo "{\"valid\": false}";
    }
} else {
    http_response_code(403);
    echo "{\"valid\": false}";
}