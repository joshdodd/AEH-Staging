//jQuery UI
$("input#application-form-startdate, input#application-form-enddate").datepicker();

//Ember Application
window.app = Ember.Application.create();

	//Controller
	App.ApplicationController = Ember.Controller.extend({
		posts: ""
  	});