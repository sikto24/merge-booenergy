import {
  createCustomer,
  createSupplyMoves,
  getConsumerAgreementStatus,
  getConsumerAgreementTemplate,
  getConsumerAgreementTemplateForOrganization,
  getCustomerInfoByPersonNumber,
  getOrganizationInfo,
  getProxySigninStatus,
  getProxySigninTemplate,
  getProxySigninTemplateForOrganization,
  getBooPriceGroups
} from './api.js';
import { formatDate, getDatesForPriceGroup, toggleModal } from './utils.js';

jQuery(document).ready(function ($) {
  // State management
  const state = {
    packageDetails: null,
    currentStep: 0,
    fetchedAddress: null,
    personNumber: '',
    fullName: '',
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    postalAddress: '',
    city: '',
    zipCode: '',
    postalCode: '',
    startDate: '',
    facilityId: null,
    areaId: null,
    billingAddress: {
      postalAddress: '',
      zipCode: '',
      postalCode: ''
    },
    consumptionAmount: '',
    isProxySigned: false,
    firstPageData: {}
  };

  // DOM elements
  const elements = {
    phoneInput: $('#phone'),
    emailInput: $('#email'),
    currentAddressView: $('#current-address-view'),
    selectedOptionAddressOption: $("input[name='address-option']"),
    facilityOption: $("input[name='facility-option']"),
    useBillingAsCurrent: $('#use-billing-as-current'),
    useBillingAsNew: $('#use-billing-as-new'),
    customBillingAddressContainer: $('#custom-billing-address-container'),
    newCustomBillingAddressContainer: $(
      '#new-custom-billing-address-container'
    ),
    newAddressRoot: $('#new-address-root'),
    newAddressView: $('#new-address-view'),
    currentAddressRoot: $('#current-address-root'),
    prefilledFacilityRoot: $('#prefilled-facility-root'),
    ownFacilityRoot: $('#own-filled-facility'),
    prefilledFacilityView: $('#prefilled-facility-view'),
    ownFilledFacility: $('#own-filled-facility-view'),
    personNumberInput: $('#person-number'),
    stepContainers: $('.step-container'),
    stepContents: $('.step-content'),
    stepHeaders: $('.step-head'),
    stepCheckIcons: $('.step-check-icon'),
    stepEditIcons: $('.step-edit-icon'),
    firstStepSaveButton: $('#first-step-save'),
    secondStepSaveButton: $('#second-step-save'),
    thirdStepSaveButton: $('#third-step-save'),
    personNumberError: $('#person-number-error'),
    billingPostalAddress: $('#billing-postal-address'),
    billingZipCode: $('#billing-zipcode'),
    billingPostalCode: $('#billing-postal-code'),
    newAddressPostalAddress: $('#new-address-postal-address'),
    newAddressZipCode: $('#new-address-zipcode'),
    newAddressPostalCode: $('#new-address-postal-code'),
    newAddressBillingPostalAddress: $('#new-address-billing-postal-address'),
    newAddressBillingZipCode: $('#new-address-billing-zipcode'),
    newAddressBillingPostalCode: $('#new-address-billing-postal-code'),
    downloadDataButton: $('#download-data-button'),
    downloadedData: $('#downloaded-data'),
    contractSignTitle: $('#contract-sign-title'),
    contractAfterSignView: $('#contract-after-sign-view'),
    signButtonContract: $('#sign-button-contract'),
    proxySignedContent: $('#proxy-signed-content'),
    proxySignedText: $('#proxy-signed-text'),
    signPowerOfAttorney: $('#sign-power-of-attorney'),
    signElectrictyContract: $('#sign-electricity-contract'),
    signButton: $('#sign-button'),
    signingModal: $('#signing-modal'),
    contractModal: $('#contract-modal'),
    signinDescription: $('#signin-description'),
    priceDetails: $('.price-details'),
    priceComparison: $('.price-comparison'),
    priceDetailsModal: $('#price-details-modal'),
    showPriceDetailsModalButton: $('#show-price-modal'),
    monthlyPrice: $('.offer-price'),
    acceptTermContainer: $('.accept-term-container'),
    acceptTermCheckbox: $('#accept-term'),
    acceptTermButton: $('#accept-term-button')
  };

  const updateOrderSummary = () => {
    const packageNames = {
      portfolio: booCustomizerPackageName.portfolio_title,
      variable: booCustomizerPackageName.variable_title,
      fixed: booCustomizerPackageName.fixed_title
    };

    const packageDescription = {
      portfolio: booCustomizerPackageName.portfolio_title_desc,
      variable: booCustomizerPackageName.variable_title_desc,
      fixed: booCustomizerPackageName.fixed_title_desc
    };

    const packageDetails = state.packageDetails;
    if (!packageDetails) return;

    $('#custom-price-modal-description').text(
      packageDescription[packageDetails?.type]
    );

    $('#order-summary-title').text(packageNames[packageDetails?.type]);
    $('#order-summary-electricity-price').text(
      packageDetails?.category_prices?.electricity_price
    );
    $('#order-summary-fee-with-vat').text(
      state?.firstPageData?.isB2B
        ? packageDetails?.monthly_fee?.fee
        : packageDetails?.monthly_fee?.fee_with_vat
    );
    $('#order-summary-total').text(
      state?.firstPageData?.isB2B
        ? packageDetails?.monthly_estimation?.estimated_price
        : packageDetails?.monthly_estimation?.estimated_price_with_vat
    );

    if (state.firstPageData.couponCode) {
      $('.package-discount-container').removeClass('hidden');
      $('.discount-text').text(state.firstPageData.couponCode);
      $('.calculated-discount').text(
        state?.packageDetails?.discount_price?.amount_with_vat
      );
      $('.discount-description').text(
        state?.packageDetails?.discount_price?.description
      );
      $('.discount-remove').on('click', () => {
        handleRemoveDiscount();
      });
    } else {
      $('.package-discount-container').addClass('hidden');
    }
  };

  const checkPackageDetails = () => {
    $('.package-selection-section').removeClass('hidden');
    elements.stepContents.hide();
    elements.stepContainers.removeClass('active-step-container');
  };

  const packageSection = document.querySelector('.package-selection-section');
  const observer = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
      if (
        mutation.attributeName === 'class' &&
        packageSection.classList.contains('hidden')
      ) {
        initSteps();
      }
    });
  });
  if (packageSection) {
    observer.observe(packageSection, { attributes: true });
  } else {
    console.log('packageSection not found');
  }

  const handleAcceptTermsViewVisibility = () => {
    if (state?.packageDetails?.price_group_id !== 1038) {
      if (elements.acceptTermContainer.length > 0) {
        elements.acceptTermContainer.addClass('hidden');
      } else {
        console.error('Accept terms container not found in DOM');
      }
      return;
    } else {
      elements.acceptTermContainer.removeClass('hidden');

      $('#show-term-button').on('click', function () {
        toggleModal('#terms-modal');
      });

      elements.signPowerOfAttorney.prop('disabled', true);
      elements.signElectrictyContract.prop('disabled', true);
      elements.acceptTermCheckbox.on('change', function () {
        const isChecked = $(this).is(':checked');
        if (isChecked) {
          elements.signPowerOfAttorney.prop('disabled', false);
          elements.signElectrictyContract.prop('disabled', false);
        } else {
          elements.signPowerOfAttorney.prop('disabled', true);
          elements.signElectrictyContract.prop('disabled', true);
        }
      });
    }
  };

  const initSteps = () => {
    elements.stepContents.hide();
    elements.stepContents.first().show();
    elements.stepContainers.removeClass('active-step-container');
    elements.stepContainers.first().addClass('active-step-container');
    elements.stepCheckIcons.hide();
    elements.stepEditIcons.first().hide();
    elements.ownFilledFacility.hide();
    elements.customBillingAddressContainer.hide();
    elements.newAddressView.hide();
    elements.newCustomBillingAddressContainer.hide();

    state.packageDetails =
      JSON.parse(localStorage.getItem('selectedPackage')) || null;
    state.firstPageData =
      JSON.parse(localStorage.getItem('firstPageData')) || {};
    state.consumptionAmount = state.firstPageData?.consumptionAmount || '';
    if (!state.packageDetails) {
      checkPackageDetails();
    }

    updateOrderSummary();
    prefillFacilityId();
    handleAcceptTermsViewVisibility();
  };

  // Utility functions
  const getInputValue = selctor => $(selctor).val().trim();
  const isChecked = selector => $(selector).is(':checked');
  const getSelectedValue = selector => $(selector + ':checked').val();
  const validateInputs = selectors =>
    selectors.every(selector => validateInput($(selector)));
  const showAlert = selector => $(selector).removeClass('hidden');
  const hideAlert = selector => $(selector).addClass('hidden');
  const appendFetchedAddress = data => {
    const fullName = data?.full_name || '';
    const address = data?.addresses?.[0] || {};
    const fetchedAddress = `
      <p>${fullName}</p>
      <p>${address['street/box']}</p>
      <p>${address.post_code} ${address.city}</p>
    `;
    $('#downloaded-data').append(fetchedAddress);
    $('#current-address-container').append(fetchedAddress);
    return fetchedAddress;
  };
  const cleanupLocalStorage = () => {
    if (localStorage.getItem('selectedPackage')) {
      localStorage.removeItem('selectedPackage');
    }
    if (localStorage.getItem('selectedPackageWithoutDiscount')) {
      localStorage.removeItem('selectedPackageWithoutDiscount');
    }
    if (localStorage.getItem('firstPageData')) {
      localStorage.removeItem('firstPageData');
    } else {
      console.log('No data found in local storage');
    }
  };

  const saveToLocalStorage = (key, value) => {
    localStorage.setItem(key, JSON.stringify(value));
  };

  const handleRemoveDiscount = () => {
    state.firstPageData.couponCode = null;
    localStorage.setItem('firstPageData', JSON.stringify(state.firstPageData));
    const newData = localStorage?.getItem('selectedPackageWithoutDiscount');
    if (newData) {
      state.packageDetails = JSON.parse(newData);
      localStorage.setItem('selectedPackage', newData);
    }
    updateOrderSummary();
  };

  const handleBillingAddress = () => {
    if (isChecked(elements.useBillingAsCurrent)) {
      return {
        postalAddress: state.postalAddress,
        zipCode: state.zipCode,
        postalCode: state.postalCode
      };
    } else {
      const isValid = validateInputs([
        elements.billingPostalAddress,
        elements.billingZipCode,
        elements.billingPostalCode
      ]);
      if (!isValid) return null;

      return {
        postalAddress: getInputValue(elements.billingPostalAddress),
        zipCode: getInputValue(elements.billingZipCode),
        postalCode: getInputValue(elements.billingPostalCode)
      };
    }
  };

  const handleNewAddress = () => {
    const isValid = validateInputs([
      elements.newAddressPostalAddress,
      elements.newAddressZipCode,
      elements.newAddressPostalCode
    ]);
    if (!isValid) return null;

    state.postalAddress = getInputValue(elements.newAddressPostalAddress);
    state.zipCode = getInputValue(elements.newAddressZipCode);
    state.postalCode = getInputValue(elements.newAddressPostalCode);

    if (isChecked(elements.useBillingAsNew)) {
      return {
        postalAddress: state.postalAddress,
        zipCode: state.zipCode,
        postalCode: state.postalCode
      };
    } else {
      const isValidBilling = validateInputs([
        elements.newAddressBillingPostalAddress,
        elements.newAddressBillingZipCode,
        elements.newAddressBillingPostalCode
      ]);
      if (!isValidBilling) return null;

      return {
        postalAddress: getInputValue(elements.newAddressBillingPostalAddress),
        zipCode: getInputValue(elements.newAddressBillingZipCode),
        postalCode: getInputValue(elements.newAddressBillingPostalCode)
      };
    }
  };

  elements.selectedOptionAddressOption.change(function () {
    var selectedValue = $(this).val();
    elements.newAddressRoot.toggleClass('address-container-selected');
    elements.currentAddressRoot.toggleClass('address-container-selected');
    if (selectedValue === 'current-address') {
      elements.currentAddressView.show();
      elements.newAddressView.hide();
    } else {
      elements.currentAddressView.hide();
      elements.newAddressView.show();
    }
  });

  const handlePriceDetailsModal = () => {
    const data = state.packageDetails;
    const isB2B = state.firstPageData?.isB2B;

    // Update monthly price
    elements.monthlyPrice.text(
      isB2B
        ? data?.monthly_estimation?.estimated_price
        : data.monthly_estimation.estimated_price_with_vat
    );

    // Generate price details HTML
    const details = `
      <div class="d-flex flex-column">
        <hr class="my-2" style="background-color: #E2DAD6;">
        <div class="d-flex justify-content-between">
          <p>Förnybar energimix</p>
          <p>Ingår alltid</p>
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
          !isB2B
            ? `<div class="d-flex justify-content-between gap-2">
                        <p>Månadsavgift inkl. moms</p>
                        <p class="text-end">${data?.monthly_fee?.fee_with_vat}</p>
                      </div>`
            : ''
        }
        ${
          isB2B
            ? `<div class="d-flex justify-content-between gap-2">
                        <p>Månadsavgift exkl. moms</p>
                        <p class="text-end">${data?.monthly_fee?.fee}</p>
                      </div>`
            : ''
        }
        
        ${
          !isB2B
            ? `<div class="d-flex justify-content-between">
                        <p>Moms (25 %)</p>
                        <p>${data?.category_prices?.vat}</p>
                      </div>`
            : ''
        }
        <hr class="my-2" style="background-color: #E2DAD6;">
        <div class="d-flex justify-content-between gap-2">
                    <p>Total ${isB2B ? 'exkl.' : 'inkl.'} moms </p>
                    <p class="fw-bold">${
                      isB2B
                        ? data.category_prices.total_price_without_vat
                        : data?.category_prices?.total_price
                    }</p>
                  </div>
        
        <!-- ${data.discount ? this.renderDiscount(data.discount) : ''} -->
      </div>
    `;

    const comparison = `
      <div class="d-flex flex-column pt-4">
                ${Object.keys(
                  isB2B ? data?.compare_prices_with_vat : data?.compare_prices
                )
                  .map(key => {
                    if (key.includes('_')) return '';
                    return `
                      <div class="d-flex justify-content-between gap-2">
                      <p>${key} kWh</p>
                      <p class="text-end">${
                        isB2B
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
    toggleModal('#price-details-modal');
  };

  const handleContractSigninInformation = (isSingleContract = false) => {
    if (isSingleContract) {
      elements.signinDescription.text(
        'Dags för signering. Du har ett avtal som behöver signeras. Köpet genomförs när du har signerat avtalet.'
      );
      $('#authorize-contract-view').hide();
      $('#electricity-contract-view').children('h6').text('1. Elavtal');
    } else {
      elements.signinDescription.text(
        'Dags för signering. Du har två avtal som behöver signeras. Köpet genomförs när du har signerat båda avtalen.'
      );
      $('#authorize-contract-view').show();
      $('#electricity-contract-view').children('h6').text('2. Elavtal');
    }
  };

  elements.facilityOption.change(function () {
    var selectedValue = $(this).val();
    elements.prefilledFacilityRoot.toggleClass('address-container-selected');
    elements.ownFacilityRoot.toggleClass('address-container-selected');
    if (selectedValue === 'facility-boo') {
      elements.prefilledFacilityView.show();
      elements.ownFilledFacility.hide();

      handleContractSigninInformation(false);
    } else {
      elements.prefilledFacilityView.hide();
      elements.ownFilledFacility.show();

      handleContractSigninInformation(true);
    }
  });

  elements.useBillingAsCurrent.change(function () {
    if (elements.useBillingAsCurrent.is(':checked')) {
      elements.customBillingAddressContainer.hide();
    } else {
      elements.customBillingAddressContainer.show();
    }
  });

  elements.useBillingAsNew.change(function () {
    if ($(this).is(':checked')) {
      elements.newCustomBillingAddressContainer.hide();
    } else {
      elements.newCustomBillingAddressContainer.show();
    }
  });

  elements.acceptTermButton.click(function () {
    elements.acceptTermCheckbox.prop('checked', true);
    elements.signPowerOfAttorney.prop('disabled', false);
    elements.signElectrictyContract.prop('disabled', false);

    toggleModal('#terms-modal');
  });

  // Utility function to update step UI
  const updateStepUI = (step, isCompleted) => {
    // Hide all step contents and remove active class
    elements.stepContents.hide().eq(step).show();
    elements.stepContainers.removeClass('active-step-container');
    elements.stepContainers.eq(step).addClass('active-step-container');

    // Update previous steps
    const prevSteps = Array.from({ length: step }, (_, i) => i);
    prevSteps.forEach(i => {
      elements.stepContainers.eq(i).addClass('completed-step-container');
      elements.stepCheckIcons.eq(i).show();
      elements.stepEditIcons.eq(i).hide();
    });

    // Update current/completed step
    const currentStep = step - 1;
    if (isCompleted && currentStep >= 0) {
      elements.stepContainers
        .eq(currentStep)
        .addClass('completed-step-container');
      elements.stepCheckIcons.eq(currentStep).show();
    } else {
      elements.stepContainers.eq(step).removeClass('completed-step-container');
      elements.stepCheckIcons.eq(step).hide();
    }

    elements.stepEditIcons.eq(step).hide();
  };

  // Function to handle moving to the next step
  const handleNextStep = step => {
    state.currentStep = step;
    updateStepUI(step, true);
  };

  // Function to show a specific step
  const showStep = step => {
    updateStepUI(step, false);
  };

  // Event listeners for step headers
  elements.stepHeaders.click(function () {
    var step = $(this).data('step') - 1;

    if (step < state.currentStep) {
      showStep(step);
    }
  });

  const handleFirstStepSave = () => {
    state.email = getInputValue(elements.emailInput);
    state.phone = getInputValue(elements.phoneInput);
    if (!state.fetchedAddress) {
      showAlert(elements.personNumberError);
      return;
    }
    const isValidEmail = validateInput(elements.emailInput);
    const isValidPhone = validateInput(elements.phoneInput);
    if (!isValidEmail || !isValidPhone) {
      return;
    }
    handleNextStep(1);
  };

  const handleSecondStepSave = () => {
    const selectedAddressOption = getSelectedValue(
      "input[name='address-option']"
    );

    if (selectedAddressOption === 'current-address') {
      state.billingAddress = handleBillingAddress();
    } else {
      state.billingAddress = handleNewAddress();
    }

    if (!state.billingAddress) return;

    handleNextStep(2);
  };

  const handleThirdStepSave = e => {
    e.preventDefault();
    e.stopPropagation();

    state.startDate = $('#start-date').val();

    if ($('input[name="facility-option"]:checked').val() !== 'facility-boo') {
      const isValidFacility = validateInput($('#facility-id'));
      const isValidAreadId = validateInput($('#area-id'));
      if (!isValidFacility || !isValidAreadId) {
        return;
      }
      state.facilityId = getInputValue('#facility-id');
      state.areaId = getInputValue('#area-id');
    }

    const isValidStartDate = validateInput($('#start-date'));
    if (!isValidStartDate) {
      $('#start-date').next('p').removeClass('hidden');
      return;
    }

    handleNextStep(3);
  };

  $(document).ready(function () {
    $('#start-date').on('input', function () {
      console.log('ab');
    });

    $('#start-date').on('change', function () {
      console.log('abc');
    });
  });

  // Check if getDatesForPriceGroup is available
  $(document).ready(function () {
    if (typeof getDatesForPriceGroup === 'function') {
      let defaultDate = '';
      if (typeof getDatesForPriceGroup === 'function') {
        const { date } = getDatesForPriceGroup();
        defaultDate = date;
      }
      $('input[name="start-date"]').val(defaultDate);
    } else {
      console.error('getDatesForPriceGroup is not defined.');
    }
  });

  // Datepicker initialization
  (function () {
    Datepicker.locales.sv = {
      days: [
        'söndag',
        'måndag',
        'tisdag',
        'onsdag',
        'torsdag',
        'fredag',
        'lördag'
      ],
      daysShort: ['sön', 'mån', 'tis', 'ons', 'tor', 'fre', 'lör'],
      daysMin: ['sö', 'må', 'ti', 'on', 'to', 'fr', 'lö'],
      months: [
        'januari',
        'februari',
        'mars',
        'april',
        'maj',
        'juni',
        'juli',
        'augusti',
        'september',
        'oktober',
        'november',
        'december'
      ],
      monthsShort: [
        'jan',
        'feb',
        'mar',
        'apr',
        'maj',
        'jun',
        'jul',
        'aug',
        'sep',
        'okt',
        'nov',
        'dec'
      ],
      today: 'Idag',
      clear: 'Rensa',
      titleFormat: 'MM yyyy',
      format: 'yyyy-mm-dd',
      weekStart: 1
    };
  })();

  // Get the default date
  const { date: defaultDate } = getDatesForPriceGroup();
  $('input[name="start-date"]').val(defaultDate);

  // Get the input element
  const elem = document?.querySelector('input[name="start-date"]');

  //Set the default date in the input field
  if (elem) {
    elem.value = defaultDate;
  }

  // Add the changeDate event listener to recall the API
  elem?.addEventListener('changeDate', async function (event) {
    const selectedDate = event.target.value;
    if (!selectedDate) return;

    try {
      const priceGroups = await getBooPriceGroups({
        date: selectedDate,
        saleDate: formatDate(new Date()),
        priceGroupId: [`${state.packageDetails?.price_group_id}`],
        netAreaId: state.firstPageData?.netAreaId,
        couponCode: state.firstPageData?.couponCode,
        consumptionAmount: state.consumptionAmount
      });

      state.packageDetails = {
        ...state.packageDetails,
        ...priceGroups?.[state.packageDetails.packageName]
      };

      updateOrderSummary();
    } catch (error) {
      console.error('Error fetching price groups:', error);
    }
  });

  if (elem) {
    const datepicker = new Datepicker(elem, {
      language: 'sv',
      autohide: true,
      minDate: new Date(new Date().setDate(new Date().getDate() + 2)) // Min selectable date is 2 days later
    });
  }

  // Listen for date change and enforce the min date rule
  elem?.addEventListener('change', function () {
    const selectedDate = new Date(this.value);
    const minSelectableDate = new Date();
    minSelectableDate.setDate(minSelectableDate.getDate() + 2);

    if (selectedDate < minSelectableDate) {
      alert('Please select a date at least 2 days from today.');
      this.value = formatDate(minSelectableDate); // Reset to the minimum valid date
    }
  });

  // Format date to YYYY-MM-DD
  // function formatDate(date) {
  //   return date.toISOString().split("T")[0];
  // }

  const toggleLoader = (button, isLoading) => {
    try {
      button.prop('disabled', isLoading);
      button.children('span').toggleClass('hidden', !isLoading);
    } catch (error) {
      console.log('error', error);
    }
  };

  const getPersonNumber = () => {
    return elements.personNumberInput.val().replace(/\D/g, '');
  };

  const downloadUserData = async () => {
    state.personNumber = getPersonNumber();
    const button = elements.downloadDataButton;
    toggleLoader(button, true);

    if (state.personNumber.length < 10) {
      showAlert(elements.personNumberError);
      toggleLoader(button, false);
      return;
    }
    try {
      let data = {};
      if (state?.firstPageData?.isB2B) {
        data = await getOrganizationInfo({
          organizationNumber: state.personNumber
        });
      } else {
        data = await getCustomerInfoByPersonNumber({
          personNumber: state.personNumber
        });
      }
      if (data && data?.success === false) {
        showAlert(elements.personNumberError);
        toggleLoader(button, false);
        return;
      }
      $('#person-number-error').addClass('hidden');
      if (data && data?.addresses?.length > 0) {
        state.fullName = data?.full_name;
        state.firstName = data?.full_name?.split(',')?.[0]?.trim();
        state.lastName = data?.full_name?.split(',')?.[1]?.trim();
        state.zipCode = data?.addresses?.[0]?.post_code;
        state.city = data?.addresses?.[0]?.city;
        state.postalAddress = data?.addresses?.[0]?.['street/box'];
        elements.personNumberInput.next().addClass('active');

        state.fetchedAddress = appendFetchedAddress(data);

        elements.firstStepSaveButton.prop('disabled', false);
      }
    } catch (error) {
      console.error(error);
      alert('An error occurred while fetching data');
    }

    toggleLoader(button, false);

    $('#downloaded-data').toggleClass('hidden');
    $('#download-data-button-container').toggleClass('hidden');
  };

  function isValidEmail(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email);
  }

  function validateInput(element) {
    const value = element.val().trim();
    const required = element.attr('required');
    const minLength = element.attr('minlength');
    const maxLength = element.attr('maxlength');
    const type = element.attr('type');

    if (required && value.length === 0) {
      element.nextAll('p').first().removeClass('hidden');
      return false;
    }

    if (minLength && value.length < minLength) {
      element.nextAll('p').first().removeClass('hidden');
      return false;
    }

    if (maxLength && value.length > maxLength) {
      element.nextAll('p').first().removeClass('hidden');
      return false;
    }

    if (type === 'email') {
      const isValid = isValidEmail(value);
      element.next().toggleClass('active', isValid);
      element.nextAll('p').first().toggleClass('hidden', isValid);
      return isValid;
    }

    element.next('span').addClass('active');
    element.nextAll('p').first().addClass('hidden');
    return true;
  }

  // $("input[required]").on("input", function () {
  //   if (validateInput($(this))) {
  //     $(this).next().addClass("active");
  //   } else {
  //     $(this).next().removeClass("active");
  //   }
  // });

  $('#read-more-info').click(function () {
    toggleModal('#info-modal');
  });

  const handleSignButtonContract = () => {
    if (elements.signButtonContract.text() === 'Tillbaka till Boo Energi') {
      window.location.href = '/klart/';

      toggleModal('#contract-modal');
      elements.contractSignTitle.show();
      elements.contractAfterSignView.toggleClass('hidden');
      elements.signButtonContract.text('Signera elavtalet');
      return;
    }
    elements.contractSignTitle.hide();
    elements.contractAfterSignView.toggleClass('hidden');
    elements.signButtonContract.text('Tillbaka till Boo Energi');
    state.isProxySigned = true;

    elements.proxySignedContent.toggleClass('hidden');
    elements.proxySignedText.text('Signerat');
    elements.signPowerOfAttorney.toggleClass('hidden');
  };

  // $("#scrive-link-attorny").click(function () {
  //   elements.signButton.removeClass("hidden");
  //   $("#scrive-link-attorny").addClass("hidden");
  // });

  const handleSignPowerOfAttorney = async () => {
    toggleModal('#signing-modal');
    const newWindow = window.open('', '_blank');
    // elements.signButton.addClass("hidden");
    // $("#scrive-link-attorny").addClass("disabled-link");
    elements.signButton.prop('disabled', true);
    elements.signButton.children('span').removeClass('hidden');

    try {
      let res;
      if (state.firstPageData?.isB2B) {
        res = await getProxySigninTemplateForOrganization({
          name: state.fullName,
          email: state.email,
          phone: state.phone,
          personal_number: state.personNumber,
          address: state.postalAddress,
          zip_code: state.zipCode,
          city: state.city,
          site_number: state.facilityId ?? undefined,
          area_id: state.areaId ?? undefined,
          estimated_consumption: state.consumptionAmount
        });
      } else {
        res = await getProxySigninTemplate({
          first_name: state.firstName,
          last_name: state.lastName,
          email: state.email,
          phone: state.phone,
          company: false,
          personal_number: state.personNumber,
          address: state.postalAddress,
          zip_code: state.zipCode || state?.firstPageData?.zipCode,
          city: state.city,
          site_number: state.facilityId ?? undefined,
          area_id: state.areaId ?? undefined,
          estimated_consumption: state.consumptionAmount
        });
      }

      if (res?.api_delivery_url) {
        // const link = document.getElementById("scrive-link-attorny");
        // link.href = res?.api_delivery_url;
        // $("#scrive-link-attorny").removeClass("disabled-link");
        if (newWindow) {
          newWindow.location.href = res?.api_delivery_url;
        } else {
          // Fallback if popup is blocked
          alert('Please allow popups for this website');
        }
      } else {
        elements.signButton.next().removeClass('hidden');
        setTimeout(() => {
          toggleModal('#signing-modal');
          elements.signButton.next().addClass('hidden');
        }, 5000);
        return;
      }

      const status = await checkIfSigned({
        func: getProxySigninStatus,
        scriveId: res?.document_id,
        buttonId: '#sign-button'
      });

      if (status?.status == 'rejected') {
        // Explicitly handle rejection case here
        $('#signing-modal .modal-content-container').addClass('hidden');
        $('#signing-modal .signing-error').removeClass('hidden');
        return;
      }

      elements.signButton.children('span').addClass('hidden');
    } catch (error) {
      console.log('error data', error);
      $('#signing-modal .modal-content-container').addClass('hidden');
      $('#signing-modal .signing-error').removeClass('hidden');
      return;
    }
  };

  $('#back-from-sign').click(function () {
    toggleModal('#signing-modal');
    $('#signing-modal .modal-content-container').removeClass('hidden');
    $('#signing-modal .signing-error').addClass('hidden');
  });

  $('#back-from-contract').click(function () {
    toggleModal('#contract-modal');
    $('#contract-modal .modal-content-container').removeClass('hidden');
    $('#contract-modal .signing-error').addClass('hidden');
  });

  // const handleErrorOnSign = () => {
  //   alert("An error occurred while signing the contract");
  //   toggleModal("#contract-modal");
  // };

  var signTitle = $('#sign-title');
  elements.signButton.click(function () {
    if ($(this).text() === 'Tillbaka till Boo Energi') {
      toggleModal('#signing-modal');
      signTitle.show();
      $('#after-sign-view').toggleClass('hidden');
      $(this).text('Signera elavtalet');
      return;
    }
    signTitle.hide();
    $('#after-sign-view').toggleClass('hidden');
    $(this).text('Tillbaka till Boo Energi');
    state.isProxySigned = true;

    $('#proxy-signed-content').toggleClass('hidden');
    $('#proxy-signed-text').text('Signerat');
    $('#sign-power-of-attorney').toggleClass('hidden');
    // $("#sign-electricity-contract").toggleClass("hidden");
  });

  function checkIfSigned({ func, scriveId, buttonId }) {
    return new Promise((resolve, reject) => {
      let interval;

      document.addEventListener('visibilitychange', async function () {
        if (document.visibilityState === 'visible') {
          interval = setInterval(async () => {
            try {
              const status = await func({ scriveId });

              // console.log("status", status?.status);

              if (status?.status == 'closed') {
                clearInterval(interval);
                $(buttonId).prop('disabled', false);
                $(buttonId).click();
                resolve(status); // Resolve the promise with the status
              } else if (status?.status == 'rejected') {
                clearInterval(interval);
                resolve(status); // Resolve instead of reject
                reject(new Error('Rejected Now'));
              }
            } catch (error) {
              clearInterval(interval);
              reject(error);
            }
          }, 3000);
        } else {
          clearInterval(interval);
        }
      });
    });
  }

  function openUrlInNewTab(url) {
    // For iOS Safari
    if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
      const newWindow = window.open('', '_blank');
      if (newWindow) {
        newWindow.location.href = url;
      } else {
        // Fallback if popup is blocked
        alert('Please allow popups for this website');
        window.location.href = url;
      }
      return;
    }

    // For other browsers
    const windowFeatures =
      'width=1000,height=800,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes';
    const newWindow = window.open(url, '_blank', windowFeatures);

    // Fallback if popup is blocked
    if (
      !newWindow ||
      newWindow.closed ||
      typeof newWindow.closed === 'undefined'
    ) {
      alert('Came to fallback');
      const link = document.createElement('a');
      link.href = url;
      link.target = '_blank';
      link.rel = 'noopener noreferrer';
      link.click();
    }
  }

  // $("#scrive-link-contract").click(function () {
  //   elements.signButtonContract.removeClass("hidden");
  //   $("#scrive-link-contract").addClass("hidden");
  // });

  elements.signElectrictyContract.click(async function () {
    toggleModal('#contract-modal');
    const newWindow = window.open('', '_blank');
    // $("#scrive-link-contract").addClass("disabled-link");
    // elements.signButtonContract.addClass("hidden");
    elements.signButtonContract.prop('disabled', true);
    elements.signButtonContract.children('span').removeClass('hidden');

    try {
      let res;
      if (state.firstPageData?.isB2B) {
        res = await getConsumerAgreementTemplateForOrganization({
          name: state.fullName,
          email: state.email,
          phone: state.phone,
          personal_number: state.personNumber,
          address: state.postalAddress,
          zip_code: state.zipCode,
          city: state.city,
          site_number: state.facilityId ?? undefined,
          area_id: state.areaId ?? undefined,
          date_from: state.startDate,
          product_name: state.packageDetails?.packageName,
          price_excluding_vat:
            state.packageDetails?.category_prices?.total_price_without_vat,
          price_including_vat:
            state.packageDetails?.category_prices?.total_price
        });
      } else {
        res = await getConsumerAgreementTemplate({
          first_name: state.firstName,
          last_name: state.lastName,
          email: state.email,
          phone: state.phone,
          personal_number: state.personNumber,
          company: false,
          address: state.postalAddress,
          zip_code: state.zipCode,
          city: state.city,
          site_number: state.facilityId ?? undefined,
          area_id: state.areaId ?? undefined,
          date_from: state.startDate,
          product_name: state.packageDetails?.packageName,
          price_excluding_vat:
            state.packageDetails?.category_prices?.total_price_without_vat,
          price_including_vat:
            state.packageDetails?.category_prices?.total_price,
          coupon_code: state.firstPageData?.couponCode ?? undefined
        });
      }
      if (res?.api_delivery_url) {
        if (newWindow) {
          newWindow.location.href = res?.api_delivery_url;
        } else {
          // Fallback if popup is blocked
          alert('Please allow popups for this website');
        }
      } else {
        elements.signButton.next().removeClass('hidden');
        setTimeout(() => {
          elements.signButtonContract.next().addClass('hidden');
          toggleModal('#contract-modal');
        }, 5000);
        return;
      }
      const status = await checkIfSigned({
        func: getConsumerAgreementStatus,
        scriveId: res?.document_id,
        buttonId: '#sign-button-contract'
      });

      if (status?.status === 'rejected') {
        throw new Error('Status rejected....');
      }

      if (status?.status === 'closed') {
        const customerData = await createCustomer({
          email: state.email,
          address: state.postalAddress,
          city: state.city,
          name: state.firstName + ' ' + state.lastName,
          phone: state.phone,
          ssn: state.personNumber,
          zipcode: state.zipCode,
          is_organization: state.firstPageData?.isB2B
        });
        const netAreaId = state.firstPageData?.netAreaId;
        const supplyMovesData = await createSupplyMoves({
          email: state.email,
          address: state.postalAddress,
          city: state.city,
          name: state.firstName + ' ' + state.lastName,
          phone: state.phone,
          ssn: state.personNumber,
          zipcode: state.zipCode,
          date_from: state.startDate,
          net_area_id: parseInt(netAreaId || 0),
          site_number: state.facilityId ?? '735999',
          is_organization: state.firstPageData?.isB2B,
          bill_name: state?.firstName + ' ' + state?.lastName,
          bill_address:
            state?.billingAddress?.postalAddress || state.postalAddress,
          bill_zipcode: state.billingAddress?.zipCode || state.zipCode,
          bill_city: state.city,
          price_group_id: state.packageDetails?.price_group_id,
          customer_id: customerData?.customer_id
        });

        elements.signButtonContract.children('span').addClass('hidden');
        saveToLocalStorage('boo-user-email', state?.email);
        cleanupLocalStorage();
      }
    } catch (error) {
      $('#contract-modal .modal-content-container').addClass('hidden');
      $('#contract-modal .signing-error').removeClass('hidden');
      return;
    }
  });

  var accordionItems = $('.accordion-item');
  accordionItems.each(function () {
    $(this).click(function () {
      $(this).toggleClass('active-step-container');
    });
  });

  const prefillFacilityId = async () => {
    const facilityIdInput = document.getElementById('facility-id');
    if (!facilityIdInput) return;
    const prefillText = '735999';

    // Set initial value
    facilityIdInput.value = prefillText;

    // Add event listener to prevent editing the prefilled part
    facilityIdInput.addEventListener('input', function (event) {
      if (!event.target.value.startsWith(prefillText)) {
        event.target.value = prefillText;
      }
    });

    // Move cursor to the end of the prefilled text when the input is focused
    facilityIdInput.addEventListener('focus', function (event) {
      setTimeout(function () {
        event.target.selectionStart = event.target.selectionEnd =
          prefillText.length;
      }, 0);
    });
  };

  // Event listeners
  elements.firstStepSaveButton.click(handleFirstStepSave);
  elements.secondStepSaveButton.click(handleSecondStepSave);
  elements.thirdStepSaveButton.click(handleThirdStepSave);
  elements.downloadDataButton.click(downloadUserData);
  elements.signPowerOfAttorney.click(handleSignPowerOfAttorney);
  elements.signButtonContract.click(handleSignButtonContract);
  elements.showPriceDetailsModalButton.click(handlePriceDetailsModal);

  // Add click event listener to each button
  $('.accordion-button').on('click', function () {
    const icon = $(this).find('.accordion-state-icon');
    const isCollapsed = $(this).hasClass('collapsed');

    const newSrc = isCollapsed
      ? icon.data('plus-icon')
      : icon.data('minus-icon');

    icon.attr('src', newSrc);
  });

  // Initialize the steps
  initSteps();
});

document.addEventListener('DOMContentLoaded', function () {
  const emailInput = document.querySelector(
    ".purchase-form input[type='email']"
  );

  // Load stored email if available
  if (localStorage.getItem('userEmail') && emailInput) {
    emailInput.value = localStorage.getItem('userEmail');
  }

  // Store email in localStorage on input change
  emailInput?.addEventListener('input', function () {
    localStorage.setItem('userEmail', emailInput.value);
  });
});
