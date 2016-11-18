import Constants from '../constants/Constants.jsx';
import AuthDispatcher from '../dispatcher/AuthDispatcher.jsx';
import ApiClient from '../clients/ApiClient.jsx';
import Env from '../config/Env.jsx';

const AuthActionCreators = {
  handleFacebookResponse: function(response) {
    AuthDispatcher.dispatch({
      actionType: Constants.FACEBOOK_RESPONSE_RECEIVED,
      data: response
    });
  },

  login: function(facebookId) {
    ApiClient.login(facebookId, (response) => {
      if (response.success) {
        AuthDispatcher.dispatch({
          actionType: Constants.AUTH_LOGGED_IN,
          data: response.data
        });
      }
    });
  }
}

export default AuthActionCreators;
