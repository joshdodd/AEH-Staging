<?php /* Template Name: Build-An-eMail */
get_header();?>
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/ui-darkness/jquery-ui-1.10.4.custom.min.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/emailGenerator.css" type="text/css" media="screen" charset="utf-8" />
	<div id="application-container">
		<div class="container">
			<div class="gutter clearfix">
				<h1><?php the_title(); ?></h1>
				<div class="floatleft onefourth">
					<h2 id="application-title" class="spec">Choose Articles</h2>
					<div id="application-create-form">
						<form name="emailGenerator" id="emailGenerator" method="POST" action="<?php bloginfo('template_directory'); ?>/emailGenerator/test.php" enctype="multipart/form-data">
							<label>Email Type</label>
								<select name="emailType" id="application-form-emailType">
									<option value="action">Action/Newsline Updates</option>
									<option value="quality">Quality Updates</option>
									<option value="institute">Institute Updates</option>
									<option value="education">Education Updates</option>
									<option value="ehen">EHEN</option>
									<option value="full">Full Site Monthly Update</option>
								</select>
							<label>Date Range</label>
								<input type="text" name="application-form-startdate" id="application-form-startdate" placeholder="Starting Date" />
								<input type="text" name="application-form-enddate" id="application-form-enddate" placeholder="End Date" />
							<label>Top Header</label>
								<input type="text" name="application-form-subheader" id="application-form-subheader" placeholder="Top Header" />

							<label>Intro Paragraph</label>
							<em style="font-size:11px;padding:0 0 5px;display:block;">* Optional</em>
								<textarea name="application-form-intro"></textarea>

							<label>Ad Image</label>
							<em style="font-size:11px;padding:0 0 5px;display:block;">* To reuse the same image, choose the upload it again. The server will find it and reuse it.</em>
								<input type="file" name="application-form-ad" id="application-form-ad" placeholder="Ad Image for email" />
							<label style="margin:15px 0 0 0;">Ad Location</label>
							<em style="font-size:11px;padding:0 0 10px 5px;display:block;">* Which column the ad will go in</em>

							<em style="font-size:11px;padding:0 0 0 5px;display:block;">Max-width Column = 286px<br>
																						Max-width Full = 572px<br>
																						Height is variable; if an image is wider than the max width if will automatically resize. It is recommended to user exact sizing if possible for loading times and compatibility.</em>
								<select name="ad-loc" id="ad-loc">
									<option value="top-right">Top Right-column</option>
									<option value="top-left">Top Left-column</option>
									<option value="top-full">Top Full</option>
									<option value="bottom-right">Bottom Right-column</option>
									<option value="bottom-left">Bottom Left-column</option>
									<option value="bottom-full">Bottom Full</option>
								</select>

							<input type="submit" name="submit" value="Get Articles"/>
						</form>
					</div>
				</div>

				<div class="floatright threefourth">
					<div id="application-sort-window">
						<h2 id="application-title">Sort Articles</h2>
						<div id="application-sort-loader">Loading Posts</div>
						<div id="application-sort">
							<ul id="sortable">

							</ul>
						</div>
					</div>
					<div id="application-preview-window">
						<h2 id="application-title">Email Preview</h2>
						<div id="application-preview-loader">Loading Preview</div>
						<div id="application-preview">

						</div>
					</div>

					<div id="application-raw-window">
						<h2 id="application-title">Email Raw HTML</h2>
						<div id="application-raw">

						</div>
						<div id="application-raw-copy">
							Copy to Clipboard
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<script src="<?php bloginfo('template_directory'); ?>/emailGenerator/jquery-ui-1.10.4.custom.min.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/emailGenerator/jquery.clipboard.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/emailGenerator/jquery.forms.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/emailGenerator/jquery.inline-edit.min.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/emailGenerator/app.js"></script>

<?php get_footer(); ?>