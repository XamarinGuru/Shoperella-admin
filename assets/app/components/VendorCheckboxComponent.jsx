import React, { Component } from "react";
import ApiClient from "../clients/ApiClient.jsx";

export default class VendorCheckbox extends Component {
    constructor(props)
    {
        super(props);
        this._checkboxOnChange = this._checkboxOnChange.bind(this);
        this.state = {
            checked: this.props.checked
        }
    }
    _checkboxOnChange(event)
    {
        if (event.target.checked === true)
        {
           //add vendor
            ApiClient.assignVendorToWish(this._checkboxAfterChange, this.props.vendor.id, this.props.wishID);
            this.setState({
                checked: true
            });
        } else {
           //remove vendor
            ApiClient.removeVendorFromWish(this._checkboxAfterChange, this.props.vendor.id, this.props.wishID);
            this.setState({
               checked: false
            });
        }
    }
    _checkboxAfterChange(data)
    {
    }
    render()
    {
        var checked = false;
        if (this.state.checked === true)
        {
            checked = true;
        }
        return (
            <li>
                <input type="checkbox" onChange={this._checkboxOnChange} checked={checked}/>
                {this.props.vendor.name}
            </li>
        );
    }
}