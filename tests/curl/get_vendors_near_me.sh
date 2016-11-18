#!/bin/sh
lat="37.9836870"
lng="-84.4560650"
coords="$lat,$lng"
curl -X GET -vv -H "Accept: application/json" \
     "localhost:8000/api/vendors?coords=$coords"
echo
