<?php 
/*
	iBlogPro3 Copyright (C) 2008-2009 Andrew Powers, PageLines.com (andrew AT pagelines DOT com)

	GPL - You can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	To view it see: <http://www.gnu.org/licenses/>.
*/

global $pagelines;

?>
<?php get_header(); ?>

<div id="left-col">
	<div id="content">
		
	 <?php require(LIB.'/_posts.php');?>


	</div> <!-- end content -->
</div> <!-- end left col -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
