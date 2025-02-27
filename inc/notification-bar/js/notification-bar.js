// jQuery(document).ready(function($) {
//     // Function to set a session cookie
//     function setSessionCookie(name, value, path = "/") {
//         document.cookie = name + "=" + value + "; path=" + path + "; samesite=lax";
//     }

//     // Function to get a cookie value
//     function getCookie(name) {
//         let matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") + "=([^;]*)"));
//         return matches ? decodeURIComponent(matches[1]) : undefined;
//     }

//     // Check if the cookie exists
//     if (getCookie("notificationBarClosed")) {
//         $('#notification-bar').hide();
//     }

//     // Close button event
//     $('#notification-close').on('click', function() {
//         $('#notification-bar').fadeOut();
//         setSessionCookie("notificationBarClosed", "true"); // Set session cookie
//     });
// });


jQuery(document).ready(function($) {
    // Function to set a session cookie (valid until browser is closed)
    function setSessionCookie(name, value) {
        document.cookie = name + "=" + value + "; path=/; samesite=lax";
    }

    // Close button event
    $('#notification-close').on('click', function() {
        $('#notification-bar').fadeOut(300); // Hide the notification bar smoothly

        setSessionCookie("notificationBarClosed", "true"); // Set session cookie

        setTimeout(function() {
            location.reload(); // Reload page after delay to remove notification bar from hook
        }, 500); // Short delay to ensure the fade-out effect
    });
});