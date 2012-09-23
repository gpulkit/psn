/*
 * SimpleModal Image Gallery
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2010 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Revision: $Id: gallery.js 251 2010-07-21 05:39:45Z emartin24 $
 */
 
 

$( function() {
	

	applyGallery(".thumb_pic_user a");
	applyGallery(".thumb_pic a");
	
	$( "#tabs" ).tabs();
	
	$( "#tabs" ).bind( "tabsselect", function(event, ui) {
		current_tab_index = ui.index;
	});
	
});


var tab1_page = 1;
var tab2_page = 1;
var current_event = 0;
var showframes_1 = 1;
var showframes_2 = 1;
var current_tab_index = 0;


function setPage(page,tab) {
	if(tab == 1) {
		tab1_page = page;
		showUser();
	} else if (tab == 2) {
		tab2_page = page;
		showEvent(0);
	}
}

function toggleframes(tab) {
	if(tab==1) {
		var checkbox = $("#framedcheckbox1");
		if( checkbox.attr("checked") != "undefined" && checkbox.attr("checked") == "checked" ) {
			 showframes_1 = 1;
		} else {
			 showframes_1 = 0;
		}
		showUser();
	} else if(tab == 2) {
		var checkbox = $("#framedcheckbox2");
		if( checkbox.attr("checked") != "undefined" && checkbox.attr("checked") == "checked" ) {
			 showframes_2 = 1;
		} else {
			 showframes_2 = 0;
		}
		showEvent(0);
	}
}
   

function showEvent(newevent) {
	
	if(newevent > 0) {
		current_event = newevent;
		tab2_page = 1;
	}
	
	//$("#tabs-2").html("Loading... ");
	
	var folder = "processed";
	if(showframes_2==0) {
		folder = "photos";
	}

	$.post(  
			"./actions_orig/geteventpictures.php",
			
			{ "event_id" : current_event, "page" : tab2_page, "folder" : folder    },
			
			function(data) {
				$("div.content").html(data);
				applyGallery(".thumb_pic a");
			},
			
			"html"
		
	);
	
}


function showUser() {
	
	//$("#tabs-1").html("Loading... ");
	var folder = "processed";
	if(showframes_1==0) {
		folder = "photos";
	} 
	
	$.post(  
		"./galleries/actions_orig/getuserpictures.php",
		{ "page" : tab1_page , "folder" : folder  },
		function(data) {
			$("div.content").html(data);
			applyGallery(".thumb_pic_user a");
		},
		"html"
	);
}

function dologin() {
	
	$("#username").attr('disabled','disabled');
	$("#password").attr('disabled','disabled');
	var username = $("#username").val();
	var password = $("#password").val();
	$.post(  
			"./actions_orig/login.php",
			
			{ "username" : username, "password" : password  },
			
			function(data) {
				
				if(data=="1") {
					showUser();	
				} else {
					alert("Wrong username or password.");
				}
				
				$("#username").removeAttr('disabled');
				$("#password").removeAttr('disabled');   
				
			},
			
			"text"
		
	);
	
}

function showCovers() {
	
	
	$("#tabs-2").html("Loading... ");
	
	$.post(  
			"./actions_orig/geteventcovers.php",
			
			null,
			
			function(data) {
				$("#tabs-2").html(data);
				//callResize();
			},
			
			"html"
		
	);
	
}

function applyGallery(elementid) {
	var G = {
		active: false,
		/*
		 * Calls SimpleModal with appropriate options 
		 */
		init: function ( id_cont ) {
			G.images = $( id_cont );
			G.images.click(function () {
				G.current_idx = G.images.index(this);
				$(G.create()).modal({
					closeHTML: '',
					overlayId: 'gallery-overlay',
					containerId: 'gallery-container',
					containerCss: {left:0, width:'1000px'},
					opacity: 80,
					position: ['40px', null],
					onOpen: G.open,
					onClose: G.close
				});

				return false;
			});
		},
		/*
		 * Creates the HTML for the viewer 
		 */
		create: function () {
			return $("<div id='gallery'> \
					<div id='gallery-image-container'> \
						<div id='gallery-controls'> \
							<div id='gallery-previous'> \
								<a href='#' id='gallery-previous-link'>&lt; <u>P</u>rev</a> \
							</div> \
							<div id='gallery-next'> \
								<a href='#' id='gallery-next-link'><u>N</u>ext &gt;</a> \
							</div> \
						</div> \
					</div> \
					<div id='gallery-meta-container'> \
						<div id='gallery-meta'> \
							<div id='gallery-info'><span id='gallery-title'></span><span id='gallery-pages'></span></div> \
							<div id='gallery-close'><a href='#' class='simplemodal-close'>X</a></div> \
						</div> \
					</div> \
				</div>");
		},
		/*
		 * SimpleModal callback to create the 
		 * viewer and open it with animations 
		 */
		open: function (d) {
			G.container = d.container[0];
			G.gallery = $('#gallery', G.container);
			G.image_container = $('#gallery-image-container', G.container);
			G.controls = $('#gallery-controls', G.container);
			G.next = $('#gallery-next-link', G.container);
			G.previous = $('#gallery-previous-link', G.container);
			G.meta_container = $('#gallery-meta-container', G.container);
			G.meta = $('#gallery-meta', G.container);
			G.title = $('#gallery-title', G.container);
			G.pages = $('#gallery-pages', G.container);
			
			d.overlay.show();
			
			d.container
					.css({height:0})
					.show(function () {
						d.data.slideDown(300, function () {
							// load the first image
							G.display();
						});
					});
					
					
			/*
			d.overlay.slideDown(300, function () {
				d.container
					.css({height:0})
					.show(function () {
						d.data.slideDown(300, function () {
							// load the first image
							G.display();
						});
					});
			});
			*/
		},
		/*
		 * SimpleModal callback to close the 
		 * viewer with animations
		 */
		close: function (d) {
			var self = this;
			
			//G.meta.hide();
			
			G.image_container.fadeOut('fast', function () {
				
				d.data.hide();
				
				d.container.fadeOut(500, function () {
					d.overlay.hide();
					self.close(); // or $.modal.close();	
				});
				
				G.unbind();
			});
			
			//TODO: this is a fix. When the modal window appears, it makes the scrolling bar appear
			if(current_tab_index == 0) {
				showUser();
			} else if ( current_tab_index == 1 ) {
				showEvent(0);
			}
			
			/*
			G.meta.slideUp(function () {
				G.image_container.fadeOut('fast', function () {
					d.data.slideUp(500, function () {
						d.container.fadeOut(500, function () {
							d.overlay.slideUp(500, function () {
								self.close(); // or $.modal.close();	
							});
						});
					});
					G.unbind();
				});
			});
			*/
		},
		/*
		 * Display the previous/next image 
		 */
		browse: function (link) {
			G.current_idx = $(link).parent().is('#gallery-next') ? (G.current_idx + 1) : (G.current_idx - 1);
			G.display();
		},
		/* display the requested image and animate the height/width of the container */
		display: function () {
			G.controls.hide();
			
			G.meta.slideUp(100, function () {
				
				G.meta_container.hide();
				G.image_container.fadeOut('fast', function () {
					$('#gallery-image', G.container).remove();

					var img = new Image();
					img.onload = function () {
						G.load(img);
					};
					img.src = G.images.eq(G.current_idx).find('img').attr('src').replace(/_(s|t|m)\.jpg$/, '.jpg');
					img.src = (img.src).replace(/&h=300&w=300$/, '&h=500&w=500');

					if (G.current_idx !== 0) {
						// pre-load prev img
						var p = new Image();
						p.src = G.images.eq(G.current_idx - 1).find('img').attr('src').replace(/_(s|t|m)\.jpg$/, '.jpg');
						p.src = (p.src).replace(/&h=300&w=300$/, '&h=500&w=500');
					}
					if (G.current_idx !== (G.images.length - 1)) {
						// pre-load next img
						var n = new Image();
						n.src = G.images.eq(G.current_idx + 1).find('img').attr('src').replace(/_(s|t|m)\.jpg$/, '.jpg');
						n.src = (n.src).replace(/&h=300&w=300$/, '&h=500&w=500');
					}
				});
			});
			
			
		},
		load: function (img) {
			var i = $(img);
			i.attr('id', 'gallery-image').hide().appendTo('body');
			//i.attr('src', i.attr('src').replace(/&h=300&w=300$/, '&h=500&w=500') );
			var h = i.outerHeight(true),
				w = i.outerWidth(true);
				
			G.gallery.height(h);
			G.gallery.width(w);
			G.show(i);

		},
		/* 
		 * Show the image and then the controls and meta 
		 */
		show: function (img) {
			img.show();
			G.image_container.prepend(img).fadeIn('slow', function () {
				G.showControls();
				G.showMeta();
			});
		},
		/*
		 * Show the image controls; previous and next 
		 */
		showControls: function () {
			G.next.hide().removeClass('disabled');
			G.previous.hide().removeClass('disabled');
			G.unbind();

			if (G.current_idx === 0) {
				G.previous.addClass('disabled');
			}
			if (G.current_idx === (G.images.length - 1)) {
				G.next.addClass('disabled');
			}
			G.controls.show();

			$('a', G.controls[0]).bind('click.gallery', function () {
				G.browse(this);
				return false;
			});
			$(document).bind('keydown.gallery', function (e) {
				if (!G.active) {
					if ((e.keyCode === 37 || e.keyCode === 80) && G.current_idx !== 0) {
						G.active = true;
						G.previous.trigger('click.gallery');
					}
					else if ((e.keyCode === 39 || e.keyCode === 78) && G.current_idx !== (G.images.length - 1)) {
						G.active = true;
						G.next.trigger('click.gallery');
					}
				}
			});
			$('div', G.controls[0]).hover(
				function () {
					var self = this,
						l = $(self).find('a:not(.disabled)');
					if (l.length > 0) {
						l.show();
					}
				},
				function () {
					$(this).find('a').hide();
				}
			);
		},
		/*
		 * Show the image meta; title, image x of x and the close X 
		 */
		showMeta: function () {
			var link = G.images.eq(G.current_idx).clone(),
				title = link.find('img').attr('title');
			link.attr('target','_new');
			G.title.html( link.attr('title', 'Full Image').html(title) ) ;
			G.pages.html('Image ' + (G.current_idx + 1) + ' of ' + G.images.length);
			G.meta_container.show();
			G.meta.show();
			G.active = false;	
			
		},
		/*
		 * Unbind gallery control events 
		 */
		unbind: function () {
			$('a', G.controls[0]).unbind('click.gallery');
			$(document).unbind('keydown.gallery');
			$('div', G.controls[0]).unbind('mouseenter mouseleave');
		}
	};

	G.init(elementid);
}

