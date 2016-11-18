#!/bin/sh
curl -vv -H "Accept: application/json" -H "Content-Type: application/json" \
     -H "X-AUTH-TOKEN: $AUTH_TOKEN" localhost:8000/api/wishes/unfulfilled
echo
