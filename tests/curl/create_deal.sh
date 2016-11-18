#!/bin/sh
vendor_id=1
title="Free Sea Bass"
caption="with purchase of one Halibut"
description="this is a test"
daily_deal="false"
hours_available="3"
json="{\"deal\": {\"title\": \"$title\", \"caption\": \"$caption\", \"description\": \"$description\", \"dailyDeal\": $daily_deal, \"hoursAvailable\": $hours_available}}"
curl -vv -H "Accept: application/json" -H "Content-Type: application/json" \
     -H "X-AUTH-TOKEN: $AUTH_TOKEN" --data "$json" \
     localhost:8000/api/vendors/$vendor_id/deals
echo
