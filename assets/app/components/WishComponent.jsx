import React from "react";
import VendorCheckbox from "./VendorCheckboxComponent.jsx";
import _ from "lodash";

const Wish = ({ wish, vendors }) => (
    <div className="col-xs-12 col-sm-12 col-md-10 col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-1 col-lg-offset-2">
        <div className="wish-component">
            <div className="wish-meta">
                <ul>
                    <li>User: {wish.user.name}</li>
                    <li>Created: {wish.created}</li>
                    <li>Updated: {wish.updated}</li>
                </ul>
            </div>
            <div className="wish-text">
                {wish.query}
            </div>
            <div className="wish-vendor-select">
                <div className="wish-vendor-title">
                    Add Vendors To Wish
                </div>
                <ul>
                    { _.map(vendors, function(vendor, key)
                        {
                            var checked = false;
                            _.forEach(wish.vendor, function(wv){
                                if (wv.id == vendor.id)
                                    {
                                        checked = true;
                                        return false;
                                        }
                                });
                            return <VendorCheckbox key={key} vendor={vendor} wishID={ wish.id } checked={checked} />;
                            }
                        )}
                </ul>
            </div>
        </div>
    </div>
);

export default Wish;