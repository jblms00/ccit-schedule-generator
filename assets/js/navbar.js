$(document).ready(function () {
    var currentPath = window.location.href.split("#")[0];
    var currentHash = window.location.hash;

    $(".navbar-nav .nav-link").each(function () {
        var linkHref = $(this).prop("href");

        if (linkHref) {
            var linkParts = linkHref.split("#");
            var linkPath = linkParts[0];
            var linkHash = linkParts[1] || "";

            if (currentPath === linkPath) {
                if (currentHash === "" && !linkHash) {
                    $(this).addClass("active");
                } else if (currentHash === `#${linkHash}`) {
                    $(this).addClass("active");
                }
            }
        }
    });
});
