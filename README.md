__Nearby__ is a simple ExpressionEngine plugin that uses the [GeoNames.org](http://www.geonames.org) API to find places near a known zip code. 

Installation
------------

Place the nearby plugin folder in your ExpressionEngine installation's third\_party folder (system/expressionengine/third\_party by default).

Since Nearby makes user of the GeoNames API, a GeoNames user account will be required to use more than the demo mode provided. You can sign up for one at [www.geonames.org/login](http://www.geonames.org/login)

Once your account is created and activated, you will need to log in to the GeoNames site, click your username in the top right of the page to bring up the user control panel and then click the "Click here to enable" link under the title "Free Web Services" to enable API usage on your account.

Once your account is fully enabled, you can pass your username to the plugin via the "username" parameter or set your username in your config.php file with the code:

`$config['nearby_username'] = "YOUR USERNAME";`

Usage
-----

{exp:nearby}

Parameters:

* zip (required) - the zipcode you'd like to search on
* limit - limit the number of results returned
* radius - distance in miles to search surrounding the desired zipcode
* username - your GeoNames username

Single Variables:

* {zip} - zipcode of result
* {placename} - plain text descriptor of result
* {latitude} - latitude of result, in decimal
* {longitude} - longitude of result, in decimal
* {distance} - the result's distance from the searched zipcode

{/exp:nearby}

