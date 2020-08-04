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

function check_user($name, $password) {
    global $REIF;
    $userinfo = json_decode(file_get_contents($REIF["root"] . "/users/" . hash("sha3-512", hash("sha3-512", $name)) . ".json"), true);
    return hash("sha3-512", hash("sha3-512", $password . $userinfo["salt0"]) . $userinfo["salt1"]) == $userinfo["password"] && $userinfo["disabled"] != true;
}

function get_user_type($name) {
    global $REIF;
    $userinfo = json_decode(file_get_contents($REIF["root"] . "/users/" . hash("sha3-512", hash("sha3-512", $name)) . ".json"), true);
    return $userinfo["type"];
}

// All functions after this comment don't have any security check.
// If the caller has permissions needs to be checked seperately with check_user and get_user_type
function create_user() {
    global $REIF;
    $args = func_get_args();
    $name = $args[0];
    if($name == "") {
        return false;
    }
    $password = $args[1];
    $type = $args[2];
    if($type < 2) {
        if(!defined($args[3])) {
            return false;
        }
    }
    if($type == 2) {
        if(!defined($args[4])) {
            return false;
        }
    }
    if(!file_exists($REIF["root"] . "/users/" . hash("sha3-512", hash("sha3-512", $name)) . ".json")) {
        $userinfo = array(
            "type" => $type
        );
        $userinfo["salt0"] = bin2hex(random_bytes(12));
        $userinfo["salt1"] = bin2hex(random_bytes(12));
        $userinfo["password"] = hash("sha3-512", hash("sha3-512", $password . $userinfo["salt0"]) . $userinfo["salt1"]);
        if($type == 1) {
            create_page($args[3], $args[3], $args[4], "", hash("sha3-512", hash("sha3-512", $name)));
        }
        if($type == 1) {
            $userinfo["navGroup"] = $args[3];
        }
        file_put_contents($REIF["root"] . "/users/" . hash("sha3-512", hash("sha3-512", $name)) . ".json", json_encode($userinfo, true));
        return true;
    } else {
        return false;
    }
}

function modify_user_name($name, $name_new) {
    global $REIF;
    $hashed_oldname = hash("sha3-512", hash("sha3-512", $name));
    $hashed_newname = hash("sha3-512", hash("sha3-512", $name_new));
    if(file_exists($REIF["root"] . "/users/$hashed_oldname.json")) {
        $userinfo = json_decode(file_get_contents($REIF["root"] . "/users/$hashed_oldname.json"), true);
        file_put_contents($REIF["root"] . "/users/$hashed_newname.json", json_encode($userinfo, true));
        unlink($REIF["root"] . "/users/$hashed_oldname.json");
        return true;
    } else {
        return false;
    }
}

function modify_user_password($name, $password) {
    global $REIF;
    $hashed_name = hash("sha3-512", hash("sha3-512", $name));
    if(file_exists($REIF["root"] . "/users/$hashed_name.json")) {
        $userinfo = json_decode(file_get_contents($REIF["root"] . "/users/$hashed_name.json"), true);
        $userinfo["salt0"] = bin2hex(random_bytes(12));
        $userinfo["salt1"] = bin2hex(random_bytes(12));
        $userinfo["password"] = hash("sha3-512", hash("sha3-512", $password . $userinfo["salt0"]) . $userinfo["salt1"]);
        file_put_contents($REIF["root"] . "/users/$hashed_name.json", json_encode($userinfo, true));
        return true;
    } else {
        return false;
    }
}

function modify_user_type($name, $type) {
    global $REIF;
    $hashed_name = hash("sha3-512", hash("sha3-512", $name));
    if(file_exists($REIF["root"] . "/users/$hashed_name.json")) {
        $userinfo = json_decode(file_get_contents($REIF["root"] . "/users/$hashed_name.json"), true);
        $userinfo["type"] = $type;
        file_put_contents($REIF["root"] . "/users/$hashed_name.json", json_encode($userinfo, true));
        return true;
    } else {
        return false;
    }
}

function delete_user($name) {
    global $REIF;
    $hashed_name = hash("sha3-512", hash("sha3-512", $name));
    unlink($REIF["root"] . "/users/$hashed_name.json");
}