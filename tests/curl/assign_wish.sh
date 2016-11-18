#!/bin/sh
vendor_id=1
wish=1
json="{\"wish\": $wish}"
curl -vv -X PUT -H "Accept: application/json" \
     -H "Content-Type: application/json" \
     -H "X-AUTH-TOKEN: $AUTH_TOKEN" --data "$json" \
     localhost:8000/api/vendors/$vendor_id/assign
