<?php /* -*- Mode: php; tab-width: 4; indent-tabs-mode: t; c-basic-offset: 4; -*- */
/**
 * $Header: $
 *
 * Copyright (c) 2010 Tekimaki LLC http://tekimaki.com
 * Copyright (c) 2010 Will James will@tekimaki.com
 *
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details *
 * $Id: $
 * @package blurb
 * @subpackage class
 */

/*
   -==-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
   Portions of this file are modifiable

   Anything between the CUSTOM BEGIN: and CUSTOM END:
   comments will be preserved on regeneration of this
   file.
   -==-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
*/





require_once( BLURB_PKG_PATH.'BitBlurb.php' );

$formblurbLists = array(
	"blurb_list_blurb_id" => array(
		'label' => 'Id',
		'note' => 'Display the blurb id.',
	),
	"blurb_list_title" => array(
		'label' => 'Blurb Name',
		'note' => 'Display the blurb name.',
	),
	"blurb_list_data" => array(
		'label' => 'About',
		'note' => 'Display the about text.',
	),
        "blurb_list_blurb_guid" => array(
		'label' => 'Blurb Guid',
		'note' => 'Display the blurb_guid',
	),
);
$gBitSmarty->assign( 'formblurbLists', $formblurbLists );





// Process the form if we've made some changes
if( !empty( $_REQUEST['blurb_settings'] ) ){



	$blurbToggles = array_merge( 
		$formblurbLists	);
	foreach( $blurbToggles as $item => $data ) {
		simple_set_toggle( $item, BLURB_PKG_NAME );
	}
}





