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

require_once( BLURB_PKG_PATH.'lookup_blurb_inc.php' );

// Now check permissions to access this page
if( $gContent->isValid() ){
	$gContent->verifyUpdatePermission();
}else{
	$gContent->verifyCreatePermission();
}

// Check if the page has changed
if( !empty( $_REQUEST["save_blurb"] ) ) {
	// Editing requires general ticket verification
	$gBitUser->verifyTicket();

	if( $gContent->store( $_REQUEST ) ) {
		bit_redirect( $gContent->getDisplayUrl() );
	} else {
		// if store fails set preview
		$_REQUEST['preview'] = TRUE;
		// And put all the variables into the object
		$gContent->preparePreview( $_REQUEST );
		$gBitSmarty->assign_by_ref( 'errors', $gContent->mErrors );
	}
}

// If we are in preview mode then preview it!
if( isset( $_REQUEST["preview"] ) ) {
	// Run verify so they see any errors with their preview
	$gContent->verify( $_REQUEST );
	// Put all the variables into the object
	$gContent->preparePreview( $_REQUEST );
	$gContent->invokeServices( 'content_preview_function', $_REQUEST );
	$gBitSmarty->assign( 'preview', TRUE );
} else {
	$gContent->invokeServices( 'content_edit_function', $_REQUEST );
}


// Prep any data we may need for the form


/* =-=- CUSTOM BEGIN: edit -=-= */

/* =-=- CUSTOM END: edit -=-= */

// Include any javascript files we need for editing



// Display the template
$gBitSystem->display( 'bitpackage:blurb/edit_blurb.tpl', tra('Edit Blurb') , array( 'display_mode' => 'edit' ));

