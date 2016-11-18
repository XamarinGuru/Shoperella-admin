shoperella-site
===============

REST API for Shoperella.

## Curl Examples

Until we have actual functional tests, curl scripts have been created for 
testing API calls.

Curl examples reside in `tests/curl`.

To use them, create a `tests/curl/dotenv` file using 
`tests/curl/dotenv.example` as a starting point.

After creating the dotenv file, source the file using the following:

    . tests/curl/dotenv

Now you will have the exports needed to run the curl scripts.

First, you'll need to run `tests/curl/get_fb_token.sh` to get the 
Facebook token needed to add to the dotenv file.

Once you have this, you can get the needed api token for most 
API calls by running `tests/curl/login.sh`.

## Configuration

Use `dotenv-example` as a template for creating a new `.env` file in the 
project root.

## Development

    composer install
    bin/console server:start

## Deployment

First, make sure you have a proper SSH config for particle:

    Host particle
      Hostname particle.fusioncorpdesign.com
      User ec2-user
      IdentityFile ~/.ssh/FusioncorpAdmin.pem

Run the following to deploy the app:

    ./deployment/deploy.sh

## API Routes

 -------------------------------------------- -------- -------- ------ ----------------------------------------------------------
  Name                                         Method   Scheme   Host   Path
 -------------------------------------------- -------- -------- ------ ----------------------------------------------------------
  ping                                         GET      ANY      ANY    /api/ping.{_format}
  post_register                                POST     ANY      ANY    /api/auth/register.{_format}
  post_auth_login                              POST     ANY      ANY    /api/auth/login.{_format}
  get_auth_session                             GET      ANY      ANY    /api/auth/session.{_format}
  get_user_logout                              GET      ANY      ANY    /api/auth/logout.{_format}
  get_vendor                                   GET      ANY      ANY    /api/vendors/{id}.{_format}
  get_vendors                                  GET      ANY      ANY    /api/vendors.{_format}
  get_vendors_by_coords                        GET      ANY      ANY    /api/vendors/by/coords.{_format}
  get_vendors_by_owner                         GET      ANY      ANY    /api/vendors/by/owner.{_format}
  get_vendors_and_deals                        GET      ANY      ANY    /api/vendors/and/deals.{_format}
  post_vendor                                  POST     ANY      ANY    /api/vendors.{_format}
  put_vendor_assign                            PUT      ANY      ANY    /api/vendors/{vendorId}/assign.{_format}
  get_vendor_assigned_wishes                   GET      ANY      ANY    /api/vendors/{vendorID}/assigned/wishes.{_format}
  post_vendor_image                            POST     ANY      ANY    /api/vendor/add/logo.{_format}
  post_wish                                    POST     ANY      ANY    /api/wishes.{_format}
  post_wish_vendor_add                         POST     ANY      ANY    /api/wishes/vendors/adds.{_format}
  post_wish_vendor_remove                      POST     ANY      ANY    /api/wishes/vendors/removes.{_format}
  get_wishes_unfulfilled                       GET      ANY      ANY    /api/wishes/unfulfilled.{_format}
  renew_vendor_deal                            POST     ANY      ANY    /api/deal/renew.{_format}
  post_vendor_deal                             POST     ANY      ANY    /api/vendors/{vendorId}/deals.{_format}
  get_vendor_deals_vendor                      GET      ANY      ANY    /api/vendor/deals/vendor.{_format}
  delete_vendor_deal                           POST     ANY      ANY    /api/deal/delete.{_format}
  post_vendor_add_favorite                     POST     ANY      ANY    /api/favorite/add.{_format}
  post_vendor_remove_favorite                  POST     ANY      ANY    /api/favorite/remove.{_format}
  post_vendor_offer                            POST     ANY      ANY    /api/create/offer.{_format}
  put_vendor_offer_redeem                      PUT      ANY      ANY    /api/vendors/{id}/offer/redeem.{_format}
  put_vendor_offer_extend                      PUT      ANY      ANY    /api/vendors/{id}/offer/extend.{_format}
  get_vendor_offer_user                        GET      ANY      ANY    /api/offers/user.{_format}
  get_vendor_offer_vendor                      GET      ANY      ANY    /api/offers/vendor/{vendorId}.{_format}
  get_vendor_vendor_redeemded                  GET      ANY      ANY    /api/offers/vendor/redeemed/{vendorId}.{_format}
  get_vendor_offer_count_for_vendor            GET      ANY      ANY    /api/offers/count/vendor/{vendorId}.{_format}
  delete_vendor_offer                          DELETE   ANY      ANY    /api/vendors/{id}/offer.{_format}
  post_offer                                   POST     ANY      ANY    /api/create/offer.{_format}
  put_offer_redeem                             PUT      ANY      ANY    /api/offers/{id}/redeem.{_format}
  put_offer_extend                             PUT      ANY      ANY    /api/offers/{id}/extend.{_format}
  get_offer_user                               GET      ANY      ANY    /api/offers/user.{_format}
  get_offer_vendor                             GET      ANY      ANY    /api/offers/vendor/{vendorId}.{_format}
  get_vendor_redeemded                         GET      ANY      ANY    /api/offers/vendor/redeemed/{vendorId}.{_format}
  get_offer_count_for_vendor                   GET      ANY      ANY    /api/offers/count/vendor/{vendorId}.{_format}
  delete_offer                                 DELETE   ANY      ANY    /api/offers/{id}.{_format}
  get_category                                 GET      ANY      ANY    /api/categories/{id}.{_format}
  post_category                                POST     ANY      ANY    /api/categories.{_format}
  post_vendor_category_activation              POST     ANY      ANY    /api/vendors/{vendorId}/categories/activations.{_format}
 -------------------------------------------- -------- -------- ------ ----------------------------------------------------------
