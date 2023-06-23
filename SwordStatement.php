<?php

/**
 * @file SwordStatement.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file LICENSE.
 *
 * @class SwordStatement
 * @brief Sword gateway plugin
 */

namespace APP\plugins\gateways\swordServer;

class SwordStatement extends \DOMDocument {

	/**
	 * Constructor.
	 * @param $data array
	 */
	function __construct($data) {
		parent::__construct();
		$root = $this->createElementNS('http://www.w3.org/2005/Atom', 'feed');
		$this->appendChild($root);
		$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:sword', 'http://purl.org/net/sword/terms/');
		$category = $this->createElement('category', $data['state']);
		$root->appendChild($category);
		$category->setAttribute('scheme', "http://purl.org/net/sword/terms/state");
		$category->setAttribute('term', 'statement');
		$category->setAttribute('label', "Sword Statement");

		$state = $this->createElement('sword:state');
		$state->setAttribute('href', $data['state_href']);
		$stateDescription = $this->createElement('sword:stateDescription', $data['state_description']);
		$state->appendChild($stateDescription);
		$root->appendChild($state);
	}
}
