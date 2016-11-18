#!/bin/sh
vendor_id="1"
category="1"
json="{\"category_activation\": {\"category\": \"$category\"}}"
curl -vv -H "Accept: application/json" -H "Content-Type: application/json" -H "X-AUTH-TOKEN: $AUTH_TOKEN" \
     --data "$json" localhost:8000/api/vendors/$vendor_id/categories/activations
echo
