document.addEventListener('DOMContentLoaded', function () {
  // Handle mouseenter and mouseleave events for menu items
  const menuItems = document.querySelectorAll(
    '.main-menu-wrapper > ul > li.menu-item-has-children'
  );
  menuItems.forEach(item => {
    let timer;

    // Mouseenter event to add the class immediately
    item.addEventListener('mouseenter', function () {
      clearTimeout(timer); // Clear any pending timer to avoid unwanted removal
      item.classList.add('mega-menu-toggle-on');
    });

    // Mouseleave event on both the parent `li` and its child `ul`
    item.addEventListener('mouseleave', function () {
      timer = setTimeout(() => {
        item.classList.remove('mega-menu-toggle-on');
      }, 300); // Delay to remove the class after mouse leaves
    });

    // Add a listener to the submenu to keep the class while hovering over it
    const submenu = item.querySelector('ul');
    if (submenu) {
      submenu.addEventListener('mouseenter', function () {
        clearTimeout(timer); // Keep the class when hovering over the submenu
        item.classList.add('mega-menu-toggle-on');
      });

      submenu.addEventListener('mouseleave', function () {
        timer = setTimeout(() => {
          item.classList.remove('mega-menu-toggle-on');
        }, 400); // Delay to remove the class when leaving submenu
      });
    }
  });

  // Handle the left and right column splitting
  const megaMenus = document.querySelectorAll('.boo-mega-sub-menu');
  megaMenus.forEach(function (megaMenu) {
    const leftColumn = document.createElement('div');
    leftColumn.classList.add('boo-menu-left');

    const rightColumn = document.createElement('div');
    rightColumn.classList.add('boo-menu-right');

    const firstItem = megaMenu.querySelector('.menu-item');
    if (firstItem) {
      leftColumn.appendChild(firstItem);
    }

    megaMenu.querySelectorAll('.menu-item').forEach(function (item) {
      if (
        item.classList.contains('boo-sub-keep-left') ||
        item.querySelector('p')
      ) {
        leftColumn.appendChild(item);
      } else {
        rightColumn.appendChild(item);
      }
    });

    megaMenu.innerHTML = '';
    megaMenu.appendChild(leftColumn);
    megaMenu.appendChild(rightColumn);

    // Group items in the right column based on "boo-sub-menu-broken-wrapper"
    const rightItems = rightColumn.querySelectorAll('li');
    let groupedItems = [];
    let currentGroup = [];

    rightItems.forEach(function (item) {
      if (item.classList.contains('boo-sub-menu-broken-wrapper')) {
        if (currentGroup.length > 0) {
          groupedItems.push(currentGroup);
        }
        currentGroup = [item];
      } else {
        currentGroup.push(item);
      }
    });

    if (currentGroup.length > 0) {
      groupedItems.push(currentGroup);
    }

    rightColumn.innerHTML = ''; // Clear the right column before appending grouped items

    groupedItems.forEach(function (group) {
      const groupDiv = document.createElement('div');
      groupDiv.classList.add('boo-mega-sub-group'); // Add the 'menu-group' class to each group

      group.forEach(function (item) {
        groupDiv.appendChild(item);
      });

      rightColumn.appendChild(groupDiv);
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".main-menu-wrapper .boo-mega-sub-menu .boo-menu-left > li:nth-child(1) a").forEach(function (anchor) {
      if (!anchor.getAttribute("href") || anchor.getAttribute("href") === "#") {
          anchor.classList.add("no-link");
      }
  });
});

