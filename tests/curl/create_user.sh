#!/bin/sh
json="{\"mobile_signup\": {\"facebookAccessToken\": \"$FACEBOOK_ACCESS_TOKEN\"}}"
curl -vv -H "Accept: application/json" -H "Content-Type: application/json" \
     --data "$json" localhost:8000/api/auth/register
echo
