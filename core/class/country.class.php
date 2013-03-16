<?php
/**
 * Allows the manipulation of countries. Codenames are based on the 
 * ISO 3166-1 alpha-2 standard.
 *
 * @author Jean-Philippe Collette
 * @package Core
 * @subpackage Misc
 */
class Country
{
	private static $countries = array(	"AF" => "Afghanistan",
										"AL" => "Albania",
										"DZ" => "Algeria",
										"AS" => "American Samoa",
										"AD" => "Andorra",
										"AO" => "Angola",
										"AI" => "Anguilla",
										"AG" => "Antigua And Barbuda",
										"AR" => "Argentina",
										"AM" => "Armenia",
										"AW" => "Aruba",
										"AU" => "Australia",
										"AT" => "Austria",
										"AZ" => "Azerbaijan",
										"BS" => "Bahamas",
										"BH" => "Bahrain",
										"BD" => "Bangladesh",
										"BB" => "Barbados",
										"BY" => "Belarus",
										"BE" => "Belgium",
										"BZ" => "Belize",
										"BJ" => "Benin",
										"BM" => "Bermuda",
										"BT" => "Bhutan",
										"BO" => "Bolivia",
										"BA" => "Bosnia And Herzegowina",
										"BW" => "Botswana",
										"BV" => "Bouvet Island",
										"BR" => "Brazil",
										"IO" => "British Indian Ocean Territory",
										"BN" => "Brunei Darussalam",
										"BG" => "Bulgaria",
										"BF" => "Burkina Faso",
										"BI" => "Burundi",
										"KH" => "Cambodia",
										"CM" => "Cameroon",
										"CA" => "Canada",
										"CV" => "Cape Verde",
										"KY" => "Cayman Islands",
										"CF" => "Central African Republic",
										"TD" => "Chad",
										"CL" => "Chile",
										"CN" => "China",
										"CX" => "Christmas Island",
										"CC" => "Cocos (Keeling) Islands",
										"CO" => "Colombia",
										"KM" => "Comoros",
										"CG" => "Congo",
										"CD" => "Congo, The Democratic Republic Of The",
										"CK" => "Cook Islands",
										"CR" => "Costa Rica",
										"CI" => "Cote D'Ivoire",
										"HR" => "Croatia",
										"CU" => "Cuba",
										"CY" => "Cyprus",
										"CZ" => "Czech Republic",
										"DK" => "Denmark",
										"DJ" => "Djibouti",
										"DM" => "Dominica",
										"DO" => "Dominican Republic",
										"TP" => "East Timor",
										"EC" => "Ecuador",
										"EG" => "Egypt",
										"SV" => "El Salvador",
										"GQ" => "Equatorial Guinea",
										"ER" => "Eritrea",
										"EE" => "Estonia",
										"ET" => "Ethiopia",
										"FK" => "Falkland Islands (Malvinas)",
										"FO" => "Faroe Islands",
										"FJ" => "Fiji",
										"FI" => "Finland",
										"FR" => "France",
										"GF" => "French Guiana",
										"PF" => "French Polynesia",
										"TF" => "French Southern Territories",
										"GA" => "Gabon",
										"GM" => "Gambia",
										"GE" => "Georgia",
										"DE" => "Germany",
										"GH" => "Ghana",
										"GI" => "Gibraltar",
										"GR" => "Greece",
										"GL" => "Greenland",
										"GD" => "Grenada",
										"GP" => "Guadeloupe",
										"GU" => "Guam",
										"GT" => "Guatemala",
										"GN" => "Guinea",
										"GW" => "Guinea-Bissau",
										"GY" => "Guyana",
										"HT" => "Haiti",
										"HM" => "Heard And Mc Donald Islands",
										"VA" => "Holy See (Vatican City State)",
										"HN" => "Honduras",
										"HK" => "Hong Kong",
										"HU" => "Hungary",
										"IS" => "Iceland",
										"IN" => "India",
										"ID" => "Indonesia",
										"IR" => "Iran (Islamic Republic Of)",
										"IQ" => "Iraq",
										"IE" => "Ireland",
										"IL" => "Israel",
										"IT" => "Italy",
										"JM" => "Jamaica",
										"JP" => "Japan",
										"JO" => "Jordan",
										"KZ" => "Kazakhstan",
										"KE" => "Kenya",
										"KI" => "Kiribati",
										"KP" => "Korea, Democratic People's Republic Of",
										"KR" => "Korea, Republic Of",
										"KW" => "Kuwait",
										"KG" => "Kyrgyzstan",
										"LA" => "Lao People's Democratic Republic",
										"LV" => "Latvia",
										"LB" => "Lebanon",
										"LS" => "Lesotho",
										"LR" => "Liberia",
										"LY" => "Libyan Arab Jamahiriya",
										"LI" => "Liechtenstein",
										"LT" => "Lithuania",
										"LU" => "Luxembourg",
										"MO" => "Macau",
										"MK" => "Macedonia, Former Yugoslav Republic Of",
										"MG" => "Madagascar",
										"MW" => "Malawi",
										"MY" => "Malaysia",
										"MV" => "Maldives",
										"ML" => "Mali",
										"MT" => "Malta",
										"MH" => "Marshall Islands",
										"MQ" => "Martinique",
										"MR" => "Mauritania",
										"MU" => "Mauritius",
										"YT" => "Mayotte",
										"MX" => "Mexico",
										"FM" => "Micronesia, Federated States Of",
										"MD" => "Moldova, Republic Of",
										"MC" => "Monaco",
										"MN" => "Mongolia",
										"ME" => "Montenegro",
										"MS" => "Montserrat",
										"MA" => "Morocco",
										"MZ" => "Mozambique",
										"MM" => "Myanmar",
										"NA" => "Namibia",
										"NR" => "Nauru",
										"NP" => "Nepal",
										"NL" => "Netherlands",
										"AN" => "Netherlands Antilles",
										"NC" => "New Caledonia",
										"NZ" => "New Zealand",
										"NI" => "Nicaragua",
										"NE" => "Niger",
										"NG" => "Nigeria",
										"NU" => "Niue",
										"NF" => "Norfolk Island",
										"MP" => "Northern Mariana Islands",
										"NO" => "Norway",
										"OM" => "Oman",
										"PK" => "Pakistan",
										"PW" => "Palau",
										"PA" => "Panama",
										"PG" => "Papua New Guinea",
										"PY" => "Paraguay",
										"PE" => "Peru",
										"PH" => "Philippines",
										"PN" => "Pitcairn",
										"PL" => "Poland",
										"PT" => "Portugal",
										"PR" => "Puerto Rico",
										"QA" => "Qatar",
										"RE" => "Reunion",
										"RO" => "Romania",
										"RU" => "Russian Federation",
										"RW" => "Rwanda",
										"KN" => "Saint Kitts And Nevis",
										"LC" => "Saint Lucia",
										"VC" => "Saint Vincent And The Grenadines",
										"WS" => "Samoa",
										"SM" => "San Marino",
										"ST" => "Sao Tome And Principe",
										"SA" => "Saudi Arabia",
										"SN" => "Senegal",
										"RS" => "Serbia",
										"SC" => "Seychelles",
										"SL" => "Sierra Leone",
										"SG" => "Singapore",
										"SK" => "Slovakia (Slovak Republic)",
										"SI" => "Slovenia",
										"SB" => "Solomon Islands",
										"SO" => "Somalia",
										"ZA" => "South Africa",
										"GS" => "South Georgia, South Sandwich Islands",
										"ES" => "Spain",
										"LK" => "Sri Lanka",
										"SH" => "St. Helena",
										"PM" => "St. Pierre And Miquelon",
										"SD" => "Sudan",
										"SR" => "Suriname",
										"SJ" => "Svalbard And Jan Mayen Islands",
										"SZ" => "Swaziland",
										"SE" => "Sweden",
										"CH" => "Switzerland",
										"SY" => "Syrian Arab Republic",
										"TW" => "Taiwan",
										"TJ" => "Tajikistan",
										"TZ" => "Tanzania, United Republic Of",
										"TH" => "Thailand",
										"TG" => "Togo",
										"TK" => "Tokelau",
										"TO" => "Tonga",
										"TT" => "Trinidad And Tobago",
										"TN" => "Tunisia",
										"TR" => "Turkey",
										"TM" => "Turkmenistan",
										"TC" => "Turks And Caicos Islands",
										"TV" => "Tuvalu",
										"UG" => "Uganda",
										"UA" => "Ukraine",
										"AE" => "United Arab Emirates",
										"GB" => "United Kingdom",
										"US" => "United States",
										"UM" => "United States Minor Outlying Islands",
										"UY" => "Uruguay",
										"UZ" => "Uzbekistan",
										"VU" => "Vanuatu",
										"VE" => "Venezuela",
										"VN" => "Viet Nam",
										"VG" => "Virgin Islands (British)",
										"VI" => "Virgin Islands (U.S.)",
										"WF" => "Wallis And Futuna Islands",
										"EH" => "Western Sahara",
										"YE" => "Yemen",
										"ZM" => "Zambia",
										"ZW" => "Zimbabwe"
										);
	/**
	 * Returns names and codenames of countries.
	 * @return Array where keys are codenames and values are country names.
	 */
	public static function getCountries()
	{
		return self::$countries;
	}
	
	/**
	 * Returns iso codename of a country if it exists, the country name otherwise.
	 * @param string $name Country name.
	 * @return Iso codename if the country exists, the country name otherwise.
	 */
	public static function getIso($name)
	{
		if($name == "")
			return "";
		$index = array_search($name, self::$countries);
		
		if($index === false)
			return $name;
		else
			return self::$countries[$index];
	}
	
	/**
	 * Returns the country name associated to an iso name.
	 * @param string $iso Iso codename
	 * @return Country name if the codename exists, the iso codename otherwise.
	 */
	public static function getName($iso)
	{
		if($iso == "")
			return "undefined";
		if(!self::isoExists($iso))
			return $iso;
		else
			return self::$countries[$iso];
	}
	
	/**
	 * Checks if an iso codename exists.
	 * @param string $iso The iso codename to check.
	 * @return True if the iso codename is valid, false otherwise.
	 */
	public static function isoExists($iso)
	{
		return array_key_exists($iso, self::$countries);
	}
	
	/**
	* Returns the url to a flag icon, representing the country.
	* @param string $iso Country iso code.
	* @return Url to the flag icon of the country, or an empty flag if the iso
	*		  is incorrect.
	*/
	public static function getFlag($iso)
	{
		if(!self::isoExists(strtoupper($iso)))
			return PATH_TPL_COMMON."images/flags/empty.png";
		else
			return PATH_TPL_COMMON."images/flags/".strtolower($iso).".png";
	}
}