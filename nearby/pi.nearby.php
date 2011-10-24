<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'nearby/config.php';

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Nearby Module Front End File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Blake Walters
 * @link		http://markupboy.com
 */

$plugin_info = array(
	'pi_name' => NEARBY_NAME,
	'pi_version' => NEARBY_VERSION,
	'pi_author' => NEARBY_AUTHOR,
	'pi_author_url' => NEARBY_AUTHOR_URL,
	'pi_description' => NEARBY_DESC,
	'pi_usage' => Nearby::usage()
);

class Nearby {
	
	public $return_data;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
		
		$zip = $this->EE->TMPL->fetch_param('zip');
		$limit = $this->EE->TMPL->fetch_param('limit');
		$radius = $this->EE->TMPL->fetch_param('radius');
		$user = $this->EE->TMPL->fetch_param('username');
		
		if(!$user) {
			$user = $this->EE->config->item('nearby_username');
		}
		
		if ($this->is_valid_zip($zip)) 
		{ 
			$data = $this->get_data($zip, $radius, $user);
			
			if(isset($data->status) && isset($data->status->message)) 
			{
				$this->return_data = "GeoNames API Access Error: " . $data->status->message;
			}
			else 
			{
				foreach ($data->postalCodes as $index => $location) {
					if($limit != "" && $index >= $limit) 
					{
						break;
					}	
					$tagdata = $this->EE->TMPL->tagdata;
					$vars = array(
						'distance' => $location->distance,
						'latitude' => $location->lat,
						'longitude' => $location->lng,
						'placename' => $location->placeName,
						'zip' => $location->postalCode
					);

					if(!$user || $user == 'demo') 
					{
						foreach ($vars as $key => $value) {
							$vars[$key] = $value . " demo";
						}
					}

					$variables[] = $vars;
				}
				
				$this->return_data = $this->EE->TMPL->parse_variables($tagdata, $variables);
			}
		}
	}
	
	// ----------------------------------------------------------------

	private function get_data($zip, $radius, $user) 
	{
		$username = $user ? $user : "demo";
		$url = "http://api.geonames.org/findNearbyPostalCodesJSON?postalcode=$zip&country=US&radius=$radius&username=$username";
		
		$data = file_get_contents($url,0,null,null);
		return json_decode($data);
	}
	
	private function is_valid_zip($zip) 
	{
		return preg_match('/^[0-9]{5}([- ]?[0-9]{4})?$/', $zip);
	}
	
	public function usage() {
		ob_start();
?>
Nearby requires a GeoNames.org account to full function.  Sign up for one at http://www.geonames.org/login.

Once your account is created and activated, you will need to log in to the GeoNames site, click your username in the top right of the page to bring up the user control panel and then click the "Click here to enable" link under the title "Free Web Services" to enable API usage on your account.

Once your account is fully enabled, you can pass your username to the plugin via the "username" parameter or set your username in your config.php file with the code:

$config['nearby_username'] = "YOUR USERNAME";

{exp:nearby}

Parameters:
zip (required) - the zipcode you'd like to search on
limit - limit the number of results returned
radius - distance in miles to search surrounding the desired zipcode
username - your GeoNames username

Single Variables:
{zip} - zipcode of result
{placename} - plain text descriptor of result
{latitude} - latitude of result, in decimal
{longitude} - longitude of result, in decimal
{distance} - the result's distance from the searched zipcode

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

}

/* End of file pi.nearby.php */
/* Location: /system/expressionengine/third_party/ */

?>