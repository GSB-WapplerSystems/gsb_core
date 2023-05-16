#!/bin/bash

# read the JSON file and extract the desired metric
metric=$(jq '.packages[0].classes[0].methods[0].ccn' report.json)

# generate the SVG badge
if [[ $metric -ge 10 ]]; then
  color="red"
else
  color="green"
fi
echo "<svg xmlns='http://www.w3.org/2000/svg' width='95' height='20'>
  <linearGradient id='a' x2='0' y2='100%'>
    <stop offset='0' stop-color='#bbb' stop-opacity='.1'/>
    <stop offset='1' stop-opacity='.1'/>
  </linearGradient>
  <rect rx='3' width='95' height='20' fill='#555'/>
  <rect rx='3' x='37' width='58' height='20' fill='${color}'/>
  <path fill='${color}' d='M37 0h4v20h-4z'/>
  <rect rx='3' width='95' height='20' fill='url(#a)'/>
  <g fill='#fff' text-anchor='middle' font-family='DejaVu Sans,Verdana,Geneva,sans-serif' font-size='11'>
    <text x='18' y='15' fill='#010101' fill-opacity='.3'>CCN</text>
    <text x='18' y='14'>CCN</text>
    <text x='65' y='15' fill='#010101' fill-opacity='.3'>${metric}</text>
    <text x='65' y='14'>${metric}</text>
  </g>
</svg>" > badge.svg
