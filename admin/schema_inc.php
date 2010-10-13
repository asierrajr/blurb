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


global $gBitSystem;

// Requirements
$gBitSystem->registerRequirements( BLURB_PKG_NAME, array(
	'liberty' => array( 'min' => '2.1.5', ),
	'libertygraph' => array( 'min' => '0.0.0', ),
));

$gBitSystem->registerPackageInfo( BLURB_PKG_NAME, array(
	'description' => "Manages text blurbs on various pages",
	'license' => '<a href="http://www.gnu.org/copyleft/lesser.html">LGPL</a>',));


// Install process
global $gBitInstaller;
if( is_object( $gBitInstaller ) ){

$tables = array(
    'blurb_data' => "
		blurb_id I4 PRIMARY,
		content_id I4 NOTNULL, 
        blurb_guid C(32) NOTNULL UNIQUE
        CONSTRAINT '
        , CONSTRAINT `blurb_content_ref` FOREIGN KEY (`content_id`) REFERENCES `liberty_content` (`content_id`)
		'
	",
);

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( BLURB_PKG_NAME, $tableName, $tables[$tableName] );
}

// $indices = array();
// $gBitInstaller->registerSchemaIndexes( BLURB_PKG_NAME, $indices );

// Sequences
$gBitInstaller->registerSchemaSequences( BLURB_PKG_NAME, array (
	'blurb_data_id_seq' => array( 'start' => 1 ),
));

// Schema defaults
$defaults = array(
);
if (count($defaults) > 0) {
	$gBitInstaller->registerSchemaDefault( BLURB_PKG_NAME, $defaults);
}


// User Permissions
$gBitInstaller->registerUserPermissions( BLURB_PKG_NAME, array(
	array ( 'p_blurb_admin'  , 'Can admin the blurb package', 'admin'      , BLURB_PKG_NAME ),
	array ( 'p_blurb_view'  , 'Can view the blurb package', 'basic'      , BLURB_PKG_NAME ),
	array ( 'p_blurb_create' , 'Can create a blurb entry'   , 'admin' , BLURB_PKG_NAME ),
	array ( 'p_blurb_view'   , 'Can view blurb entries'     , 'basic'      , BLURB_PKG_NAME ),
	array ( 'p_blurb_update' , 'Can update any blurb entry' , 'admin'    , BLURB_PKG_NAME ),
	array ( 'p_blurb_expunge', 'Can delete any blurb entry' , 'admin'      , BLURB_PKG_NAME ),
	array ( 'p_blurb_admin'  , 'Can admin any blurb entry'  , 'admin'      , BLURB_PKG_NAME ),
));

// Default Preferences
$gBitInstaller->registerPreferences( BLURB_PKG_NAME, array(
	array ( BLURB_PKG_NAME , 'blurb_default_ordering'      , 'blurb_id_desc' ),
	array ( BLURB_PKG_NAME , 'blurb_list_title'            , 'y'              ),
));

// ### Register content types
$gBitInstaller->registerContentObjects( BLURB_PKG_NAME, array(
    'BitBlurb'=>BLURB_PKG_PATH.'BitBlurb.php',
));


}