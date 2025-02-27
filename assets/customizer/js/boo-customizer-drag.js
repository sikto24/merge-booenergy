jQuery(document).ready(function ($) {
  // Single Blog Layout Drag-and-Drop
  const singleBlogContainer = $(
    'li#customize-control-single_blog_layout_orders'
  );

  const singleBlogItems = JSON.parse(
    wp.customize('single_blog_layout_orders').get()
  );
  const singleBlogUl = $('<ul class="sortable-list"></ul>');

  singleBlogItems.forEach(item => {
    const label = {
      publish_date: 'Publish Date',
      content: 'Content',
      skolan_attached_video: 'Skolan Attached Video',
      linked_post: 'Post',
      extra_content: 'Additional Content',
      linked_post_skolan: 'Skolan Post'
    }[item];

    singleBlogUl.append(
      `<li data-item="${item}" class="sortable-item">${label}</li>`
    );
  });

  singleBlogContainer.append(singleBlogUl);

  // Initialize Sortable for Single Blog Layout
  new Sortable(singleBlogUl[0], {
    animation: 150,
    onSort: function () {
      const newOrder = [];
      singleBlogUl.find('.sortable-item').each(function () {
        newOrder.push($(this).data('item'));
      });
      wp.customize('single_blog_layout_orders').set(JSON.stringify(newOrder));
    }
  });
});
