<?php
/* Addition to Simple Edit in Admin screen for Pages
	# Note: anyone with edit privs can change things here
	# Sections: 
	# 	- Basic information
	# 		- Status (for sale, for rent, pending sale, pending lease, sold, rented)
	# 		- List Price
	#		- Sales Price (if sold)
	# 		- Listed Date
	# 		- Closed Date
	# 		- Price Change Date (reduced / increased)
	# 		- Listed by who? (choose Author or Someone else)
	# 	- gallery/video/maps information 
	# 		- select NextGen Gallery
	# 		- select WordTube video
	# 		- select 360 pics
	# 		- Geocode for Google Maps (latitude, longitude)
	# 		- select downloadable attachments
	# 		- Neighborhood / tags / links / etc
	#
#
# Changelog:
# [2008-07-27] 	Added tabindex to fields (starting at 101 to avoid conflicts)
  */
?>
<div id="pagepost-realestate" class="postbox ">
<h3><?php _e('Real Estate - Property Information','greatrealestate'); ?></h3>

<div class="inside">

<fieldset id="listings1-set">
<legend><?php _e('Great Real Estate Controls','greatrealestate'); ?></legend>

<div>
<p>
<input tabindex="101"
 type="checkbox" name="listings_featured" id="listings_featured"
value="featured" <?php echo ($listing->featured == 'featured') ? 'checked="checked"' : ""; ?> />
<label for="listings_featured"><?php _e('Featured','greatrealestate'); ?></label>
<em><?php _e('check to feature this listing','greatrealestate'); ?></em>
</p>

</div>
</fieldset>

<fieldset id="listings2-set">
<legend><?php _e('Listing Information (Pricing and Sales Information)','greatrealestate'); ?></legend>

<div>

<p>
<select tabindex="102" name="listings_status" class="status-input required" 
id="listings_status">
<option value=""><?php _e('Select a Status','greatrealestate'); ?></option>
<?php re_status_dropdown($listing->status); ?></select>
<label for="listings_status" class="selectit"><?php _e('Property Status','greatrealestate'); ?></label>
</p>

<p>
<input tabindex="103" type="text" name="listings_listprice" class="price-input number" 
id="listings_listprice" size="10" value="<?php echo $listing->listprice; ?>" />
<label for="listings_listprice"><?php _e('List Price','greatrealestate'); ?></label>

<input tabindex="104" type="text" name="listings_listdate" class="date-input date" 
id="listings_listdate" size="10" value="<?php echo get_listing_listdate(); ?>" />
<label for="listings_listdate"><?php _e('List Date','greatrealestate'); ?></label> 
<em><?php _e('mm/dd/yyyy E.G.: 04/01/2008','greatrealestate'); ?></em>
</p>

<p>
<input tabindex="105" type="text" name="listings_saleprice" class="price-input number" 
id="listings_saleprice" size="10" value="<?php echo $listing->saleprice; ?>" />
<label for="listings_saleprice" class="selectit"><?php _e('Sale Price (if sold)','greatrealestate'); ?></label>

<input tabindex="106" type="text" name="listings_saledate" class="date-input date" 
id="listings_saledate" size="10" value="<?php echo get_listing_saledate(); ?>" />
<label for="listings_saledate" class="selectit"><?php _e('Sale Date (if sold)','greatrealestate'); ?></label> 
<em><?php _e('mm/dd/yyyy E.G.: 10/31/2008','greatrealestate'); ?></em>
</p>

<p><input tabindex="107" type="text" name="listings_blurb" class="blurb-input" 
id="listings_blurb" size="60" value="<?php echo $listing->blurb; ?>" />
<label for="listings_blurb"><?php _e('Brief Blurb','greatrealestate'); ?></label>
<em><?php _e('e.g., "Nice 4BR home west of Lantana"','greatrealestate'); ?></em>
</p>

<p>
<input tabindex="108" type="text" name="listings_address" 
id="listings_address" size="60" value="<?php echo $listing->address ?>" />
<label for="listings_address"><?php _e('Street Address','greatrealestate'); ?></label>
<em><?php _e('e.g., "123 Main St"','greatrealestate'); ?></em>
</p>

<p>
<input tabindex="109" type="text" name="listings_city" 
id="listings_city" size="30" value="<?php echo $listing->city; ?>" />
<label for="listings_city"><?php _e('City','greatrealestate'); ?></label>
<em><?php _e('e.g., "Anytown"','greatrealestate'); ?></em>
</p>

<p>
<input tabindex="110" type="text" name="listings_state" 
id="listings_state" size="15" value="<?php echo $listing->state; ?>" />
<label for="listings_state"><?php _e('State','greatrealestate'); ?></label>
<em><?php _e('2 letter abbreviation, e.g. FL for Florida','greatrealestate'); ?></em></p>

<p>
<input tabindex="111" type="text" name="listings_postcode" 
id="listings_postcode" size="5" value="<?php echo $listing->postcode; ?>" />
<label for="listings_postcode"><?php _e('Zip Code','greatrealestate'); ?></label>
<em><?php _e('e.g., "33462"','greatrealestate'); ?></em>
</p>

<p><input tabindex="112" type="text" name="listings_mlsid" 
id="listings_mlsid" size="10" value="<?php echo $listing->mlsid ?>" />
<label for="listings_mlsid"><?php _e('MLS ID','greatrealestate'); ?></label>
<em><?php _e ('The listing\'s MLS ID, e.g., "R2916712"','greatrealestate'); ?></em>
</p>

<p>
<input tabindex="113" type="text" class="number" name="listings_bedrooms" 
id="listings_bedrooms" size="2" value="<?php echo $listing->bedrooms; ?>" />
<label for="listings_bedrooms"><?php _e('Bedrooms','greatrealestate'); ?></label>&nbsp;

<input 
tabindex="114" type="text" class="number" name="listings_bathrooms" 
id="listings_bathrooms" size="2" value="<?php echo $listing->bathrooms; ?>" />
<label for="listings_bathrooms"><?php _e('Full Baths','greatrealestate'); ?></label>&nbsp;

<input tabindex="115" type="text" class="number" name="listings_halfbaths" 
id="listings_halfbaths" size="2" value="<?php echo $listing->halfbaths; ?>" />
<label for="listings_halfbaths"><?php _e('Half Baths','greatrealestate'); ?></label>&nbsp;

<input tabindex="116" type="text" class="number" name="listings_garage" 
id="listings_garage" size="2" value="<?php echo $listing->garage; ?>" />
<label for="listings_garage"><?php _e('Garage Spaces','greatrealestate'); ?></label>
</p>

<p>
<input tabindex="117" type="text" class="number" name="listings_acsf" 
id="listings_acsf" size="5" value="<?php echo $listing->acsf; ?>" />
<label for="listings_acsf"><?php _e('Sqft (Living)','greatrealestate'); ?></label>&nbsp;

<input tabindex="118" type="text" class="number" name="listings_totsf" 
id="listings_totsf" size="5" value="<?php echo $listing->totsf; ?>" />
<label for="listings_totsf"><?php _e('Sqft (Total)','greatrealestate'); ?></label>&nbsp;

<input tabindex="119" type="text" class="number" name="listings_acres" 
id="listings_acres" size="5" value="<?php echo $listing->acres; ?>" />
<label for="listings_acres"><?php _e('Acres','greatrealestate'); ?></label>
</p>

<p>
<select tabindex="120" name="listings_featureid[]" multiple="multiple" 
id="listings_featureid" style="height: 10em;" size="5">
<option value="0"><?php _e('Select Feature(s)','greatrealestate'); ?></option>
<?php get_listing_featuredropdown($listing->featureid); ?>
</select>
<label for="listings_featureid"><?php _e('Features','greatrealestate'); ?></label>
<br />
<em><?php _e('Select one or more','greatrealestate'); ?></em>
</p>

</div>
</fieldset>

<fieldset id="listings3-div">
<legend><?php _e('Multimedia Content (Video, Photos, Brochures, etc)','greatrealestate'); ?></legend>
<div>

<p>
<select tabindex="121" id="listings_galleryid" name="listings_galleryid" class="gallery-input" >
<option value=""><?php _e('Select a Gallery','greatrealestate'); ?></option>
<?php get_listing_gallerydropdown($listing->galleryid); ?></select>
<label for="listings_galleryid"><?php _e('NextGen Gallery','greatrealestate'); ?></label>
<a target="_blank" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=nggallery-manage-gallery" title="<?php _e('Leave page and manage galleries','greatrealestate'); ?>"><?php _e('Manage Galleries','greatrealestate'); ?></a>
</p>

<p>
<select tabindex="122" id="listings_videoid" name="listings_videoid" class="video-input" >
<option value=""><?php _e('Select a Video','greatrealestate'); ?></option>
<?php get_listing_videodropdown($listing->videoid); ?></select>
<label for="listings_videoid"><?php _e('WordTube Video','greatrealestate'); ?></label>
<a target="_blank" href="<?php echo get_option('siteurl'); ?>/wp-admin/edit.php?page=wordtube-admin.php" title="<?php _e('Leave page and manage videos','greatrealestate'); ?>"><?php _e('Manage Videos','greatrealestate'); ?></a>
</p>

<p>
<select tabindex="123" id="listings_downloadid" name="listings_downloadid[]" multiple="multiple" style="height: 10em;" size="5">
<option value="0"><?php _e('Select Download(s)','greatrealestate'); ?></option>
<?php get_listing_downloaddropdown($listing->downloadid); ?>
</select>
<label for="listings_downloadid"><?php _e('DownloadManager Items (PDF)','greatrealestate'); ?></label>
<a target="_blank" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=wp-downloadmanager/download-manager.php" title="<?php _e('Leave page and manage downloads','greatrealestate'); ?>"><?php _e('Manage Downloads','greatrealestate'); ?></a>
<br />
<em><?php _e('Select one or more','greatrealestate'); ?></em>
</p>

<p>
<select tabindex="124" id="listings_panoid" name="listings_panoid[]" 
multiple="multiple" style="height: 10em;" size="5">
<option value="0"><?php _e('Select Panorama(s)','greatrealestate'); ?></option>
<?php get_listing_panodropdown($listing->panoid); ?>
</select>
<label for="listings_panoid"><?php _e('Panoramas','greatrealestate'); ?></label>
<?php _e('use the Add media button','greatrealestate'); ?>
<a href="media-upload.php?post_id=<?php echo $_GET['post']; ?>&amp;TB_iframe=true&amp;height=500&amp;width=640" class="thickbox" title='Add Media'><img src='images/media-button-other.gif' alt='Add Media' /></a>
<?php _e('above the page window to upload a QTVR file','greatrealestate'); ?>
<br />
<em><?php _e('Select one or more','greatrealestate'); ?></em>
</p>

<p>
<input tabindex="125" id="listings_latitude" type="text" name="listings_latitude" class="geo-input"
size="15" value="<?php echo $listing->latitude; ?>" />
<label for="listings_latitude"><?php _e('Latitude (eg: 26.123456)','greatrealestate'); ?></label>
</p>

<p>
<input tabindex="126" id="listings_longitude" type="text" name="listings_longitude" class="geo-input" 
size="15" value="<?php echo $listing->longitude; ?>" />
<label for="listings_longitude" class="selectit"><?php _e('Longitude (eg: -80.123456)','greatrealestate'); ?></label>
</p>


</div>
</fieldset>

<p><?php _e('This information about the property listing will be used for custom display and searching. You should provide as much information as possible.','greatrealestate'); ?><br />
<em><?php _e('If you did not intend for this page to be a property listing, change Page Parent (below) and save, and this section should disappear.','greatrealestate'); ?></em></p>
</div>
</div>

