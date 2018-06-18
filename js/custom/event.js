$( document ).ready(function() {
	
	//get the url parameter for event id and string it - e.g. "eid1" - THIS COULD CONFLICT WITH URL REWRITE
//	var searchParams = new URLSearchParams(window.location.search); //?anything=123
//	var eid = searchParams.get("eid"); //123
	
	$.ajax({
	  type: "POST",
	  //data: "getEventRounds",
	  data: {action:"getAllUsersPicks"},
	  url: "classes/wcEventHandler.php",
	  dataType: "json",
	  async: false,
	  success: function(data){
		$('.frontpage').html(data);
		//$('.frontpage').html(data);				
		$(document).foundation();
						
	  }
	});
	
	$(".usergrid").hide();
	
	$(".userscorename").click(function(){
		var uid = "u" + this.id.substring(4);//gets the event id from div id	
		var usergrid = ".grid" +uid;
		$(usergrid).slideDown("fast");
		var aTag = $("a[name='"+ uid +"']");
		$('html,body').animate({scrollTop: aTag.offset().top},'fast');
	});
	
	$(".closename").click(function(){
		var uid = ".gridu" + this.id.substring(5);//gets the event id from div id	
		$(uid).slideUp('fast');
	});
	
	
	
});





