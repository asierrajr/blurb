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
{form}
<input type="hidden" name="page" value="{$page}" />
	{jstabs}

	{* Are there homeable settings? *}

	
		{jstab title="Blurb Settings"}
	{jstabs}



			{jstab title="Blurb List Settings"}
				{legend legend="Blurb List Settings"}
					{foreach from=$formblurbLists key=item item=output}
						<div class="row">
							{formlabel label=`$output.label` for=$item}
							{forminput}
								{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
								{formhelp note=`$output.note` page=`$output.page`}
							{/forminput}
						</div>
					{/foreach}
				{/legend}
				<div class="row submit">
					<input class="button" type="submit" name="blurb_settings" value="{tr}Change preferences{/tr}" />
				</div>
			{/jstab}
{* End List Settings *}

		{/jstabs}
	{/jstab}

	
	{/jstabs}
{/form}
{/strip}