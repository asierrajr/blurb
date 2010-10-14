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


// Initialization
require_once( '../kernel/setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage( 'blurb' );

/* =-=- CUSTOM BEGIN: security -=-= */

/* =-=- CUSTOM END: security -=-= */

// Define content lookup keys
$typeNames = array(
	);
$typeIds = array(
		"blurb_id"	);
$typeContentIds = array(
		"blurb_content_id"	);
$typeFields = array(
	"blurb_blurb_guid",
	);	

if(!empty($_REQUEST['blurb_guid'])){
	$_REQUEST['blurb_blurb_guid'] = $_REQUEST['blurb_guid'];
}
	
	
// If a content type key id is requested load it up
$requestType = NULL;
$requestKeyType = NULL;
foreach( $_REQUEST as $key => $val ) {
    if (in_array($key, $typeNames)) {
        $requestType = substr($key, 0, -5);
        $requestKeyType = 'name';
        break;
    }
    elseif (in_array($key, $typeIds)) {
        $requestType = substr($key, 0, -3);
        $requestKeyType = 'id';
        break;
    }
    elseif (in_array($key, $typeContentIds)) {
        $requestType = substr($key, 0, -11);
        $requestKeyType = 'content_id';
        break;
    }
	elseif (in_array($key, $typeFields)) {
        $requestType = substr($key, 0, strpos($key, "_"));
        $requestKeyType = 'field';
        break;
    }
}


// If there is an id to get, specified or default, then attempt to get it and display
if( !empty( $_REQUEST[$requestType.'_blurb_guid'] ) || 
	!empty( $_REQUEST[$requestType.'_name'] ) ||
    !empty( $_REQUEST[$requestType.'_id'] ) ||
    !empty( $_REQUEST[$requestType.'_content_id'] ) ) {
	// Look up the content
	require_once( BLURB_PKG_PATH.'lookup_'.$requestType.'_inc.php' );

	if( !$gContent->isValid() ) {
		// Check permissions to access this content in general
		$gContent->verifyViewPermission();

		// They are allowed to see that this does not exist.
		$gBitSystem->setHttpStatus( 404 );
		$gBitSystem->fatalError( tra( "The requested ".$gContent->getContentTypeName()." (id=".$_REQUEST[$requestType.'_id'].") could not be found." ) );
	}

	// Now check permissions to access this content
	$gContent->verifyViewPermission();

	
	// Call display services
	$displayHash = array( 'perm_name' => $gContent->mViewContentPerm );
	$gContent->invokeServices( 'content_display_function', $displayHash );

	// Add a hit to the counter
	$gContent->addHit();

	/* =-=- CUSTOM BEGIN: indexload -=-= */
	/* =-=- CUSTOM END: indexload -=-= */

	// Display the template
	$gBitSystem->display( 'bitpackage:blurb/display_'.$requestType.'.tpl', htmlentities($gContent->getField('title', 'Blurb '.ucfirst($requestType))) , array( 'display_mode' => 'display' ));

}else{

	/* =-=- CUSTOM BEGIN: index -=-= */
		$indexTitle = tra('Blurb');
		$gBitSmarty->assign( 'indexTitle', $indexTitle );
		$gBitSystem->display( 'bitpackage:blurb/display_index.tpl', $indexTitle, array( 'display_mode' => 'display' ));
	/* =-=- CUSTOM END: index -=-= */

}
