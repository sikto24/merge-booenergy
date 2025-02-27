jQuery(document).ready(function ($) {
  const loadMorebtnCta = $('.load-more-btn-posts-frist');
  const loadMorebtnCatArea = $('.post-filter-wrapper');
  const CurrentPostType = loadMorebtnCatArea.data('post-type');
  let currentCategorySlug = '';
  let currentPage = 1; // Track the current page globally

  // Hide the Load More button initially
  loadMorebtnCta.hide();

  // Filter Posts via AJAX
  $('.post-filter-wrapper ul li a').on('click', function (e) {
    e.preventDefault();

    const categorySlug = $(this).data('slug');
    currentCategorySlug = categorySlug;
    currentPage = 1; // Reset to the first page when switching categories

    const targetContainer = $('#blog-postbox-main');
    const action = categorySlug === 'all' ? 'load_all_posts' : 'filter_posts';

    $.ajax({
      url: boo_posts_ajax_params.ajax_url,
      type: 'POST',
      data: {
        action: action,
        category_slug: categorySlug,
        post_type: CurrentPostType,
        nonce: boo_posts_ajax_params.nonce
      },
      beforeSend: function () {
        targetContainer.html(''); // Clear current posts
      },
      success: function (response) {
        targetContainer.html(response); // Load new posts

        // Reset and manage Load More button visibility
        checkLoadMoreVisibility();

        if (categorySlug === 'all') {
          $('#boo-load-more-posts, .load-more-btn-posts-second').show();
          loadMorebtnCta.hide();
        } else {
          $('#boo-load-more-posts, .load-more-btn-posts-second').hide();
          loadMorebtnCta.show();
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX Error:', error);
      }
    });
  });

  // Highlight Active Category Tag
  $('.post-filter-wrapper li:nth-child(1) a').addClass('boo-categories-active');
  $('.post-filter-wrapper li a').on('click', function (e) {
    e.preventDefault();
    $('.post-filter-wrapper li a').removeClass('boo-categories-active');
    $(this).addClass('boo-categories-active');
  });

  // Check and Update Load More Button Visibility
  function checkLoadMoreVisibility() {
    const loadMoreBtn = $('#load-more-post-cat');
    const maxPages = $('#filter-cat-max-page').data('max-pages');

    if (currentPage >= maxPages) {
      loadMoreBtn.hide();
    } else {
      loadMoreBtn.show();
    }
  }

  // Load More Posts Functionality
  $('#load-more-post-cat').on('click', function (e) {
    e.preventDefault();

    const loadMoreBtn = $(this);
    const maxPages = $('#filter-cat-max-page').data('max-pages');

    if (currentPage < maxPages) {
      currentPage += 1; // Increment page number

      $.ajax({
        url: boo_blog_ajax.ajax_url,
        type: 'POST',
        data: {
          action: 'load_more_posts',
          category_slug: currentCategorySlug,
          post_type: CurrentPostType,
          page: currentPage,
          nonce: boo_blog_ajax.nonce
        },
        beforeSend: function () {
          loadMoreBtn.text('Loading...').prop('disabled', true);
        },
        success: function (html) {
          $('#blog-postbox-main').append(html); // Append new posts
          loadMoreBtn.text('Ladda fler').prop('disabled', false);

          // Hide Load More button if no more pages
          if (currentPage >= maxPages) {
            loadMoreBtn.hide();
          }
        },
        error: function (xhr, status, error) {
          console.error('Error:', error);
          loadMoreBtn.text('Ladda fler').prop('disabled', false);
        }
      });
    } else {
      loadMoreBtn.hide(); // Hide if no more pages
    }
  });
});
