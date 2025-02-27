const megaMenus = document.querySelectorAll('.boo-mega-sub-menu');
megaMenus.forEach(function (megaMenu) {
  const leftColumn = document.createElement('span');
  leftColumn.classList.add('boo-menu-left');

  const rightColumn = document.createElement('span');
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

  rightColumn.innerHTML = '';

  groupedItems.forEach(function (group) {
    const groupDiv = document.createElement('div');
    groupDiv.classList.add('boo-mega-sub-group');

    group.forEach(function (item) {
      groupDiv.appendChild(item);
    });

    rightColumn.appendChild(groupDiv);
  });
});
