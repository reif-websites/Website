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

var config;
let copiedText = "Copied!";

function callApi(endpoint, data) {
    var returnValue;
    return returnValue;
}

function setConfig(configStr) {
    config = JSON.parse(configStr);
}

function translatePage(data) {
    for(element in data) {
        if(document.getElementById(element) && document.getElementById(element).tagName != "INPUT") {
            document.getElementById(element).innerText = data[element];
        } else if(document.getElementById(element)) {
            if(document.getElementById(element).type == "submit") {
                document.getElementById(element).value = data[element];
            } else {
                document.getElementById(element).setAttribute("placeholder", data[element]);
            }
        }
    }
    if(data["copiedText"]) {
        copiedText = data["copiedText"];
    }
}
function preparePage() {
    var userLang = navigator.language || navigator.userLanguage;
    fetch(`../locales/${userLang}/setup.json`)
    .then(response => response.json())
    .then(returnData => translatePage(returnData))
    .catch(error => {
        console.log(error);
    });
}

function createUser() {
    if(document.getElementById("username").value != "" 
        && document.getElementById("password").value != "")
    {
        fetch(`../api/v0/users/first/code.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(
                {
                    username: document.getElementById("username").value,
                    password: document.getElementById("password").value
                }
            ),
        })
        .then(response => response.json())
        .then(json => codePage(json.code))
        .catch(error => {
            console.log(error);
        });
    }
}

function codePage(code) {
    document.getElementById("container").innerHTML = '<h2 id="heading2">Verification</h2> \
    <div id="errorNotVerified">We couldn\'t find the file or the content is not valid. \
    Are you sure you are using the correct filename and copied the code correctly?</div> \
    <div id="verifyExplanation">To verify that you are the owner of this website, \
    please paste the following code into the file "REIF_LOGIN_CODE" in the directory with the Reif files on your server. \
    </div><input id="loginCode" type="text" readonly class="copy" id="copyCode" onclick="copyContent(document.getElementById(\'loginCode\'));"> \
    <button class="copy" onclick="copyContent(document.getElementById(\'loginCode\'));"><i class="far fa-copy"></i></button> \
    <button id="continue" onclick="finishUser();">Continue</button>';
    preparePage();
    document.getElementById("loginCode").value = code;
}

function copyContent(element) {
    if(element.value != copiedText) {
        element.select();
        element.setSelectionRange(0, 99999);
        document.execCommand("copy");
        let originalContent = element.value;
        element.value = copiedText;
        setTimeout(function() { element.value = originalContent; }, 1000);
    }
}

function finishUser() {
    fetch(`../api/v0/users/first/finish.php`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(json => finishedPage(json.valid))
    .catch(error => {
        console.log(error);
    });
}

function finishedPage(valid) {
    if(valid) {
        document.getElementById("container").innerHTML = '<h2 id="heading3">Setup finished</h2> \
        <div id="finalText">The setup is now finished! Thank you for using Reif.</div> \
        <div id="yourWebsiteIsThere">You can now access your website</div><a id="siteLink" href="../content/">there.</a><div id="yourWebsiteIsThere2"></div>';
        preparePage();
    } else {
        document.getElementById("errorNotVerified").style.display = "block";
    }
}
