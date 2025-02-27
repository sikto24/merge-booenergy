// export const API_URL = "http://localhost:8000/api/web/v1/";
// export const API_URL =
//   'https://api.staging.boowebbackend.strativ-support.se/api/web/v1/';
// export const API_URL_V2 =
//   'https://api.staging.boowebbackend.strativ-support.se/api/web/v2/';
// // const API_KEY = "JRNR4Gp3.egcFSU9RZjCLbMUR2U7Qe1olDQcIpZss";
// const API_KEY = 'Api-Key cpScLNYv.4L1oHlJtFLxcw4rdSpN0ZgqvsRCLxqRQ';

let API_URL;
let API_URL_V2;
let API_KEY;

const currentURL = window.location.href;
const url = new URL(currentURL);
const domain = url.hostname;

if (domain === 'booenergi.se' || domain === 'prod.booenergi.se') {
  API_URL = 'https://api.booenergi.se/api/web/v1/';
  API_URL_V2 = 'https://api.booenergi.se/api/web/v2/';
  API_KEY = 'Api-Key UZ2ehkpq.74zQQ3qK9B0nFxyQkPaVnW0mmdbDBJ2x';
} else if (domain === 'staging.booenergi.se') {
  API_URL = 'https://api.staging.booenergi.se/api/web/v1/';
  API_URL_V2 = 'https://api.staging.booenergi.se/api/web/v2/';
  API_KEY = 'Api-Key hEeAQDEa.IHfbL6z03FrXDsvfysrmFuCm4lthp4gp';
} else {
  API_URL = 'http://api.dev.staging.booenergi.se/api/web/v1/';
  API_URL_V2 = 'http://api.dev.staging.booenergi.se/api/web/v2/';
  API_KEY = 'Api-Key cpScLNYv.4L1oHlJtFLxcw4rdSpN0ZgqvsRCLxqRQ';
}

const headers = {
  'Content-Type': 'application/json',
  Authorization: API_KEY
};

const options = {
  method: 'GET',
  headers: {
    ...headers
  }
};

export const getZipCode = async ({ zipCode }) => {
  try {
    const response = await fetch(`${API_URL_V2}zipcodes/${zipCode}/`, options);
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const getElectricRanges = async ({ zipCode }) => {
  try {
    const response = await fetch(
      `${API_URL}netareas/electric-range/${zipCode}/`,
      options
    );
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const getNetArea = async ({ namespaceDesignation }) => {
  try {
    const response = await fetch(
      `${API_URL}netareas/${namespaceDesignation}/`,
      options
    );
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const getBooPriceGroups = async ({
  date,
  saleDate,
  priceGroupId,
  netAreaId,
  couponCode,
  consumptionAmount
}) => {
  try {
    const payload = {
      date: date,
      sale_date: saleDate,
      //   price_group_id_list: [1161, 1041, 1039, 1147, 1148, 1038],
      price_group_id_list: priceGroupId,
      net_area_id: netAreaId,
      coupon_code: couponCode ?? undefined,
      consumption_amount: consumptionAmount
    };
    const response = await fetch(`${API_URL_V2}products/boo-price-groups/`, {
      ...options,
      method: 'POST',
      body: JSON.stringify(payload)
    });
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const getCustomerInfoByPersonNumber = async ({ personNumber }) => {
  try {
    const response = await fetch(
      `${API_URL}customers/person-information/${personNumber}`,
      options
    );
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const getProxySigninTemplate = async data => {
  try {
    const response = await fetch(
      `${API_URL}scrive/fullmakt/signing-template/`,
      {
        ...options,
        method: 'POST',
        body: JSON.stringify(data)
      }
    );
    const res = await response.json();
    return res;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const getProxySigninTemplateForOrganization = async data => {
  try {
    const response = await fetch(
      `${API_URL}scrive/fullmakt/organization/signing-template/`,
      {
        ...options,
        method: 'POST',
        body: JSON.stringify(data)
      }
    );
    const res = await response.json();
    return res;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const getProxySigninStatus = async ({ scriveId }) => {
  try {
    const response = await fetch(
      `${API_URL}scrive/fullmakt/status/${scriveId}`,
      options
    );
    const data = await response.json();
    return data;
  } catch (error) {
    console.log('Error working');
    console.error(error);
    throw error;
  }
};

export const getConsumerAgreementTemplate = async data => {
  try {
    const response = await fetch(
      `${API_URL}scrive/konsumentavtal/signing-template/`,
      {
        ...options,
        method: 'POST',
        body: JSON.stringify(data)
      }
    );
    const res = await response.json();
    return res;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const getConsumerAgreementTemplateForOrganization = async data => {
  try {
    const response = await fetch(
      `${API_URL}scrive/konsumentavtal/organization/signing-template/`,
      {
        ...options,
        method: 'POST',
        body: JSON.stringify(data)
      }
    );
    const res = await response.json();
    return res;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const getConsumerAgreementStatus = async ({ scriveId }) => {
  try {
    const response = await fetch(
      `${API_URL}scrive/konsumentavtal/status/${scriveId}`,
      options
    );
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const createCustomer = async data => {
  try {
    const response = await fetch(`${API_URL}customers/`, {
      ...options,
      method: 'POST',
      body: JSON.stringify(data)
    });
    const res = await response.json();
    return res;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const createSupplyMoves = async data => {
  try {
    const response = await fetch(`${API_URL}customers/supply-moves/`, {
      ...options,
      method: 'POST',
      body: JSON.stringify(data)
    });
    const res = await response.json();
    return res;
  } catch (error) {
    console.error(error);
    throw error;
  }
};

export const getOrganizationInfo = async ({ organizationNumber }) => {
  try {
    const response = await fetch(
      `${API_URL}customers/organization-information/${organizationNumber}`,
      options
    );
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
    throw error;
  }
};
