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
    if(get_user_type($postData["username"]) > 1) {
        if($postData["new_type"] == 0) {
            if(create_user($postData["new_username"], $postData["new_password"], $postData["new_type"], $postData["new_page"], $postData["new_page_group"])) {
                echo "{\"valid\": true, \"existing\": false}";
            } else {
                echo "{\"valid\": true, \"existing\": true}";
            }
        } elseif ($postData["new_type"] == 1) {
            if(create_user($postData["new_username"], $postData["new_password"], $postData["new_type"], $postData["new_group"])) {
                echo "{\"valid\": true, \"existing\": false}";
            } else {
                echo "{\"valid\": true, \"existing\": true}";
            }
        } else {
            if(create_user($postData["new_username"], $postData["new_password"], $postData["new_type"])) {
                echo "{\"valid\": true, \"existing\": false}";
            } else {
                echo "{\"valid\": true, \"existing\": true}";
            }
        }
    } else {
        http_response_code(403);
        echo "{\"valid\": false}";
    }
} else {
    http_response_code(401);
    echo "{\"valid\": false}";
}