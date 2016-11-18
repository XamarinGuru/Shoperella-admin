#!/bin/sh
vendor_id=1
title="Free Drink"
caption="with purchase of Pick 2"
description="this is a test"
daily_deal="true"
hours_available=""
json="{\"deal\": {\"title\": \"$title\", \"caption\": \"$caption\", \"description\": \"$description\", \"dailyDeal\": $daily_deal, \"hoursAvailable\": \"$hoursAvailable\"}}"
curl -vv -H "Accept: application/json" -H "Content-Type: application/json" \
     -H "X-AUTH-TOKEN: $AUTH_TOKEN" --data "$json" \
     localhost:8000/api/vendors/$vendor_id/deals
echo
