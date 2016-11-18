#!/bin/sh
vendor_id=1
curl -vv -H "Accept: application/json" "localhost:8000/api/vendors/$vendor_id"
