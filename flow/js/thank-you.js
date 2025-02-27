document.addEventListener('DOMContentLoaded', function () {
  if (window.location.pathname.includes('klart')) {
    console.log('On klart page');

    const userEmail = localStorage.getItem('userEmail');
    if (!userEmail) {
      window.location.href = '/';
      return;
    }
    console.log('User email:', userEmail);

    const showEmailAddress = document.getElementById('thank-you-email-address');
    console.log('Email element found:', !!showEmailAddress);

    if (showEmailAddress && userEmail) {
      showEmailAddress.textContent = userEmail;
      console.log('Email displayed on page');
    }

    // Check if ajax_object exists
    console.log('AJAX URL:', ajax_object?.ajax_url);

    if (userEmail) {
      fetch(ajax_object.ajax_url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `action=send_email_notification&email=${encodeURIComponent(
          userEmail
        )}`
      })
        .then(response => {
          console.log('Response received:', response);
          return response.json();
        })
        .then(data => {
          console.log('Email sent response:', data);
        })
        .catch(error => {
          console.error('Mail Error:', error);
        });
      localStorage.removeItem('userEmail');
    }
  }
});
