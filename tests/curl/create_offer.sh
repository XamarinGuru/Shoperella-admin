#!/bin/sh
vendor_id="1"
deal="1"
wish="1"
json="{\"offer\": {\"deal\": \"$deal\", \"wish\": \"$wish\"}}"
curl -vv -H "Accept: application/json" -H "Content-Type: application/json" -H "X-AUTH-TOKEN: $AUTH_TOKEN" \
     --data "$json" localhost:8000/api/vendors/$vendor_id/offers
echo
