# Location Search
A simple test app to search a database of locations by latitude / longitude.

Built with my slim MVC framework.

Works as a web form search, or as a url API. 

The user can query for "least populated", "most populated", "closest" or "farthest" city within a specified radius of a lat/lon pair. 

The service returns 20 suggested cities are displayed as an HTML table, or in a JSON bundle, depending on the options used. 

Demo the web interface

http://rmw.technology/inmarket/search

Demo The API by using a url of the form

http://rmw.technology/inmarket/search/[LAT]/[LON]/[CRITERIA]/[RADIUS]/[VIEW_TYPE]

where:
lat & lon are latitude and longitude coordinates
criteria is 1 - most populated, 2 - least populated, 3 - closest, 4 - farthest
radius is an integer indicating miles around the lat/lon to constrain the search to
view type is 1 - HTML, 2 - JSON

For example, to get a JSON result of the most populated cities within 100 miles of Hollywood the URL is...
http://rmw.technology/inmarket/search/34.1030456/-118.3286613,15/1/100/2
