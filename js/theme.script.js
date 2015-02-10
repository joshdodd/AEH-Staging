$(document).ready(function($){

	/////////////////////////////////////////////
	// Connections Page Stuff
	/////////////////////////////////////////////

	var a = {};
	a.sortBy  = "last_name"; // last_name, first_name, job_title
	a.pageNo  = 1;     //starting page number
	a.perPage = 20;    //how many results to display
	a.sortDir = "asc"; //sort ascending or descending (asc/desc)
	a.filter  = "all"; //filter aeh = hospital members only, all = all
	a.search  = "";    //search term. Initially blank
	var loadingGif = "<span id='MNSPAN'><img id='MNLoader' src='http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/loaderMN.gif' />Loading Connections</span>"; //loading animation

	//Default Starting Page Content
	$("#paginationc li:first").css({'color' : '#FF0084'}).css({'border' : 'none'});
	$("div#connectioncontent div.clisting").load(siteDir+"/membernetwork/ajax-all-members", {data: a});	// main content
	$("div#sidebar-connections").load(siteDir+"/membernetwork/ajax-update-sidebar");						// logged-in sidebar
	$("div#sidebar-logged-out").load(siteDir+"/membernetwork/ajax-logged-out-sidebar");					// logged-out sidebar
	$("div#domain-container").html(loadingGif).load(siteDir+"/membernetwork/ajax-get-domains");		    // Domain Manager

	//Pagination Click
	$(document).on("click", "#paginationc li", function() {
		//CSS Styles
		$("#paginationc li").css({'border' : 'solid #dddddd 1px'}).css({'color' : '#0063DC'});

		$(this).css({'color' : '#FF0084'}).css({'border' : 'none'});

		//Loading Data
		a.pageNo = this.id;
		$("div#connectioncontent div.clisting").html(loadingGif).load(siteDir+"/membernetwork/ajax-all-members", {data: a});
	});

	//job-title click
	$(document).on("click", "table#connection-table div.job-title", function() {
		a.sortBy = "job_title";
		a.sortDir = toggledir(a.sortDir);
		$("div#connectioncontent div.clisting").html(loadingGif).load(siteDir+"/membernetwork/ajax-all-members", {data: a});
	});

	//last name click
	$(document).on("click", "table#connection-table div.last-name", function() {
		a.sortBy = "last_name";
		a.sortDir = toggledir(a.sortDir);
		$("div#connectioncontent div.clisting").html(loadingGif).load(siteDir+"/membernetwork/ajax-all-members", {data: a});
	});

	//first name click
	$(document).on("click", "table#connection-table div.first-name", function() {
		a.sortBy = "first_name";
		a.sortDir = toggledir(a.sortDir);
		$("div#connectioncontent div.clisting").html(loadingGif).load(siteDir+"/membernetwork/ajax-all-members", {data: a});
	});

	//search on change event
	$(document).on("keypress", "input#profile-search", function(event) {
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13'){
			console.log('pres');
			a.search = $(this).val();
			$("div#connectioncontent div.clisting").html(loadingGif).load(siteDir+"/membernetwork/ajax-all-members", {data: a});
		}
	});

	//filter hospital v non-hospital members click
	$(document).on("click", "div#filterstaff", function(){
		if (a.filter == "all"){
			a.filter = "aeh";
		}else{
			a.filter = "all";
		}
		console.log(a.filter);
		$("div#connectioncontent div.clisting").html(loadingGif).load(siteDir+"/membernetwork/ajax-all-members", {data: a});
	});

	//change the number per page
	$(document).on("change", "select#perpage", function() {
		a.perPage = $("select#perpage option:selected").val();
		a.pageNo = 1; //reset page number or else it could be pointing at non existent page
		$("div#connectioncontent div.clisting").html(loadingGif).load(siteDir+"/membernetwork/ajax-all-members", {data: a});
	});

	//add connection click
	$(document).on("click", "div.add-connection button.add-button", function(i) {
		var this_id = $(this).attr("alt");
		var new_button = '<button class="added-button">Approval Pending</button>';
		$("div#sidebar-connections div.myfriends div.pendingnotify").load(siteDir+"/membernetwork/ajax-add-members?id=" + this_id, function() {
			$("div#sidebar-connections").load(siteDir+"/membernetwork/ajax-update-sidebar");
			$("div#add" + this_id + " div.add-connection").empty().append(new_button);
		});
	});

	/*************************************************************************************************************************************/
	//remove connection from member profile page click (stage 1 - create remove & cancel buttons)
	$(document).on("click", "button#remove", function(i) {
			$("div#removebuttons").css("display","inline");
			$("button#remove").css("display","none");
	});
	//remove connection from member profile page click (stage 2A - the delete button itself)
	$(document).on("click", "button#remove-yes", function(i) {
		var this_id = $(this).attr("name");
		$("button#remove-yes").text('Removed!').load(siteDir+"/membernetwork/ajax-remove-friend?id=" + this_id, function() {
			$("div#sidebar-connections").load(siteDir+"/membernetwork/ajax-update-sidebar");
			$("div#removalbuttons").css("display","none"); //hide all removal buttons from the profile page now as not a friend anymore
			window.location.reload();
		});
	});
	//remove connection from member profile page click (stage 2B - the cancel delete button)
	$(document).on("click", "button#remove-no", function(i) {
			$("div#removebuttons").css("display","none");
			$("button#remove").css("display","inline");
	});
	/*************************************************************************************************************************************/
	//approve connection click
	$(document).on("click", "div.friendedmeicon button.approveme", function(i) {
		var this_id = $(this).attr('data-friendID');
		var temp = $(this);
		$("div#sidebar-connections div.myfriends div.friendednotify").html(loadingGif).load(siteDir+"/membernetwork/ajax-add-my-friend?id=" + this_id, function() {
			$("div#sidebar-connections").load(siteDir+"/membernetwork/ajax-update-sidebar");
			$("div#connectioncontent div.clisting").load(siteDir+"/membernetwork/ajax-all-members", {data: a});
		});
	});

	//deny connection click
	$(document).on("click", "div.friendedmeicon p.friendID button", function(i) {
		var this_id = $(this).attr("name");
		var temp = $(this);
		$("div#sidebar-connections div.myfriends div.friendednotify").html(loadingGif).load(siteDir+"/membernetwork/ajax-deny-connection?id=" + this_id, function() {
			$("div#sidebar-connections").load(siteDir+"/membernetwork/ajax-update-sidebar");
			$("div#connectioncontent div.clisting").load(siteDir+"/membernetwork/ajax-all-members", {data: a});
		});
	});

	/*************************************************************************************************************************************/
	//remove domain (stage 1 - create remove & cancel buttons)
	$(document).on("click", "div.removedomain button.button-one", function(i) {
		$(this).css("display","none");
		$("div.removedomain button.button-two").css("display","inline");
	});

	//remove domain from the list
	$(document).on("click", "div.removedomain button.button-two", function(i) {
		var this_id = $(this).attr("id");
			$(this).load(siteDir+"/membernetwork/ajax-delete-domain?id=" + this_id, function() {
				$("div#domain-container").html(loadingGif).load(siteDir+"/membernetwork/ajax-get-domains");
			});
	});

	/*************************************************************************************************************************************/

	function toggledir(x){
		if (x == "asc"){x = "desc";}else{x = "asc";}
		return x;
	}
});

function checkAll(formname, checktoggle){
  var checkboxes = new Array();
  checkboxes = document[formname].getElementsByTagName('input');

  for (var i=0; i<checkboxes.length; i++){
    if (checkboxes[i].type == 'checkbox'){
      checkboxes[i].checked = checktoggle;
    }
  }
}

