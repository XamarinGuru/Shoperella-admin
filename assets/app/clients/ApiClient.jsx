import $ from 'jquery';

// Shoperella API Client
//
// All Shoperella REST API actions live here.
//
// All methods take data arguments that are used in the request,
// followed by a callback that takes the response data as an argument.
//
// The response is an object with a "success" attribute set to true or false
// and a data attribute with the original data from the API response.
const ApiClient = {
  login: function(facebookId, callback) {
    $.ajax({
      url: '/api/auth/login',
      type: 'post',
      data: {facebookId: facebookId},
      success: (data) => {
        callback(this.generateSuccessResponse(data));
      }
    });
  },

  unfulfilledWishes: function(callback) {
    $.ajax({
      url: '/dashboard/api/getUnfulfilledWishes',
      type: 'get',
      success: (data) => {
        callback(this.generateSuccessResponse(data));
      }
    });
  },

  assignVendorToWish: function(callback, vendorID, wishID) {
    $.ajax({
      url: '/dashboard/api/addVendorToWish',
      type: 'post',
      data: {
        "vendorID": vendorID,
        "wishID": wishID
      },
      success: (data) => {
        callback(this.generateSuccessResponse(data));
      }
    });
  },

  removeVendorFromWish: function(callback, vendorID, wishID) {
    $.ajax({
      url: '/dashboard/api/removeVendorFromWish',
      type: 'post',
      data: {
        "vendorID": vendorID,
        "wishID": wishID
      },
      success: (data) => {
        callback(this.generateSuccessResponse(data));
      }
    });
  },

  getVendors: function(callback) {
    $.ajax({
      url: '/dashboard/api/getVendors',
      type: 'get',
      success: (data) => {
        callback(this.generateSuccessResponse(data));
      }
    });
  },

  generateSuccessResponse: function(data) {
    return {success: true, data: data};
  }
};

export default ApiClient;
