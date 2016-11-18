#!/bin/sh
facebook_id=10153384029951918
json="{\"facebookId\": \"$facebook_id\"}"
curl -vv -H "Accept: application/json" -H "Content-Type: application/json" \
     --data "$json" localhost:8000/api/auth/login
echo
