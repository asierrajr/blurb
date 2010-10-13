<?php /* -*- Mode: php; tab-width: 4; indent-tabs-mode: t; c-basic-offset: 4; -*- */
/* vim: :set fdm=marker : */
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

/**
* BitBlurb class
* A class which represents a blurb.
*
* @version $Revision: $
* @class BitBlurb
*/

/**
 * Initialize
 */
require_once( LIBERTY_PKG_PATH.'LibertyContent.php' );
require_once( LIBERTY_PKG_PATH . 'LibertyValidator.php' );

/* =-=- CUSTOM BEGIN: require -=-= */

/* =-=- CUSTOM END: require -=-= */


/**
* This is used to uniquely identify the object
*/
define( 'BITBLURB_CONTENT_TYPE_GUID', 'bitblurb' );

class BitBlurb extends LibertyContent {
	/**
	 * mBlurbId Primary key for our Blurb class object & table
	 *
	 * @var array
	 * @access public
	 */
	var $mBlurbId;

	var $mVerification;

	var $mSchema;

	/**
	 * BitBlurb During initialisation, be sure to call our base constructors
	 *
	 * @param numeric $pBlurbId
	 * @param numeric $pContentId
	 * @access public
	 * @return void
	 */
	function BitBlurb( $pBlurbId=NULL, $pContentId=NULL ) {
		LibertyContent::LibertyContent();
		$this->mBlurbId = $pBlurbId;
		$this->mContentId = $pContentId;
		$this->mContentTypeGuid = BITBLURB_CONTENT_TYPE_GUID;
		$this->registerContentType( BITBLURB_CONTENT_TYPE_GUID, array(
			'content_type_guid'	  => BITBLURB_CONTENT_TYPE_GUID,
			'content_name' => 'Blurb',
			'content_name_plural' => 'Blurbs',
			'handler_class'		  => 'BitBlurb',
			'handler_package'	  => 'blurb',
			'handler_file'		  => 'BitBlurb.php',
			'maintainer_url'	  => 'http://www.tekimaki.com'
		));
		// Permission setup
		$this->mCreateContentPerm  = 'p_blurb_create';
		$this->mViewContentPerm	   = 'p_blurb_view';
		$this->mUpdateContentPerm  = 'p_blurb_update';
		$this->mExpungeContentPerm = 'p_blurb_expunge';
		$this->mAdminContentPerm   = 'p_blurb_admin';
	}

	/**
	 * load Load the data from the database
	 * 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function load() {
		if( $this->verifyId( $this->mBlurbId ) || $this->verifyId( $this->mContentId ) ) {
			// LibertyContent::load()assumes you have joined already, and will not execute any sql!
			// This is a significant performance optimization
			$lookupColumn = $this->verifyId( $this->mBlurbId ) ? 'blurb_id' : 'content_id';
			$bindVars = array();
			$selectSql = $joinSql = $whereSql = '';
			array_push( $bindVars, $lookupId = @BitBase::verifyId( $this->mBlurbId ) ? $this->mBlurbId : $this->mContentId );
			$this->getServicesSql( 'content_load_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

			$query = "
				SELECT blurb.*, lc.*,
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name,
				lch.`hits`,
				lf.`storage_path` as avatar,
				lfp.storage_path AS `primary_attachment_path`
				$selectSql
				FROM `".BIT_DB_PREFIX."blurb_data` blurb
					INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = blurb.`content_id` ) $joinSql
					LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON( uue.`user_id` = lc.`modifier_user_id` )
					LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON( uuc.`user_id` = lc.`user_id` )
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_content_hits` lch ON( lch.`content_id` = lc.`content_id` )
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_attachments` a ON (uue.`user_id` = a.`user_id` AND uue.`avatar_attachment_id`=a.`attachment_id`)
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_files` lf ON (lf.`file_id` = a.`foreign_id`)
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_attachments` la ON( la.`content_id` = lc.`content_id` AND la.`is_primary` = 'y' )
					LEFT OUTER JOIN `".BIT_DB_PREFIX."liberty_files` lfp ON( lfp.`file_id` = la.`foreign_id` )
				WHERE blurb.`$lookupColumn`=? $whereSql";
			$result = $this->mDb->query( $query, $bindVars );

			if( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$this->mContentId = $result->fields['content_id'];
				$this->mBlurbId = $result->fields['blurb_id'];

				$this->mInfo['creator'] = ( !empty( $result->fields['creator_real_name'] ) ? $result->fields['creator_real_name'] : $result->fields['creator_user'] );
				$this->mInfo['editor'] = ( !empty( $result->fields['modifier_real_name'] ) ? $result->fields['modifier_real_name'] : $result->fields['modifier_user'] );
				$this->mInfo['display_name'] = BitUser::getTitle( $this->mInfo );
				$this->mInfo['display_url'] = $this->getDisplayUrl();
				$this->mInfo['parsed_data'] = $this->parseData();

				/* =-=- CUSTOM BEGIN: load -=-= */

				/* =-=- CUSTOM END: load -=-= */

				LibertyContent::load();
			}
		}
		return( count( $this->mInfo ) );
	}

	/**
	* Deal with text and images, modify them apprpriately that they can be returned to the form.
	* @param $pParamHash data submitted by form - generally $_REQUEST
	* @return array of data compatible with edit form
	* @access public
	**/
	function preparePreview( &$pParamHash ){
		global $gBitSystem, $gBitUser;

		if( empty( $this->mInfo['user_id'] ) ) {
			$this->mInfo['user_id'] = $gBitUser->mUserId;
			$this->mInfo['creator_user'] = $gBitUser->getField( 'login' );
			$this->mInfo['creator_real_name'] = $gBitUser->getField( 'real_name' );
		}

		$this->mInfo['creator_user_id'] = $this->mInfo['user_id'];

		if( empty( $this->mInfo['created'] ) ){
			$this->mInfo['created'] = $gBitSystem->getUTCTime();
		}

		$this->previewFields($pParamHash);


		// Liberty should really have a preview function that handles these
		// But it doesn't so we handle them here.
		if( isset( $pParamHash['blurb']["title"] ) ) {
			$this->mInfo["title"] = $pParamHash['blurb']["title"];
		}

		if( isset( $pParamHash['blurb']["summary"] ) ) {
			$this->mInfo["summary"] = $pParamHash['blurb']["summary"];
		}

		if( isset( $pParamHash['blurb']["format_guid"] ) ) {
			$this->mInfo['format_guid'] = $pParamHash['blurb']["format_guid"];
		}

		if( isset( $pParamHash['blurb']["edit"] ) ) {
			$this->mInfo["data"] = $pParamHash['blurb']["edit"];
			$this->mInfo['parsed_data'] = $this->parseData();
		}
	}

	/**
	 * store Any method named Store inherently implies data will be written to the database
	 * @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	 * This is the ONLY method that should be called in order to store( create or update ) an blurb!
	 * It is very smart and will figure out what to do for you. It should be considered a black box.
	 *
	 * @param array $pParamHash hash of values that will be used to store the data
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function store( &$pParamHash ) {
		// Don't allow uses to cut off an abort in the middle.
		// This is particularly important for classes which will
		// touch the filesystem in some way.
		$abort = ignore_user_abort(FALSE);
		if( $this->verify( $pParamHash )
			&& LibertyContent::store( $pParamHash['blurb'] ) ) {
			$this->mDb->StartTrans();
			$table = BIT_DB_PREFIX."blurb_data";
			if( $this->mBlurbId ) {
				if( !empty( $pParamHash['blurb_store'] ) ){
					$locId = array( "blurb_id" => $pParamHash['blurb']['blurb_id'] );
					$result = $this->mDb->associateUpdate( $table, $pParamHash['blurb_store'], $locId );
				}
			} else {
				$pParamHash['blurb_store']['content_id'] = $pParamHash['blurb']['content_id'];
				if( @$this->verifyId( $pParamHash['blurb_id'] ) ) {
					// if pParamHash['blurb']['blurb_id'] is set, some is requesting a particular blurb_id. Use with caution!
					$pParamHash['blurb_store']['blurb_id'] = $pParamHash['blurb']['blurb_id'];
				} else {
					$pParamHash['blurb_store']['blurb_id'] = $this->mDb->GenID( 'blurb_data_id_seq' );
				}
				$this->mBlurbId = $pParamHash['blurb_store']['blurb_id'];

				$result = $this->mDb->associateInsert( $table, $pParamHash['blurb_store'] );
			}


			/* =-=- CUSTOM BEGIN: store -=-= */

			/* =-=- CUSTOM END: store -=-= */


			$this->mDb->CompleteTrans();
			$this->load();
		} else {
			$this->mErrors['store'] = tra('Failed to save this').' blurb.';
		}
		// Restore previous state for user abort
		ignore_user_abort($abort);
		return( count( $this->mErrors )== 0 );
	}

	/**
	 * verify Make sure the data is safe to store
	 * @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	 * This function is responsible for data integrity and validation before any operations are performed with the $pParamHash
	 * NOTE: This is a PRIVATE METHOD!!!! do not call outside this class, under penalty of death!
	 *
	 * @param array $pParamHash reference to hash of values that will be used to store the page, they will be modified where necessary
	 * @access private
	 * @return boolean TRUE on success, FALSE on failure - $this->mErrors will contain reason for failure
	 */
	function verify( &$pParamHash ) {
		// make sure we're all loaded up of we have a mBlurbId
		if( $this->verifyId( $this->mBlurbId ) && empty( $this->mInfo ) ) {
			$this->load();
		}

		if( @$this->verifyId( $this->mInfo['content_id'] ) ) {
			$pParamHash['blurb']['content_id'] = $this->mInfo['content_id'];
		}

		// It is possible a derived class set this to something different
		if( @$this->verifyId( $pParamHash['blurb']['content_type_guid'] ) ) {
			$pParamHash['blurb']['content_type_guid'] = $this->mContentTypeGuid;
		}

		if( @$this->verifyId( $pParamHash['blurb']['content_id'] ) ) {
			$pParamHash['blurb']['blurb_store']['content_id'] = $pParamHash['blurb']['content_id'];
		}

		// Use $pParamHash here since it handles validation right
		$this->validateFields($pParamHash);

		if( !empty( $pParamHash['blurb']['data'] ) ) {
			$pParamHash['blurb']['edit'] = $pParamHash['blurb']['data'];
		}

		// If title specified truncate to make sure not too long
		// TODO: This shouldn't be required. LC should validate this.
		if( !empty( $pParamHash['blurb']['title'] ) ) {
			$pParamHash['blurb']['content_store']['title'] = substr( $pParamHash['blurb']['title'], 0, 160 );
		} else if( empty( $pParamHash['blurb']['title'] ) ) { // else is error as must have title
			$this->mErrors['title'] = tra('You must enter a title for this').' $this->getContentTypeName().';
		}

		// collapse the hash that is passed to parent class so that service data is passed through properly - need to do so before verify service call below
		$hashCopy = $pParamHash;
		$pParamHash['blurb'] = array_merge( $hashCopy, $pParamHash['blurb'] );


		/* =-=- CUSTOM BEGIN: verify -=-= */

		/* =-=- CUSTOM END: verify -=-= */


		// if we have an error we get them all by checking parent classes for additional errors and the typeMaps if there are any
		if( count( $this->mErrors ) > 0 ){
			// check errors of base class so we get them all in one go
			LibertyContent::verify( $pParamHash['blurb'] );
		}

		return( count( $this->mErrors )== 0 );
	}

	/**
	 * expunge
	 *
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure
	 */
	function expunge() {
		global $gBitSystem;
		$ret = FALSE;
		if( $this->isValid() ) {
			$this->mDb->StartTrans();


			/* =-=- CUSTOM BEGIN: expunge -=-= */

			/* =-=- CUSTOM END: expunge -=-= */


			$query = "DELETE FROM `".BIT_DB_PREFIX."blurb_data` WHERE `content_id` = ?";
			$result = $this->mDb->query( $query, array( $this->mContentId ) );
			if( LibertyContent::expunge() ) {
				$ret = TRUE;
			}
			$this->mDb->CompleteTrans();
			// If deleting the default/home blurb record then unset this.
			if( $ret && $gBitSystem->getConfig( 'blurb_home_id' ) == $this->mBlurbId ) {
				$gBitSystem->storeConfig( 'blurb_home_id', 0, BLURB_PKG_NAME );
			}
		}
		return $ret;
	}




	/**
	 * isValid Make sure blurb is loaded and valid
	 * 
	 * @access public
	 * @return boolean TRUE on success, FALSE on failure
	 */
	function isValid() {
		return( @BitBase::verifyId( $this->mBlurbId ) && @BitBase::verifyId( $this->mContentId ));
	}

	/**
	 * getList This function generates a list of records from the liberty_content database for use in a list page
	 *
	 * @param array $pParamHash
	 * @access public
	 * @return array List of blurb data
	 */
	function getList( &$pParamHash ) {
		global $gBitSystem;
		// this makes sure parameters used later on are set
		LibertyContent::prepGetList( $pParamHash );

		$selectSql = $joinSql = $whereSql = '';
		$bindVars = array();
		array_push( $bindVars, $this->mContentTypeGuid );
		$this->getServicesSql( 'content_list_sql_function', $selectSql, $joinSql, $whereSql, $bindVars, NULL, $pParamHash );


		/* =-=- CUSTOM BEGIN: getList -=-= */

		/* =-=- CUSTOM END: getList -=-= */


		// this will set $find, $sort_mode, $max_records and $offset
		extract( $pParamHash );

		if (empty($sort_mode) || ! strpos($sort_mode, '.') ) {
			$sort_mode_prefix = 'lc.';
		} else {
			$sort_mode_prefix = '';
		}

		if( is_array( $find ) ) {
			// you can use an array of pages
			$whereSql .= " AND lc.`title` IN( ".implode( ',',array_fill( 0,count( $find ),'?' ) )." )";
			$bindVars = array_merge ( $bindVars, $find );
		} elseif( is_string( $find ) ) {
			// or a string
			$whereSql .= " AND UPPER( lc.`title` )like ? ";
			$bindVars[] = '%' . strtoupper( $find ). '%';
		}

		$query = "
			SELECT blurb.*, lc.`content_id`, lc.`title`, lc.`data` $selectSql, lc.`format_guid`, lc.`user_id`, lc.`modifier_user_id`,
				uu.`email`, uu.`login`, uu.`real_name`
			FROM `".BIT_DB_PREFIX."blurb_data` blurb
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = blurb.`content_id` ) $joinSql
				INNER JOIN `".BIT_DB_PREFIX."users_users`     uu ON uu.`user_id`     = lc.`user_id`
			WHERE lc.`content_type_guid` = ? $whereSql
			ORDER BY ".$sort_mode_prefix.$this->mDb->convertSortmode( $sort_mode );
		$query_cant = "
			SELECT COUNT(*)
			FROM `".BIT_DB_PREFIX."blurb_data` blurb
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = blurb.`content_id` ) $joinSql
				INNER JOIN `".BIT_DB_PREFIX."users_users`     uu ON uu.`user_id`     = lc.`user_id`
			WHERE lc.`content_type_guid` = ? $whereSql";
		$result = $this->mDb->query( $query, $bindVars, $max_records, $offset );
		$ret = array();
		while( $res = $result->fetchRow() ) {

			if ( $gBitSystem->isFeatureActive( 'blurb_list_data' ) 
				|| !empty( $pParamHash['parse_data'] )
			){
				// parse data if to be displayed in lists 
				$parseHash['format_guid']	= $res['format_guid'];
				$parseHash['content_id']	= $res['content_id'];
				$parseHash['user_id']		= $res['user_id'];
				$parseHash['data']			= $res['data'];
				$res['parsed_data'] = $this->parseData( $parseHash ); 
			}

			/* =-=- CUSTOM BEGIN: getListIter -=-= */

			/* =-=- CUSTOM END: getListIter -=-= */

			$ret[] = $res;
		}
		$pParamHash["cant"] = $this->mDb->getOne( $query_cant, $bindVars );

		// add all pagination info to pParamHash
		LibertyContent::postGetList( $pParamHash );
		return $ret;
	}

	/**
	 * getDisplayUrl Generates the URL to the blurb page
	 * 
	 * @access public
	 * @return string URL to the blurb page
	 */
	function getDisplayUrl($pSection = NULL) {
		global $gBitSystem;
		$ret = NULL;

		/* =-=- CUSTOM BEGIN: getDisplayUrl -=-= */

		/* =-=- CUSTOM END: getDisplayUrl -=-= */		

		// Did the custom code block give us a URL?
		if ($ret == NULL) {
			if( @$this->isValid() ) {
				if( $gBitSystem->isFeatureActive( 'pretty_urls' ) || $gBitSystem->isFeatureActive( 'pretty_urls_extended' )) {
					$ret = BLURB_PKG_URL.'blurb/'.$this->mBlurbId;
				} else {
					$ret = BLURB_PKG_URL."index.php?blurb_id=".$this->mBlurbId;
				}
			}
		}

		// Do we have a section request
		if (!empty($pSection)) {
			if( $gBitSystem->isFeatureActive( 'pretty_urls' ) || $gBitSystem->isFeatureActive( 'pretty_urls_extended' )) {
				if ( substr($ret, -1, 1) != "/" ) {
					$ret .= "/";
				}
				$ret .= $pSection;
			} else {
				if (preg_match('|\?|', $ret)) {
					$ret .= '&';
				} else {
					$ret .= '?';
				}
				$ret .= "section=".$pSection;
			}
		}

		return $ret;
	}

	/**
	 * previewFields prepares the fields in this type for preview
	 */
	function previewFields(&$pParamHash) {
		$this->prepVerify();
		LibertyValidator::preview(
		$this->mVerification['blurb_data'],
			$pParamHash['blurb'],
			$this->mInfo);
	}

	/**
	 * validateFields validates the fields in this type
	 */
	function validateFields(&$pParamHash) {
		$this->prepVerify();
		LibertyValidator::validate(
			$this->mVerification['blurb_data'],
			$pParamHash['blurb'],
			$this, $pParamHash['blurb_store']);
	}

	/**
	 * prepVerify prepares the object for input verification
	 */
	function prepVerify() {
		if (empty($this->mVerification['blurb_data'])) {

	 		/* Validation for title */
	$this->mVerification['blurb_data']['null']['title'] = TRUE;
	 		/* Validation for data */
	$this->mVerification['blurb_data']['null']['data'] = TRUE;
	 		/* Validation for summary */
	$this->mVerification['blurb_data']['null']['summary'] = TRUE;
	 		/* Validation for blurb_guid */
			$this->mVerification['blurb_data']['string']['blurb_guid'] = array(
				'name' => 'Blurb Guid',
				'required' => '1',
				'max_length' => '32'
			);

		}
	}

	/**
	 * prepVerify prepares the object for input verification
	 */
	public function getSchema() {
		if (empty($this->mSchema['blurb_data'])) {

	 		/* Schema for title */
			$this->mSchema['blurb_data']['title'] = array(
				'name' => 'title',
				'type' => 'null',
				'label' => 'Blurb Name',
				'help' => '',
			);
	 		/* Schema for data */
			$this->mSchema['blurb_data']['data'] = array(
				'name' => 'data',
				'type' => 'null',
				'label' => 'About',
				'help' => 'A statement about the blurb.',
			);
	 		/* Schema for summary */
			$this->mSchema['blurb_data']['summary'] = array(
				'name' => 'summary',
				'type' => 'null',
				'label' => 'Description',
				'help' => 'About this blurb',
			);
	 		/* Schema for blurb_guid */
			$this->mSchema['blurb_data']['blurb_guid'] = array(
				'name' => 'blurb_guid',
				'type' => 'string',
				'label' => 'Blurb Guid',
				'help' => 'Unique guid for referencing/defining in the templates',
				'required' => '1',
				'max_length' => '32'
			);
		}


		return $this->mSchema;
	}
	
	/**
	 * gets id by look up fields
	 */
	function getIdByLookUp( $pParamHash ) {
		return $this->mDb->getOne( "SELECT blurb_id FROM `".BIT_DB_PREFIX."blurb_data` blurb LEFT JOIN `".BIT_DB_PREFIX."liberty_content` lc ON (blurb.`content_id` = lc.`content_id`) WHERE blurb.`".key($pParamHash)."` = ?", array($pParamHash) );
	}
	
	// Getters for reference column options - return associative arrays formatted for generating html select inputs




	// {{{ =================== Custom Helper Mthods  ====================


	/* This section is for any helper methods you wish to create */
	/* =-=- CUSTOM BEGIN: methods -=-= */
	
	/* =-=- CUSTOM END: methods -=-= */


	// }}} -- end of Custom Helper Methods

}
