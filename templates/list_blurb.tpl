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

<div class="listing blurb blurb">
	<div class="header">
		<h1>{tr}{$gContent->getContentTypeName(TRUE)}{/tr}</h1>
	</div>

	<div class="body">
		{minifind sort_mode=$sort_mode}

		{form id="checkform"}
			<input type="hidden" name="offset" value="{$control.offset|escape}" />
			<input type="hidden" name="sort_mode" value="{$control.sort_mode|escape}" />

			<table class="data">
				<tr>
					{if $gBitSystem->isFeatureActive( 'blurb_list_blurb_id' ) eq 'y'}
						<th>{smartlink ititle="Blurb Id" isort=blurb_id offset=$control.offset iorder=desc idefault=1}</th>
					{/if}

					{if $gBitSystem->isFeatureActive( 'blurb_list_title' ) eq 'y'}
						<th>{smartlink ititle="Blurb Name" isort=title offset=$control.offset}</th>
					{/if}


					{if $gBitSystem->isFeatureActive('blurb_list_data' ) eq 'y'}
						<th>{smartlink ititle="About" isort=data offset=$control.offset}</th>
					{/if}
					{if $gBitSystem->isFeatureActive('blurb_list_summary' ) eq 'y'}
						<th>{smartlink ititle="Description" isort=summary offset=$control.offset}</th>
					{/if}
					{if $gBitSystem->isFeatureActive('blurb_list_blurb_guid' ) eq 'y'}
						<th>{smartlink ititle="Blurb Guid" isort=blurb_guid offset=$control.offset}</th>
					{/if}


					{if $gBitSystem->isFeatureActive( 'blurb_list_summary' ) eq 'y'}
						<th>{smartlink ititle="Text" isort=data offset=$control.offset}</th>
					{/if}

					<th>{tr}Actions{/tr}</th>
				</tr>

				{foreach item=dataItem from=$blurbList}
					<tr class="{cycle values="even,odd"}">
						{if $gBitSystem->isFeatureActive( 'list_blurb_id' )}
							<td><a href="{$smarty.const.BLURB_PKG_URL}index.php?blurb_id={$dataItem.blurb_id|escape:"url"}" title="{$dataItem.blurb_id}">{$dataItem.blurb_id}</a></td>
						{/if}

						{if $gBitSystem->isFeatureActive( 'blurb_list_title' )}
							<td><a href="{$smarty.const.BLURB_PKG_URL}index.php?blurb_id={$dataItem.blurb_id|escape:"url"}" title="{$dataItem.blurb_id}">{$dataItem.title|escape}</a></td>
						{/if}


	 		     	     		{if $gBitSystem->isFeatureActive('blurb_list_title' ) eq 'y'}
								<td>{$dataItem.title|escape}</td>
						{/if}
	 		     	     		{if $gBitSystem->isFeatureActive('blurb_list_data' ) eq 'y'}
								<td>{$dataItem.data|escape}</td>
						{/if}
	 		     	     		{if $gBitSystem->isFeatureActive('blurb_list_summary' ) eq 'y'}
								<td>{$dataItem.summary|escape}</td>
						{/if}
	 		     	     		{if $gBitSystem->isFeatureActive('blurb_list_blurb_guid' ) eq 'y'}
								<td>{$dataItem.blurb_guid|escape}</td>
						{/if}


						{if $gBitSystem->isFeatureActive( 'blurb_list_summary' )}
							<td>{$dataItem.summary|escape}</td>
						{/if}


						<td class="actionicon">
						{if $gBitUser->hasPermission( 'p_blurb_blurb_update' )}
							{smartlink ititle="Edit" ifile="edit_blurb.php" ibiticon="icons/accessories-text-editor" blurb_id=$dataItem.blurb_id}
						{/if}
						{if $gBitUser->hasPermission( 'p_blurb_blurb_expunge' )}
							<input type="checkbox" name="checked[]" title="{$dataItem.title|escape}" value="{$dataItem.blurb_id}" />
						{/if}
						</td>
					</tr>
				{foreachelse}
					<tr class="norecords"><td colspan="16">
						{tr}No records found{/tr}
					</td></tr>
				{/foreach}
			</table>

			{if $gBitUser->hasPermission( 'p_blurb_blurb_expunge' )}
				<div style="text-align:right;">
					<script type="text/javascript">/* <![CDATA[ check / uncheck all */
						document.write("<label for=\"switcher\">{tr}Select All{/tr}</label> ");
						document.write("<input name=\"switcher\" id=\"switcher\" type=\"checkbox\" onclick=\"BitBase.BitBase.switchCheckboxes(this.form.id,'checked[]','switcher')\" /><br />");
					/* ]]> */</script>

					<select name="submit_mult" onchange="this.form.submit();">
						<option value="" selected="selected">{tr}with checked{/tr}:</option>
						<option value="remove_blurb_data">{tr}remove{/tr}</option>
					</select>

					<noscript><div><input class="button" type="submit" value="{tr}Submit{/tr}" /></div></noscript>
				</div>
			{/if}
		{/form}

		{pagination}
	</div><!-- end .body -->
</div><!-- end .listing -->
{/strip}
