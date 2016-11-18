#!/bin/sh
name="IOSTRUCT"
street1="1200 Kalone Way"
street2=""
zip="40515"
json="{\"vendor\": {\"name\": \"$name\", \"street1\": \"$street1\", \"street2\": \"$street2\", \"zip\": \"$zip\"}}"
curl -vv -H "Accept: application/json" -H "Content-Type: application/json" -H "X-AUTH-TOKEN: $AUTH_TOKEN" \
     --data "$json" localhost:8000/api/vendors
echo
