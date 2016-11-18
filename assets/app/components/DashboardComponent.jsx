import React from 'react';
//import AuthStore from '../stores/AuthStore.jsx';
import {Router, Route, hashHistory} from 'react-router';
import _ from "lodash";

import ApiClient from "../clients/ApiClient.jsx";
import Wish from "./WishComponent.jsx";

class Dashboard extends React.Component {
  constructor(props) {
      super();
      this.state = {
          wishes: {},
          vendors: {},
      };

      this.setWishes    = this.setWishes.bind(this);
      this.setVendors   = this.setVendors.bind(this);
      this._sortVendors = this._sortVendors.bind(this);
    //this.state = this.getAuthState();
  }

  //getAuthState() {
  //  return {
  //    loggedIn: AuthStore.loggedIn
  //  }
  //}
  //
  setWishes(wishes)
  {
      this.setState({
          wishes: wishes.data
      });
  }

  setVendors(vendors)
  {
      this.setState({
         vendors: vendors.data
      });
  }

  componentDidMount(){
      ApiClient.getVendors(this.setVendors);
      ApiClient.unfulfilledWishes(this.setWishes);

    //if (!this.state.loggedIn) {
    //  hashHistory.push('/auth');
    //}
    //AuthStore.addChangeListener(() => this._onAuthChange());
  }
  //
  //componentWillUnmount() {
  //  AuthStore.removeChangeListener(this._onAuthChange);
  //}
  //
  //_onAuthChange() {
  //  this.setState(this.getAuthState());
  //}

  _sortVendors()
  {
      var col1 = [];
      var col2 = [];
      var col3 = [];

      var size = _.size(this.state.vendors);

      var colSize = _.round(size/3);

      _.map(this.state.vendors, function(vendor, i){
          if (i <= colSize)
          {
              col1.push(vendor);
          } else if (i <= (colSize*2))
          {
              col2.push(vendor);
          } else {
              col3.push(vendor);
          }
      });


      return [col1, col2, col3]

  }

  render(){

    var wishes = [];


    for(var i = 0; i < this.state.wishes.length; i++){
        wishes.push(<Wish key={i} wish={this.state.wishes[i]} vendors={this.state.vendors} />)
    }
    return (
        <div id="page-container">
            <header>
              <div className="app-title">
                Shoperella
              </div>
            </header>
            <div className="main-container container-fluid">
                <div className="row">
                    {wishes}
                </div>
            </div>
        </div>
      );
  }
}

export default Dashboard;