function cardAjax(ids) {
    ids.forEach(function(id) {
        if (!$('#mentorDisplay').find('#' + id["account_ID"]).length) {
            let cached = localStorage.getItem(id["account_ID"]);
            if (cached != null) {
                document.getElementById("mentorDisplay").innerHTML += cached;
                imageAjax(id);
            } else {
                if (id["account_ID"] > 4) {
                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            localStorage.setItem(id["account_ID"], this.responseText);
                            document.getElementById("mentorDisplay").innerHTML += this.responseText;

                            imageAjax(id);
                        }
                    };
                    xmlhttp.open("GET", "card.php?id=" + id["account_ID"], true);
                    xmlhttp.send();
                }
            }
        }
    });
}

function imageAjax(id) {

    let cached = localStorage.getItem(id["account_ID"] + "_img");
    if (cached != null) {
        document.getElementById(id["account_ID"]).src = cached;
    } else {
        if (id["account_ID"] > 4) {
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    localStorage.setItem(id["account_ID"] + "_img", this.responseText);
                    document.getElementById(id["account_ID"]).src = this.responseText;
                }
            };
            xmlhttp.open("GET", "image.php?account_id=" + id["account_ID"], true);
            xmlhttp.send();
        }
    }
}