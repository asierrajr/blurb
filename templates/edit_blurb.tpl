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
<div class="floaticon">{bithelp}</div>

<div class="edit blurb blurb">
	<div class="header">
		<h1>
			{if $gContent->mInfo.blurb_id}
				{tr}Edit {$gContent->mInfo.title|escape}{/tr}
			{else}
				{tr}Create New {$gContent->getContentTypeName()}{/tr}
			{/if}
		</h1>
	</div>

	<div class="body">
		{formfeedback warning=$errors}
		{form enctype="multipart/form-data" id="editblurbform"}
			{* =-=- CUSTOM BEGIN: input -=-= *}

			{* =-=- CUSTOM END: input -=-= *}
			<input type="hidden" name="content_id" value="{$gContent->mContentId}" />
			<div class="servicetabs">
			{jstabs id="servicetabs"}
				{* =-=- CUSTOM BEGIN: servicetabs -=-= *}

				{* =-=- CUSTOM END: servicetabs -=-= *}
				{* any service edit template tabs *}
				{include file="bitpackage:liberty/edit_services_inc.tpl" serviceFile="content_edit_tab_tpl" display_help_tab=1}
			{/jstabs}
			</div>
			<div class="editcontainer">
			{jstabs}
				{if $preview eq 'y'}
					{jstab title="Preview"}
						{legend legend="Preview"}
						<div class="preview">
							{include file="bitpackage:blurb/display_blurb.tpl" page=`$gContent->mInfo.blurb_id`}
						</div>
						{/legend}
					{/jstab}
				{/if}
				{jstab title="Edit"}
				{legend legend=$gContent->getContentTypeName()}
						<input type="hidden" name="blurb[blurb_id]" value="{$gContent->mInfo.blurb_id}" />
						{formfeedback warning=$errors.store}

						<div class="row" id="row_title">
							{formfeedback warning=$errors.title}
							{formlabel label="Blurb Name" for="title"}
							{forminput}
								<input type="text" size="50" name="blurb[title]" id="title" value="{$gContent->mInfo.title|escape}" />
							{/forminput}
						</div>
						<div class="row" id="row_blurb_blurb_guid" style="">
								{formfeedback warning=$errors.blurb_guid}
	{formlabel label="Blurb Guid" for="blurb_guid"}
	{forminput}

	        	    <input type="text" id="blurb_guid" name="blurb[blurb_guid]" value="{if $smarty.request.blurb_guid}{$smarty.request.blurb_guid}{else}{$gContent->getField("blurb_guid")}{/if}" {if $smarty.request.blurb_guid || $smarty.request.blurb_guid_readonly}READONLY{/if} />
					{if $smarty.request.blurb_guid}<input type="hidden" name="blurb_guid_readonly" value="true">{/if}
{required}
	{formhelp note="Unique guid for referencing/defining in the templates"}
	{/forminput}
						</div>
						{textarea label="About" name="blurb[edit]" help="A statement about the blurb."}{$gContent->mInfo.data}{/textarea}
						{* any simple service edit options *}
						{include file="bitpackage:liberty/edit_services_inc.tpl" serviceFile="content_edit_mini_tpl"}


						<div class="row submit">
							<input class="button" type="submit" name="preview" value="{tr}Preview{/tr}" />
							<input class="button" type="submit" name="save_blurb" value="{tr}Save{/tr}" />
						</div>
					{/legend}
				{/jstab}
			{/jstabs}
			</div>
		{/form}
	</div><!-- end .body -->
</div><!-- end . -->

{/strip}