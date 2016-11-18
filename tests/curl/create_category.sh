#!/bin/sh
name="Retail"
json="{\"category\": {\"name\": \"$name\"}}"
curl -vv -H "Accept: application/json" -H "Content-Type: application/json" -H "X-AUTH-TOKEN: $AUTH_TOKEN" \
     --data "$json" localhost:8000/api/categories
echo
