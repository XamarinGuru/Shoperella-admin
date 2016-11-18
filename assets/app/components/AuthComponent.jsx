import React from 'react';
import AuthActionCreators from '../actions/AuthActionCreators.jsx';
import {Router, Route, hashHistory} from 'react-router';
import FacebookLogin from 'react-facebook-login';
import AuthStore from '../stores/AuthStore.jsx';
import Env from '../config/Env.jsx';

class Auth extends React.Component {
  constructor(props) {
    super();
    this.state = this.getAuthState();
  }

  getAuthState() {
    return {
      facebookResponseReceived: AuthStore.facebookResponseReceived,
      loggedIn: AuthStore.loggedIn
    }
  }

  componentDidMount() {
    if (this.state.loggedIn) {
      this.redirectToDashboard();
    }
    AuthStore.addChangeListener(() => this._onAuthChange());
  }

  componentWillUnmount() {
    AuthStore.removeChangeListener(this._onAuthChange);
  }

  // AuthStore listener callback
  // react to changes in state
  _onAuthChange() {
    this.setState(this.getAuthState());
    if (this.state.loggedIn) {
      this.redirectToDashboard();
    }
    if (this.state.facebookResponseReceived && !this.state.loggedIn) {
      AuthActionCreators.login(AuthStore.facebookId);
    }
  }

  render() {
    return (
      <FacebookLogin
       appId={Env.FACEBOOK_APP_ID}
       autoLoad={true}
       fields="name,email,picture"
       callback={AuthActionCreators.handleFacebookResponse} />
    );
  }

  redirectToDashboard() {
    hashHistory.push('/');
  }
}

export default Auth;
