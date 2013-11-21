<?php
namespace TYPO3\GoMapsExt\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Marc Hirdes <Marc_Hirdes@gmx.de>, clickstorm GmbH
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
 *
 * @package go_maps_ext
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class MapController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * mapRepository
	 *
	 * @var \TYPO3\GoMapsExt\Domain\Repository\MapRepository
	 */
	protected $mapRepository;
	
	/**
	 * addressRepository
	 *
	 * @var \TYPO3\GoMapsExt\Domain\Repository\AddressRepository
	 */
	protected $addressRepository;

	/**
	 * injectMapRepository
	 *
	 * @param \TYPO3\GoMapsExt\Domain\Repository\MapRepository $mapRepository
	 * @return void
	 */
	public function injectMapRepository(\TYPO3\GoMapsExt\Domain\Repository\MapRepository $mapRepository) {
		$this->mapRepository = $mapRepository;
	} 
	
	/**
	 * injectAddressRepository
	 *
	 * @param \TYPO3\GoMapsExt\Domain\Repository\AddressRepository $addressRepository
	 * @return void
	 */
	public function injectAddressRepository(\TYPO3\GoMapsExt\Domain\Repository\AddressRepository $addressRepository) {
		$this->addressRepository = $addressRepository;
	} 
	
	public function initializeAction() {
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['go_maps_ext']);
		$googleMapsLibrary = $this->extConf['googleMapsLibrary'] ? 
			htmlentities($this->extConf['googleMapsLibrary']) : 
			'http://maps.google.com/maps/api/js?v=3.12&amp;sensor=false';
		$headerData = '<script type="text/javascript" src="' . $googleMapsLibrary . '"></script>
					 	';
		$this->extConf['openByClick'] = $this->settings['infoWindow']['openByClick'];
		$this->extConf['closeByClick'] = $this->settings['infoWindow']['closeByClick'];
		
		if($this->extConf['include_library'] == 1) {
			if($this->extConf['jQuery'] == 1) {
				$headerData .= '<script type="text/javascript" src="' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) . 'Resources/Public/Scripts/jquery-1.9.1.min.js"></script>
				';
			} else {
				$headerData .= '<script type="text/javascript" src="' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) . 'Resources/Public/Scripts/mootools-1.4.5-core.js"></script>
				';	
			}
		}

		$headerData .= '<script type="text/javascript" src="' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) . 'Resources/Public/Scripts/markerclusterer_compiled.js"></script>
		';
		
		if($this->extConf['jQuery'] == 1) {
			$headerData .= '<script type="text/javascript" src="' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) . 'Resources/Public/Scripts/jquery.gomapsext.js"></script>
			';
		} else {
			$headerData .= '<script type="text/javascript" src="' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) . 'Resources/Public/Scripts/mootools.gomapsext.js"></script>
			';	
		}
		
		if($this->extConf['footerJS'] == 1) {
			$GLOBALS['TSFE']->additionalFooterData['tx_gomapsap'] = $headerData;
		} else {
			$GLOBALS['TSFE']->additionalHeaderData['tx_gomapsap'] = $headerData;
		}
	}
	
	/**
	 * action show
	 *
	 * @param \TYPO3\GoMapsExt\Domain\Model\Map $map
	 * @return void
	 */
	public function showAction(\TYPO3\GoMapsExt\Domain\Model\Map $map = NULL) {
		$map = $this->mapRepository->findByUid($this->settings['map']);
		
		if($this->settings['storagePid']) {
			$addresses = $this->addressRepository->findAllAddresses($map, $this->settings['storagePid']);
			} else {
			$addresses = $map->getAddresses();	
		}
		
		
		$this->view->assignMultiple(array(
			'request' => $this->request->getArguments(),
			'map' => $map,
			'addresses' => $addresses,
			'settings' => $this->extConf
		));
	} 

}
?>