jQuery(document).ready(function ($) {
  if (notificationPopup.is_user_logged_in === '1') {
    return;
  }

  if (sessionStorage.getItem('notificationPopupShown')) {
    return;
  }

  $.ajax({
    url: notificationPopup.ajax_url,
    type: 'POST',
    data: {
      action: 'fetch_active_notifications',
      nonce: notificationPopup.nonce
    },
    success: function (response) {
      if (response.success && response.data.length > 0) {
        $('body').addClass('menu-open popup-active');

        let popupHtml = `<div id="notification-popup"><div class="popup-content">`;
        popupHtml += `<h3 class="popup-title">${response.data[0].title}</h3><div class="notification-item-wrapper">`;

        response.data.forEach(notification => {
          popupHtml += `
            <div class="notification-item">
              <p class="popup-time">${notification.time}</p>
              <p class="popup-text">${notification.content}</p>
            </div>
          `;
        });

        popupHtml += `
            </div>
          <div class="popup-buttons">
            <a href="${response.data[0].permalink}" class="popup-button status-button">Se aktuell driftstatus</a>
          </div>
          <button id="popup-close" class="popup-button close-button">Stäng</button>
        </div></div>`;

        $('body').append(popupHtml);

        // **Set session storage as soon as popup is shown**
        sessionStorage.setItem('notificationPopupShown', 'true');

        $('#popup-close').on('click', function () {
          $('body').removeClass('menu-open popup-active');
          $('#notification-popup').fadeOut();
        });
      }
    }
  });
});
