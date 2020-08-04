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
    if(get_user_type($_POST["username"]) == 2 || $_POST["username"] == $_POST["changing"]) {
        echo "{\"valid\": true}";
        if(defined($_POST["new_name"])) {
            modify_user_name($_POST["changing"], $_POST["new_name"]);
        }
        if(defined($_POST["new_pw"])) {
            modify_user_password($_POST["changing"], $_POST["new_pw"]);
        }
        if(defined($_POST["new_type"]) && get_user_type($_POST["username"]) == 2) {
            modify_user_type($_POST["changing"], $_POST["new_type"]);
        }
    } else {
        http_response_code(401);
        echo "{\"valid\": false}";
    }
} else {
    http_response_code(403);
    echo "{\"valid\": false}";
}