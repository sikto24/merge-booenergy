// Sync range and number input for size
export function syncSliderWithInput(rangeId, inputId, func) {
  const $range = jQuery(`#${rangeId}`);
  const $input = jQuery(`#${inputId}`);

  if (!$range.length || !$input.length) {
    console.error(`Elements with IDs "${rangeId}" or "${inputId}" not found.`);
    return null;
  }

  function updateInput(value) {
    func(value);
    $input.val(value);
  }

  function updateRange(value) {
    const parsedValue = parseFloat(value);
    const min = parseFloat($range.attr("min"));
    const max = parseFloat($range.attr("max"));

    if (parsedValue >= min && parsedValue <= max) {
      $range.val(Math.round(parsedValue));
    } else {
      console.error(`Input value "${value}" is out of range (${min}-${max}).`);
    }
  }

  $range.on("input", (e) => updateInput(e.target.value));
  $input.on("input", (e) => updateRange(e.target.value));

  return $range.val();
}

// Toggle modal using jQuery
export function toggleModal(modalId) {
  const $modal = jQuery(modalId);
  if ($modal.length) {
    $modal.modal("toggle");
  } else {
    console.error(`Modal with ID "${modalId}" not found.`);
  }
}

// Format date to YYYY-MM-DD
export function formatDate(date) {
  const d = new Date(date);
  if (isNaN(d.getTime())) {
    console.error(`Invalid date: ${date}`);
    return "";
  }
  const year = d.getFullYear();
  const month = `${d.getMonth() + 1}`.padStart(2, "0");
  const day = `${d.getDate()}`.padStart(2, "0");
  return `${year}-${month}-${day}`;
}

// Get starting date and sale date
export const getDatesForPriceGroup = () => {
  const today = new Date();
  const date = formatDate(today);
  const dateFromNextFifteenth = new Date(today.setDate(today.getDate() + 15));
  const dateFromNextFifteenthFormatted = formatDate(dateFromNextFifteenth);

  return {
    // date: dateFromNextFifteenthFormatted,
    date: dateFromNextFifteenth.toISOString().split("T")[0],
    saleDate: date,
  };
};



// Get monthly fee with VAT
export function getMonthlyFeeWithVat(products) {
  const product = products.find((product) => product["ProductID"] === 1050);
  if (!product) {
    console.error("Product with ID 1050 not found.");
    return 0;
  }
  return product["Cost"] * (1 + product["VatCost"]);
}

export function isB2B() {
  return jQuery("#b2b-slider-section").length > 0;
}

// Show package price
export function showPackagePrice(
  packageId,
  products,
  handleRemoveDiscount,
  isDiscount,
  productsWithoutDiscount
) {
  const price = isB2B()
    ? products?.monthly_estimation?.estimated_price
    : products?.monthly_estimation?.estimated_price_with_vat;
  const priceWithoutDiscount =
    productsWithoutDiscount?.monthly_estimation?.estimated_price_with_vat;
  const totalCategoryPrice = isB2B()
    ? products.category_prices.total_price_without_vat
    : products?.category_prices?.total_price;
  const disountPrice = products?.discount_price;

  if (price) {
    jQuery(`#${packageId} .offer-price`).text(`${price}`);
    jQuery(`#${packageId} .total-category-price`).text(`${totalCategoryPrice}`);
    if (isDiscount && disountPrice?.amount_numerical > 0) {
      jQuery(`#${packageId} .original-price`).text(`${priceWithoutDiscount}`);
    } else {
      jQuery(`#${packageId} .original-price`).text(``);
    }

    if (disountPrice?.amount_numerical > 0) {
      jQuery(`#${packageId} .package-discount-container`).removeClass("hidden");
      jQuery(`#${packageId} .calculated-discount`).text(
        `${disountPrice?.amount_with_vat}`
      );
      jQuery(`#${packageId} .discount-description`).text(
        `${disountPrice?.description}`
      );
      jQuery(`#${packageId} .discount-remove`).on("click", async () => {
        const removeIcon = jQuery(`#${packageId} .discount-remove`);
        removeIcon.addClass("hidden");
        removeIcon.next().addClass("active");

        const res = await handleRemoveDiscount();

        if (res) {
          removeIcon.removeClass("hidden");
          removeIcon.next().removeClass("active");
        }
      });
    }
  } else {
    console.error(`Failed to calculate price for packageId="${packageId}".`);
  }
}
