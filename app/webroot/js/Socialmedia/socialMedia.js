
var base_url = window.location.origin;

/***************** Twitter *****************************************/

	(function($){
    //  inspired by DISQUS
    $.oauthpopup = function(options){
        if (!options || !options.path) {
            throw new Error("options.path must not be empty");
        }
        options = $.extend({
            windowName: 'ConnectWithOAuth' // should not include space for IE
          , windowOptions: 'location=0,status=0,width=600,height=400,position=fixed,top=200,left=400,directories=no,titlebar=no,toolbar=no,menubar=no,scrollbars=no,resizable=no'
          , callback: function(){ window.location.reload(); }
        }, options);

        var oauthWindow   = window.open(options.path, options.windowName, options.windowOptions);
        var oauthInterval = window.setInterval(function(){
            if (oauthWindow.closed) {
                window.clearInterval(oauthInterval);
                options.callback();
            }
        }, 1000);
    };
 
    //bind to element and pop oauth when clicked
    $.fn.oauthpopup = function(options) {
        $this = $(this);
        $this.click($.oauthpopup.bind(this, options));
    };
})(jQuery);
//title, description, picture,share_url
// <![CDATA[			
	function twLogin(e){    
    var title= e.getAttribute("data-tw-title");
    var description = e.getAttribute('data-tw-desc');
    var picture = e.getAttribute('data-tw-picture');
    var share_url  = e.getAttribute('data-tw-url');
    var tw_share_url = ((share_url!='') ? share_url : 'https://talentedge.in/');
		$.oauthpopup({
			path: base_url+tw_auth_url+'?title='+title+'&description='+description+'&picture='+picture+'&share_url='+tw_share_url,
			callback: function(res){
				window.close();
			}
		});	
	}

  function fbLogin(e){
    /*var title= e.getAttribute("data-fb-title");
    var description = e.getAttribute('data-fb-desc');
    var picture = e.getAttribute('data-fb-picture');
    var share_url  = e.getAttribute('data-fb-url');
    var tw_share_url = ((share_url!='') ? share_url : 'https://talentedge.in/');
    $.oauthpopup({
      path: base_url+fb_auth_url+'?title='+title+'&description='+description+'&picture='+picture+'&share_url='+tw_share_url,
      callback: function(res){
        window.close();
      }
    });*/
  }

  function liLogin(e){
    var title= e.getAttribute("data-li-title");
    var description = e.getAttribute('data-li-desc');
    var picture = e.getAttribute('data-li-picture');
    var share_url  = e.getAttribute('data-li-url');
    var tw_share_url = ((share_url!='') ? share_url : 'https://talentedge.in/');
    $.oauthpopup({
      path: base_url+li_auth_url+'?title='+title+'&description='+description+'&picture='+picture+'&share_url='+tw_share_url,
      callback: function(res){
        window.close();
      }
    });
  }
  
// ]]>
