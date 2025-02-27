jQuery(function ($) {
  const $input = $('#search-input');
  const $suggestions = $('#search-suggestions');

  $input.on('input', function () {
    const query = $(this).val();

    if (query.length < 3) {
      $suggestions.empty();
      return;
    }

    $.ajax({
      url: boo_live_search_params.ajax_url,
      type: 'POST',
      data: {
        action: 'boo_live_search',
        query: query
      },
      success: function (response) {
        if (response.success && response.data.length) {
          let suggestionsHtml = response.data
            .map(
              suggestion =>
                `<div class="suggestion">
                                <a href="${suggestion.link}">${suggestion.title}</a>
                            </div>`
            )
            .join('');
          $suggestions.html(suggestionsHtml).show();
        } else {
          $suggestions
            .html('<div class="no-suggestions">No suggestions found</div>')
            .show();
        }
      },
      error: function () {
        $suggestions
          .html('<div class="error">Error fetching suggestions</div>')
          .show();
      }
    });
  });

  // Hide suggestions on outside click
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.search-form').length) {
      $suggestions.hide();
    }
  });
});
