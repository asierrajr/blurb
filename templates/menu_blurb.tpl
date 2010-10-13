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
	<ul>
		{if $gBitUser->hasPermission( 'p_blurb_view')}
			<li><a class="item" href="{$smarty.const.BLURB_PKG_URL}index.php">{tr}Blurb Home{/tr}</a></li>


			{if $gBitUser->hasPermission( 'p_blurb_view')}
				<li><a class="item" href="{$smarty.const.BLURB_PKG_URL}list_blurb.php">{tr}List Blurbs{/tr}</a></li>
			{/if}


		{/if}


		{if $gBitUser->hasPermission( 'p_blurb_create')}
		<li><a class="item" href="{$smarty.const.BLURB_PKG_URL}edit_blurb.php">{tr}Create Blurb{/tr}</a></li>
		{/if}


	</ul>
{/strip}