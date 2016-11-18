import React from 'react';
import ReactDom from 'react-dom';
import {Router, Route, browserHistory} from 'react-router';
import Dashboard from './components/DashboardComponent.jsx';
import Auth from './components/AuthComponent.jsx';

ReactDom.render(
	<Router history={browserHistory}>
        <Route path="/dashboard" component={Dashboard}>
        </Route>
    </Router>,
  document.getElementById('page-wrapper')
);

//this is for auth when it gets figured out
//<Route path="/auth" component={Auth}/>
