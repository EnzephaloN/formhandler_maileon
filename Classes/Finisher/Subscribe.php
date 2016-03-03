<?php
namespace Enzephalon\FormhandlerMaileon\Finisher;
/***************************************************************
*  Copyright notice
*
*  (c) 2015 Johannes C. Schulz, <into@enzephalon.de> EnzephaloN IT-Solutions
*
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 *
 * @author	Johannes C. Schulz, <info@enzephalon.de>
 * @package	Tx_Formhandler
 * @subpackage	Finisher
 */
class Subscribe extends Maileon {

	/**
	 * The main method called by the controller
	 *
	 * @return array The probably modified GET/POST parameters
	 */
	public function process() {
		$this->addReceiver();
		return $this->gp;
	}

	/**
	 *
	 * @return void
	 */
	protected function addReceiver() {
		$standardfields = array();
		$standardfields['source'] = $this->settings['source'];
		$standardfields = array_merge($standardfields,$this->parseFields('fields.'));

		$arr_customfields = array();
		$s_customfield = explode(';',$this->settings['customfields']);
		foreach( $s_customfield as $s_value) {
			$temp = explode("=",$s_value);
			$arr_customfields[][$temp[0]] = $temp[1];
		}
		$customfields = array_merge($arr_customfields,$this->parseFields('additionalfields.'));

        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $this->getUrl($standardfields));
        curl_setopt($curlSession, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlSession, CURLOPT_HEADER, 1);
        curl_setopt($curlSession, CURLOPT_HTTPHEADER, $this->getHeaderstrings());
        curl_setopt($curlSession, CURLOPT_POST, 1);
        curl_setopt($curlSession, CURLOPT_POSTFIELDS, $this->getContent($standardfields,$customfields));
        $response = curl_exec($curlSession);
        $response = $response ? $response: null;
        $code =  substr($response, 9,3);
        
        return $code;		
	}

	public function getUrl($standardfields){
		$doikeyParam = "&doimailing=".trim($this->settings['doiKey']);
		$url = str_replace(" ", "", $this->settings['formUrl']."/".htmlspecialchars($standardfields['email'])."?".$this->settings['formParams'].$doikeyParam);
		return $url;
	}

	public function getHeaderstrings(){
		$apikeyEncoded = base64_encode(trim($this->settings['apiKey']));
		$header = array();
		$header[] = "Content-Type: application/vnd.maileon.api+xml; charset=utf-8";
		$header[] = "Accept: application/vnd.maileon.api+xml; charset=utf-8";
		$header[] = "Authorization: Basic ".$apikeyEncoded;
		$header[] = "Expect:";
		return $header;
	}

	public function getContent($standardfields,$customfields){
		$content="<contact>"
					."<email>".$standardfields['email']."</email>"
					."<standard_fields>"
						."<field><name>LASTNAME</name><value>".$standardfields['lastname']."</value></field>"
						."<field><name>FIRSTNAME</name><value>".$standardfields['firstname']."</value></field>"
						."<field><name>SALUTATION</name><value>".$standardfields['salutation']."</value></field>"
					."</standard_fields>"
					."<custom_fields>";
					foreach( $customfields as $customfield){
						foreach( $customfield as $singleKey => $singleValue){
							if($singleValue != ""){
								$content .= "<field><name>".$singleKey."</name><value>".$singleValue."</value></field>";
							}
						}
					}
					$content .= "</custom_fields>"
				."</contact>";
		return $content;
	}

}
