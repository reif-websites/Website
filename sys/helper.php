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

// This searches for specific file name in a specific path, and returns its full path

function find_file($filename, $dir) {
    $list = scandir($dir);
    if(in_array($filename, $list, true)) {
        return "$dir/$filename";
    }
    foreach($list as $number => $found) {
        if(is_dir("$dir/$found") && $found != "." && $found != "..") {
            $check = find_file($filename, "$dir/$found");
            if($check != "") {
                return $check;
            }
        }
    }
    return;
}

// Basic caching functionality
// $cachepath: Path of the cache folder
// $cacheinfo: Where in the cache folder the result should be stored
// $function: Name of the function which should be cached.
// $timeout: How long a value should be stored in seconds
function result_cache($cachepath, $cacheinfo, $function, $timeout) {
    $timefile = "$cachepath/$cacheinfo.time";
    $contentfile = "$cachepath/$cacheinfo.file";
    mkdir(dirname($timefile), 0777, true);
    $now = time();
    if(!file_exists($contentfile) || $now - file_get_contents($timefile) >= $timeout) {
        $return = call_user_func($function);
        file_put_contents($timefile, $now);
        file_put_contents($contentfile, var_export($return, true));
        return $return;
    } else {
        $exported = file_get_contents($contentfile);
        eval('$return = ' . $exported.';');
        return $return;
    }
}

// Used for translation. Replaces %1 within the first argument with the second argument, %2 with the third argument and so on.
function str_insert() {
    $args = func_num_args();
    $arg_list = func_get_args();
    $str = $arg_list[0];
    if($args == 0) {
        return;
    }
    foreach($arg_list as $number => $arg) {
        if($number > 0) {
            $str = str_replace("%" . $number, $arg_list[$number], $str);
        }
    }
    return $str;
}

function starts_with($string, $start) {
    if(substr($string, 0, strlen($needle)) == $start) {
        return true;
    } else {
        return false;
    }
}

function ends_With($string, $end) {
   $length = strlen($end);
   if($length == 0) {
       return true;
   }
   if(substr($string, -$length) == $end) {
       return true;
   } else {
       return false;
   }
}