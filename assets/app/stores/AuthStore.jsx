import Constants from '../constants/Constants.jsx';
import {EventEmitter} from 'events';
import AuthDispatcher from '../dispatcher/AuthDispatcher.jsx';

const AUTH_CHANGE_EVENT = 'AUTH_CHANGE_EVENT';

class AuthStore extends EventEmitter {
  constructor() {
    super();
    this.facebookAuthData = {};
    this.authData = {};
  }

  setFacebookAuthData(data) {
    this.facebookAuthData = data;
    this.emitChange();
  }

  setAuthData(data) {
    this.authData = data;
    this.emitChange();
  }

  get loggedIn() {
    if (!this.authData) {
      return;
    }
    return this.authData.api_token !== undefined;
  }

  get facebookResponseReceived() {
    if (!this.facebookAuthData) {
      return;
    }
    return true;
  }

  get facebookId() {
    if (!this.facebookAuthData) {
      return;
    }
    return this.facebookAuthData.id;
  }

  emitChange() {
    this.emit(AUTH_CHANGE_EVENT);
  }

  addChangeListener(callback) {
    this.on(AUTH_CHANGE_EVENT, callback);
  }

  removeChangeListener(callback) {
    this.removeListener(AUTH_CHANGE_EVENT, callback);
  }
}

// store is a singleton
const authStore = new AuthStore();

authStore.dispatchToken = AuthDispatcher.register((action) => {
  if (action.actionType == Constants.FACEBOOK_RESPONSE_RECEIVED) {
    authStore.setFacebookAuthData(action.data);
  }
  if (action.actionType == Constants.AUTH_LOGGED_IN) {
    authStore.setAuthData(action.data);
  }
});

export default authStore;
