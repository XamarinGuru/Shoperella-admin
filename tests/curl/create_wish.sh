#!/bin/sh
query="black dress"
latitude="38.048188"
longitude="-84.493460"
json="{\"wish\": {\"query\": \"$query\", \"latitude\": \"$latitude\", \"longitude\": \"$longitude\"}}"
curl -vv -H "Accept: application/json" -H "Content-Type: application/json" \
     -H "X-AUTH-TOKEN: $AUTH_TOKEN" --data "$json" localhost:8000/api/wishes
echo
