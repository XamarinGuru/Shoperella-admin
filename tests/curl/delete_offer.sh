#!/bin/sh
offer_id=1
curl -vv -X DELETE -H "Accept: application/json" \
     -H "X-AUTH-TOKEN: $AUTH_TOKEN" --data "$json" \
     localhost:8000/api/offers/$offer_id
