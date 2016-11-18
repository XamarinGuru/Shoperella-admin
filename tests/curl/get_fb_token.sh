#!/bin/sh
curl -vv -X GET "https://graph.facebook.com/oauth/access_token?client_id=$FACEBOOK_CLIENT_ID&client_secret=$FACEBOOK_CLIENT_SECRET&grant_type=client_credentials"
echo
