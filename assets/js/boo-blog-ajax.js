jQuery(document).ready(function ($) {
  const loadMoreBtn = $('#load-more-post-btn');
  let currentPage = 3;
  const totalPosts = loadMoreBtn.data('total-posts');
  const postsPerPage = 5;
  const maxPages = Math.ceil(totalPosts / postsPerPage);

  loadMoreBtn.on('click', function (e) {
    console.log('Load More Clicked', maxPages);
    e.preventDefault();

    if (currentPage <= maxPages) {
      console.log('Current Page', currentPage);
      console.log('Max Page', maxPages);

      $.ajax({
        url: boo_blog_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'load_more_posts',
          page: currentPage,
          nonce: boo_blog_ajax.nonce,
          post_type: loadMoreBtn.data('post-type')
        },
        beforeSend: function () {
          loadMoreBtn.text('Loading...').prop('disabled', true);
        },
        success: function (html) {
          $('#boo-load-more-posts').append(html);
          loadMoreBtn.text('Ladda fler').prop('disabled', false);
          currentPage++; // Increment the page number after successful load

          if (currentPage > maxPages) {
            loadMoreBtn.hide();
          } else {
            loadMoreBtn.show();
          }
        },
        error: function (xhr, status, error) {
          console.error('Error:', error);
          loadMoreBtn.after(
            "<p class='error-message'>Failed to load more posts. Please try again.</p>"
          );
          loadMoreBtn.text('Ladda fler').prop('disabled', false);
        }
      });
    } else {
      loadMoreBtn.hide();
    }
  });
});
