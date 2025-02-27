import {
  showPackagePrice,
  syncSliderWithInput,
  toggleModal,
  getDatesForPriceGroup,
  isB2B
} from './utils.js';
import { getZipCode, getBooPriceGroups } from './api.js';

jQuery(document).ready(function ($) {
  const types = ['portfolio', 'variable', 'fixed'];
  const CONSUMTION_AMOUNT = 2000;
  const HOUSE_SIZE = 5;

  // State Management
  const state = {
    isValidZipcode: false,
    zipCode: null,
    namspaceDesignation: null,
    allPriceGroups: null,
    allPriceGroupsWithoutDiscount: null,
    packages: {
      portfolio: null,
      variable: null,
      fixed: null
    },
    couponCode: null,
    netAreaId: null,
    consumptionAmount: CONSUMTION_AMOUNT,
    houseSize: HOUSE_SIZE,
    annualConsumption: 0
  };

  // DOM Elements
  const elements = {
    sizeSlider: $('#size'),
    usageSlider: $('#usage'),
    slizeValue: $('#sizeValue'),
    usageValue: $('#usageValue'),
    seePriceButton: $('.purchase-flow #see-price-button'),
    postcodeError: $('.purchase-flow #postcode-error'),
    portfolioPriceModal: $('.purchase-flow #show-price-modal-1'),
    discountVaildationButton: $('.purchase-flow #discount-button'),
    postcodeInput: $('.purchase-flow #postcode'),
    checkmark: $('.checkmark'),
    packages: {
      first: $('#first-package'),
      second: $('#second-package'),
      third: $('#third-package')
    },
    discountInput: $('#discount-input'),
    discountError: $('#discount-error'),
    discountAccordionContent: $('#discount-accordion-content'),
    discountContent: $('#discount-content'),
    discountText: $('#discount-text'),
    discountRemove: $('#discount-remove'),
    discountRemoveFromModal: $('#discount-remove-from-modal'),
    annualConsumptionSlider: $('#annual-consumtion'),
    annualConsumptionValue: $('#annual-consumtion-value'),
    annualConsumptionCheckbox: $('#annual-consumption-checkbox'),
    annualConsumptionOptions: $("input[name='annual-consumption-option']"),
    contactSection: $('.contact-section'),
    consumptionMediumSection: $('.consumption-medium-section'),
    consumptionLargeSection: $('.consumption-large-section'),
    contactButton: $('.purchase-flow .contact-button'),
    packageSection: $('#package-section'),
    packageDiscountContainer: $('.package-discount-container'),
    originalPrice: $('.original-price')
  };

  // Utils
  const utils = {
    extractValues: values =>
      (values || []).map(item => item.trim()).filter(Boolean),

    getPriceGroupIds: () => [
      ...utils.extractValues(booPurchaseFlowData?.portfolio_package_values),
      ...utils.extractValues(booPurchaseFlowData?.movable_package_values),
      ...utils.extractValues(booPurchaseFlowData?.fixed_package_value)
    ],

    validatePostcode: async postcode => {
      try {
        const response = await getZipCode({ zipCode: postcode });
        return response?.zipcode_information?.length > 0 ? response : false;
      } catch (error) {
        console.error('Postcode validation error:', error);
        return false;
      }
    },

    accordionClick: (accordionId, chevronId, contentId) => {
      const accordion = $(`#${accordionId}`);
      const chevron = $(`#${chevronId}`);
      const content = $(`#${contentId}`);

      accordion.on('click', () => {
        if (content.hasClass('active')) {
          content.removeClass('active');
          chevron.removeClass('rotate');
          return;
        }
        content.toggleClass('hidden');
        chevron.toggleClass('rotate');
      });
    }
  };

  // UI Updates
  const ui = {
    toggleLoader: show => {
      const button = elements.seePriceButton;
      button.prop('disabled', show);
      button.children('span').toggleClass('hidden', !show);
    },

    updatePackageVisibility: () => {
      $('#first-package').toggle(!!state.packages.portfolio);
      $('#second-package').toggle(!!state.packages.variable);
      $('#third-package').toggle(!!state.packages.fixed);
    },

    updateFooter: () => {
      $('.card-footer-info')?.hide();
      $('.card-footer-price')?.css('display', 'block');
    }
  };

  // Event Handlers
  const handlers = {
    async handlePostcodeInput() {
      const postcode = elements.postcodeInput.val().trim();
      const loader = $(elements.checkmark)[1];

      if (postcode.length >= 5) {
        elements.postcodeInput.next().removeClass('active');
        $(loader).addClass('active');
        const validationResult = await utils.validatePostcode(postcode);
        $(loader).removeClass('active');

        if (validationResult) {
          state.isValidZipcode = true;
          state.zipCode = postcode;
          state.namspaceDesignation =
            validationResult.electric_range_information?.natomradePostnummer?.item[0]?.elnat?.natomradeBeteckning;
          state.netAreaId =
            validationResult.network_area_information?.[0]?.net_area_id;
          const city = validationResult?.zipcode_information?.[0]?.city;

          // Add HTML template with check icon and city
          elements.postcodeInput
            .addClass('valid')
            .next()
            .html(
              `<i class="fa-solid fa-check" style="color: #009A44;"></i> <span style="padding-left: 4px">${city}</span>`
            )
            .addClass('active');
          elements.postcodeError.addClass('hidden');
        } else {
          state.isValidZipcode = false;
          elements.postcodeInput
            .removeClass('valid')
            .next()
            .removeClass('active');
          elements.postcodeError.removeClass('hidden');
        }
      } else {
        state.isValidZipcode = false;
        elements.postcodeInput
          .removeClass('valid')
          .next()
          .removeClass('active');
        // elements.postcodeError.removeClass("hidden");
      }
    },

    async handleDiscountValidation() {
      const value = $('#discount-input').val();
      const loader = elements.discountInput.next();
      if (value === '') {
        return;
      }

      loader.addClass('active');
      const { date, saleDate } = getDatesForPriceGroup();
      const res = await getBooPriceGroups({
        date: date,
        saleDate: saleDate,
        priceGroupId: utils.getPriceGroupIds(),
        netAreaId: state.netAreaId,
        couponCode: value,
        consumptionAmount: state.consumptionAmount
      });
      loader.removeClass('active');

      if (res?.success === false) {
        $('#discount-error').addClass('active');
        return;
      }

      $('#discount-error').removeClass('active');
      state.couponCode = value;

      $('#discount-accordion-content').addClass('hidden');
      $('#discount-content').removeClass('hidden');
      $('#discount-text').text(value);

      // update discount text to all packages
      $('.discount-text').text(value);
    },

    async handleSeePriceButton() {
      ui.toggleLoader(true);

      if (!state.isValidZipcode) {
        elements.postcodeError.text('Ange ett giltigt postnummer');
        elements.postcodeError.removeClass('hidden');
        ui.toggleLoader(false);
        return;
      }

      elements.postcodeError.addClass('hidden');

      try {
        localStorage.setItem('netAreaId', state.netAreaId);

        const { date, saleDate } = getDatesForPriceGroup();
        const priceGroups = await getBooPriceGroups({
          date,
          saleDate,
          priceGroupId: utils.getPriceGroupIds(),
          netAreaId: state.netAreaId,
          couponCode: state.couponCode,
          consumptionAmount: state.consumptionAmount
        });

        if (!!state.couponCode) {
          const priceGroupsWithoutDiscount = await getBooPriceGroups({
            date,
            saleDate,
            priceGroupId: utils.getPriceGroupIds(),
            netAreaId: state.netAreaId,
            couponCode: null,
            consumptionAmount: state.consumptionAmount
          });
          state.allPriceGroupsWithoutDiscount = priceGroupsWithoutDiscount;
          console.log(state.couponCode, 'Hello');
        }

        state.allPriceGroups = priceGroups;
        if (priceGroups) {
          handlers.updatePackages(priceGroups);

          ui.updatePackageVisibility();
          ui.updateFooter();

          scrollToElement('package-section');
        }
      } catch (error) {
        console.error('Error fetching price groups:', error);
      } finally {
        ui.toggleLoader(false);
      }
    },

    updatePackages(priceGroups) {
      if (priceGroups.boo_portfolio) {
        state.packages.portfolio = {
          ...priceGroups.boo_portfolio,
          packageName: 'boo_portfolio'
        };
        showPackagePrice(
          'first-package',
          state.packages.portfolio,
          this.handleRemoveDiscount,
          !!state.couponCode,
          state.allPriceGroupsWithoutDiscount?.boo_portfolio
        );
      }
      if (priceGroups.variable) {
        state.packages.variable = {
          ...priceGroups.variable,
          packageName: 'variable'
        };
        showPackagePrice(
          'second-package',
          state.packages.variable,
          this.handleRemoveDiscount,
          !!state.couponCode,
          state.allPriceGroupsWithoutDiscount?.variable
        );
      }
      if (priceGroups.fixed_1) {
        state.packages.fixed = {
          ...priceGroups.fixed_1,
          packageName: 'fixed_1'
        };
        $('#time-one').prop('checked', true);
        showPackagePrice(
          'third-package',
          state.packages.fixed,
          this.handleRemoveDiscount,
          !!state.couponCode,
          state.allPriceGroupsWithoutDiscount?.fixed_1
        );
      }

      // Update visibility
      $(elements.packages.first).toggle(!!state.packages.portfolio);
      $(elements.packages.second).toggle(!!state.packages.variable);
      $(elements.packages.third).toggle(!!state.packages.fixed);
    },

    handleDiscountInput() {
      const hasValue = elements.discountInput.val() !== '';
      elements.discountVaildationButton
        .prop('disabled', !hasValue)
        .toggleClass('disabled-text', !hasValue);
    },

    async handleRemoveDiscount() {
      try {
        // const loader = elements.discountRemove.next();
        // elements.discountRemove?.addClass("hidden");
        // loader.addClass("active");
        state.couponCode = null;
        const { date, saleDate } = getDatesForPriceGroup();
        const priceGroups = await getBooPriceGroups({
          date,
          saleDate,
          priceGroupId: utils.getPriceGroupIds(),
          netAreaId: state.netAreaId,
          couponCode: null,
          consumptionAmount: state.consumptionAmount
        });
        // loader.removeClass("active");
        // elements.discountRemove?.removeClass("hidden");

        if (priceGroups) {
          state.allPriceGroups = priceGroups;
          handlers.updatePackages(priceGroups);
          ui.updatePackageVisibility();
          ui.updateFooter();
        }
        elements.discountContent?.addClass('hidden');
        elements.discountAccordionContent.removeClass('hidden');
        elements.packageDiscountContainer.addClass('hidden');
        elements.discountInput.val('');
        return true;
      } catch (error) {
        // loader.removeClass("active");
        console.error('Error removing discount:', error);
        return false;
      }
    },

    handleLocalstroageSave() {
      const firstPageData = {
        zipCode: state.zipCode,
        couponCode: state.couponCode,
        netAreaId: state.netAreaId,
        consumptionAmount: state.consumptionAmount,
        houseSize: state.houseSize,
        isB2B: isB2B()
      };
      localStorage.setItem('firstPageData', JSON.stringify(firstPageData));
    },

    handleContactButtonClick() {
      window.location.href = '/kontakta/';
    },

    handleAnnualConsumptionOptions() {
      const value = $(this).val();

      const isSmall = value === 'small';
      const isMedium = value === 'medium';
      const isLarge = value === 'large';
      elements.annualConsumptionSlider.prop('disabled', !isSmall);
      elements.annualConsumptionValue.prop('disabled', !isSmall);
      elements.annualConsumptionValue.next().css('color', 'var(--text-grey)');
      elements.seePriceButton.prop('disabled', !isSmall);

      elements.packageSection.toggleClass('hidden', !isSmall);
      elements.consumptionMediumSection.toggleClass('hidden', !isMedium);
      elements.consumptionLargeSection.toggleClass('hidden', !isLarge);
      // state.annualConsumption = isChecked ? 0 : state.consumptionAmount;
    }
  };

  // Handle housing type selection
  $('.btn-group .btn').each(function () {
    $(this).click(function () {
      $('.btn-group .btn').removeClass('active');
      $(this).addClass('active');
    });
  });

  $('.ac-header').each(function () {
    $(this).click(function () {
      const sibling = $(this).next();
      if (sibling) {
        sibling.toggleClass('active');
        $(this).toggleClass('sm-hidden');
      }
    });
  });

  function updateBackground(customRange) {
    var value =
      ((customRange.value - customRange.min) /
        (customRange.max - customRange.min)) *
      100;
    customRange?.style?.setProperty('--value', value + '%');
  }

  function scrollToElement(elementId) {
    const element = $(`#${elementId}`);
    if (element.length) {
      $('html, body').animate(
        {
          scrollTop: element.offset().top - 100
        },
        500
      );
    }
  }

  function initializeRangeInputs() {
    $('input[type="range"]').each(function () {
      updateBackground(this);
    });
    $('input[type="range"]').on('input', function () {
      updateBackground(this);
    });
    $('#sizeValue').on('input', function () {
      $('#size').val(this.value);
      state.houseSize = this.value;
      updateBackground($('#size')[0]);
    });
    $('#usageValue').on('input', function () {
      $('#usage').val(this.value);
      state.consumptionAmount = this.value;
      updateBackground($('#usage')[0]);
    });
    elements.annualConsumptionSlider.on('input', function () {
      elements.annualConsumptionValue.val(this.value);
      state.consumptionAmount = this.value;
      updateBackground(this);
    });
    elements.annualConsumptionValue.on('input', function () {
      elements.annualConsumptionSlider.val(this.value);
      state.consumptionAmount = this.value;
      updateBackground(elements.annualConsumptionSlider[0]);
    });
  }

  var accordionItems = $('.accordion-item');
  accordionItems.each(function () {
    const accordionItem = $(this);
    const accordionButton = $(this).find('.accordion-button');
    accordionButton.click(function () {
      accordionItem.toggleClass('active-step-container');
    });
  });

  $('input[name="bindingTime"]').change(function () {
    if (state.allPriceGroups?.hasOwnProperty(`fixed_${this.value}`)) {
      state.packages.fixed = {
        ...state.allPriceGroups[`fixed_${this.value}`],
        packageName: `fixed_${this.value}`
      };
      showPackagePrice(
        'third-package',
        state.allPriceGroups[`fixed_${this.value}`],
        handlers.handleRemoveDiscount,
        !!state.couponCode,
        state.allPriceGroupsWithoutDiscount?.[`fixed_${this.value}`]
      );
    }
  });

  function handleNavigateToPurchaseForm(selectedPackage) {
    localStorage.setItem('selectedPackage', JSON.stringify(selectedPackage));
    handlers.handleLocalstroageSave();
    if (window.location.pathname.includes('start')) {
      $('.package-selection-section').addClass('hidden');
    } else {
      // $("#sizeValue").val(HOUSE_SIZE);
      // $("#usageValue").val(CONSUMTION_AMOUNT);

      window.location.href = isB2B() ? '/start-foretag' : '/start/';
    }
  }

  window.addEventListener('pageshow', function (event) {
    var historyTraversal =
      event.persisted ||
      (typeof window.performance != 'undefined' &&
        window.performance.navigation.type === 2);
    if (historyTraversal) {
      // Handle page restore.
      localStorage.clear();

      // Clear all inputs
      $('#size')?.val(HOUSE_SIZE);
      $('#usage')?.val(CONSUMTION_AMOUNT);
      $('#sizeValue')?.val(HOUSE_SIZE);
      $('#usageValue')?.val(CONSUMTION_AMOUNT);
      if (elements.sizeSlider?.[0]) {
        updateBackground($('#size')[0]);
        updateBackground($('#usage')[0]);
      }
      $('#postcode')?.val('');
      $('#discount-input')?.val('');
      elements.annualConsumptionSlider?.val(CONSUMTION_AMOUNT);
      elements.annualConsumptionValue?.val(CONSUMTION_AMOUNT);
      if (elements.annualConsumptionSlider[0]) {
        updateBackground(elements.annualConsumptionSlider[0]);
      }
    }
  });

  const handleSelectPackage = () => {
    types.forEach(type => {
      $(`#select-${type}-package-button`).click(() => {
        const selectedPackage = { ...state.packages[type], type };

        const selectedPackageWithoutDiscount = {
          ...state.allPriceGroupsWithoutDiscount?.[
            selectedPackage?.packageName
          ],
          packageName: selectedPackage?.packageName,
          type
        };
        localStorage.setItem(
          'selectedPackageWithoutDiscount',
          JSON.stringify(selectedPackageWithoutDiscount)
        );
        handleNavigateToPurchaseForm(selectedPackage);
      });
    });
  };

  $('.accordion-button').on('click', function () {
    const icon = $(this).find('.accordion-state-icon');
    const isCollapsed = $(this).hasClass('collapsed');

    const newSrc = isCollapsed
      ? icon.data('plus-icon')
      : icon.data('minus-icon');

    icon.attr('src', newSrc);
  });

  const updateConsumptionAmount = value => {
    state.consumptionAmount = value;
  };

  const updateHouseSize = value => {
    state.houseSize = value;
  };

  // Initialize
  const init = () => {
    elements.discountVaildationButton.css('color', '#101010');

    syncSliderWithInput('size', 'sizeValue', updateHouseSize);
    syncSliderWithInput('usage', 'usageValue', updateConsumptionAmount);
    syncSliderWithInput(
      'annual-consumtion',
      'annual-consumtion-value',
      updateConsumptionAmount
    );

    initializeRangeInputs();

    utils.accordionClick(
      'discount-accordion',
      'discount-accordion-chevron',
      'discount-accordion-content'
    );

    handleSelectPackage();

    // Event listeners
    elements.postcodeInput.on('input', handlers.handlePostcodeInput);
    elements.seePriceButton.on('click', handlers.handleSeePriceButton);
    elements.discountInput.on('input', handlers.handleDiscountInput);
    elements.discountVaildationButton.click(handlers.handleDiscountValidation);
    elements.discountRemove.click(async () => {
      const loader = elements.discountRemove.next();
      elements.discountRemove?.addClass('hidden');
      loader.addClass('active');
      const res = await handlers.handleRemoveDiscount();

      loader.removeClass('active');
      elements.discountRemove?.removeClass('hidden');
    });
    elements.annualConsumptionOptions.change(
      handlers.handleAnnualConsumptionOptions
    );
    elements.contactButton.on('click', handlers.handleContactButtonClick);
  };

  init();

  const priceModals = {
    types: ['portfolio', 'variable', 'fixed'],

    getElements(type) {
      return {
        modal: $(`#price-modal-${type}`),
        monthlyPrice: $(`.offer-price-${type}`),
        totalCategoryPrice: $(`.total-category-price-${type}`),
        priceDetails: $(`.price-details-${type}`),
        priceComparison: $(`.price-comparison-${type}`)
      };
    },

    formatCurrency(amount, currency = 'kr') {
      return `${Number(amount).toFixed(2)} ${currency}`;
    },

    formatPrice(price, unit = 'öre/kWh') {
      return `${Number(price).toFixed(2)} ${unit}`;
    },

    renderPriceDetails(type, data) {
      const elements = this.getElements(type);

      // Update monthly price
      elements?.monthlyPrice?.text(
        isB2B()
          ? data?.monthly_estimation?.estimated_price
          : data.monthly_estimation.estimated_price_with_vat
      );

      // Generate price details HTML
      const details = `
        <div class="d-flex flex-column">
          <hr class="my-2" style="background-color: #E2DAD6;">
          <div class="d-flex justify-content-between gap-2">
            <p>Förnybar energimix</p>
            <p class="text-end">Ingår alltid</p>
          </div>
          <div class="d-flex justify-content-between gap-2">
            <p>${
              !data?.packageName?.includes('fixed')
                ? 'Rörligt snittpris (föregående månad)'
                : 'Elpris'
            } </p>
            <p class="text-end">${data?.category_prices?.electricity_price}</p>
          </div>
          ${
            data?.packageName === 'boo_portfolio'
              ? `<div class="d-flex justify-content-between gap-2">
            <p>Marknadsprisanpassning</p>
            <p class="text-end">${data?.category_prices?.market_adjustment_price}</p>
          </div>`
              : ''
          }
          ${
            data?.packageName?.includes('fixed')
              ? ''
              : `<div class="d-flex justify-content-between gap-2">
            <p>Elcertifikat</p>
            <p class="text-end">${data?.category_prices?.certificate_price}</p>
          </div>`
          }
          ${
            !isB2B()
              ? `<div class="d-flex justify-content-between gap-2">
                <p>Månadsavgift inkl. moms</p>
                <p class="text-end">${data?.monthly_fee?.fee_with_vat}</p>
              </div>`
              : ''
          }
          ${
            isB2B()
              ? `<div class="d-flex justify-content-between gap-2">
                <p>Månadsavgift exkl. moms</p>
                <p class="text-end">${data?.monthly_fee?.fee}</p>
              </div>`
              : ''
          }
          ${
            !!data?.discount_price?.amount_numerical
              ? this.renderDiscount(data.discount_price)
              : ''
          }
          ${
            !isB2B()
              ? `<div class="d-flex justify-content-between">
                <p>Moms (25 %)</p>
                <p>${data?.category_prices?.vat}</p>
              </div>`
              : ''
          }
          <hr class="my-2" style="background-color: #E2DAD6;">
          <div class="d-flex justify-content-between gap-2">
            <p>Total ${isB2B() ? 'exkl.' : 'inkl.'} moms </p>
            <p class="fw-bold">${
              isB2B()
                ? data.category_prices.total_price_without_vat
                : data?.category_prices?.total_price
            }</p>
          </div>
        </div>
      `;

      const comparison = `
        <div class="d-flex flex-column pt-4">
          ${Object.keys(
            !isB2B() ? data?.compare_prices_with_vat : data?.compare_prices
          )
            .map(key => {
              if (key.includes('_')) return '';
              return `
                <div class="d-flex justify-content-between gap-2">
                <p>${key} kWh</p>
                <p class="text-end">${
                  !isB2B()
                    ? data?.compare_prices_with_vat?.[key]
                    : data?.compare_prices?.[key]
                }</p>
              </div>
              `;
            })
            .join('')}
        </div>
      `;

      elements.priceDetails.html(details);
      elements.priceComparison.html(comparison);
    },

    renderDiscount(discount) {
      return `
        <hr class="my-2" style="background-color: #E2DAD6;">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex discount-content-container">
            <p>${state?.couponCode}</p>
            <i class="fa-solid fa-x cursor-pointer" id="discount-remove-from-modal"></i>
          </div>
          <div class="text-end">
            <p>${discount?.amount_with_vat}</p>
          </div>
        </div>
        <hr class="my-2" style="background-color: #E2DAD6;">
      `;
    },

    showModal(type, data) {
      const elements = this.getElements(type);
      this.renderPriceDetails(type, data);
      elements.modal.modal('show');
    },

    init() {
      this.types.forEach(type => {
        // Debug modal trigger
        $(`#show-${type}-modal`).click(() => {
          const packageData = state.packages[type];
          this.showModal(type, packageData);
        });

        $(`#price-modal-${type}`).on('shown.bs.modal', () => {
          // Handle discount removal
          $('#discount-remove-from-modal').click(async () => {
            await handlers.handleRemoveDiscount();
            this.showModal(type, state.packages[type]);
          });
        });
      });
    }
  };

  // Initialize price modals
  priceModals.init();
});
