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

if(check_user($_POST["username"], $_POST["password"])) {
    if(get_user_type($_POST["username"]) > 1) {
        if($_POST["new_type"] == 0) {
            if(create_user($_POST["new_username"], $_POST["new_password"], $_POST["new_type"], $_POST["new_page"], $_POST["new_page_group"])) {
                echo "{\"valid\": true, \"existing\": false}";
            } else {
                echo "{\"valid\": true, \"existing\": true}";
            }
        } elseif ($_POST["new_type"] == 1) {
            if(create_user($_POST["new_username"], $_POST["new_password"], $_POST["new_type"], $_POST["new_group"])) {
                echo "{\"valid\": true, \"existing\": false}";
            } else {
                echo "{\"valid\": true, \"existing\": true}";
            }
        } else {
            if(create_user($_POST["new_username"], $_POST["new_password"], $_POST["new_type"])) {
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