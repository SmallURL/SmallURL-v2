$(document).ready(function () {
    function a(e) {
        e = e.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function (e) {
            return '<a href="' + e + '" >' + e + "</a>"
        });
        e = e.replace(/\B@([_a-z0-9]+)/ig, function (e) {
            return '<a href="http://twitter.com/' + e.substring(1) + '" style="font-weight:lighter;" >' + e.charAt(0) + e.substring(1) + "</a>"
        });
        return e
    }

    function f(e) {
        var t = e.split(" ");
        e = t[1] + " " + t[2] + ", " + t[5] + " " + t[3];
        var n = Date.parse(e);
        var r = arguments.length > 1 ? arguments[1] : new Date;
        var i = parseInt((r.getTime() - n) / 1e3);
        var s = e.substr(4, 2) + " " + e.substr(0, 3);
        i = i + r.getTimezoneOffset() * 60;
        if (i < 60) {
            return "1m"
        } else if (i < 120) {
            return "1m"
        } else if (i < 60 * 60) {
            return parseInt(i / 60).toString() + "m"
        } else if (i < 120 * 60) {
            return "1h"
        } else if (i < 24 * 60 * 60) {
            return parseInt(i / 3600).toString() + "h"
        } else if (i < 48 * 60 * 60) {
            return s
        } else {
            return s
        }
    }
    var e = 4;
    var t = "SURLNetwork";
    var n = false;
    var r = false;
    var i = true;
    var s = false;
    var o = "";
    var u = "";
    u += '<div id="loading-container"><img src="//static.<?=SITE_URL?>/smallurl/img/icons/loading.gif" width="32" height="32" alt="tweet loader" /></div>';
    $("#twitter_update_list").html(o + u);
    $.getJSON("//<?php echo SITE_URL; ?>/do/public/twitter/tweet.php", function (t) {
        var s = "";
        var u = 1;
        for (var l = 0; l < t.length; l++) {
            var c = t[l].user.name;
            var h = t[l].user.screen_name;
            var p = t[l].user.profile_image_url_https;
            var d = t[l].text;
            var v = false;
            var m = false;
            var g = t[l].id_str;
            if (typeof t[l].retweeted_status != "undefined") {
                p = t[l].retweeted_status.user.profile_image_url_https;
                c = t[l].retweeted_status.user.name;
                h = t[l].retweeted_status.user.screen_name;
                g = t[l].retweeted_status.id_str;
                v = true
            }
            if (t[l].text.substr(0, 1) == "@") {
                m = true
            }
            if ((r == true || v == false && r == false) && (n == true || n == false && m == false)) {
                if (t[l].text.length > 1 && u <= e) {
                    if (i == true) {
                        d = a(d)
                    }
                    if (u == 1) {
                        s += o
                    }
                    var string = f(t[l].created_at);
                    var exploded = string.split(' ');
                    if(!exploded[1]) {
                        exploded[1] = 'ago';
                }
                    s += '<div class="tweet">';
                    s += '<div class="tweetdate"> <span class="tweetday"> '+ exploded[0]+' </span><span class="tweetmonth"> ' + exploded[1] +' </span></div> <div class="tweetmessage"> ' + d + '</div>';
                    //s += '<div class="twitter-text"><p><li class="twitter_date"><span class="tweet-time"><a href="https://twitter.com/' + h + "/status/" + g + '">' + f(t[l].created_at) + "</a></span></li><br /><br/><br/>" + d + "</p><br/></div>";
                    s += "</div>";
                    u++
                }
            }
        }
        $("#twitter_update_list").html(s)
    })
})