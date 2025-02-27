/***************************************************
==================== JS INDEX ======================
 * ***************************************************
01. Toggle For Footer
02. Section Slider
03. Recent Blog Slider
04. Find Area Title 
05. Notification Count
06. Mobile Menu
07. Search Bar
08. Magnific PopUp
09. Blog Inner Section Slider
10. Related Blog Posts Slider
11. Studion Video Carousel
12. Sticky Header
13. Boo Skolan Section Slider.



 ****************************************************/
(function ($) {
  ('use strict');

  let arrowLeftSVG = `<span class="boo-slider-arrow-left"><svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.12 18.296a.426.426 0 0 0 0-.6L1.445 10.02a1 1 0 0 1-.215-.32h20.345a.425.425 0 1 0 0-.85H1.225c.05-.115.12-.225.215-.32L9.115.856a.426.426 0 0 0 0-.6.426.426 0 0 0-.6 0L.84 7.93a1.91 1.91 0 0 0 0 2.7l7.675 7.675a.426.426 0 0 0 .6 0" fill="#E2DAD6"/></svg></span>`;
  let arrowRightSVG = `<span class="boo-slider-arrow-right"><svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.88.626a.426.426 0 0 0 0 .6L20.555 8.9c.095.095.165.205.215.32H.425a.425.425 0 1 0 0 .85h20.35a1 1 0 0 1-.215.32l-7.675 7.675a.426.426 0 0 0 0 .6.426.426 0 0 0 .6 0l7.675-7.675a1.91 1.91 0 0 0 0-2.7L13.485.616a.426.426 0 0 0-.6 0" fill="#E2DAD6"/></svg></span>`;

  let windowWidth = window.innerWidth;

  // 01. Toggle For Footer
  function initializeFooterToggle() {
    $('.boo-footer-widget-title').off('click');

    if (windowWidth < 767) {
      const titles = $('.boo-footer-widget-title');
      const menuContainers = $('.footer-top-area-widget');

      if (titles.length && menuContainers.length) {
        titles.each(function (index) {
          $(this).on('click', function () {
            menuContainers.eq(index).toggleClass('menu-toggle-active');
          });
        });
      }
      $(
        '.footer-top-bottom-area-wrapper .footer-top-bottom-single-area:nth-child(1) .boo-footer-widget-title'
      ).click();
    } else {
      $('.footer-top-area-widget').removeClass('menu-toggle-active');
    }
  }
  // 02. Section Slider
  function booSliderSection() {
    if ($('.boo-slider-section').hasClass('slick-initialized')) {
      $('.boo-slider-section').slick('unslick');
    }
    // Check for window width and initialize slick slider
    if (window.innerWidth < 1200) {
      $('.boo-slider-section').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2000,
        dots: true,
        arrows: false,
        infinite: false,
        lazyLoad: 'ondemand',
        prevArrow: arrowLeftSVG,
        nextArrow: arrowRightSVG,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 2.1
            }
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1.3
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1.1
            }
          }
        ]
      });
    }
  }

  // 03. Recent Blog Slider
  function recentBlogSlider() {
    $('.recent-blog-loop .elementor-loop-container')
      .on('init', function () {
        $('.recent-blog-loop .slick-slide[data-slick-index="0"]').hide();
        // Move arrows inside slick-dots
        var $slickDots = $('.recent-blog-loop .slick-dots');
        var $leftArrow = $('.recent-blog-loop .boo-slider-arrow-left');
        var $rightArrow = $('.recent-blog-loop .boo-slider-arrow-right');

        // Append arrows to the dots container
        $slickDots.prepend($leftArrow);
        $slickDots.append($rightArrow);
      })
      .slick({
        slidesToShow: 3.34,
        slidesToScroll: 3,
        autoplay: false,
        autoplaySpeed: 2000,
        dots: true,
        arrows: true,
        infinite: false,
        lazyLoad: 'ondemand',
        prevArrow: arrowLeftSVG, // Custom Left Arrow
        nextArrow: arrowRightSVG, // Custom Right Arrow
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 2.1,
              slidesToScroll: 2
            }
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1.3,
              slidesToScroll: 1
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1.1,
              slidesToScroll: 1,
              infinite: false
            }
          }
        ]
      });
  }

  // 04. Find Area Title
  function findAreaTitle() {
    if (windowWidth < 767) {
      $('.find-area-wrapper h5').on('click', function () {
        const $currentArea = $(this).closest('.single-find-area');
        const $currentWrapper = $currentArea.find('.icon-lists-wrapper');

        $('.find-area-wrapper .icon-lists-wrapper')
          .not($currentWrapper)
          .slideUp();
        $('.find-area-wrapper h5')
          .not($(this))
          .removeClass('boo-find-active-icons');

        // Toggle the clicked section
        $currentWrapper.slideToggle();
        $(this).toggleClass('boo-find-active-icons');
      });
      $('.find-area-wrapper .icon-lists-wrapper').hide();
      $('.find-area-wrapper .single-find-area:nth-child(1) h5').trigger(
        'click'
      );
    }
  }

  // 05. Notification Count
  function booTopbarNotificationCount() {
    var $notificationWrapper = $(
      '.menu-topbar-menu-right-container .boo-top-bar-right-notification a'
    );

    if ($notificationWrapper.length && booNotificationData.count) {
      var $countSpan = $('<span></span>')
        .addClass('notification-count')
        .text(booNotificationData.count);

      $notificationWrapper.append($countSpan);
    }
  }

  // 06. Mobile Menu
  function booMobileMenu() {
    $('.boo-hamburger-menu').on('click', function () {
      $(this).toggleClass('boo-hamburger-menu-active');
      $('.boo-mobile-menu-wrapper').toggleClass('boo-menu-active');
      $('body').toggleClass('menu-open');
    });
    if ($(window).width() < 1024) {
      $(
        '.main-menu-wrapper li.menu-item-has-children > .boo-mega-sub-menu li:nth-child(1)'
      )
        .addClass('back-menu-item')
        .find('a')
        .addClass('go-back')
        .attr('href', 'javascript:void(0)');

      $('.main-menu-wrapper li.menu-item-has-children').on(
        'click',
        function () {
          $(this).children('.boo-mega-sub-menu').show();
          $(this).children('.boo-mega-sub-menu').addClass('sub-menu-active');
        }
      );
      $('body').on('click', '.back-menu-item', function () {
        $(this).parent().hide();
        $(this).parent().removeClass('sub-menu-active');
      });
    }
  }

  // 07. Search Bar
  function searchBar() {
    $('.top-bar-search-icon').on('click', function () {
      $('.boo-search-bar-area-wrapper').addClass('search-opened');
      $('body').addClass('body-search-opened');
      $('body').addClass('menu-open');
    });

    $('.search-close-btn').on('click', function () {
      $('.boo-search-bar-area-wrapper').removeClass('search-opened');
      $('body').removeClass('menu-open');
      $('body').removeClass('body-search-opened');
    });
  }

  // 08. magnific popup
  function magnificpopup() {
    $('.boo-video-play-btn').magnificPopup({
      type: 'iframe',
      iframe: {
        patterns: {
          youtube: {
            index: 'youtube.com/',
            id: 'v=',
            src: 'https://www.youtube.com/embed/%id%?autoplay=1&rel=0'
          },
          vimeo: {
            index: 'vimeo.com/',
            id: '/',
            src: 'https://player.vimeo.com/video/%id%?autoplay=1&title=0&byline=0&portrait=0'
          }
        }
      }
    });
  }

  // 09. Blog Inner Section Slider
  function booBlogSectionSlider() {
    if ($('#boo-posts-inner-section').hasClass('slick-initialized')) {
      $('#boo-posts-inner-section').slick('unslick');
    }
    if (windowWidth <= 767) {
      $('#boo-posts-inner-section').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2000,
        dots: true,
        arrows: false,
        infinite: false,
        lazyLoad: 'ondemand'
      });
    }
  }

  //10. Related Blog Posts Slider
  function relatedBlogPostsSlider() {
    if ($('.related-blog-posts').hasClass('slick-initialized')) {
      $('.related-blog-posts').slick('unslick');
    }
    if (windowWidth <= 767) {
      $('.related-blog-posts').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2000,
        dots: true,
        arrows: false,
        infinite: false,
        lazyLoad: 'ondemand'
      });
    }
  }

  // 11. Studion Video Carousel
  function booStudionVideoCarousel() {
    $('.boo-studion-video-carousel-wrapper').slick({
      slidesToShow: 2.6,
      slidesToScroll: 2,
      autoplay: false,
      autoplaySpeed: 2000,
      dots: true,
      arrows: true,
      infinite: false,
      lazyLoad: 'ondemand',
      prevArrow: arrowLeftSVG, // Custom Left Arrow
      nextArrow: arrowRightSVG, // Custom Right Arrow

      responsive: [
        {
          breakpoint: 1025,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 769,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 2,
            infinite: false
          }
        }
      ]
    });
    // Move arrows inside slick-dots
    var $slickDots = $('.boo-studion-video-carousel-wrapper .slick-dots');
    var $leftArrow = $(
      '.boo-studion-video-carousel-wrapper .boo-slider-arrow-left'
    );
    var $rightArrow = $(
      '.boo-studion-video-carousel-wrapper .boo-slider-arrow-right'
    );

    // Append arrows to the dots container
    $slickDots.prepend($leftArrow);
    $slickDots.append($rightArrow);
  }

  // 12:  Sticky Header
  function booStickyHeader() {
    var header = $('.header-area-wrapper');
    var body = $('body');
    var sticky = header.offset().top;

    $(window).scroll(function () {
      if ($(window).scrollTop() > sticky) {
        header.addClass('sticky');
        body.addClass('sticky-active');
      } else {
        header.removeClass('sticky');
        body.removeClass('sticky-active');
      }
    });
  }

  // 13. Boo Skolan Section Slider.

  function booSkolanSectionSlider() {
    if ($('.boo-posts-inner-section-skolan').hasClass('slick-initialized')) {
      $('.boo-posts-inner-section-skolan').slick('unslick');
    }

    if (windowWidth <= 1024) {
      $('.boo-posts-inner-section-skolan').slick({
        slidesToShow: 1.2,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2000,
        dots: true,
        arrows: false,
        infinite: false,
        lazyLoad: 'ondemand'
      });
    }
  }

  // Function to initialize or reinitialize all features
  function initializeAll() {
    initializeFooterToggle();
    booSliderSection();
    recentBlogSlider();
    findAreaTitle();
    booMobileMenu();
    searchBar();
    magnificpopup();
    booBlogSectionSlider();
    relatedBlogPostsSlider();
    booStudionVideoCarousel();
    booStickyHeader();
    booSkolanSectionSlider();
  }

  $(document).ready(function () {
    initializeAll();
    booTopbarNotificationCount();
  });

  $(window).on('resize', initializeAll);

  // Refresh browser on window resize
  //   let resizeTimer;
  //   let initialWidth = window.innerWidth;

  //   $(window).on('resize', function () {
  //     clearTimeout(resizeTimer);
  //     resizeTimer = setTimeout(() => {
  //       if (window.innerWidth !== initialWidth) {
  //         location.reload();
  //       }
  //     }, 200);
  //   });
})(jQuery);

// Fix Error For Slick Slider
jQuery(document).on('turbo:before-cache', function ($) {
  const sliders = document.querySelectorAll('.slick-initialized');

  sliders.forEach(item => {
    $(item).slick('unslick');
  });
});

// Add Height Based on Title
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.why-we-think-main').forEach(section => {
    let titles = section.querySelectorAll('h5');
    let maxHeight = 0;

    // Find the tallest h5 in this section
    titles.forEach(title => {
      maxHeight = Math.max(maxHeight, title.offsetHeight);
    });

    // Apply the max height only within this section
    titles.forEach(title => {
      title.style.minHeight = maxHeight + 'px';
    });
  });
});
