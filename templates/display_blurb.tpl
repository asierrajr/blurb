{strip}
{*
 * $Header: $
 *
 * Copyright (c) 2010 Tekimaki LLC http://tekimaki.com
 * Copyright (c) 2010 Will James will@tekimaki.com
 *
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details *
 * $Id: $
 * @package blurb
 * @subpackage templates
 *}
{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='nav' serviceHash=$gContent->mInfo}
<div class="display blurb blurb">
	<div class="floaticon">
		{if $print_page ne 'y'}
			{* =-=- CUSTOM BEGIN: icons -=-= *}

			{* =-=- CUSTOM END: icons -=-= *}
			{if $gContent->hasUpdatePermission()}
				<a title="{tr}Edit this {$gContent->getContentTypeName()|strtolower}{/tr}" href="{$smarty.const.BLURB_PKG_URL}edit_blurb.php?blurb_id={$gContent->mInfo.blurb_id}">{biticon ipackage="icons" iname="accessories-text-editor" iexplain="Edit `$gContent->mType.content_name`"}</a>
			{/if}
			{if $gContent->hasExpungePermission()}
				<a title="{tr}Remove this {$gContent->getContentTypeName()|strtolower}{/tr}" href="{$smarty.const.BLURB_PKG_URL}remove_blurb.php?blurb_id={$gContent->mInfo.blurb_id}">{biticon ipackage="icons" iname="edit-delete" iexplain="Remove Blurb"}</a>
			{/if}
		{/if}<!-- end print_page -->
	</div><!-- end .floaticon -->

	<div class="header">
		<h1>{$gContent->mInfo.title|escape|default:$gContent->getContentTypeName()}</h1>

		<div class="date">
			{tr}Created by{/tr}: {displayname user=$gContent->mInfo.creator_user user_id=$gContent->mInfo.creator_user_id real_name=$gContent->mInfo.creator_real_name}, {tr}Last modification by{/tr}: {displayname user=$gContent->mInfo.modifier_user user_id=$gContent->mInfo.modifier_user_id real_name=$gContent->mInfo.modifier_real_name}, {$gContent->mInfo.last_modified|bit_long_datetime}
		</div>
		{* =-=- CUSTOM BEGIN: header -=-= *}

		{* =-=- CUSTOM END: header -=-= *}
	</div><!-- end .header -->

	<div class="body">
		<div class="content">
			{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='body' serviceHash=$gContent->mInfo}
			{* =-=- CUSTOM BEGIN: body -=-= *}

			{* =-=- CUSTOM END: body -=-= *}


			<div class="row blurb_guid">
				<label>Blurb Guid:</label>&nbsp;{$gContent->getField('blurb_guid')|escape}
			</div>

			{$gContent->mInfo.parsed_data}


		</div><!-- end .content -->
	</div><!-- end .body -->
</div><!-- end .blurb -->
{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='view' serviceHash=$gContent->mInfo}
{/strip}