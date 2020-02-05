var array = "", 
	array_inv = "", 
	i = 1, 
	t = 1, 
	t_1 = 1, 
	rmtyp = null, 
	chat = null, 
	grp_name = null,
	k = 0,
	j = 0,
	l = 0,
	m = 0,
	map_j = 0,
	current_pic = null,
	pic_prev = null,
	pic_prev_chat = null,
	open1 = false, 
	open2 = false, 
	open3 = false, 
	open4 = false, 
	open5 = false, 
	open6 = false, 
	chat_side = false,
	emoji = false,
	v = new Date(),
	recording = false,
	start_timestamp;
	

	///////////////////////////////////////////////////
	//////////////// SHARE LOCATION ///////////////////
	///////////////////////////////////////////////////
	
		function initMap(element, loc = 0, lat = 0, lng = 0, acc = 0) {
			var map = new google.maps.Map(element);
			var accuracy_marker = new google.maps.Circle({
				strokeColor: '#2196f3',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: '#2196f3',
				fillOpacity: 0.35,
				map: map,
			});
			var marker = new google.maps.Marker({
				map: map,
				icon: {
					path: google.maps.SymbolPath.CIRCLE,
					scale: 6,
					fillColor: '#0d47a1',
					fillOpacity: 1,
					strokeWeight: 0
				}
			});

			if(loc == 0) {
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(function(position) {
						var pos = {
							lat: position.coords.latitude,
							lng: position.coords.longitude
						};
						var accuracy = position.coords.accuracy;
						map.setZoom(16);
						map.setCenter(pos);
						accuracy_marker.setCenter(pos);
						accuracy_marker.setRadius(accuracy);
						marker.setPosition(pos);
						marker.addListener('click', function() {
							map.setZoom(16);
							map.setCenter(marker.getPosition());
						});
						$("#share-location-btn").removeClass("disabled");
					});
				} else {
					Materialize.toast( "Your browser doesn't support Geolocation.", 4000 );
				}
			} else {
				var pos = {
					lat: parseFloat(lat),
					lng: parseFloat(lng)
				};
				map.setZoom(16);
				map.setCenter(pos);
				accuracy_marker.setCenter(pos);
				accuracy_marker.setRadius(parseInt(acc));
				marker.setPosition(pos);
				marker.addListener('click', function() {
					map.setZoom(16);
					map.setCenter(marker.getPosition());
				});
			}
		}
		
	$("body").on("click", ".map-msg-cover", function() {
		var pos = {
			lat: parseFloat($(this).next().attr("lat")),
			lng: parseFloat($(this).next().attr("lng"))
		};
		var acc = parseInt($(this).next().attr("acc"));
		$("#modal52").modal({
			ready: function() {
				var map = new google.maps.Map($("#big-modal-map")[0], {zoom: 16, center: pos});
				var accuracy_marker = new google.maps.Circle({
					strokeColor: '#2196f3',
					strokeOpacity: 0.8,
					strokeWeight: 2,
					fillColor: '#2196f3',
					fillOpacity: 0.35,
					map: map,
					center: map.getCenter(),
					radius: acc
				});
				var marker = new google.maps.Marker({
					map: map,
					icon: {
						path: google.maps.SymbolPath.CIRCLE,
						scale: 6,
						fillColor: '#0d47a1',
						fillOpacity: 1,
						strokeWeight: 0
					},
					position: map.getCenter()
				});
			}, 
			complete: function() {
				$("#big-modal-map").html("");
			}
		}).modal("open");
	});

(function(window){
  window.Voice = window.Voice || {};
	Voice.voice = {
    
    stream: false,
    input: false,
    
    init_called: false,

    init: function(){
    	try {
    		window.AudioContext = window.AudioContext||window.webkitAudioContext;
    		navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
    		window.URL = window.URL || window.webkitURL;
    		if(navigator.getUserMedia === false){
				Materialize.toast( "Voice Notes is not supported in your browser.", 4000 );
				var window_width = $(window).width();
				if(window_width <= 640) {
					$("#send-msg").removeClass("hide");
					$(".send-msg-bg").removeClass("hide");
					$("#open-emoji").removeClass("hide");
					$("#close-emoji").removeClass("hide");
				}
				Voice.voice.stop();
				$("#voice-confirm-btn").addClass("hide");
				$("#voice-reset-btn").addClass("hide");
				$("#voice-recording").addClass("hide");
				$("#send-msg").css("width", "calc( 100% - 30px )");
				recording = false;
				clearInterval(voice_timer_interval);
    		}
    		this.context = new AudioContext();
    	} catch(e) {
    		Materialize.toast( "Voice Notes is not supported in your browser.", 4000 );
			var window_width = $(window).width();
			if(window_width <= 640) {
				$("#send-msg").removeClass("hide");
				$(".send-msg-bg").removeClass("hide");
				$("#open-emoji").removeClass("hide");
				$("#close-emoji").removeClass("hide");
			}
			Voice.voice.stop();
			$("#voice-confirm-btn").addClass("hide");
			$("#voice-reset-btn").addClass("hide");
			$("#voice-recording").addClass("hide");
			$("#send-msg").css("width", "calc( 100% - 30px )");
			recording = false;
			clearInterval(voice_timer_interval);
    	}
    },
    record: function(output, finishCallback, recordingCallback){
    	var finishCallback = finishCallback || function(){};
		var recordingCallback = recordingCallback || function(){};
      
		if(this.init_called === false){
    		this.init();
    		this.init_called = true;
    	}
      
		var $that = this;
    	navigator.getUserMedia({audio: true}, function(stream){
    		
			$that.input = $that.context.createMediaStreamSource(stream);
        
    		$that.recorder = new Recorder($that.input, {'recordingCallback': recordingCallback});
        
    		$that.stream = stream;
    		$that.recorder.record();
    		finishCallback(stream);
    	}, function() {
    		Materialize.toast( "Please check your microphone.", 4000 );
			var window_width = $(window).width();
			if(window_width <= 640) {
				$("#send-msg").removeClass("hide");
				$(".send-msg-bg").removeClass("hide");
				$("#open-emoji").removeClass("hide");
				$("#close-emoji").removeClass("hide");
			}
			$("#voice-confirm-btn").addClass("hide");
			$("#voice-reset-btn").addClass("hide");
			$("#voice-recording").addClass("hide");
			$("#send-msg").css("width", "calc( 100% - 30px )");
			recording = false;
			clearInterval(voice_timer_interval);
    	});
    },
    stop: function(){
    	this.recorder.stop();
    	this.recorder.clear();
    	this.stream.getTracks().forEach(function (track) {
        track.stop();
      });
    	return this;
    },
    export: function(callback, type){
        this.recorder.exportWAV(function(blob) {
          if(type == "" || type == "blob"){
            callback(blob);
          }else if(type == "URL"){
            var url = URL.createObjectURL(blob);
            callback(url);
          }
        });
      
    }
  };
})(window);
	
// Check If the Email is Valid
function isValidEmailAddress( emailAddress ) {	
	var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
	return pattern.test( emailAddress );
}

function escString( str ) {
    return str.replace(/\"/g, '').replace(/\'/g, '');
}

function array_map (callback) {
  var argc = arguments.length
  var argv = arguments
  var obj = null
  var cb = callback
  var j = argv[1].length
  var i = 0
  var k = 1
  var m = 0
  var tmp = []
  var tmpArr = []

  var $global = (typeof window !== 'undefined' ? window : global)

  while (i < j) {
    while (k < argc) {
      tmp[m++] = argv[k++][i]
    }

    m = 0
    k = 1

    if (callback) {
      if (typeof callback === 'string') {
        cb = $global[callback]
      } else if (typeof callback === 'object' && callback.length) {
        obj = typeof callback[0] === 'string' ? $global[callback[0]] : callback[0]
        if (typeof obj === 'undefined') {
          throw new Error('Object not found: ' + callback[0])
        }
        cb = typeof callback[1] === 'string' ? obj[callback[1]] : callback[1]
      }
      tmpArr[i++] = cb.apply(obj, tmp)
    } else {
      tmpArr[i++] = tmp
    }

    tmp = []
  }

  return tmpArr
}

function preg_quote (str, delimiter) {
  return (str + '')
    .replace(new RegExp('[.\\\\+*?\\[\\^\\]$(){}=!<>|:\\' + (delimiter || '') + '-]', 'g'), '\\$&')
}

function ban_word(word)
{
	if(ban_words_list.length > 0) {
		var re = new RegExp('(?![^<]*>)\\b('+array_map('preg_quote', ban_words_list).join('|')+')\\b', "g");
		return word.replace(re, function(match, text) {
			var index = ban_words_list.indexOf(text.toLowerCase());
			if(index >= 0) {
				return ban_words[index].replace(/\\/g, '');;
			} else {
				return "@#!?";
			}
				
		});
	} else {
		return word;
	}
}

function closeSideCard( element_class ) {
	$( element_class ).velocity( 
				{ translateX: "100%", translateY: "0", translateZ: "0" }, 
				{ duration: 225, queue: !1, easing: "easeInOutQuad", complete: function() {
																					$( this ).css( { display: "none", opacity: 0 } )
																				}
				}
	);
}

function openSideCard ( element_class ) {
	$( element_class ).css({ display: "block", opacity: 1, transform: "translateX(0)" }).velocity( "stop", !1 ).velocity(
																	{ translateX: "-100%", translateY: "0", translateZ: "0" },
																	{ duration: 500, queue: !1, easing: "easeInOutQuad" }
																);
}

function scrollToBottom( element_id ) {
	$( element_id ).scrollTop( $( element_id ).prop( "scrollHeight" ) );
}

function pasteHtmlAtCaret(html) {
    var sel, range;
    if (window.getSelection && navigator.userAgent.indexOf("Firefox") < 1) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ( (node = el.firstChild) ) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);

            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    } else {
		$(".send-msg").append(html);
	}
}

function moveCursor( el )
{
    el.focus();
    if (typeof window.getSelection != "undefined"
            && typeof document.createRange != "undefined") {
        var range = document.createRange();
        range.selectNodeContents(el);
        range.collapse(false);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (typeof document.body.createTextRange != "undefined") {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.collapse(false);
        textRange.select();
    }
}

function getTime() {
	var date = new Date();	// Get Date
	var hour = date.getHours();	// Get Hour
	var minute = date.getMinutes();	// Get Minute
	
	var time_vars = [ hour , minute ];
	
	$.each( time_vars, function( i, variable ) {
		if( [0, 1, 2, 3, 4, 5, 6, 7, 8, 9].indexOf( variable ) > -1 ) {
			if( time_vars[0] == variable ) {
				hour = "0" + variable;
			} else {
				minute = "0" + variable;
			}		
		}
	});
	return hour + ":" + minute;	// Create the Time (Hours:Minutes)
}

function sendNotification( title, msg, chatid, userid, roomtype, img ) {
	
	var container = $('<div>').html(msg);
	if( container.find("img").length > 0 ) {		
		container.find('img').replaceWith(function() { return this.alt; })
		msg = container.html();
	}
	 
	var notification = new Notification( title, { 
												body: msg,
												icon: img,
												tag: title
	});
	
	var notification_sound = new Audio( "./include/notification.aac" );
	notification_sound.play(); 

	
	
	notification.onclick = function( ev ) {
		ev.preventDefault();
		notification.close();
		$(".invite-btn").removeClass("hide");
		$( ".invite-reveal" ).css( "transform", "translateY(0)" );
		$( ".card-reveal" ).css( "transform", "translateY(0)" );
		$( "#modal1" ).modal("close");
		window.focus();

		if( chat !== chatid ) {
			chat = chatid;	
			$( ".chat-reveal" ).css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {$(this).css("display", "none")}});
			$(".send-msg").attr("contenteditable", "true");
			$("#send-btn").removeClass("disabled");
			$( "#voice-btn" ).removeClass( "disabled" );
			$("#open-emoji").removeClass("disabled");
			$("#clear-chat").parent().removeClass("disabled");
			$("#users").parent().removeClass("hide");
			$( ".click-to-toggle" ).removeClass( "hide" );
			$(".invite-reveal .b-chip").remove();
			$.post( "./action.php?act=check-user", { data: userid, chatid: chatid }).done(function( stat ) {
				if( stat == 0 ) {
					$( ".send-msg" ).attr( "contenteditable", "false" );
					$( "#send-btn" ).addClass( "disabled" );
					$( "#voice-btn" ).addClass( "disabled" );
					$( "#open-emoji" ).addClass( "disabled" );
					$( "#clear-chat" ).parent().addClass( "hide" );
					$( "#users" ).parent().addClass( "hide" );
				}
			});
			$( "#msg-content" ).html( "" );	// Reset the Chat Content
			$( "#loading" ).removeClass( "hide" );	// Show the Loading Element
			
			grp_name = $( "#" + chatid + " span" ).html(); // Chat Name
			
			$.post( "./action.php?act=chat-details", { userid: userid, username: username, token: token, chat: chat }, function( data ) {
				var response = jQuery.parseJSON( data );
				if(response.error != "") {
					Materialize.toast( response.error, 4000 );
					setTimeout(function() {
						location.reload();
					},3000);
				} else {
					var ppl_num = response.user_chips.length + 1;
					t = ppl_num;
					t_1 = t;
					$( "#modal1 p" ).html( response.user_list );
					$.each( response.user_chips, function( i , val ) {
						$( ".invite-reveal .inv-users" ).append( "<div class='b-chip' attr-id='" + val + "'>" + response.user_chips_names[i] + "</div>" );
					});
					$( ".invite-reveal .inv-capacity" ).html( ppl_num + "/" + max_capacity );
					$("#dropdown3").html(response.dropdown);
					rmtyp = response.roomtype;
					if( response.pic == 1 ) {
						$( "#chat-main-rev" ).addClass( "hide" );
						$( "#chat-img-main-rev" ).attr( "src", response.url ).removeClass( "hide" );
					} else {
						if(rmtyp == 1) {
							$( "#chat-main-rev" ).html( "person" ).removeClass( "hide" );
						} else {
							$( "#chat-main-rev" ).html( "group" ).removeClass( "hide" );
						}
						$( "#chat-img-main-rev" ).attr( "src", "" ).addClass( "hide" );
					}
				}
			});	

			$( ".chat-nav-un" ).html( grp_name );	// Load the Chat Name
			
			setTimeout(function() {
				$( ".chat-reveal" ).css({ display: "block", opacity: 0 }).velocity( "stop", !1 ).velocity({ opacity: 1 },{ duration: 250, queue: !1, easing: "easeInOutQuad", complete:function(){$("#send-msg").focus();$( "#" + chat + " .custom-badge" ).remove();}});
			}, 100);
			
			// Make an Ajax Call for Loading previous messages
			$.post( './action.php?chat=' + chat, function( response4 ) {
				setTimeout(function() {
					$( "#loading" ).addClass( "hide" );	// Hide loading element
					$( "#msg-content" ).html( response4 );	// Load Messages to Chat Page
					$(".map-msg").ready(function() {
						for(map_i = 0; map_i < $(".map-msg").length; map_i++) {
							initMap($(".map-msg[num='"+map_i+"']")[0], 1, $(".map-msg[num='"+map_i+"']").attr("lat"), $(".map-msg[num='"+map_i+"']").attr("lng"), $(".map-msg[num='"+map_i+"']").attr("acc"));
						}
					});
					scrollToBottom("#msg-content");
				}, 400);
			});
		}
	}
	
	setTimeout(function() {
		notification.close();
	}, 4000);
	
}

function notifyMe(msg, title, roomtype, username, chatid, userid, img_path) {
	
	if( roomtype == 1 ) {
		name_a = title.split( '|' );
		if( name_a[0] === username ) {
			var chatname = name_a[0];
		} else {
			var chatname = name_a[1];
		}
	} else {
		var chatname = title;
	}
	// Let's check if the browser supports notifications
	if ( !("Notification" in window) ) {
	  	var notification_sound = new Audio( "./include/notification.aac" );
		notification_sound.play();
	}

	// Let's check whether notification permissions have already been granted
	else if ( Notification.permission === "granted" ) {
	
		var message = msg.replace(/::e\|\|([a-f0-9\-]+)::/g, function(match, text) {
			var code_points = text.split('-').map(function(n) {
			return parseInt(n, 16);
			});
			return String.fromCodePoint.apply(undefined, code_points);
		});
	
		// If it's okay let's create a notification
		sendNotification(chatname, message, chatid, userid, roomtype, img_path);
	}
	
	// Otherwise, we need to ask the user for permission
	else if ( Notification.permission === "denied" ) {
		var notification_sound = new Audio( "./include/notification.aac" );
		notification_sound.play();
	}
}
function openFABMenu(btn) {
    $this = btn;
    if ($this.hasClass('active') === false) {

      // Get direction option
      var horizontal = $this.hasClass('horizontal');
      var offsetY, offsetX;

      if (horizontal === true) {
        offsetX = 40;
      } else {
        offsetY = 40;
      }

      $this.addClass('active');
      $this.find('ul .btn-floating').velocity(
        { scaleY: ".4", scaleX: ".4", translateY: offsetY + 'px', translateX: offsetX + 'px'},
        { duration: 0 });

      var time = 0;
      $this.find('ul .btn-floating').reverse().each( function () {
        $(this).velocity(
          { opacity: "1", scaleX: "1", scaleY: "1", translateY: "0", translateX: '0'},
          { duration: 80, delay: time });
        time += 40;
      });
    }
  };

function recorderTimer(now) {
    var d = Math.round(+new Date()/1000);
    var a = (d - now) - 7200;
	date = new Date(a * 1000);
    $("#voice-recording-text").html(date.toLocaleTimeString());
}
$( document ).ready( function() {

	// Create a new WebSocket object.
	websocket = new ReconnectingWebSocket( wsUri );
	openFABMenu($(".action-btn").parent());
	
	if (("Notification" in window)) {
		// Request Notification Permisson
		Notification.requestPermission();
	}

	$('body').on('blur keyup paste input focus','.send-msg', function() { 
		$(this).trigger('change');
	});
	
	$('body').on('click','.send-msg-bg', function() { 
		$(".send-msg").trigger('change').focus();
	});

	$('body').on('change', '.send-msg', function() {
		if( $(this).html().replace(/<div.*\/div>/, '').length > 0) {
			$('.send-msg-bg').addClass("hide");
			if(voice_notes == 1) {
				$('#voice-btn').addClass("hide");
				$('#send-btn').removeClass("hide");
			}
		} else {
			$('.send-msg-bg').removeClass("hide");
			if(voice_notes == 1) {
				$('#voice-btn').removeClass("hide");
				$('#send-btn').addClass("hide");
			}
		}
	});
	
	$('#emoji-table').velocity("slideUp");
	
	$( "body" ).on( "click", "#open-emoji", function() {
		if( emoji == false ) {
			$( this ).addClass( "hide" );
			$( "#close-emoji" ).removeClass( "hide" );
			$( "#emoji-table" ).velocity("stop").velocity("slideDown", { complete: function(){
				$("#msg-content").velocity("stop").velocity( {height:  "-= 250"},{ duration: 0, queue: !1, easing: "easeInOutQuad", complete: function() { scrollToBottom("#msg-content"); emoji = true; } });
				$('.emoji-list').perfectScrollbar("update");
			} });
		}
	});
	
	$( "body" ).on( "click", "#close-emoji", function() {
		if( emoji == true ) {
			$( this ).addClass( "hide" );
			$( "#open-emoji" ).removeClass( "hide" );
			$( "#emoji-table" ).velocity("stop").velocity("slideUp", { complete: function(){
				$("#msg-content").velocity("stop").velocity( {height:  "+= 250"},{ duration: 0, queue: !1, easing: "easeInOutQuad", complete: function() { scrollToBottom("#msg-content"); emoji = false; } });
			} });
		}
	});
	
	$( "body" ).on( "click", "#emoji-table", function(ev) {	
		ev.preventDefault();
	});
	
	$( "body" ).on( "click", ".emoji-link", function( ev ) {
		ev.preventDefault();
		var emoji_tag = $(this).attr("alt");
		var emoji_class = $(this).attr("class").replace("emoji-link", "emoji-link-small");
		var emoji_src = $(this).attr("src");
		$('.send-msg').focus();
		$('.send-msg-bg').addClass("hide");
		
		pasteHtmlAtCaret("<img ondragstart='return false;' alt='" + emoji_tag + "' src='" + emoji_src + "' style='background-image: url(\"./include/web-imgs/emojis.png\");' class='" + emoji_class + "' draggable='false' />");
		scrollToBottom(".send-msg");
	});	
	
	$( "body" ).on( "click", "#edit-group-name", function( ev ) {
		ev.preventDefault();
		$("#grp-name").addClass("hide");
		var name_val = $("#grp-name").text();	
		$(this).addClass("hide");
		$("#reset-group-name").removeClass("hide");
		$("#confirm-group-name").removeClass("hide");
		$("#grp-name-input").val(name_val).removeClass("hide");
	});	
	
	$( "body" ).on( "click", "#edit-status", function( ev ) {
		ev.preventDefault();
		$("#user_status").addClass("hide");
		var status = $("#user_status").text();	
		$(this).addClass("hide");
		$("#reset-status").removeClass("hide");
		$("#confirm-status").removeClass("hide");
		$("#user-status-input").val(status).removeClass("hide");
	});	
	
	$( "body" ).on( "click", "#reset-group-name", function( ev ) {
		ev.preventDefault();
		$("#grp-name-input").addClass("hide");
		$("#grp-name").removeClass("hide");
		
		$("#edit-group-name").removeClass("hide");
		$("#reset-group-name").addClass("hide");
		$("#confirm-group-name").addClass("hide");		
	});	
	
	$( "body" ).on( "click", "#reset-status", function( ev ) {
		ev.preventDefault();
		$("#user-status-input").addClass("hide");
		$("#user_status").removeClass("hide");
		
		$("#edit-status").removeClass("hide");
		$("#reset-status").addClass("hide");
		$("#confirm-status").addClass("hide");		
	});	
	
	$( "body" ).on( "click", "#confirm-group-name", function( ev ) {
		ev.preventDefault();
		var name_val = $("#grp-name-input").val();
		if (name_val.length <= max_group_name) {
			if( websocket.readyState == 1 ) {	// Check the connection

				var change_group_name = {
					name: username,
					iduser: userid,
					msgtype: 7,
					token: token,
					room: chat,
					edit_grp_name: name_val
				};
										
				var json_change_group_name = JSON.stringify( change_group_name );							
				websocket.send( json_change_group_name );
			} else { // If there is no connection
				Materialize.toast( "Could not connect to WebSocket server.", 4000 );
			}
		} else {
			Materialize.toast( "Group name cannot be longer than " + max_group_name + ".", 4000 );
		}
	});
	
	$( "body" ).on( "click", "#confirm-status", function( ev ) {
		ev.preventDefault();
		var status = $("#user-status-input").val();
		$.post( "./action.php?act=update-user-status", { user_id: userid,username: username, token:token, status: status }, function( data ) {
			if(data == -1) {
				Materialize.toast( "Group name cannot be longer than " + max_group_name + ".", 4000 );
			} else if(data == 0) {
				Materialize.toast( "An error occured.", 4000 );
			} else if(data == 1) {
				$("#user-status-input").addClass("hide");
				$("#user_status").html(status).removeClass("hide");
				$("#edit-status").removeClass("hide");
				$("#reset-status").addClass("hide");
				$("#confirm-status").addClass("hide");
			} else if(data.length > 0) {
				$("#user-status-input").addClass("hide");
				$("#user_status").html(data).removeClass("hide");
				$("#edit-status").removeClass("hide");
				$("#reset-status").addClass("hide");
				$("#confirm-status").addClass("hide");
			} else {
				Materialize.toast( "An error occured.", 4000 );
			}
		});
	});

	// On "New Chat" Button Click // For Sending a New Personal Message
	$( "body" ).on( "click", "#new-msg", function( ev ) {
		ev.preventDefault();
		$( ".custom-nav i.left" ).fadeOut( "fast" );	// Hide Profile Picture
		$( ".custom-nav img.left" ).fadeOut( "fast" );	// Hide Profile Picture
		$( ".custom-nav h5.nav-un" ).fadeOut( "fast" );	// Hide Title
		$( "#add-btn" ).fadeOut( "fast" );	// Hide Add Button ("+" Button)
		$( "#new-msg" ).fadeOut( "fast" );	// Hide New Message Button
		$( "#new-grp" ).fadeOut( "fast" );	// Hide New Group Button
		$( ".close-rev" ).fadeOut( "fast" );
		setTimeout(function() {
			$( ".new-msg" ).removeClass( "hide" );	// Show Search User Input
			$( ".clear-btn" ).fadeIn( "fast" );	// Show Go Back Button ("X" Button)
		}, 400);
	});

	// On "New Chat" Button Click // For Sending a New Personal Message
	$( "body" ).on( "click", "#new-friend", function( ev ) {
		ev.preventDefault();
		$( ".custom-nav i.left" ).fadeOut( "fast" );	// Hide Profile Picture
		$( ".custom-nav img.left" ).fadeOut( "fast" );	// Hide Profile Picture
		$( ".custom-nav h5.nav-un" ).fadeOut( "fast" );	// Hide Title
		$( "#add-btn" ).fadeOut( "fast" );	// Hide Add Button ("+" Button)
		$( "#new-msg" ).fadeOut( "fast" );	// Hide New Message Button
		$( "#new-grp" ).fadeOut( "fast" );	// Hide New Group Button
		$( ".close-rev" ).fadeOut( "fast" );
		$( ".clear-btn" ).fadeOut( "fast" );
		$( ".new-msg" ).addClass( "hide" );
		$( "#search" ).val("");
		$( ".search-res" ).hide( "fast" );	// Hide Search Results Content
		$( ".main-window" ).removeClass( "hide" );	// Show Main Page
		setTimeout(function() {
			$( ".new-friend" ).removeClass( "hide" );
			$( ".clear-btn-friend" ).fadeIn( "fast" );
		}, 400);
	});
	
	// On "New Group" Button Click // For Creating a New Group
	$( "body" ).on( "click", "#new-grp", function( ev ) {
		ev.preventDefault();
		$( "#add-btn" ).fadeOut( "fast" );	// Hide Add Button ("+" Button)
		$( "#new-msg" ).fadeOut( "fast" );	// Hide New Message Button
		$( "#new-grp" ).fadeOut( "fast" );	// Hide New Group Button
		$( "#friends-btn" ).fadeOut( "fast" );	// Hide New Group Button
		$( ".card-reveal" ).css({ display: "block", opacity: 0 }).velocity( "stop", !1 ).velocity({ opacity: 1 },{ duration: 250, queue: !1, easing: "easeInOutQuad", complete: function() {$( ".close-rev" ).fadeIn( "fast" );}});
	});
	
	// On "X" Button Click in "New Chat" Page
	$( "body" ).on( "click", ".clear-btn", function( ev ) {
		ev.preventDefault();
		$( "#search" ).val( "" ); // Reset Search Input Value
		$( ".new-msg" ).addClass( "hide" ); // Hide Search User Input
		$( ".clear-btn" ).fadeOut( "fast" ); // Hide Go Back Button ("X" Button)
		setTimeout(function() {
			$("#add-btn").fadeIn("fast");	// Show Add Button ("+" Button)
			$( "#new-msg" ).fadeIn( "fast" );	// Hide New Message Button
			$( "#new-grp" ).fadeIn( "fast" );	// Hide New Group Button
			$( ".close-rev" ).fadeOut( "fast" );
			$(".custom-nav i.left").fadeIn( "fast" );	// Show Profile Picture
			$(".custom-nav img.left").fadeIn( "fast" );	// Show Profile Picture
			$(".custom-nav h5.nav-un").fadeIn( "fast" );	// Show Username
		}, 400);
		$( ".search-res" ).hide( "fast" );	// Hide Search Results Content
		$( ".main-window" ).removeClass( "hide" );	// Show Main Page
	});
	
	// On "X" Button Click in "New Chat" Page
	$( "body" ).on( "click", ".clear-btn-friend", function( ev ) {
		ev.preventDefault();
		$( "#search_friend" ).val( "" ); // Reset Search Input Value
		$( ".new-friend" ).addClass( "hide" ); // Hide Search User Input
		$( ".clear-btn-friend" ).fadeOut( "fast" ); // Hide Go Back Button ("X" Button)
		setTimeout(function() {
			$("#add-btn").fadeIn("fast");	// Show Add Button ("+" Button)
			$( "#new-msg" ).fadeIn( "fast" );	// Hide New Message Button
			$( "#new-grp" ).fadeIn( "fast" );	// Hide New Group Button
			$( ".close-rev" ).fadeOut( "fast" );
			$(".custom-nav i.left").fadeIn( "fast" );	// Show Profile Picture
			$(".custom-nav img.left").fadeIn( "fast" );	// Show Profile Picture
			$(".custom-nav h5.nav-un").fadeIn( "fast" );	// Show Username
		}, 400);
		$( ".search-res" ).hide( "fast" );	// Hide Search Results Content
		$( ".main-window" ).removeClass( "hide" );	// Show Main Page
	});
	
	$( "body" ).on( "click", ".register-btn", function( ev ){
		ev.preventDefault();
		$("#password-reveal").addClass("hide");
		$("#login-reveal").addClass("hide");
		$("#registration-reveal").removeClass("hide").css({ display: "block", opacity: 0 }).velocity( "stop", !1 ).velocity({ opacity: 1 },{ duration: 300, queue: !1, easing: "easeInOutQuad"});
	});
	
	// On "X" Button Click in "Registration" Page
	$( "body" ).on( "click", ".close-register", function( ev ) {
		ev.preventDefault();
		$("#registration-reveal").css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {$("#login-reveal").removeClass("hide");$(this).css("display", "none");}});
	});
	
	// Live Search in "New Chat" Page
	 $( "body" ).on( "input", "#search", function() {
        var search = $( this ).val();	// Get the Search Value
		if( search.length >= min_search_lenght ) {	// If search input is not empty
			
			// Make an Ajax Call for do the Search
			$.post( "./action.php?act=search", {search: search, page:1}, function( response ) {
				$( ".main-window" ).addClass( "hide" );	// Hide Main Page
				$( ".search-res" ).fadeIn( "fast" ).html( response ); // Show Search Results
			});
		} else { // If search input is empty
			$( ".search-res" ).hide( "fast" );	// Hide Search Results
			$( ".main-window" ).removeClass( "hide" ); // Show Main Page
			$('.custom-cont').perfectScrollbar("update");
		}
     });
	
	// Live Search in "New Chat" Page
	 $( "body" ).on( "input", "#search_friend", function() {
        var search = $( this ).val();	// Get the Search Value
		if( search.length >= min_search_lenght ) {	// If search input is not empty
			
			// Make an Ajax Call for do the Search
			$.post( "./action.php?act=search-friend", {search: search, page:1}, function( response ) {
				if($(".friends").hasClass("opened")) {
					closeSideCard( ".friends" );
				}
				$( ".main-window" ).addClass( "hide" );	// Hide Main Page
				$( ".search-res" ).fadeIn( "fast" ).html( response ); // Show Search Results
			});
		} else { // If search input is empty
			$( ".search-res" ).hide( "fast" );	// Hide Search Results
			$( ".main-window" ).removeClass( "hide" ); // Show Main Page
			$('.custom-cont').perfectScrollbar("update");
		}
     });
	 
	 $("body").on("click", ".search-pagination li", function(e){
		 e.preventDefault();
		 var page = $("a", this).attr("attr-page");
		 if(page > 0) {
			var search = $( "#search" ).val();	// Get the Search Value
					 
			// Make an Ajax Call for do the Search
			$.post( "./action.php?act=search-friend", {search: search, page: page}).done(function( response ) {
				if($(".friends").hasClass("opened")) {
					closeSideCard( ".friends" );
				}
				$( ".search-res" ).html( response ); // Show Search Results
			});

		 }
	 });
	 
	 // Add User Chip
	$( "body" ).on( "click", ".add", function( ev ) {
		ev.preventDefault();
		if( i < max_capacity ) { // Check If There are Free Space
			var uid = $( this ).attr( "attr-id" ); // Get Username From the Search Result
			var un = $( this ).attr( "attr-un" ); // Get Username From the Search Result
										
			// Make an Ajax Call for Adding the User and Store User in Array Both in PHP & JQuery
			$.post( "./action.php?act=add-chip", { uid: uid,array: array }, function( response1 ) {
				if( response1 != 0 && response1 != 1 ) { // If there is not any errors
					array = response1;	// JQuery Array
					$( ".grp-users" ).append( "<div class=\'c-chip\'>" + un + "<i class=\'material-icons remove-chip\' attr-id=\'" + uid + "\'>close</i></div>");	// Create a Chip for People that are in the List			
				}
				i = array.split( "," ).length;	// Number of People in the Array
				$( ".capacity" ).html( i + "/" + max_capacity );	// Update the Free Space
			});
		}
	});	
	
	// Remove User Chip
	$( "body" ).on( "click", ".remove-chip", function() {
		var uid = $( this ).attr( "attr-id" ); // Get Username From the Chip
		$( this ).parent().remove(); // Remove the Chip
							
		// Make an Ajax Call for Removing the User from the Array
		$.post( "./action.php?act=remove-chip", { uid: uid, array: array }, function( response2 ) {
			if( response2 != 0 ) {	// If there is not any errors
				array = response2; // JQuery Array
				i = array.split( "," ).length; // Number of People in the Array
				$( ".capacity" ).html( i + "/" + max_capacity ); // Update the Free Space
			}
		});
	});		
	 
	 // Live Search in "New Group" Page
	 $( "body" ).on( "input", ".card-reveal #grp-s", function() {
        var search = $( this ).val();	// Get the Search Value
		
		if( search.length >= min_search_lenght ){	// If search input is not empty
			// Make an Ajax Call for do the Search
			$.post( "./action.php?act=grp-search", { search: search, page:1 }, function( response ) {
				$( ".grp-search-content" ).fadeIn( "fast" ).html( response );	// Show Search Results	
			});
		} else {
			$( ".grp-search-content" ).hide( "fast" );	// Hide the Search Results
			$(".card-reveal").perfectScrollbar("update");
		}
	});
	
	 $("body").on("click", ".group-pagination li", function(e){
		 e.preventDefault();
		 var page = $("a", this).attr("attr-page");
		 if(page > 0) {
			var search = $( ".card-reveal #grp-s" ).val();	// Get the Search Value
					 
			// Make an Ajax Call for do the Search
			$.post( "./action.php?act=grp-search", {search: search, page: page}).done(function( response ) {
				$(".grp-search-content" ).html( response ); // Show Search Results
			});

		 }
	 });
	
	$( "body" ).on( "click", "#create-group", function( ev ){
		ev.preventDefault();
		grp_name = $( ".grpname input" ).val();	// Chat Name
		$( ".settings-title" ).text("");
		$( "#grp-name" ).text("");
		$( ".cdropdown5" ).addClass("hide");
		$( "#edit-group-name" ).addClass("hide");
		$( "#group" ).addClass("hide");
		$( "#pp-act-grp-div" ).addClass("hide");
		$( "#group-img" ).attr("src", "").addClass("hide");
		if( websocket.readyState == 1 ) { // Check the connection
			if( max_group_name >=  $( ".grpname input" ).val().length ) {
				if( i > 1 ) {					
					if( grp_name !== "" ){
						var photo = $( "#create-img" ).prop( "files" )[0];
						if(photo == undefined) {
							photo = "";
						}
						var form_data = new FormData();                  
						var inputs = ["file", "token", "userid", "gn", "array"];		
						$.each(inputs, function (obj, val) {
							var input;
							switch(val){
								case "file":
									input = photo;
								break;
								case "token":
									input = token;
								break;
								case "userid":
									input = userid;
								break;
								case "gn":
									input = grp_name;
								break;
								case "array":
									input = array;
								break;
							}
							form_data.append(val, input);
						});

						$.ajax({
							url: "./action.php?act=create-group",
							data: form_data,
							processData: false,
							contentType: false,
							dataType: "json",
							type: "POST",
							success: function( data ) {
								if( data.stat != 1 && data.stat != 4 ) {
									Materialize.toast( data.error, 4000 );
								} else {
									chat = data.chat_id;
									$(".close-rev").addClass("hide");
									$.post( "./action.php?act=chat-number", { token: token, user_id: userid, username: username, chatid: data.chat_id } ).done( function( num_data ) {
										var num_response = jQuery.parseJSON( num_data );
										if( num_response.chat == 1 ) {
											$( ".not-any" ).addClass("hide");
											$( "#pmlist .msgs " ).removeClass("hide");
										}
										var gettime = getTime();
										$( "#msg-content" ).html( "" );	// Clear the #msg-content area
										var chat_id = data.chat_id;	// Hashed chat ID
										$( ".chat-nav-un" ).html( grp_name );	// Load Chat Name
										
										$.post( "./action.php?act=chat-details", { userid: userid, username: username, token: token, chat: chat_id }, function( data ) {
											var response = jQuery.parseJSON( data );
											if(response.error != "") {
												Materialize.toast( response.error, 4000 );
												setTimeout(function() {
													location.reload();
												},3000);
											} else {
												var ppl_num = response.user_chips.length + 1;
												t = ppl_num;
												t_1 = t;
												$( "#modal1 p" ).html( response.user_list );
												$.each( response.user_chips, function( i , val ) {
													$( ".invite-reveal .inv-users" ).append( "<div class='b-chip' attr-id='" + val + "'>" + response.user_chips_names[i] + "</div>" );
												});
												$( ".invite-reveal .inv-capacity" ).html( ppl_num + "/" + max_capacity );
												$("#dropdown3").html(response.dropdown);
												rmtyp = response.roomtype;
												if( response.pic == 1 ) {
													$( "#chat-main-rev" ).addClass( "hide" );
													$( "#chat-img-main-rev" ).attr( "src", response.url ).removeClass( "hide" );
													$( "ul.msgs" ).prepend( "<li id='" + chat_id + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 hide'>group</i><img id='chat-img-main' class='circle chat-list-photo' width='66' height='66' src='" + response.pic + "'><span class='title truncate'>" + grp_name + "</span><p><div class='last_message truncate'><br></div><div class='last_message_time'>" + gettime + "</div></p></li>" );
												} else {
													if(rmtyp == 1) {
														$( "#chat-main-rev" ).html( "person" ).removeClass( "hide" );
													} else {
														$( "#chat-main-rev" ).html( "group" ).removeClass( "hide" );
													}
													$( "#chat-img-main-rev" ).attr( "src", "" ).addClass( "hide" );
													$( "ul.msgs" ).prepend( "<li id='" + chat_id + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2'>group</i><img id='chat-img-main' class='circle chat-list-photo hide' width='66' height='66' src=''><span class='title truncate'>" + grp_name + "</span><p><div class='last_message truncate'><br></div><div class='last_message_time'>" + gettime + "</div></p></li>" );
												}
												var time = getTime();	// Get the Time
														
												// Create a Message for notifying other users and adding the chat to their chat list
												var create_msg = {
													name: username,	// Username
													room: chat_id,	// Hashed Chat ID
													iduser: userid,	// User ID
													msgtype: 3,	// New Group
													rmtype: 0,	// Group
													chatname: grp_name,	// Chat Name
													time: time,	// Current Time
													token: token
												};
										
												var json_create = JSON.stringify( create_msg );	// Convert msg into JSON
												websocket.send( json_create );	// Send the JSON Message
											}
										});	
									});

									array = "";	// Clear array
									i = 1;	// Reset i Value

									$( "#loading" ).addClass( "hide" );	// Remove Loading Icon
									$( "#msg-content" ).html( data );	// Load Messages
									$( "#add-btn" ).fadeIn( "fast" ); // Show add button	
									$( "#new-msg" ).fadeIn();
									$( "#new-grp" ).fadeIn();
									$( "#friends-btn" ).fadeIn();
									$( ".capacity" ).html( i + "/" + max_capacity );	// Reset Capacity
									$( "#grp-s" ).val( "" ).removeClass("active" );	// Clear User Search Input
									$( "#grp" ).val( "" ).removeClass( "active" );	// Clear Group Name Input
									$( ".grp-search-content" ).html( "" );	// Remove Previous User Search Results
									$( ".c-chip" ).remove();	// Remove Previously Added Users
									open = false;
										
									$.post( "./action.php?act=load-chat-settings", { chat: chat, username: username, userid: userid, token: token } ).done( function( data2 ) {
										$(".card .chat-settings").html(data2);
										$('.profile-image-thumb a').simpleLightbox();
										$(".profile-image-thumb img").on("load", function(){
											var element = $(this)[0];
											var element2 = $(this);
											if(element.naturalHeight >= element.naturalWidth) {
												element2.css("width", "100px");
											} else {
												element2.css("height", "100px");
											}
										});
									});
										
									$( ".chat-reveal" ).css({ display: "block", opacity: 0 }).velocity( "stop", !1 ).velocity(
																	{ opacity: 1 },
																	{ duration: 250, queue: !1, easing: "easeInOutQuad", complete: function() {
																		$( "#c-group-img" ).attr( "src", "" ).addClass( "hide" );
																		$( "i#c-group" ).removeClass( "hide" );			
																		$( "#c-grp-img-remove" ).addClass( "hide" );
																		$( "#create-img" ).val( "" ); 
																		$( ".card-reveal" ).css({ display: "none", opacity: 0 });
																		$("#send-msg").focus();
																	} }
																);
									
								}
						}});
						$( "#create-img" ).val("");
					} else {
						Materialize.toast( "Group name cannot be empty.", 4000 );
						$( ".grpname input" ).addClass( "invalid" );
					}
				} else {
					Materialize.toast( "There must be at least 2 people in the group.", 4000 );
				}
			} else {
				Materialize.toast( "Group name cannot be longer than " + max_group_name + ".", 4000 );
			}
		} else {
			Materialize.toast( "Could not connect to WebSocket server.", 4000 );
		}
	});

			// Send Personal Message Button Click
			$( "body" ).on( "click", ".pm", function( ev ) {		
				ev.preventDefault();
				var uid = $( this ).attr( "attr-id" ); // User ID
				var un = $( this ).prev().text(); // Username
				
				$( ".settings-title" ).text("");
				$( "#grp-name" ).text("");
				$( ".cdropdown5" ).addClass("hide");
				$( "#edit-group-name" ).addClass("hide");
				$( "#group" ).addClass("hide");
				$( "#pp-act-grp-div" ).addClass("hide");
				$( "#group-img" ).attr("src", "").addClass("hide");
				
				setTimeout( function() {
					$(".custom-nav i.left").fadeIn( "fast" );	// Show Profile Picture
					$(".custom-nav img.left").fadeIn( "fast" );	// Show Profile Picture
				}, 500);
				
				// Make an Ajax Call for Creating a Personal Message
				$.post( "./action.php?act=pm", { uid: uid, ui: userid, token: token, username: username}).done( function( data ){
					var response = jQuery.parseJSON( data );
					token = response.token;
					if( response.check_stat !== "error" ){ // Check if there are any errors	
						$( "#msg-content" ).html( "" );	// Clear the chat page
						$( "#loading" ).removeClass( "hide" );	// Show the loading element
						var chat_name = response.chat_name; // Chat name
						var time = getTime();	// Current Time
						chat = response.chat_id;	// Update Chat ID

						$.post( "./action.php?act=chat-details", { userid: userid, username: username, token: token, chat: chat }, function( data ) {
							var response = jQuery.parseJSON( data );
							if(response.error != "") {
								Materialize.toast( response.error, 4000 );
								setTimeout(function() {
									location.reload();
								},3000);
							} else {
								var ppl_num = response.user_chips.length + 1;
								t = ppl_num;
								t_1 = t;
								$( "#modal1 p" ).html( response.user_list );
								$.each( response.user_chips, function( i , val ) {
									$( ".invite-reveal .inv-users" ).append( "<div class='b-chip' attr-id='" + val + "'>" + response.user_chips_names[i] + "</div>" );
								});
								$( ".invite-reveal .inv-capacity" ).html( ppl_num + "/" + max_capacity );
								$("#dropdown3").html(response.dropdown);
								rmtyp = response.roomtype;
							}
						});	
						
						$.post( "./action.php?act=load-chat-settings", { chat: chat, username: username, userid: userid, token: token } ).done( function( data2 ) {
							$(".card .chat-settings").html(data2);
							$('.profile-image-thumb a').simpleLightbox();
							$(".profile-image-thumb img").on("load", function(){
								var element = $(this)[0];
								var element2 = $(this);
								if(element.naturalHeight >= element.naturalWidth) {
									element2.css("width", "100px");
								} else {
									element2.css("height", "100px");
								}
							});
						});
						
						$( ".chat-nav-un" ).html( chat_name );	// Load Chat Name
						
						setTimeout( function() {
							$( "#search" ).val( "" );	// Reset Search Input Value
							$( ".new-msg" ).addClass("hide");	// Hide Search User Input
							$( ".clear-btn" ).fadeOut();	// Hide Go Back Button ("X" Button)
							$( ".close-rev" ).fadeOut( "fast" );
							$( "#add-btn" ).fadeIn();	// Show Add Button ("+" Button)
							$( "#new-msg" ).fadeIn();
							$( "#new-grp" ).fadeIn();
							$( ".search-res" ).hide();	// Hide Search Results Content
							$( ".main-window" ).removeClass( "hide" );	// Show Main Page
							$( ".custom-nav i.left" ).fadeIn( "fast" );	// Show Avatar
							$( ".custom-nav h5.nav-un" ).fadeIn( "fast" );	// Show Username			
						}, 500 );	
						$.post( "./action.php?act=chat-number", { token: token, user_id: userid, chatid: chat, username: username} ).done( function( num_data ) {
							var num_response = jQuery.parseJSON( num_data );
								if( num_response.chat >= 0 ) {
									$( ".not-any" ).addClass("hide");
									$( "#pmlist .msgs " ).removeClass("hide");
									if( response.status == 0 && $( "li#" + chat ).length == 0 ) {
										if( response.pic_exist == 1 ) {
											$( "ul.msgs" ).prepend( "<li id='" + chat + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 hide'>person</i><img id='chat-img-main' class='circle chat-list-photo' src='" + response.picture + "'><span class='title truncate'>" + chat_name + "</span><p><div class='last_message truncate'><br></div><div class='last_message_time'>" + time + "</div></p></li>" );
										} else {
											$( "ul.msgs" ).prepend( "<li id='" + chat + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2'>person</i><img id='chat-img-main' class='circle chat-list-photo hide'><span class='title truncate'>" + chat_name + "</span><p><div class='last_message truncate'><br></div><div class='last_message_time'>" + time + "</div></p></li>" );
										}
									}
								}
							});
						if( response.status != 0 ) {
							
							if( response.pic_exist == 1 ) {
								$( "ul.msgs" ).prepend( "<li id='" + chat + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 hide'>person</i><img id='chat-img-main' class='circle chat-list-photo' src='" + response.picture + "'><span class='title truncate'>" + chat_name + "</span><p><div class='last_message truncate'><br></div><div class='last_message_time'>" + time + "</div></p></li>" );
							} else {
								$( "ul.msgs" ).prepend( "<li id='" + chat + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2'>person</i><img id='chat-img-main' class='circle chat-list-photo hide'><span class='title truncate'>" + chat_name + "</span><p><div class='last_message truncate'><br></div><div class='last_message_time'>" + time + "</div></p></li>" );
							}
							$.post( "./action.php?act=update-user-stat", { userid: userid, chatid: chat } );
						}
						
						if($(".online-users").hasClass("opened")) {
								$(".online-users").removeClass("opened");
								closeSideCard( ".online-users" );
						}
						
						if($(".friends").hasClass("opened")) {
								$(".friends").removeClass("opened");
								closeSideCard( ".friends" );
						}
						$("#modal1").modal("close");
						$( ".chat-reveal" ).css({ display: "block", opacity: 0 }).velocity( "stop", !1 ).velocity({ opacity: 1 },{ duration: 250, queue: !1, easing: "easeInOutQuad", complete:function(){$("#send-msg").focus();}});
						
						// Make an Ajax Call for loading previous messages
						$.post( "./action.php?chat=" + chat, function( data ) {
							setTimeout( function() {
								$( "#loading" ).addClass( "hide" );	// Hide the loading element
								$( "#msg-content" ).html( data );	// Load the previous messages
								$(".map-msg").ready(function() {
									for(map_i = 0; map_i < $(".map-msg").length; map_i++) {
										initMap($(".map-msg[num='"+map_i+"']")[0], 1, $(".map-msg[num='"+map_i+"']").attr("lat"), $(".map-msg[num='"+map_i+"']").attr("lng"), $(".map-msg[num='"+map_i+"']").attr("acc"));
									}
								});
								scrollToBottom("#msg-content");
							}, 400 );	
						});
					}
				});	
			});	

	$( "body" ).on( "click", "#edit-profile-images", function( ev ) {		
		ev.preventDefault();
		setTimeout( function() {
			$(".custom-nav i.left").fadeIn( "fast" );	// Show Profile Picture
			$(".custom-nav img.left").fadeIn( "fast" );	// Show Profile Picture
		}, 500);
						
		setTimeout( function() {
			$( "#search" ).val( "" );	// Reset Search Input Value
			$( ".new-msg" ).addClass("hide");	// Hide Search User Input
			$( ".clear-btn" ).fadeOut();	// Hide Go Back Button ("X" Button)
			$( ".close-rev" ).fadeOut( "fast" );
			$( "#add-btn" ).fadeIn();	// Show Add Button ("+" Button)
			$( "#new-msg" ).fadeIn();
			$( "#new-grp" ).fadeIn();
			$( ".search-res" ).hide();	// Hide Search Results Content
			$( ".main-window" ).removeClass( "hide" );	// Show Main Page
			$( ".custom-nav i.left" ).fadeIn( "fast" );	// Show Avatar
			$( ".custom-nav h5.nav-un" ).fadeIn( "fast" );	// Show Username			
		}, 500 );
		if($(".online-users").hasClass("opened")) {
			$(".online-users").removeClass("opened");
			closeSideCard( ".online-users" );
		}
		if($(".friends").hasClass("opened")) {
			$(".friends").removeClass("opened");
			closeSideCard( ".friends" );
		}
		$( ".profile-picture-reveal" ).css({ display: "block", opacity: 0 }).velocity( "stop", !1 ).velocity({ opacity: 1 },{ duration: 250, queue: !1, easing: "easeInOutQuad", complete:function(){$("#send-msg").focus();}});
	});	
	
	$( "body" ).on( "click", "#user-list-btn", function( ev ) {
		ev.preventDefault();
		if(!$(".online-users").hasClass("opened")) {
			$("#online-loading").removeClass("hide");
			$(".online-users").addClass("opened");
			openSideCard( ".online-users" );
			$.post( "./action.php?act=online-users", {page: 1}).done(function( respond ) {
				var response = jQuery.parseJSON( respond );
				$("#online-loading").addClass("hide");
				$("#online-user-num").html(response.title);
				if(response.stat == 1) {
					$( ".online-user-list" ).html( response.users ); // Show Search Results
					$( ".pagi" ).html( response.pagination ); // Show Search Results
				} else {
					$( ".online-user-list" ).html( response.users );
				}	
			});
		}	
	});
	
	$("body").on("click", "#user-profile-div", function() {
		var target_id = $(this).attr("attr-id");
		$.post( "./action.php?act=load-user-profile", {userid: target_id}).done( function( data2 ) {
			if(data2 == -1 || data2 == 0) {
				Materialize.toast( "An error occured.", 4000 );
			} else {
				openSideCard(".chat-settings");
				chat_side = true;
				$(".card .chat-settings").html(data2);
				$('.profile-image-thumb a').simpleLightbox();
				$(".profile-image-thumb img").on("load", function(){
					var element = $(this)[0];
					var element2 = $(this);
					if(element.naturalHeight >= element.naturalWidth) {
						element2.css("width", "100px");
					} else {
						element2.css("height", "100px");
					}
				});
			}
		});
	});
	
	$("body").on("click", ".friends-pagination li", function(e){
		 e.preventDefault();
		 if(!$(this).hasClass("active")) {
			 $("#friend-loading").removeClass("hide");
			 var page = $("a", this).attr("attr-page");
			 if(page > 0) {					 
				$.post( "./action.php?act=friends", {page: page, userid: userid, token: token, username: username}).done(function( respond ) {
					$("#friend-loading").addClass("hide");
					var response = jQuery.parseJSON( respond );
					if(response.stat == 1) {
						$( ".online-user-list" ).html( response.users ); // Show Search Results
						$( ".pagi" ).html( response.pagination ); // Show Search Results
					} else {
						$( ".online-user-list" ).html( response.users );
					}		
				});
			 }
		 }
	 });
	 
	 $("body").on("click", ".refresh-friend-list", function() {
		$("#friend-loading").removeClass("hide");
		var page = 1;
		$.post( "./action.php?act=friends", {page: page, userid: userid, token: token, username: username}).done(function( respond ) {
			$("#friend-loading").addClass("hide");
			var response = jQuery.parseJSON( respond );
					if(response.stat == 1) {
						$( ".friend-list" ).html( response.users ); // Show Search Results
						$( ".pagi-friend" ).html( response.pagination ); // Show Search Results
					} else {
						$( ".friend-list" ).html( response.users );
					}	
		});
	 });
	
	$( "body" ).on( "click", "#friends-btn", function( ev ) {
		ev.preventDefault();
		if(!$(".friends").hasClass("opened")) {
			$("#friend-loading").removeClass("hide");
			$(".friends").addClass("opened");
			openSideCard( ".friends" );
			var page = 1;
			$.post( "./action.php?act=friends", {page: page, userid: userid, token: token, username: username}).done(function( respond ) {
				var response = jQuery.parseJSON( respond );
				$("#friend-loading").addClass("hide");
						if(response.stat == 1) {
							$( ".friend-list" ).html( response.users ); // Show Search Results
							$( ".pagi-friend" ).html( response.pagination ); // Show Search Results
						} else {
							$( ".friend-list" ).html( response.users );
						}	
			});
		}		
	});
	
	$( "body" ).on( "click", "#add-friend", function( ev ) {
		ev.preventDefault();
		var friend_id = $(this).attr("attr-id");
		$.post( "./action.php?act=add-friend", { userid: userid, token: token, username: username, target_id: friend_id }, function( data ) {
			if(data == "error1") {
				Materialize.toast( "Invalid Username.", 4000 );
			} else if (data == "error2") {
				Materialize.toast( "Invalid Token.", 4000 );
			} else if (data == "done") {
				$("#add-friend[attr-id='"+friend_id+"']").attr("id", "remove-friend").html("Remove Friend");
				Materialize.toast( "Successfully added.", 4000 );
			} else {
				Materialize.toast( "An error occured.", 4000 );
			}
		});	
	});
	
	$( "body" ).on( "click", "#remove-friend", function( ev ) {
		ev.preventDefault();
		var friend_id = $(this).attr("attr-id");
		$.post( "./action.php?act=remove-friend", { userid: userid, token: token, username: username, target_id: friend_id }, function( data ) {
			if(data == "error1") {
				Materialize.toast( "Invalid Username.", 4000 );
			} else if (data == "error2") {
				Materialize.toast( "Invalid Token.", 4000 );
			} else if (data == "done") {
				$("#remove-friend[attr-id='"+friend_id+"']").attr("id", "add-friend").html("Add Friend");
				Materialize.toast( "Successfully removed.", 4000 );
			} else {
				Materialize.toast( "An error occured.", 4000 );
			}
		});	
	});
	
	$( "body" ).on( "click", "#clear-chat", function( ev ) {
		ev.preventDefault();
		$.post( "./action.php?act=clear-chat", { user_id: userid, token: token, chat_id: chat }, function( data ) {
			if( data !== "error" ) {
				token = data;
				$( ".chat-msgs*" ).remove();	// Clear Chat
			} else {
				Materialize.toast( "Error.", 4000 );
			}
			
		});
	});
	
	$( "body" ).on( "click", ".kick", function( ev ) {
		ev.preventDefault();
		
		if( websocket.readyState == 1 ) {	// Check the connection
			var target = $( this ).attr( "attr-id" );	// Target Username
			var kick = {
				name: username,	// Username
				room: chat,		// Crypted Chat ID
				iduser: userid,	// User ID
				msgtype: "0",	// Kick Type
				token: token,	// Token (For Security)
				target: target,	// Target User
				rmtype: rmtyp	// Room Type
			};
					
			var json_kick = JSON.stringify( kick );
					
			websocket.send( json_kick );
			$( ".invite-reveal .b-chip" ).filter( '[attr-id="' + target + '"]' ).remove();
			t = t - 1;
			$( ".inv-capacity" ).html( t + "/" + max_capacity ); // Update the Free Space
		} else { // If there is no connection
			Materialize.toast( "Could not connect to WebSocket server.", 4000 );
		}	
	});
	
	// On "X" Button Click in "New Group" Page
	$( "body" ).on( "click", ".close-rev", function() {
		$( this ).fadeOut( "fast" );
		$( "#grp-s" ).val( "" ).removeClass( "valid" );	// Reset Search Input
		$( "#grp" ).val( "" ).removeClass( "valid" );	// Reset Group Name
		$( ".grp-search-content" ).hide().html( "" );	// Remove the Search Results
		$( ".card-reveal .c-chip" ).remove();	// Remove Chips
		array = "";
		i = 1;
		$( ".card-reveal" ).css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {$(this).css("display", "none");$( "#add-btn" ).fadeIn( "fast" ); $( "#new-msg" ).fadeIn( "fast" );$( "#new-grp" ).fadeIn( "fast" );$( "#friends-btn" ).fadeIn( "fast" );}});
		setTimeout( function() { $( ".capacity" ).html( i + "/" + max_capacity ); }, 300 );	// Reset Capacity
	 });	
	 
	$( "body" ).on( "click", "#user-settings", function( ev ) {
		ev.preventDefault();
		openSideCard( ".user-settings" );
	});
	
	$( "body" ).on( "click", ".chat-reveal #chat-main-rev", function( ev ) {
		ev.preventDefault();
		if( chat_side == false ) {
			var body_width = $( "body" ).width();
			var width = $( ".chat-settings" ).width() + 48;
			var chat_width = $( ".chat-reveal" ).width();
			var net_width = chat_width - width;
			if(body_width > 950) {
				$("#msg-content").velocity( 
						{ width: net_width }, 
						{ duration: 500, queue: !1, easing: "easeInOutQuad" }
				);
				$(".msg-box").velocity( 
						{ width: net_width }, 
						{ duration: 500, queue: !1, easing: "easeInOutQuad" }
				);
			}
			openSideCard( ".chat-settings" );
			chat_side = true;
		}
	});
	
	$( "body" ).on( "click", ".chat-reveal #chat-img-main-rev", function( ev ) {
		ev.preventDefault();
		if( chat_side == false ) {
			var body_width = $( "body" ).width();
			var width = $( ".chat-settings" ).width() + 48;
			var chat_width = $( ".chat-reveal" ).width();
			var net_width = chat_width - width;
			if(body_width > 950) {
				$("#msg-content").velocity( 
						{ width: net_width }, 
						{ duration: 500, queue: !1, easing: "easeInOutQuad" }
				);
				$(".msg-box").velocity( 
						{ width: net_width }, 
						{ duration: 500, queue: !1, easing: "easeInOutQuad" }
				);
			}
			openSideCard( ".chat-settings" );
			chat_side = true;
		}
	});

	$( "body" ).on("click", "#share-photo", function() {
		$( ".share-photo-files" ).trigger( "click" );
	}).on("click", "#share-video", function() {
		$( ".share-video-input" ).trigger( "click" );
	}).on("click", "#share-file", function() {
		$( ".share-file" ).trigger( "click" );
	}).on("click", "#share-music", function() {
		$( ".share-music-input" ).trigger( "click" );
	}).on("click", "#share-location", function() {
		$("#modal51").modal("open");
	}).on("mouseover", "#share-photo", function() {
		$(".photo-hover").removeClass("hide");
	}).on("mouseout", "#share-photo", function() {
		$(".photo-hover").addClass("hide");
	}).on("mouseover", "#share-video", function() {
		$(".video-hover").removeClass("hide");
	}).on("mouseout", "#share-video", function() {
		$(".video-hover").addClass("hide");
	}).on("mouseover", "#share-file", function() {
		$(".file-hover").removeClass("hide");
	}).on("mouseout", "#share-file", function() {
		$(".file-hover").addClass("hide");
	}).on("mouseover", "#share-music", function() {
		$(".music-hover").removeClass("hide");
	}).on("mouseout", "#share-music", function() {
		$(".music-hover").addClass("hide");
	}).on("mouseover", "#share-location", function() {
		$(".location-hover").removeClass("hide");
	}).on("mouseout", "#share-location", function() {
		$(".location-hover").addClass("hide");
	});
	
	$("body").on("click",".share-img-list img",function() {
		var src = $(this).attr("src");
		$(".share-img-preview").attr("src", src);
	}).on("click",".share-video-list i",function() {
		var name = $(this).attr("name");
		$("#modal3 #vid-name").html(name);
	}).on("click",".share-file-list i",function() {
		var name = $(this).attr("name");
		$("#modal4 #file-name").html(name);
	}).on("click",".share-music-list i",function() {
		var name = $(this).attr("name");
		$("#modal5 #music-name").html(name);
	});
	
	$( ".share-photo-files" ).change( function() {
		if(this.files.length>0) {
			var share_i = 0;
			$(".share-img-list").html("");
			$(".share-img-preview").val("");
			if(this.files.length <= max_photo) {
				$('#modal2').modal("open");
				if (typeof (FileReader) != "undefined") {				
					$($(this)[0].files).each(function () {
						var file = $(this);
						var reader = new FileReader();
						reader.onload = function (e) {
							if(share_i == 0) {
								$(".share-img-preview").attr("src", e.target.result);
								share_i++;
							}
							$(".share-img-list").append("<div class='share-img-list-div'><img src='" + e.target.result + "' /></div>");
							$(".share-img-list").perfectScrollbar("update");
						}
						reader.readAsDataURL(file[0]);
					});
				} else {
					Materialize.toast( "Your browser does not support image previews.", 4000 );
				}
			} else {
				if(max_photo == 1) {
					Materialize.toast( "You cannot send more than 1 file.", 4000 );
				} else {
					Materialize.toast( "You cannot send more than " + max_photo + " files.", 4000 );
				}
				
			}
		}
	});
	
	$( ".share-video-input" ).change( function() {
		if(this.files.length>0) {
			$(".share-video-list").html("");
			$(".video-file").html("");
			if(this.files.length <= max_video) {
				var names = new Array();
				for (i = 0; i < $(this)[0].files.length; i++) {
					names.push(escString($(this)[0].files[i].name));
				}
				
				$.each(names, function(i, val) {
					if(i == 0) {
						$(".video-file").html("<p><i class='material-icons large'>movie</i></p><p id='vid-name'id='vid-name'>"+val+"</p>");
					}
					$(".share-video-list").append("<div class='share-video-list-div'><p><i name='"+val+"' class='material-icons large'>movie</i></p></div>");
					$(".share-video-list").perfectScrollbar("update");
				});
				$('#modal3').modal("open");

			} else {
				if(max_video == 1) {
					Materialize.toast( "You cannot send more than 1 file.", 4000 );
				} else {
					Materialize.toast( "You cannot send more than " + max_video + " files.", 4000 );
				}
				
			}
		}
	});
	
	$( ".share-file" ).change( function() {
		if(this.files.length>0) {
			$(".share-file-list").html("");
			$(".file-file").html("");
			if(this.files.length <= max_file) {
				var names = new Array();
				for (i = 0; i < $(this)[0].files.length; i++) {
					names.push(escString($(this)[0].files[i].name));
				}
				
				$.each(names, function(i, val) {
					if(i == 0) {
						$(".file-file").html("<p><i class='material-icons large'>insert_drive_file</i></p><p id='file-name'>"+val+"</p>");
					}
					$(".share-file-list").append("<div class='share-file-list-div'><p><i name='"+val+"' class='material-icons large'>insert_drive_file</i></p></div>");
					$(".share-file-list").perfectScrollbar("update");
				});
				$('#modal4').modal("open");

			} else {
				if(max_file == 1) {
					Materialize.toast( "You cannot send more than 1 file.", 4000 );
				} else {
					Materialize.toast( "You cannot send more than " + max_file + " files.", 4000 );
				}
				
			}
		}

	});
	
	$( ".share-music-input" ).change( function() {
		if(this.files.length>0) {
			$(".share-music-list").html("");
			$(".music-file").html("");
				var names = new Array();
				for (i = 0; i < $(this)[0].files.length; i++) {
					names.push(escString($(this)[0].files[i].name));
				}
				
				$.each(names, function(i, val) {
					if(i == 0) {
						$(".music-file").html("<p><i class='material-icons large'>headset</i></p><p id='music-name'id='music-name'>"+val+"</p>");
					}
					$(".share-music-list").append("<div class='share-music-list-div'><p><i name='"+val+"' class='material-icons large'>headset</i></p></div>");
					$(".share-music-list").perfectScrollbar("update");
				});
				$('#modal5').modal("open");
		}
	});
	
	current_pic = $("#pp-img").attr("src");
	
	$('#modal2').modal({complete: function() {
		$( ".share-photo-files" ).val("");
	}});
	$('#modal3').modal({complete: function() { 
		$( ".share-video-input" ).val("");
	}});
	$('#modal4').modal({complete: function() { 
		$( ".share-file" ).val("");
	}});
	$('#modal5').modal({complete: function() { 
		$( ".share-music" ).val("");
	}});
	$('#modal51').modal({
		ready: function() {
			initMap($("#modal-map")[0]);
		},
		complete: function() { 
			$( "#modal-map" ).html("");
			$("#share-location-btn").addClass("disabled");
		}
	});
	
	$("body").on("click","#share-upload",function(){
		if( websocket.readyState == 1 ) {
			$(".share-photo-token").val(token);
			$(".share-photo-userid").val(userid);
			$(".share-photo-username").val(username);
			$(".share-photo-room").val(chat);
			var time = getTime();
			var img_i = 0;
			var numbers_img_i = new Array();
			var numbers_k = new Array();
			var names = new Array();
			for (i = 0; i < $(".share-photo-files")[0].files.length; i++) {
				names.push(escString($(".share-photo-files")[0].files[i].name));
			}
			
			window.preview = function (input) {
				if (input.files && input.files[0]) {
					$(input.files).each(function () {
						var reader = new FileReader();
						reader.readAsDataURL(this);
						reader.onload = function (e) {
							$("#msg-content").append("<div class='chat-msgs'><div class='my-msgs'><div class='my-usr-msg'><a class='image-link' num='"+img_i+"'><div class='image-thumb'><img class='shared-images temp' src='"+e.target.result+"' alt='' title=''/></div></a></div><div class='msg-time left'>" + time + "</div><i class='material-icons right msg-icon tiny' num='"+k+"'>schedule</i></div></div>");			
							numbers_img_i.push(l);
							numbers_k.push(k);
							img_i++;;
							k++;
							scrollToBottom("#msg-content"); // Go to bottom
						}
					});
				}
			}		
			
			
			
			$("#share_photo_form").ajaxForm({
				url: "./action.php?act=share-photo",
				type: "POST",
				dataType: "json",
				beforeSend: function() {
					preview($(".share-photo-files")[0]);
				},
				success:function(data){
					if(!$.isEmptyObject(data.err)) {
						Materialize.toast( data.err, 4000 );
					} else {
						$.each(data.stat, function(i, val) {
							if(val == 0) {
								Materialize.toast( data.error[i], 4000 );
								$( ".image-link[num=" + numbers_img_i[i] + "]" ).parent().next().next().last().text( "priority_high" ).css( "color", "red" );
								$( ".image-link[num=" + numbers_img_i[i] + "] .image-thumb" ).append("<div class='image-error'>Upload Again</div>");
							} else {
								setTimeout( function() {
									$( ".image-link[num=" + numbers_img_i[i] + "] img").attr("src", data.file[i]);
									$( ".image-link[num=" + numbers_img_i[i] + "]").attr("href", data.file[i]);
									var send_file = {
										name: username,	// Username
										iduser: userid,	// User ID
										msgtype: "8",	// Send Photo
										token: token,	// Token (For Security)
										share_media: data.file[i],	// Chat Picture
										room: chat,
										rmtype: rmtyp,
										k: numbers_k[i],
										file: "img",
										time: time,
										file_names: names[i],
									};
									var json_send_file = JSON.stringify( send_file );							
									websocket.send( json_send_file );
									$(".image-link").simpleLightbox();
								}, i * 100 );
								
							}
						});
						
						$(".share-img-list").html("");
						$(".share-img-preview").val("");
					}
				},
				error:function(e){
					$.each(numbers_k, function(i, val) {
						$( ".image-link[num=" + numbers_img_i[i] + "]" ).parent().next().next().last().text( "priority_high" ).css( "color", "red" );
						$( ".image-link[num=" + numbers_img_i[i] + "] .image-thumb" ).append("<div class='image-error'>Upload Again</div>");
					});
				}
			}).submit();
		} else {
			Materialize.toast( "Could not connect to WebSocket server.", 4000 );
		}
		$(".share-photo-files").val("");
	});
	
	$("body").on("click", ".video-link", function(e){
		e.preventDefault();
		var vid_disp_height = $(window).height() - 20;
		var vid_disp_width = $(window).width() - 20;
		$.simpleLightbox.open({content: "<div id='vid-disp' style='height: " + vid_disp_height + "px; width: " + vid_disp_width + "px; position: relative;'><video class='video-display' src='"+$("video", this).attr("src")+"' type='"+$("video", this).attr("type")+"' controls autoplay loop><div class='file-bg'><a href='"+$("video", this).attr("src")+"'><div class='file-bg-text'>Download</div></a></div></video></div>"});
	});
	
	$("body").on("click", ".download-file", function() {
		window.location.href = "./action.php?download=" + btoa(unescape(encodeURIComponent($(this).attr("href")))) + "&name=" + btoa(unescape(encodeURIComponent($(this).attr("name")))) + "&v=" + v.getTime();
	}),
	
	$("body").on("click","#video-upload",function(){
		if( websocket.readyState == 1 ) {
			$(".share-video-token").val(token);
			$(".share-video-userid").val(userid);
			$(".share-video-username").val(username);
			$(".share-video-room").val(chat);
			var time = getTime();
			var numbers_j = new Array();
			var numbers_k = new Array();
			var names = new Array();
			for (i = 0; i < $(".share-video-input")[0].files.length; i++) {
				names.push(escString($(".share-video-input")[0].files[i].name));
			}

			$("#share_video_form").ajaxForm({
				url: "./action.php?act=share-video",
				type: "POST",
				dataType: "json",
				beforeSend: function() {
					$.each(names, function(i, val) {
						$("#msg-content").append("<div class='chat-msgs'><div class='my-msgs'><div class='my-usr-msg'><a class='video-link' num='"+j+"'><div class='image-thumb'><video class='shared-vid' src='' type='video/mp4'></video></div></a></div><div class='msg-time left'>" + time + "</div><i class='material-icons right msg-icon tiny' num='"+k+"'>schedule</i></div></div>");
						numbers_j.push(j);
						numbers_k.push(k);
						j++;
						k++;
					});
					scrollToBottom("#msg-content"); // Go to bottom
				},
				success:function(data){
					if(!$.isEmptyObject(data.err)) {
						Materialize.toast( data.err, 4000 );
					} else {
						$.each(data.stat, function(i, val) {
							if(val == 0) {
								Materialize.toast( data.error[i], 4000 );
								$( ".video-link[num=" + numbers_j[i] + "]" ).parent().next().next().last().text( "priority_high" ).css( "color", "red" );
								$( ".video-link[num=" + numbers_j[i] + "] .image-thumb" ).html("").append("<div class='file-bg'><div class='file-bg-text'>Error</div></div>").parent().removeClass("video-link");
							} else {
								setTimeout( function() {
									if(data.type[i] == "video/mp4" || data.type[i] == "video/webm" || data.type[i] == "video/ogg") {
										$(".video-link[num="+numbers_j[i]+"] video").attr("src", data.file[i]);
										$(".video-link[num="+numbers_j[i]+"] video").attr("type", data.type[i]);
										$( ".video-link[num="+numbers_j[i]+"] ").attr("href", data.file[i]);
										$( ".video-link[num="+numbers_j[i]+"] .image-thumb" ).append("<div class='video-play'><i class='material-icons'>play_circle_outline</i></div>");
									} else {
										$(".video-link[num="+numbers_j[i]+"]").parent().append("<div class='file-bg'><a href='"+data.file[i]+"'><div class='file-bg-text'>Download</div></a></div>")
										$(".video-link[num="+numbers_j[i]+"]").remove();
									}
									var send_file = {
										name: username,	// Username
										iduser: userid,	// User ID
										msgtype: "8",	// Send Photo
										token: token,	// Token (For Security)
										share_media: data.file[i],	// Chat Picture
										room: chat,
										rmtype: rmtyp,
										k: numbers_k[i],
										file: "vid",
										mime: data.type[i],
										file_names: names[i],
										time: time
									};
													
									var json_send_file = JSON.stringify( send_file );							
									websocket.send( json_send_file );
								}, i * 100);
							}
						});
					}
				},
				error:function(e){
					$.each(names, function(i, val) {
						$( ".video-link[num=" + numbers_j[i] + "]" ).parent().next().next().last().text( "priority_high" ).css( "color", "red" );
						$( ".video-link[num=" + numbers_j[i] + "] .image-thumb" ).html("").append("<div class='file-bg'><div class='file-bg-text'>Upload Again</div></div>").parent().removeClass("video-link");
					});
				}
			}).submit();
		} else {
			Materialize.toast( "Could not connect to WebSocket server.", 4000 );
		}
		$(".share-video-input").val("");
		$(".video-file").html("");
	});
	
	$("body").on("click","#file-upload",function(){
		if( websocket.readyState == 1 ) {
			$(".share-file-token").val(token);
			$(".share-file-userid").val(userid);
			$(".share-file-username").val(username);
			$(".share-file-room").val(chat);
						
			var names = new Array();
			for (i = 0; i < $(".share-file")[0].files.length; i++) {
				names.push(escString($(".share-file")[0].files[i].name));
			}
			var time = getTime();
			var numbers_l = new Array();
			var numbers_k = new Array();
				
			$("#share_file_form").ajaxForm({
				url: "./action.php?act=share-file",
				type: "POST",
				dataType: "json",
				beforeSend: function() {
					$.each(names, function(i, val) {
						$("#msg-content").append("<div class='chat-msgs'><div class='my-msgs'><div class='my-usr-msg fls' num='"+l+"'><p><i href='' name='' class='material-icons clickable shared-file large download-file'>archive</i></p><p class='file-name'>" + val + "</p></div><div class='msg-time left'>" + time + "</div><i class='material-icons right msg-icon tiny' num='"+k+"'>schedule</i></div></div>");
						numbers_l.push(l);
						numbers_k.push(k);
						l++;
						k++;
					});
					scrollToBottom("#msg-content"); // Go to bottom
				},
				success:function(data){
					if(!$.isEmptyObject(data.err)) {
						Materialize.toast( data.err, 4000 );
					} else {
						$.each(data.stat, function(i, val) {
							if(val == 0) {
								Materialize.toast( data.error[i], 4000 );
								$( ".fls[num=" + numbers_l[i] + "]" ).next().next().last().text( "priority_high" ).css( "color", "red" );
								$( ".fls[num=" + numbers_l[i] + "] i" ).html("error").removeClass("download-file").removeClass("clickable").css({"color": "#f44336", "cursor": "default"});
							} else {
								setTimeout( function() {
									$( ".fls[num=" + numbers_l[i] + "] .download-file" ).attr("href", data.file[i]);
									$( ".fls[num=" + numbers_l[i] + "] .download-file" ).attr("name", names[i]);
									var send_file = {
										name: username,	// Username
										iduser: userid,	// User ID
										msgtype: "8",	// Send Photo
										token: token,	// Token (For Security)
										share_media: data.file[i],	// Chat Picture
										room: chat,
										rmtype: rmtyp,
										k: numbers_k[i],
										file: "file",
										file_names: names[i],
										time: time
									};
									var json_send_file = JSON.stringify( send_file );							
									websocket.send( json_send_file );
								}, i * 100);
							}
						});
					}
				},
				error:function(e){
					$.each(names, function(i, val) {
						$( ".fls[num=" + numbers_l[i] + "]" ).next().next().last().text( "priority_high" ).css( "color", "red" );
						$( ".fls[num=" + numbers_l[i] + "] i" ).html("error").removeClass("download-file").removeClass("clickable").css({"color": "#f44336", "cursor": "default"});
					});
				}
			}).submit();
		} else {
			Materialize.toast( "Could not connect to WebSocket server.", 4000 );
		}
		$(".share-file-input").val("");
	});
	
	$("body").on("click","#music-upload",function(){
		if( websocket.readyState == 1 ) {
			$(".share-music-token").val(token);
			$(".share-music-userid").val(userid);
			$(".share-music-username").val(username);
			$(".share-music-room").val(chat);
			var time = getTime();
			var numbers_m = new Array();
			var numbers_k = new Array();
			var names = new Array();
			for (i = 0; i < $(".share-music-input")[0].files.length; i++) {
				names.push(escString($(".share-music-input")[0].files[i].name));
			}
			
			$("#share_music_form").ajaxForm({
				url: "./action.php?act=share-music",
				type: "POST",
				dataType: "json",
				beforeSend: function() {
					$.each(names, function(i, val) {
						$("#msg-content").append("<div class='chat-msgs'><div class='my-msgs'><div class='my-usr-msg music-link' num='"+m+"'><p><i href='' name='' class='material-icons clickable shared-file large download-file'>headset</i></p><p class='file-name'>" + names[i] + "</p><p class='shared-music hide'><audio src='' type='audio/mp3' controls></audio></p><div class='msg-time left'>" + time + "</div><i class='material-icons right msg-icon tiny' num='"+k+"'>schedule</i></div></div>");
						numbers_m.push(m);
						numbers_k.push(k);
						m++;
						k++;
					});
					scrollToBottom("#msg-content"); // Go to bottom
				},
				success:function(data){
						$.each(data.stat, function(i, val) {
							if(val == 0) {
								Materialize.toast( data.error[i], 4000 );
								$( ".music-link[num=" + numbers_m[i] + "]" ).next().next().last().text( "priority_high" ).css( "color", "red" );
								$( ".music-link[num=" + numbers_m[i] + "] i" ).html("error").removeClass("download-file").removeClass("clickable").css({"color": "#f44336", "cursor": "default"});
							} else {
								
									if(data.type[i] == "audio/mpeg" || data.type[i] == "audio/wav" || data.type[i] == "audio/ogg" || data.type[i] == "audio/mp3") {
										$(".music-link[num="+numbers_m[i]+"] audio").attr("src", data.file[i]);
										$(".music-link[num="+numbers_m[i]+"] audio").attr("type", data.type[i]);
										$(".music-link[num="+numbers_m[i]+"] .shared-music").removeClass("hide");
										$( ".music-link[num="+numbers_m[i]+"] .download-file").attr("href", data.file[i]);
										$( ".music-link[num="+numbers_m[i]+"] .download-file").attr("name", names[i]);
									} else {
										$( ".music-link[num="+numbers_m[i]+"] .download-file" ).attr("href", data.file[i]);
										$( ".music-link[num="+numbers_m[i]+"] .download-file" ).attr("name", names[i]);
										$( ".music-link[num="+numbers_m[i]+"] .shared-music" ).remove();
									}
									var send_file = {
										name: username,	// Username
										iduser: userid,	// User ID
										msgtype: "8",	// Send Photo
										token: token,	// Token (For Security)
										share_media: data.file[i],	// Chat Picture
										room: chat,
										rmtype: rmtyp,
										k: numbers_k[i],
										file: "music",
										file_names: names[i],
										mime: data.type[i],
										time: time
									};
													
									var json_send_file = JSON.stringify( send_file );							
									websocket.send( json_send_file );
								
							}
						});
				},
				error:function(e){
					$.each(names, function(i, val) {
						$( ".music-link[num=" + numbers_m[i] + "]" ).next().next().last().text( "priority_high" ).css( "color", "red" );
						$( ".music-link[num=" + numbers_m[i] + "] i" ).html("error").removeClass("download-file").removeClass("clickable").css({"color": "#f44336", "cursor": "default"});
					});
				}
			}).submit();
		} else {
			Materialize.toast( "Could not connect to WebSocket server.", 4000 );
		}
		$(".share-music-input").val("");
		$(".music-file").html("");
	});
	
	$("body").on("click","#share-location-btn",function(){
		if( websocket.readyState == 1 ) {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
					pos = {
						lat: position.coords.latitude,
						lng: position.coords.longitude,
						acc: position.coords.accuracy
					};
					var time = getTime();
					$("#msg-content").append("<div class='chat-msgs'><div class='my-msgs'><div class='my-usr-msg'><div class='map-msg-cover'></div><div class='map-msg' numm='"+k+"' lat='"+pos.lat+"' lng='"+pos.lng+"' acc='"+pos.acc+"'></div></div><div class='msg-time left'>"+time+"</div><i class='material-icons right msg-icon tiny'>done</i></div></div>");
					var send_file = {
							name: username,	// Username
							iduser: userid,	// User ID
							msgtype: "8",	// Send Photo
							token: token,	// Token (For Security)
							share_media: pos,	// Chat Picture
							room: chat,
							rmtype: rmtyp,
							k: k,
							file: "location",
							time: time
						};

					var json_send_file = JSON.stringify( send_file );							
					websocket.send( json_send_file );
					initMap($(".map-msg[numm='"+k+"']")[0], 1, pos.lat, pos.lng, pos.acc);
					scrollToBottom("#msg-content");
					k++;
				});
			} else {
				Materialize.toast( "Your browser doesn't support Geolocation.", 4000 );
			}
		}
	});
	
	 $( "body" ).on( "click", ".close-online-users", function() {
		if($(".online-users").hasClass("opened")) {
			$(".online-users").removeClass("opened");
			closeSideCard( ".online-users" );
		}		
	 });
	
	 $( "body" ).on( "click", ".close-friends", function() {
		if($(".friends").hasClass("opened")) {
			$(".friends").removeClass("opened");
			closeSideCard( ".friends" );
		}		
	 });
	
	$( "body" ).on( "click", ".custom-cont", function( e ) {
		if ($(e.target).closest( ".user-settings" ).length === 0 && $(".user-settings").css("display") != "none") {
			closeSideCard( ".user-settings" );
			
			setTimeout( function() {
				if( !jQuery.isEmptyObject( pic_prev )) {
					$( "#pp-img" ).attr( "src", pic_prev );
				} else {
					if($.isEmptyObject(current_pic)) {
						$( "#pp-img" ).attr( "src", "" ).addClass( "hide" );
						$( "i#pp" ).removeClass( "hide" );
						$( "#pp-act-div" ).addClass("hide");
					}		
				}
				$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});
				
				pic_prev = null;
			},500);
		}
		if ($(e.target).closest( ".online-users" ).length === 0) {
			if($(".online-users").hasClass("opened")) {
				closeSideCard( ".online-users" );
				$(".online-users").removeClass("opened");
			}		
		}
		if ($(e.target).closest( ".friends" ).length === 0) {
			if($(".friends").hasClass("opened")) {
				closeSideCard( ".friends" );
				$(".friends").removeClass("opened");
			}		
		}
	});	
	 
	$( "body" ).on("click", "#msg-content", function( e ) {
		
		if ($(e.target).closest( ".chat-settings" ).length === 0) {
			if( chat_side == true) {
				closeSideCard( ".chat-settings" );
				$( "#msg-content" ).velocity({ width: "100%" }, { duration: 300, easing: "easeInOutQuad"} );
				$( ".msg-box" ).velocity({ width: "100%" }, { duration: 300, easing: "easeInOutQuad"} );
				setTimeout( function() {
					if( !jQuery.isEmptyObject( pic_prev_chat )) {
						$( "#chat-img" ).attr( "src", pic_prev_chat );
					} else {
						$( "#chat-img" ).attr( "src", "" ).addClass( "hide" );
						$( "i#chat" ).removeClass( "hide" );
					}
					$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});
					
					pic_prev_chat = null;
				},500);
				chat_side = false;
			}
		}
	});	
	
	$("body").on("click", ".online-pagination li", function(e){
		e.preventDefault();
		if(!$(this).hasClass("active")) {
			var page = $("a", this).attr("attr-page");
			if(page > 0) {
				$("#online-loading").removeClass("hide");
				$.post( "./action.php?act=online-users", {page: page}).done(function( respond ) {
					var response = jQuery.parseJSON( respond );
					$("#online-loading").addClass("hide");
					$("#online-user-num").html(response.title);
					if(response.stat == 1) {
						$( ".online-user-list" ).html( response.users ); // Show Search Results
						$( ".pagi" ).html( response.pagination ); // Show Search Results
					} else {
						$( ".online-user-list" ).html( response.users );
					}	
				});
			}
		}
	});
	 
	$("body").on("click", ".refresh-list", function() {
		$("#online-loading").removeClass("hide");
		$.post( "./action.php?act=online-users", {page: 1}).done(function( respond ) {
			var response = jQuery.parseJSON( respond );
			$("#online-loading").addClass("hide");
			$("#online-user-num").html(response.title);
			if(response.stat == 1) {
				$( ".online-user-list" ).html( response.users ); // Show Search Results
				$( ".pagi" ).html( response.pagination ); // Show Search Results
			} else {
				$( ".online-user-list" ).html( response.users );
			}	
		});
	 });
	 
	 $("body").on( "click", ".close-user-settings", function() {
		closeSideCard( ".user-settings" );
		
		if( !jQuery.isEmptyObject( pic_prev )) {
			$( "#pp-img" ).attr( "src", pic_prev );
		} else {
			if($.isEmptyObject(current_pic)) {
				$( "#pp-img" ).attr( "src", "" ).addClass( "hide" );
				$( "i#pp" ).removeClass( "hide" );
				$( "#pp-act-div" ).addClass("hide");
			}
		}
		$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});
		
		pic_prev = null;
	 });
	 
	 $( "body" ).on( "click", ".close-chat-settings", function() {
		if( chat_side == true ) { 
			closeSideCard( ".chat-settings" );
			
			if( !jQuery.isEmptyObject( pic_prev_chat )) {
				$( "#chat-img" ).attr( "src", pic_prev_chat );
			} else {
				$( "#chat-img" ).attr( "src", "" ).addClass( "hide" );
				$( "i#chat" ).removeClass( "hide" );
			}
			$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});
			$( "#msg-content" ).velocity({ width: "100%" }, { duration: 300, easing: "easeInOutQuad"} );
			$( ".msg-box" ).velocity({ width: "100%" }, { duration: 300, easing: "easeInOutQuad"} );
			
			pic_prev_chat = null;
			chat_side = false;
		}
	 });
	
	 $( "body" ).on( "click", "#users", function( ev ) {
		 ev.preventDefault();
		 $("#modal1").modal("open");
	 });
	 
	 $( "body" ).on( "click", ".cdropdown4", function( ev ) {
		ev.preventDefault();
		$( ".change-pp" ).addClass( "active-pp" );
	 });
	 
	$( document ).click( function( e ) {
		if ($(e.target).closest( ".cdropdown4" ).length === 0) {
			$( ".change-pp" ).removeClass( "active-pp" );		
			
		} else if ($(e.target).closest( ".cdropdown5" ).length === 0) {
			$( ".chat-change-pp" ).removeClass( "active-pp" );
		}
	});
	
	$( "body" ).on( "click", "#upload-photo", function( ev ) {
		ev.preventDefault();
		$( "#upload-pp" ).trigger( "click" );
	});
	
	$( "body" ).on( "click", "#chat-upload-photo", function( ev ) {
		ev.preventDefault();
		if( open == true ) {
			$("#dropdown5").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});
			open = false;
			$( ".chat-change-pp" ).removeClass( "active-pp" );
		}
		$( "#upload-chat" ).trigger( "click" );
	});
	
	$( "body" ).on( "click", "#create-upload-photo", function( ev ) {
		ev.preventDefault();
		$( "#create-img" ).trigger( "click" );
	});
	
	$( "body" ).on( "click", "#remove-photo", function( ev ) {
		ev.preventDefault();
		$.post( "./action.php?act=remove-pp", { token: token, userid: userid } ).done( function( data ) {
			if( data != "error" ) {				
				$( "#pp-img" ).attr( "src", "" ).addClass( "hide" );
				$( "i#pp" ).removeClass( "hide" );			
				$( "#pp-img-main" ).attr( "src", "" ).addClass( "hide" );
				$( "i#pp-main" ).removeClass( "hide" );
				pic_prev = null;
				$( "#upload-pp" ).val( "" );
				$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});				
				$( "#pp-act-div" ).addClass("hide");
				current_pic = null;
				if( websocket.readyState == 1 ) {	// Check the connection

					var update_pp = {
						name: username,	// Username
						iduser: userid,	// User ID
						msgtype: "5",	// Change Photo
						token: token,	// Token (For Security)
						chat_img: ""	// Chat Picture
					};
								
					var json_update_pp = JSON.stringify( update_pp );							
					websocket.send( json_update_pp );
				} else { // If there is no connection
					Materialize.toast( "Could not connect to WebSocket server.", 4000 );
				}
			}
		});
	});
	
	$( "body" ).on( "click", "#create-remove-photo", function( ev ) {
		ev.preventDefault();
		$( "#c-group-img" ).attr( "src", "" ).addClass( "hide" );
		$( "i#c-group" ).removeClass( "hide" );			
		$( "#c-grp-img-remove" ).addClass( "hide" );
		$( "#create-img" ).val( "" );
	});
	
	$( "body" ).on( "click", "#chat-remove-photo", function( ev ) {
		ev.preventDefault();
		if( open == true ) {
			$("#dropdown5").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});
			open = false;
			$( ".chat-change-pp" ).removeClass( "active-pp" );
		}
		$.post( "./action.php?act=remove-chat", { token: token, userid: userid, chatid: chat, username: username } ).done( function( data ) {
			if( data != "error" ) {				
				$( "#group-img" ).attr( "src", "" ).addClass( "hide" );
				$( "i#group" ).removeClass( "hide" );			
				$( "#chat-img-main-rev" ).attr( "src", "" ).addClass( "hide" );
				$( "i#chat-main-rev" ).removeClass( "hide" ).html("group");
				$( ".main-window li#" + chat + " #chat-img-main" ).attr( "src", "" ).addClass( "hide" );
				$( ".main-window li#" + chat + " #chat-main" ).removeClass( "hide" );
				$( "#pp-act-grp-div" ).addClass("hide");
				
				pic_prev_chat = null;
				$( "#upload-chat" ).val( "" );
				
				if( websocket.readyState == 1 ) {	// Check the connection

					var update_group_pp = {
						name: username,	// Username
						iduser: userid,	// User ID
						msgtype: "6",	// Change Photo
						token: token,	// Token (For Security)
						chat_img: "",	// Chat Picture
						room: chat
					};
								
					var json_update_group_pp = JSON.stringify( update_group_pp );							
					websocket.send( json_update_group_pp );
				} else { // If there is no connection
					Materialize.toast( "Could not connect to WebSocket server.", 4000 );
				}
			}
		});
	});
	
	$( "#create-img" ).change( function() {
		if ( this.files && this.files[0] ) {
            var reader = new FileReader();
            reader.onload = function ( e ) {
                $( "#c-group-img" ).attr( "src", e.target.result ).removeClass( "hide" );
				$( "i#c-group" ).addClass( "hide" );
				$( "#c-grp-img-remove" ).removeClass( "hide" );
            }
            reader.readAsDataURL(this.files[0]);
        }
	});
	
	$( "body" ).on("change", "#upload-pp", function() {
		pic_prev = $( "#pp-img" ).attr( "src" );
		if ( this.files && this.files[0] ) {
            var reader = new FileReader();
            reader.onload = function ( e ) {
                $( "#pp-img" ).attr( "src", e.target.result ).removeClass( "hide" );
				$( "i#pp" ).addClass( "hide" );
				$( "#save-pp-ul" ).css("display", "block");
				Materialize.fadeInImage("#save-pp-ul");
				$( "#pp-act-div" ).removeClass( "hide" );
            }
            reader.readAsDataURL(this.files[0]);
        }
	});
	
	$( "body" ).on( "change", "#upload-chat", function() {
		pic_prev_chat = $( "#chat-img-main-rev" ).attr( "src" );
		if ( this.files && this.files[0] ) {
            var reader = new FileReader();
            reader.onload = function ( e ) {
                $( "#group-img" ).attr( "src", e.target.result ).removeClass( "hide" );
				$( "i#group" ).addClass( "hide" );
				$( "#save-chat-ul" ).css("display", "block");
				Materialize.fadeInImage( "#save-chat-ul" );
				$( "#pp-act-grp-div" ).removeClass( "hide" );
            }
            reader.readAsDataURL(this.files[0]);
        }
	});
	
	$( "body" ).on( "click", "#save-pp", function( ev ) {
		ev.preventDefault();
		var photo = $( "#upload-pp" ).prop( "files" )[0];
		var form_data = new FormData();                  
		var inputs = ["file", "token", "userid", "previous"];
		
		if( !jQuery.isEmptyObject( pic_prev )) {	
			var img_prev = pic_prev.split('/').pop();
		} else {
			var img_prev = "";
		}
		
		$.each(inputs, function (obj, val) {
			var input;
			switch(val){
				case "file":
					input = photo;
				break;
				case "token":
					input = token;
				break;
				case "userid":
					input = userid;
				break;
				case "previous":
					input = img_prev;
				break;
			}
			form_data.append(val, input);
		});
		
		$.ajax({
			url: "./action.php?act=upload-pp",
			data: form_data,
			processData: false,
			contentType: false,
			type: "POST",
			dataType: "json",
			success: function( data ) {
				if( data.stat == 0 ) {
					Materialize.toast( data.error, 4000 );
				} else {
					$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});
					$( "#pp-img-main" ).attr( "src", data.file ).removeClass( "hide" );
					$( "#pp-img" ).attr( "src", data.file )
					$( "i#pp-main" ).addClass( "hide" );
					current_pic = data.file;
					
					if( websocket.readyState == 1 ) {	// Check the connection

						var update_pp = {
							name: username,	// Username
							iduser: userid,	// User ID
							msgtype: "5",	// Change Photo
							token: token,	// Token (For Security)
							chat_img: data.file	// Chat Picture
						};
								
						var json_update_pp = JSON.stringify( update_pp );							
						websocket.send( json_update_pp );
					} else { // If there is no connection
						Materialize.toast( "Could not connect to WebSocket server.", 4000 );
					}
				}
			}
		});
		$( "#upload-pp" ).val("");
				
	});
	
	$( "body" ).on( "click", "#save-chat-photo", function( ev ) {
		ev.preventDefault();
		var photo = $( "#upload-chat" ).prop( "files" )[0];
		var form_data = new FormData();                  
		var inputs = ["file", "token", "userid", "previous", "chatid", "username"];
		
		if( !jQuery.isEmptyObject( pic_prev_chat )) {	
			var img_prev_group = pic_prev_chat.split('/').pop();
		} else {
			var img_prev_group = "";
		}
		
		$.each(inputs, function (obj, val) {
			var input;
			switch(val){
				case "file":
					input = photo;
				break;
				case "token":
					input = token;
				break;
				case "userid":
					input = userid;
				break;
				case "previous":
					input = img_prev_group;
				break;
				case "chatid":
					input = chat;
				break;
				case "username":
					input = username;
				break;
			}
			form_data.append(val, input);
		});
		
		$.ajax({
			url: "./action.php?act=upload-chat-pp",
			data: form_data,
			processData: false,
			contentType: false,
			type: "POST",
			dataType: "json",
			success: function( data ) {
				if( data.stat == 0 ) {
					Materialize.toast( data.error, 4000 );
				} else {
					$( "#save-chat-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});					
					$( "#chat-img-main-rev" ).attr( "src", data.file ).removeClass( "hide" );
					$( "i#chat-main-rev" ).addClass( "hide" );
					
					if( websocket.readyState == 1 ) {	// Check the connection

						var update_group_pp = {
							name: username,	// Username
							iduser: userid,	// User ID
							msgtype: "6",	// Change Photo
							token: token,	// Token (For Security)
							chat_img: data.file,	// Chat Picture
							room: chat
						};
									
						var json_update_group_pp = JSON.stringify( update_group_pp );							
						websocket.send( json_update_group_pp );
					} else { // If there is no connection
						Materialize.toast( "Could not connect to WebSocket server.", 4000 );
					}
				}
			}
		});
		$( "#upload-chat" ).val("");
				
	});
	
	$( "body" ).on( "click", "#discard-pp", function( ev ) {
		ev.preventDefault();
		
		if( !jQuery.isEmptyObject( pic_prev )) {
			$( "#pp-img" ).attr( "src", pic_prev );
			$( "#upload-pp" ).val("");
		} else {
			$( "#pp-img" ).attr( "src", "" ).addClass( "hide" );
			$( "i#pp" ).removeClass( "hide" );
			$( "#upload-pp" ).val("");
		}
		$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});
		
		pic_prev = null;
	}); 
	
	$( "body" ).on( "click", "#discard-chat-photo", function( ev ) {
		ev.preventDefault();
		
		if( !jQuery.isEmptyObject( pic_prev_chat )) {
			$( "#group-img" ).attr( "src", pic_prev_chat );
			$( "#upload-chat" ).val("");
		} else {
			$( "#group-img" ).attr( "src", "" ).addClass( "hide" );
			$( "i#group" ).removeClass( "hide" );
			$( "#upload-chat" ).val("");
		}
		$("#pp-act-grp-div").addClass("hide");
		$( "#save-chat-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});
		
		pic_prev_chat = null;
	}); 

	 $( "body" ).on( "click", ".msgs li", function() {
		chat = $( this ).attr( "id" ); // Hashed Chat ID
		$( ".send-msg" ).attr( "contenteditable", "true" );
		$( "#send-btn" ).removeClass("disabled");
		$( "#voice-btn" ).removeClass("disabled");
		$( "#open-emoji" ).removeClass( "disabled") ;
		$( "#clear-chat" ).parent().removeClass( "disabled" );
		$( ".click-to-toggle" ).removeClass( "hide" );
		$( "#users" ).parent().removeClass( "hide" );
		$( ".settings-title" ).text("");
		$( "#grp-name" ).text("");
		$( ".cdropdown5" ).addClass("hide");
		$( "#edit-group-name" ).addClass("hide");
		$( "#group" ).addClass("hide");
		$( "#pp-act-grp-div" ).addClass("hide");
		$( "#group-img" ).attr("src", "").addClass("hide");
		$("#online-status").html("");
		
		$.post( "./action.php?act=check-user", { data: userid, chatid: chat } ).done( function( stat ) {
			if( stat == 0 ) {
				$( ".send-msg" ).attr( "contenteditable", "false" );
				$( "#send-btn" ).addClass( "disabled" );
				$( "#voice-btn" ).addClass( "disabled" );
				$( "#open-emoji" ).addClass( "disabled" );
				$( "#clear-chat" ).parent().addClass( "hide" );
				$( "#users" ).parent().addClass( "hide" );
			}
		});
		$( "#msg-content" ).html( "" );	// Reset the Chat Content
		$( "#loading" ).removeClass( "hide" );	// Show the Loading Element
		
		grp_name = $( "#" + chat + " span" ).html(); // Chat Name
		var profile = $( "#" + chat + " i" ).html(); // User Picture

		$.post( "./action.php?act=load-chat-settings", { chat: chat, username: username, userid: userid, token: token } ).done( function( data2 ) {
			$(".card .chat-settings").html(data2);
			$('.profile-image-thumb a').simpleLightbox();
			$(".profile-image-thumb img").on("load", function(){
				var element = $(this)[0];
				var element2 = $(this);
				if(element.naturalHeight >= element.naturalWidth) {
					element2.css("width", "100px");
				} else {
					element2.css("height", "100px");
				}
			});
		});

		$.post( "./action.php?act=chat-details", { userid: userid, username: username, token: token, chat: chat }, function( data ) {
			var response = jQuery.parseJSON( data );
			if(response.error != "") {
				Materialize.toast( response.error, 4000 );
				setTimeout(function() {
					location.reload();
				},3000);
			} else {
				var ppl_num = response.user_chips.length + 1;
				t = ppl_num;
				t_1 = t;
				$( "#modal1 p" ).html( response.user_list );
				$.each( response.user_chips, function( i , val ) {
					$( ".invite-reveal .inv-users" ).append( "<div class='b-chip' attr-id='" + val + "'>" + response.user_chips_names[i] + "</div>" );
				});
				$( ".invite-reveal .inv-capacity" ).html( ppl_num + "/" + max_capacity );
				$("#dropdown3").html(response.dropdown);
				if(response.online == 1 || response.online == 0) {
					if(response.online == 1) {
						$("#online-status").removeClass("red-text text-lighten-2").addClass("green-text text-lighten-3").html("Online");
					} else {
						$("#online-status").removeClass("green-text text-lighten-3").addClass("red-text text-lighten-2").html("Offline");
					}
				} else {
					$("#online-status").html("");
				}
				rmtyp = response.roomtype;
				if( response.pic == 1 ) {
					$( "#chat-main-rev" ).addClass( "hide" );
					$( "#chat-img-main-rev" ).attr( "src", response.url ).removeClass( "hide" );
				} else {
					if(rmtyp == 1) {
						$( "#chat-main-rev" ).html( "person" ).removeClass( "hide" );
					} else {
						$( "#chat-main-rev" ).html( "group" ).removeClass( "hide" );
					}
					$( "#chat-img-main-rev" ).attr( "src", "" ).addClass( "hide" );
				}
			}
		});	

		$( ".chat-nav-un" ).html( grp_name );	// Load the Chat Name
		
		$( ".chat-reveal" ).css({ display: "block", opacity: 0 }).velocity( "stop", !1 ).velocity({ opacity: 1 },{ duration: 250, queue: !1, easing: "easeInOutQuad", complete:function() {$("#send-msg").focus();$( "#" + chat + " .custom-badge" ).remove();}});
		
		// Make an Ajax Call for Loading previous messages
		$.post( "./action.php?chat=" + chat).done(function( response4 ) {
				
			$( "#loading" ).addClass( "hide" );	// Hide loading element
			$( "#msg-content" ).html( response4 );	// Load Messages to Chat Page
			scrollToBottom("#msg-content");
			$("#msg-content img").on("load", function() {
				scrollToBottom("#msg-content");
				msg_content = $("#msg-content").height();
			});
			$(".map-msg").ready(function() {
				for(map_i = 0; map_i < $(".map-msg").length; map_i++) {
					initMap($(".map-msg[num='"+map_i+"']")[0], 1, $(".map-msg[num='"+map_i+"']").attr("lat"), $(".map-msg[num='"+map_i+"']").attr("lng"), $(".map-msg[num='"+map_i+"']").attr("acc"));
				}
			});
			$('.image-link').simpleLightbox();
		});
	});	
	
	// Add User Chip (Invite)
	$( "body" ).on( "click", ".inv-add", function( ev ) {
		ev.preventDefault();
		if( t < max_capacity ) {	// Check If There are Free Space
			var un = $( this ).attr( "attr-un" );	// Get Username From the Search Result
			var uid = $( this ).attr( "attr-id" );	// Get Username From the Search Result
			// Make an Ajax Call for Adding the User and Store User in Array Both in PHP & JQuery
			$.post( "./action.php?act=inv-add-chip", { uid: uid, array: array_inv, chatid: chat, username: username } ).done(function( response1 ) {
				if( response1 != 0 && response1 != 1 ) {	// If there is not any errors
					array_inv = response1;	// JQuery Array
					$( ".inv-users" ).append( "<div class='c-chip'>" + un + "<i class='material-icons remove-chip-inv' attr-id='" + uid + "'>close</i></div>" );	// Create a Chip for People that are in the List			
					t++;	// Number of People in the Array
				}
				$( ".inv-capacity" ).html( t + "/" + max_capacity );	// Update the Free Space
			});
		}
	});	
	
	// Remove User Chip (Invite)
	$( "body" ).on( "click", ".remove-chip-inv", function() {
		var uid = $( this ).attr( "attr-id" );	// Get Username From the Chip
		$( this ).parent().remove();	// Remove the Chip
							
		// Make an Ajax Call for Removing the User from the Array
		$.post( "./action.php?act=remove-chip", { uid: uid, array: array_inv }, function( response2 ) {
			if( response2 != 0 ) {	// If there is not any errors
				array_inv = response2; // JQuery Array
				t--; // Number of People in the Array
				$( ".inv-capacity" ).html( t + "/" + max_capacity ); // Update the Free Space
			}
		});
	});
	
	$( "body" ).on( "click", "#invite-btn", function( ev ) {
		ev.preventDefault();
		if( rmtyp == 0 ){
			if( websocket.readyState == 1 ) {	// Check the connection
				$.post( "./action.php?act=invite-group", { chatid: chat, array: array_inv, token: token, username: username, userid: userid}).done( function( data ){
					if( data != "error" ){
						var response = $.parseJSON( data );
						var time = getTime();	// Get the Time						
						var temp_i = 0;
						$.each( response.users, function( i, val ) {							
							if( !$.isEmptyObject( response.pics[i] ) ) {
								$( "#modal1 p ul" ).append( "<li class='collection-item avatar users' attr-id='" + val + "'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 hide z-depth-1'>person</i><img id='chat-img-main' class='circle chat-list-photo z-depth-1' src='" + response.pics[i] + "'><span class='title truncate pm clickable' attr-id='"+response.ids[temp_i]+"'>" + response.usernames[temp_i] + "</span>"+response.onlines[temp_i]+"<a href='#' attr-id='" + val + "' class='secondary-content kick'><i class='material-icons clickable'>cancel</i></a></li>" );
							} else {
								$( "#modal1 p ul" ).append( "<li class='collection-item avatar users' attr-id='" + val + "'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 z-depth-1'>person</i><img id='chat-img-main' class='circle chat-list-photo hide z-depth-1'><span class='title truncate pm clickable' attr-id='"+response.ids[temp_i]+"'>" + response.usernames[temp_i] + "</span>"+response.onlines[temp_i]+"<a href='#' attr-id='" + val + "' class='secondary-content kick'><i class='material-icons clickable'>cancel</i></a></li>" );
							}
							$( ".invite-reveal .inv-users" ).append( "<div class='b-chip' attr-id='" + val + "'>" + response.usernames[temp_i] + "</div>" );
							temp_i++;
						});
							
							var invite_msg = {
								name: username,	// Username
								room: chat,	// Hashed Chat ID
								iduser: userid,	// User ID
								msgtype: 4,	// Invite to Group
								rmtype: 0,	// Group
								users: response.users, // Only for inviting people
								chatname: grp_name,	// Chat Name
								time: time,	// Current Time
								token: token
							};				
							var json_invite = JSON.stringify( invite_msg );	// Convert msg into JSON
							websocket.send( json_invite );	// Send the JSON Message
						
						
						array_inv = null;	// Clear array
						$( ".inv-capacity" ).html( t + "/" + max_capacity );	// Reset Capacity
						$( "#inv-s" ).val( "" ).removeClass( "active" );
						$( ".chat-details" ).removeClass( "hide" );
						$( ".close-rev-inv" ).addClass( "hide" );
						$( ".inv-search-content" ).hide().html( "" );	// Remove Previous User Search Results
						$( ".invite-reveal .c-chip .remove-chip-inv" ).remove();	// Remove Previously Added User's Delete Buttons
						$( ".invite-reveal .c-chip" ).remove();	// Remove Previously Added Users		
			
						$( ".invite-reveal" ).css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {$(this).css("display", "none");$(".invite-btn").removeClass("hide");}});
					} else {
						Materialize.toast( "Error.", 4000 );
					}
				});
			} else {
				Materialize.toast( "Could not connect to WebSocket server.", 4000 );
			}
		}
		
	});

	// On Back Button Click in the Chat Page // For Going Back to Main Page
	$( "body" ).on( "click", ".back-btn", function( ev ) {
		ev.preventDefault();
		if( chat_side == true ) {
			if( !jQuery.isEmptyObject( pic_prev_chat )) {
				$( "#chat-img" ).attr( "src", pic_prev_chat );
			} else {
				$( "#chat-img" ).attr( "src", "" ).addClass( "hide" );
				$( "i#chat" ).removeClass( "hide" );
			}
			$( "#save-pp-ul" ).css({"opacity":0, "display":"none"});
			$( "#msg-content" ).css("width", "100%");
			$( ".msg-box" ).css("width", "100%");
				
			pic_prev_chat = null;
			chat_side = false;
			array_inv = null;
			$( ".invite-reveal .b-chip" ).remove();
			$( "#" + chat + " .custom-badge" ).remove();
			$( ".chat-details" ).removeClass( "hide" );
			$( ".close-rev-inv" ).addClass( "hide" );
			$( "#modal1" ).modal("close");
			
			$( ".chat-settings" ).velocity({ translateX: "100%", translateY: "0", translateZ: "0" }, { duration: 225, queue: !1, easing: "easeInOutQuad", complete: function() {
				$( this ).css({ display: "none", opacity: 0 });
				if($(".invite-reveal").css("opacity") == 1) {
					$( ".invite-reveal" ).css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {
						$(this).css("display", "none");
						$( ".chat-reveal" ).css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {
							$(this).css("display", "none"); 
							if( emoji == true ) {
								$( "#emoji-table" ).velocity("stop").velocity("slideUp", { complete: function(){
									$( "#close-emoji" ).addClass( "hide" );
									$( "#open-emoji" ).removeClass( "hide" );
									$("#msg-content").velocity("stop").velocity({height:  "+= 250"},{ duration: 0, queue: !1, easing: "easeInOutQuad", complete: function() { 
										scrollToBottom("#msg-content"); 
										emoji = false; 
										$("#msg-content").css("height", "calc(100% - 175px)"); 
									}});
								}});
							}
						}});
					}});
				} else {
					$( ".chat-reveal" ).css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {
						$(this).css("display", "none"); 
						if( emoji == true ) {
							$( "#emoji-table" ).velocity("stop").velocity("slideUp", { complete: function(){
								$( "#close-emoji" ).addClass( "hide" );
								$( "#open-emoji" ).removeClass( "hide" );
								$("#msg-content").velocity("stop").velocity({height:  "+= 250"},{ duration: 0, queue: !1, easing: "easeInOutQuad", complete: function() { 
									scrollToBottom("#msg-content"); 
									emoji = false; 
									$("#msg-content").css("height", "calc(100% - 175px)"); 
								}});
							}});
						}
					}});
				}
			}});
			$.post( "./action.php?act=unload", { chatid: chat } );  // Remove unread messages
		} else {
			array_inv = null;
			$( ".invite-reveal .b-chip" ).remove();
			$( "#" + chat + " .custom-badge" ).remove(); 	// Remove unread messages Element
			$( ".chat-details" ).removeClass( "hide" );
			$( ".close-rev-inv" ).addClass( "hide" );
			$( "#modal1" ).modal("close");
			if($(".invite-reveal").css("opacity") == 1) {
				$( ".chat-reveal" ).css({ display: "none", opacity: 0 });
				$( ".invite-reveal" ).css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {
					$(this).css("display", "none");
					$(".invite-btn").removeClass("hide");
				}});
				if( emoji == true ) {
					$( "#emoji-table" ).velocity("stop").velocity("slideUp", { complete: function(){$( "#close-emoji" ).addClass( "hide" );$( "#open-emoji" ).removeClass( "hide" );$("#msg-content").velocity("stop").velocity( {height:  "+= 250"},{ duration: 0, queue: !1, easing: "easeInOutQuad", complete: function() { scrollToBottom("#msg-content"); emoji = false; $("#msg-content").css("height", "calc(100% - 175px)"); } });}});
				}
			} else {
				$( ".invite-reveal" ).css({ display: "none", opacity: 0 });
				$(".invite-btn").removeClass("hide");
				$( ".chat-reveal" ).css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {
					$(this).css("display", "none"); 
					if( emoji == true ) {
						$( "#emoji-table" ).velocity("stop").velocity("slideUp", { complete: function(){
							$( "#close-emoji" ).addClass( "hide" );
							$( "#open-emoji" ).removeClass( "hide" );
							$("#msg-content").velocity("stop").velocity( {height:  "+= 250"},{ duration: 0, queue: !1, easing: "easeInOutQuad", complete: function() { 
								scrollToBottom("#msg-content"); 
								emoji = false;
								$("#msg-content").css("height", "calc(100% - 175px)"); 
							}});
						}});
					}
				}});
			}
			$.post( "./action.php?act=unload", { chatid: chat } );  // Remove unread messages
		}
		chat = null;
		$(".send-msg").html("");
		$(".send-msg-bg").removeClass("hide");
	});
	
	$( "body" ).on( "click", "#leave-delete", function( ev ) {
		ev.preventDefault();
		if( websocket.readyState == 1 ) {	// Check the connection
			$.post( "./action.php?act=chat-number", { token: token, user_id: userid, chatid: chat, username:username } ).done( function( num_data ) {
				var num_response = jQuery.parseJSON( num_data );
				if( num_response.chat == 1 ) {
					$( ".not-any" ).removeClass("hide");
					$( "#pmlist .msgs " ).addClass("hide");
				}

				var leave = {
					name: username,	// Username
					room: chat,	// Crypted Chat ID
					iduser: userid,	// User ID
					msgtype: "2",	// Leave Type
					token: token	// Token (For Security)
				};
						
				var json_leave = JSON.stringify( leave );
							
				websocket.send( json_leave );
				
				t = 1;
				array_inv = null;
			
				$( ".chat-reveal" ).css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {$(this).css("display", "none")}});
			});
		} else {
			Materialize.toast( "Could not connect to WebSocket server.", 4000 );
		}
	});
	
	$( ".msg-box" ).keyup( function( event ){
		if( event.keyCode == 13 ){
			event.preventDefault();
			$( "#send-btn" ).click();
		}
	});
	$('.send-msg').keydown(function(e) {
		if (e.keyCode === 13) {
		  return false;
		}
	  });
	
	$( "body" ).on( "click", "#send-btn", function( ev ){ //use clicks message send button	
		ev.preventDefault();
		
		if( emoji == true ) {
			$( "#close-emoji" ).addClass( "hide" );
			$( "#open-emoji" ).removeClass( "hide" );
			$( "#emoji-table" ).velocity("stop").velocity("slideUp", { complete: function(){
				$("#msg-content").velocity("stop").velocity( {height:  "+= 250"},{ duration: 0, queue: !1, easing: "easeInOutQuad", complete: function() { scrollToBottom("#msg-content"); emoji = false; } });
			} });
		}
		
		var mymessage = $('.send-msg').html().replace(/<div.*\/div>/, '').replace(/<span.*\/span>/, '').replace(/<p.*\/p>/, '');
		
		if(mymessage.indexOf("&nbsp;") > -1 || mymessage.indexOf(" ") > -1 || mymessage.lastIndexOf("&nbsp;") || mymessage.lastIndexOf(" ")){
		   mymessage = mymessage.replace( /&nbsp;/g,'' ).replace(/(^[\s]+|[\s]+$)/g, '');
		}
		if( mymessage == "" || /^\s+$/.test( mymessage ) ){ //emtpy message?
			$( ".send-msg" ).html( "" ); //reset text
			return;
		}
		
		var container = $('<div>').html(mymessage);
		container.find('img').replaceWith(function() { var list = this.classList; return "::e||" + list[2].slice(7) + "::" });		
		var edited_message = container.html();
	
		$( ".send-msg" ).html( "" ); //reset text
		$( ".send-msg-bg" ).removeClass( "hide" );
		if(voice_notes == 1) {
			$('#voice-btn').removeClass("hide");
			$('#send-btn').addClass("hide");
		}
		
		var time = getTime();
		
		$( "#msg-content" ).append( "<div class='chat-msgs'><div class='my-msgs'><div class='my-usr-msg'>" + ban_word(mymessage) + "</div><div class='msg-time left'>" + time + "</div><i class='material-icons right msg-icon tiny' num='" + k + "'>schedule</i></div></div>" );
		
		scrollToBottom("#msg-content"); // Go to bottom
		
		var msg = {
			message: edited_message,	// Message
			name: username,	// Username
			room: chat,		// Crypted Chat ID
			iduser: userid,	// User ID
			msgtype: "1",	// Message Type
			rmtype: rmtyp,	// Room Type
			time:time,		// Current Time
			token: token,	// Token (For Security)
			k: k
		};
				
		var json_msg = JSON.stringify( msg );
		if( websocket.readyState == 1 ) {
			websocket.send( json_msg );
		} else {
			$( ".msg-icon" ).last().text( "priority_high" ).css( "color", "red" ).addClass( "clickable-icon" );
		}
		k++;

	});
	
	$( "body" ).on( "click", ".msg-icon", function( ev ) {
		if( $( this ).html() === "priority_high" && $(this).hasClass("clickable-icon")) {
			
			var mymessage = $( this ).prev().prev().html();
			var time = $( this ).prev().text();
			var num = $( this ).attr( "num" );
			
			var msg = {
				message: mymessage,	// Message
				name: username,	// Username
				room: chat,		// Crypted Chat ID
				iduser: userid,	// User ID
				msgtype: "1",	// Message Type
				rmtype: rmtyp,	// Room Type
				time:time,		// Current Time
				token: token,	// Token (For Security)
				k: num
			};
					
			var json_msg = JSON.stringify( msg );
			
			if( websocket.readyState == 1 ) {
				websocket.send( json_msg );
			} else {
				Materialize.toast( "Could not connect to WebSocket server.", 4000 );
			}
			
		}
	});
	
	$( "body" ).on( "click", ".invite-btn", function( ev ) {
		ev.preventDefault();
		$( ".chat-details" ).addClass( "hide" );
		$( ".close-rev-inv" ).removeClass( "hide" );
		$( "#modal1" ).modal("close");
		setTimeout( function() {
			$( ".invite-reveal" ).css({ display: "block", opacity: 0 }).velocity( "stop", !1 ).velocity({ opacity: 1 },{ duration: 250, queue: !1, easing: "easeInOutQuad", complete: function() {$(".invite-btn").addClass("hide");}});
		}, 650 );
	});	
	
	$( "body" ).on( "click", ".chat-details", function( ev ) {
		ev.preventDefault();
		
		if( chat_side == true ) {
			closeSideCard( ".chat-settings" );
			
			if( !jQuery.isEmptyObject( pic_prev_chat )) {
				$( "#chat-img" ).attr( "src", pic_prev_chat );
			} else {
				$( "#chat-img" ).attr( "src", "" ).addClass( "hide" );
				$( "i#chat" ).removeClass( "hide" );
			}
			$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});
			$( "#msg-content" ).velocity({ width: "100%" }, { duration: 300, easing: "easeInOutQuad"} );
			$( ".msg-box" ).velocity({ width: "100%" }, { duration: 300, easing: "easeInOutQuad"} );
			
			pic_prev_chat = null;
			chat_side = false;
		}
	});	
	
	$( "body" ).on( "click", ".close-rev-inv", function() {
		 array_inv = null;
		 t = t_1;
		 $( "#add-btn" ).fadeIn( "fast" ); // Show Add Button ("+" Button)
		 $( "#new-msg" ).fadeIn();
		 $( "#new-grp" ).fadeIn();
		 $( ".chat-details" ).removeClass( "hide" );
		 $( ".close-rev-inv" ).addClass( "hide" );
		 $( ".invite-reveal #inv-s" ).val( "" ).removeClass( "valid" );	// Reset Search Input
		 $( ".invite-reveal .inv-search-content" ).hide();	// Remove the Search Results
		 $( ".invite-reveal .c-chip" ).remove();	// Remove Chips
		 $( ".invite-reveal" ).css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {$(this).css("display", "none");$(".invite-btn").removeClass("hide");}});
	 });
	 
	 // Live Search in "Invite" Page
	 $( "#inv-s" ).on( "input", function(){
       var search = $( this ).val();	// Get the Search Value
		
		if( search.length >= 3 ) {	// If search input is not empty
			// Make an Ajax Call for do the Search
			$.post( "./action.php?act=inv-search", { search: search, page:1 }, function( response ) {
				$( ".inv-search-content" ).fadeIn( "fast" ).html( response );	// Show Search Results	
			});
		} else {
			$( ".inv-search-content" ).hide( "fast" );	// Hide the Search Results
		}
     });
	 
	 $("body").on("click", ".inv-pagination li", function(e){
		 e.preventDefault();
		 var page = $("a", this).attr("attr-page");
		 if(page > 0) {
			var search = $( "#inv-s" ).val();	// Get the Search Value
					 
			// Make an Ajax Call for do the Search
			$.post( "./action.php?act=inv-search", {search: search, page: page}).done(function( response ) {
				$(".inv-search-content" ).html( response ); // Show Search Results
			});

		 }
	 });
	
 
	// Message received from server?
	websocket.onmessage = function( ev ) {
		var msg = JSON.parse( ev.data ); //PHP sends Json data
		if( !$.isEmptyObject( msg.type ) ) {
			var type = msg.type; // message type
			if(type == "user_media_img" || type == "user_media_vid" || type == "user_media_file" || type == "user_media_music" || type == "user_media_voice_note" || type == "user_media_location" ) {
				if(type == "user_media_location") {
					var media = jQuery.parseJSON(msg.media);
				} else {
					var media = msg.media;
				}
			}
			if((type == "user_media_file" || type == "user_media_music") && !$.isEmptyObject( msg.mime )) {
				var rec_mime = msg.mime;
			}
			if((type == "user_media_file" || type == "user_media_music") && !$.isEmptyObject( msg.filename )) {
				var filename = msg.filename;
			}
		}
		if( !$.isEmptyObject( msg.message ) ) {
			var stat = 1;
			var rec_msg = msg.message; // Message
			var message = rec_msg.replace(/\:\:e\|\|(.*?)\:\:/g, "<img ondragstart='return false;' alt='&#x$1' src='./include/web-imgs/blank.jpg' style='background-image: url(\"./include/web-imgs/emojis.png\");' class='emoji-link-small sprite sprite-$1' draggable='false' />").replace(/ alt='(.*?)'/g, function(x) { return x.replace(/-/g, ';&#x'); });
		} else {
			var message = "<br>";
		}
		if( !$.isEmptyObject( msg.name ) ) {
			var uname = msg.name; // Username
		}
		if( !$.isEmptyObject( msg.userid ) ) {
			var usrid = msg.userid;	// User ID
		}
		var room = msg.chat_id; // Crypted Chat ID
		if( !$.isEmptyObject( msg.target ) ) {
			var rtarget = msg.target;
		}
		if( !$.isEmptyObject( msg.target_id ) ) {
			var rtarget_id = msg.target_id;
		}
		if( !$.isEmptyObject( msg.roomtype ) ) {
			var roomtype = msg.roomtype;	// Room Type // 0 - Group // 1 - Personal Message
		}
		if( !$.isEmptyObject( msg.time ) ) {
			var time = msg.time; // Current Time
		}
		
		if( msg.do == 1 || msg.do == 2 || msg.do == 3 || msg.do == 4 || msg.do == 5 || msg.do == 6 || msg.do == 97 || msg.do == 98 || msg.do == 99 ) {
			var doit = msg.do; // Message's Purpose
		}
		if( !$.isEmptyObject( msg.chat_name ) ) {
			var name = msg.chat_name; // Chat Name
		}
		if( !$.isEmptyObject( msg.grp_name ) ) {
			var new_grp_name = msg.grp_name; // Chat Name
		}
		if( !$.isEmptyObject( msg.token ) && usrid === userid ) {
			token = msg.token;	// Update the Token
		}		
		if( !$.isEmptyObject( msg.k ) && usrid === userid ) {
			var rec_k = msg.k;	// Received K value
		}		
		if( !$.isEmptyObject(msg.img) ) {
			var chat_img = msg.img;	// Profile / Group Image
		}

		if( type === "left" && usrid === userid ) {
				$( ".msgs li#" + room ).remove();
		}

		if( doit == 98 && usrid === userid ) {
			$( ".msgs li#" + room ).remove();
			if(room == chat) {
				Materialize.toast( "Chat room has been deleted by admin.", 4000 );
				$( ".chat-reveal" ).css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {$(this).css("display", "none")}});
			}
		}
		if( type === "kick" && username === rtarget ) {
			$( "li#" + room + " .last_message" ).html( message );
			$( "li#" + room + " .last_message_time" ).html( time );
		}

		if(msg.error_stat == 1 && usrid == userid) {
			Materialize.toast( msg.error, 4000 );
			if(msg.ip_ban == 1) {
				location.reload();
			}
			if( msg.msg_exists == 1 ) {
				$( ".msg-icon" ).last().text( "priority_high" ).css( "color", "red" ).addClass( "clickable-icon" );
			}
		} else {
			if ( doit == 99 ) {
				if( usrid == userid ) {							
					location.reload();
				}
			}
				if(doit == 97 && room == chat && userid != msg.userid) {
					if(msg.online == 1) {
						if(msg.roomtype == 1) {
							$("#online-status").removeClass("red-text text-lighten-2").addClass("green-text text-lighten-3").html("Online");
						}
						$("#online-status-all[attr-id='"+msg.userid+"']").removeClass("red-text text-lighten-2").addClass("green-text text-lighten-2").html("Online");
					} else if(msg.online == 0) {
						if(msg.roomtype == 1) {
							$("#online-status").removeClass("green-text text-lighten-3").addClass("red-text text-lighten-2").html("Offline");
						}
						$("#online-status-all[attr-id='"+msg.userid+"']").removeClass("green-text text-lighten-2").addClass("red-text text-lighten-2").html("Offline");
					}
					
				}
			$.post( "./action.php?act=check-user", { data: userid, chatid: room } ).done( function( c_stat ) {
				if( c_stat == 1  && doit != 98) {
					if( doit == 1 && usrid != userid && type != "join" ) {	// Create new item to the chat list
					
						// If It is a Personal Message Conversation, Rename the Chat Name
						if( roomtype == 1 ) {
							var room_pic_text = "person";
							name_a = name.split( "|" );
							if( name_a[0] === username ) {
								chatname = name_a[1];
							} else {
								chatname = name_a[0];
							}
						} else {
							chatname = name;
							var room_pic_text = "group";
						}
												
						$.post( "./action.php?act=unread", { ui: userid,hr: room } ).done( function( data ) { 
								$( ".not-any" ).addClass( "hide" );	// Remove "You do not have any conversations." Element
								$( "ul.msgs" ).removeClass( "hide" );	// Show Chat List Table							
								if( data == 0 ) {	// If There is no Unread Messages
									if( !$.isEmptyObject( chat_img ) ) {
										$( "ul.msgs" ).prepend( "<li id='" + room + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 hide z-depth-1'>" + room_pic_text + "</i><img id='chat-img-main' class='circle chat-list-photo z-depth-1' src='" + chat_img + "'><span class='title truncate'>" + chatname + "</span><p><div class='last_message truncate'><br></div><div class='last_message_time'>" + time + "</div></p></li>" );
									} else {
										$( "ul.msgs" ).prepend( "<li id='" + room + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 z-depth-1'>" + room_pic_text + "</i><img id='chat-img-main' class='circle chat-list-photo hide z-depth-1'><span class='title truncate'>" + chatname + "</span><p><div class='last_message truncate'><br></div><div class='last_message_time'>" + time + "</div></p></li>" );
									}
									$('.custom-cont').perfectScrollbar("update");
								} else {	// If There are Unread Messages
									if( !$.isEmptyObject( chat_img ) ) {
										$( "ul.msgs" ).prepend( "<li id='" + room + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 hide z-depth-1'>" + room_pic_text + "</i><img id='chat-img-main' class='circle chat-list-photo z-depth-1' src='" + chat_img + "'><span class='title truncate'>" + chatname + "</span><p><div class='last_message truncate'>" + message + "</div><div class='last_message_time'>" + time + "</div></p><span data-badge-caption='New Message' class='new badge custom-badge " + room + "'>1</span></li>" );
									} else {
										$( "ul.msgs" ).prepend( "<li id='" + room + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 z-depth-1'>" + room_pic_text + "</i><img id='chat-img-main' class='circle chat-list-photo hide z-depth-1'><span class='title truncate'>" + chatname + "</span><p><div class='last_message truncate'>" + message + "</div><div class='last_message_time'>" + time + "</div></p><span data-badge-caption='New Message' class='new badge custom-badge " + room + "'>1</span></li>" );
									}
									$('.custom-cont').perfectScrollbar("update");
								}
						});		
					
					} else if(doit == 1) {
						$( "#" + room + " .last_message_time" ).text( time );
						$( "#" + room + " .last_message" ).html( message );
					} else if ( doit == 2  && type !== "join" ) {	// Update Unread Messages
						$( "ul.msgs" ).find( "li#" + room ).prependTo( "ul.msgs" );	// Move chat to the top
						$( "#" + room + " .last_message_time" ).text( time );
						$( "#" + room + " .last_message" ).html( message );
						
						// Get the number of unread messages
						$.post( "./action.php?act=unread", { ui: userid, hr: room } ).done( function( data ) {				
							if( data == 1 ) {
								$( "#" + room ).append( "<span class='new badge custom-badge " + room + "' data-badge-caption='New Message'>1</span>" );	// Create 1 New Message Element
							} else {
								$( "." + room ).text( data ).attr("data-badge-caption", "New Messages");	// Update New Message Element
							}	
						});
					} else if ( doit == 3 ) { // Update Profile Picture
						if( usrid != userid ) {
							if( !$.isEmptyObject( chat_img ) ) {
								$( "#" + room + " #chat-img-main" ).attr( "src", chat_img ).removeClass( "hide" );
								$( "#" + room + " i#chat-main" ).addClass( "hide" );
							} else {
								$( "#" + room + " #chat-img-main" ).attr( "src", "" ).addClass( "hide" );
								$( "#" + room + " i#chat-main" ).removeClass( "hide" );
							}
						}
						if( chat === room ) {
							
							if( !$.isEmptyObject( chat_img ) ) {
								$( "li#" + uname + " #chat-img-main-rev-user" ).attr( "src", chat_img ).removeClass( "hide" );
								$( "li#" + uname + " i#chat-main-rev-user" ).addClass( "hide" );
								$( "#chat-img-main-rev" ).attr( "src", chat_img ).removeClass( "hide" );
								$( "i#chat-main-rev" ).addClass( "hide" );
							} else {
								$( "li#" + uname + " #chat-img-main-rev-user" ).attr( "src", "" ).addClass( "hide" );
								$( "li#" + uname + " i#chat-main-rev-user" ).removeClass( "hide" );
								$( "#chat-img-main-rev" ).attr( "src", "" ).addClass( "hide" );
								$( "i#chat-main-rev" ).removeClass( "hide" );
							}
						}
					} else if ( doit == 4 ) { // Update Group Picture
						if( !$.isEmptyObject( chat_img ) ) {							
							$( "#" + room + " #chat-img-main" ).attr( "src", chat_img ).removeClass( "hide" );
							$( "#" + room + " i#chat-main" ).addClass( "hide" );
						} else {
							$( "#" + room + " #chat-img-main" ).attr( "src", "" ).addClass( "hide" );
							$( "#" + room + " i#chat-main" ).removeClass( "hide" );
						}
						if( chat === room ) {
							if( !$.isEmptyObject( chat_img ) ) {
								$( "#chat-img-main-rev" ).attr( "src", chat_img ).removeClass( "hide" );
								$( "i#chat-main-rev" ).addClass( "hide" );
							} else {
								$( "#chat-img-main-rev" ).attr( "src", "" ).addClass( "hide" );
								$( "i#chat-main-rev" ).removeClass( "hide" );
							}
						}
					} else if ( doit == 5 ) { // Change Group Name
						if( usrid == userid ) {							
							$("#grp-name-input").addClass("hide");
							$("#grp-name").removeClass("hide");
							$("#edit-group-name").removeClass("hide");
							$("#reset-group-name").addClass("hide");
							$("#confirm-group-name").addClass("hide");
						}
						if( chat === room ) {
							$(".chat-nav-un").text(new_grp_name);
							$("#grp-name").text(new_grp_name);
						}
						$(".msgs li#" + room + " span.title").text(new_grp_name);
					}
				}
			});
			
			
			if( !jQuery.isEmptyObject( room ) && doit != 98 ) {
				if( (type == "usermsg" || type == "user_media_img" || type == "user_media_vid" || type == "user_media_file" || type == "user_media_music" || type == "user_media_voice_note" || type == "user_media_location") && room == chat && stat == 1 ) {
					$.post( "./action.php?act=check-user", { data: userid, chatid: room } ).done( function( c_stat ) {
						if( c_stat == 1 ) {
							if( usrid == userid ) {
								$( ".msg-icon[num=" + rec_k + "]" ).text( "done" ).css( "color", "black" ).removeClass( "clickable-icon" );
								if ( type == "user_media_img" ) {
									$( ".msg-icon[num=" + rec_k + "]" ).prev().prev().children().children().children().removeClass("temp");
								}
							} else {
								if ( type == "user_media_img" ) { // Send Image
										msg_media = "<a href='" + media + "' class='image-link'><div class='image-thumb'><img class='shared-images' src='" + media + "' alt='' title=''/></div></a>";
										if( roomtype == 1 ) {		
											$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='other-usr-msg'>" + msg_media + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
											scrollToBottom("#msg-content"); // Go to bottom
										} else {
											$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='username'>" + uname + "</div><div class='other-usr-msg'>" + msg_media + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
											scrollToBottom("#msg-content"); // Go to bottom
										}
									$('.image-link').simpleLightbox();
									
								} else if ( type == "user_media_vid" ) { // Send Video
									
										if(rec_mime != "video/mp4" && rec_mime != "video/webm" && rec_mime != "video/ogg") {
											msg_media = "<div class='file-bg'><a href='" + media + "'><div class='file-bg-text'>Download</div></a></div>";
										} else {
											msg_media = "<a href='" + media + "' class='video-link'><div class='image-thumb'><video class='shared-vid' src='" + media + "' type='" + rec_mime + "'><div class='file-bg'><div class='file-bg-text'>Download</div></div></video><div class='video-play'><i class='material-icons'>play_circle_outline</i></div></div></a>";
										}
										if( roomtype == 1 ) {		
											$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='other-usr-msg'>" + msg_media + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
											scrollToBottom("#msg-content"); // Go to bottom
										} else {
											$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='username'>" + uname + "</div><div class='other-usr-msg'>" + msg_media + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
											scrollToBottom("#msg-content"); // Go to bottom
										}
									
								} else if ( type == "user_media_file" ) { // Send Video
																	
										msg_media = "<p><i href='" + media + "' name='" + filename + "' class='material-icons large shared-file download-file clickable'>archive</i></p><p class='file-name'>" + filename + "</p>";
										
										if( roomtype == 1 ) {		
											$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='other-usr-msg'>" + msg_media + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
											scrollToBottom("#msg-content"); // Go to bottom
										} else {
											$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='username'>" + uname + "</div><div class='other-usr-msg'>" + msg_media + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
											scrollToBottom("#msg-content"); // Go to bottom
										}
									
								} else if ( type == "user_media_music" ) { // Send Video
										if(rec_mime == "audio/mpeg" || rec_mime == "audio/wav" || rec_mime == "audio/ogg" || rec_mime == "audio/mp3") {										
											msg_media = "<p><i href='" + media + "' name='" + filename + "' class='material-icons clickable shared-file large download-file'>headset</i></p><p class='file-name'>" + filename + "</p><p class='shared-music'><audio src='" + media + "' type='" + rec_mime + "' controls></audio></p>";
										} else {
											msg_media = "<p><i href='" + media + "' name='" + filename + "' class='material-icons clickable shared-file large download-file'>headset</i></p><p class='file-name'>" + filename + "</p>";
										}									
									
										if( roomtype == 1 ) {		
											$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='other-usr-msg music-link'>" + msg_media + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
											scrollToBottom("#msg-content"); // Go to bottom
										} else {
											$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='username'>" + uname + "</div><div class='other-usr-msg music-link'>" + msg_media + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
											scrollToBottom("#msg-content"); // Go to bottom
										}
									
								} else if ( type == "user_media_voice_note" ) { // Send Video
										msg_media = "<p class='shared-music'><audio src='" + media + "' type='audio/wav' controls></audio></p>";
										if( roomtype == 1 ) {		
											$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='other-usr-msg voice-note'>" + msg_media + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
											scrollToBottom("#msg-content"); // Go to bottom
										} else {
											$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='username'>" + uname + "</div><div class='other-usr-msg voice-note'>" + msg_media + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
											scrollToBottom("#msg-content"); // Go to bottom
										}
									
								} else if ( type == "user_media_location" ) { // Send Video
										if( roomtype == 1 ) {		
											$("#msg-content").append("<div class='chat-msgs'><div class='other-msgs'><div class='other-usr-msg'><div class='map-msg-cover'></div><div class='map-msg' num_r='"+map_j+"' lat='"+media.lat+"' lng='"+media.lng+"' acc='"+media.acc+"'></div></div><div class='msg-time left'>"+time+"</div></div></div>");
											scrollToBottom("#msg-content"); // Go to bottom
										} else {
											$("#msg-content").append("<div class='chat-msgs'><div class='other-msgs'><div class='username'>" + uname + "</div><div class='other-usr-msg'><div class='map-msg-cover'></div><div class='map-msg' num_r='"+map_j+"' lat='"+media.lat+"' lng='"+media.lng+"' acc='"+media.acc+"'></div></div><div class='msg-time left'>"+time+"</div></div></div>");
											scrollToBottom("#msg-content"); // Go to bottom
										}
										initMap($(".map-msg[num_r='"+map_j+"']")[0], 1, $(".map-msg[num_r='"+map_j+"']").attr("lat"), $(".map-msg[num_r='"+map_j+"']").attr("lng"), $(".map-msg[num_r='"+map_j+"']").attr("acc"));
										map_j++;
								} else {
									if( roomtype == 1 ) {		
										$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='other-usr-msg'>" + message + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
										scrollToBottom("#msg-content"); // Go to bottom
									} else {
										$( "#msg-content" ).append( "<div class='chat-msgs'><div class='other-msgs'><div class='username'>" + uname + "</div><div class='other-usr-msg'>" + message + "</div><div class='msg-time left'>" + time + "</div></div></div>" );
										scrollToBottom("#msg-content"); // Go to bottom
									}
								}
							}
						}
					});
				}
				if( type === "system" && chat === room && stat == 1 ) {
					$.post("./action.php?act=check-user", { data: userid, chatid: room } ).done( function( c_stat ) {
						if( c_stat == 1 ){
							$( "#msg-content" ).append( "<div class='chat-msgs'><div class='system'>" + message + "</div></div>" );
							scrollToBottom("#msg-content"); // Go to bottom
						}
					});
				}
				if( type === "left" && room === chat && usrid !== userid && roomtype == 0 ) {
					$.post( "./action.php?act=check-user", { data: userid, chatid: room } ).done( function( c_stat ) {
						if( c_stat == 1 ) {
							$( "#msg-content" ).append( "<div class='chat-msgs'><div class='system'>" + message + "</div></div>" );
							scrollToBottom("#msg-content"); // Go to bottom
						}
					});
				}			
				if( type === "kick" && username !== rtarget && room === chat ) {
					$.post( "./action.php?act=check-user", { data: userid, chatid: room } ).done( function( c_stat ) {
						if( c_stat == 1 ) {
							$( "#msg-content" ).append( "<div class='chat-msgs'><div class='system'>" + message + "</div></div>" );
							scrollToBottom("#msg-content"); // Go to bottom
						}
					});
				}
				if( type === "kick" && username === rtarget && room === chat ) {
					$( ".chat-msgs" ).remove();
					$( "#msg-content" ).append( "<div class='chat-msgs'><div class='system'>You have kicked.</div></div>" );
					scrollToBottom("#msg-content"); // Go to bottom
					$( ".send-msg" ).attr( "contenteditable", "false" );
					$( "#send-btn" ).addClass( "disabled" );
					$( "#voice-btn" ).addClass( "disabled" );
					$( "#open-emoji" ).addClass( "disabled" );
					$( "#clear-chat" ).parent().addClass( "hide" );
					$( "#users" ).parent().addClass( "hide" );
					$( "#modal1 p" ).remove();
					$( "#modal1" ).modal("close");
					$( ".click-to-toggle" ).addClass( "hide" );
				}
			}
			
			if( doit != 98 && doit != 97 && (type == "usermsg" || type == "user_media_img" || type == "user_media_vid" || type == "user_media_file" || type == "user_media_music" || type == "user_media_voice_notes" || type == "user_media_location") && userid != usrid && chat != room ) {
				$.post( "./action.php?act=check-user", { data: userid, chatid: room } ).done( function( c_stat ) {
					if( c_stat == 1 ) {
						if(doit != 99) {
							if(type != "usermsg") {
								if(type == "user_media_img") {
									rec_msg = "Image";
								} else if(type == "user_media_vid") {
									rec_msg = "Video";
								} else if(type == "user_media_file") {
									rec_msg = "File";
								} else if(type == "user_media_music") {
									rec_msg = "Music";
								} else if(type == "user_media_voice_note") {
									rec_msg = "Voice Note";
								} else if(type == "user_media_location") {
									rec_msg = "Location";
								}
								
							}
							if( msg.img == "" || msg.img == null || msg.img == undefined ) {
								if( roomtype == 1 ) {
									var chat_img_notification = "./include/web-imgs/person.png";
								} else {
									var chat_img_notification = "./include/web-imgs/group.png";
								}
							} else {
								var chat_img_notification = img_path + chat_img;
							}
							if(type == "system" || type == "kick") {
								notifyMe( rec_msg, "SYSTEM", roomtype, uname, room, userid, chat_img_notification );
							} else {
								notifyMe( rec_msg, name, roomtype, uname, room, userid, chat_img_notification );
							}
						}
					}
				});
			} else if (!document.hasFocus() && doit != 97 && doit != 98 && doit != 99) {
					if(type != "usermsg") {
							if(type == "user_media_img") {
								rec_msg = "Image";
							} else if(type == "user_media_vid") {
								rec_msg = "Video";
							} else if(type == "user_media_file") {
								rec_msg = "File";
							} else if(type == "user_media_music") {
								rec_msg = "Music";
							} else if(type == "user_media_voice_note") {
								rec_msg = "Voice Note";
							} else if(type == "user_media_location") {
								rec_msg = "Location";
							}
					}
					if( msg.img == "" || msg.img == null || msg.img == undefined ) {
						if( roomtype == 1 ) {
							var chat_img_notification = "./include/web-imgs/person.png";
						} else {
							var chat_img_notification = "./include/web-imgs/group.png";
						}
					} else {
						var chat_img_notification = img_path + chat_img;
					}
					if(type == "system" || type == "kick") {
						notifyMe( rec_msg, "SYSTEM", roomtype, uname, room, userid, chat_img_notification );
					} else {
						notifyMe( rec_msg, name, roomtype, uname, room, userid, chat_img_notification );
					}
			}
			if( type === "left" && usrid !== userid && doit != 98 ) {
						$( "li#" + room + " .last_message" ).html( message );
						$( "li#" + room + " .last_message_time" ).html( time );
			}
			
			if( type === "kick" && username !== rtarget && doit != 98) {
						$( "li#" + room + " .last_message" ).html( message );
						$( "li#" + room + " .last_message_time").html( time );
			}
			if( type === "left" && roomtype == 0 && doit != 98 ) {
				$( ".users#" + uname ).remove();
				$( ".invite-reveal .b-chip" ).filter( "[attr-id='" + uname + "']" ).remove();
			}
			if( type === "kick" && chat == room && doit != 98 ) {
				$( "#modal1 .user-list .users[attr-id='"+rtarget_id+"']" ).remove();
			}
			if( type === "join" && doit != 98 ) {
						$( "#msg-content" ).append( "<div class='chat-msgs'><div class='system'>" + rtarget + " joined.</div></div>" );	// Show "USER joined." message
						scrollToBottom("#msg-content"); // Go to bottom
						$( "ul.msgs" ).find( "li#"  + room ).prependTo( "ul.msgs" );	// Move chat to the top
						$( "li#" + room + " .last_message" ).html( message );	// Update last message
						$( "li#" + room + " .last_message_time" ).html( time );	// Update last message time
						if( userid !== usrid ) {
							if( !$.isEmptyObject( chat_img ) ) {
								$( "#modal1 p ul" ).append( "<li class='collection-item avatar users' attr-id='" + rtarget + "'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 hide z-depth-1'>person</i><img id='chat-img-main' class='circle chat-list-photo z-depth-1' src='" + chat_img + "'><span class='title pm clickable truncate' attr-id='"+rtarget+"'>" + rtarget + "</span>"+msg.online_text+"</li>" );
							} else {
								$( "#modal1 p ul" ).append( "<li class='collection-item avatar users' attr-id='" + rtarget + "'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 z-depth-1'>person</i><img id='chat-img-main' class='circle chat-list-photo hide z-depth-1'><span class='title'>" + rtarget + "</span>"+msg.online_text+"</li>" );
							}
							if( doit == 1 && rtarget === username ) {
								$( ".not-any" ).addClass( "hide" );	// Remove "You do not have any conversations." Element
								$( "ul.msgs" ).removeClass( "hide" );	// Show Chat List Table
								if( !$.isEmptyObject( chat_img ) ) {
									$( "ul.msgs" ).prepend( "<li id='" + room + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 hide z-depth-1'>person</i><img id='chat-img-main' class='circle chat-list-photo z-depth-1' src='" + chat_img + "'><span class='title truncate'>" + name + "</span><p><div class='last_message truncate'>" + message + "</div><div class='last_message_time'>" + getTime() + "</div></p></li>" );
								} else {
									$( "ul.msgs" ).prepend( "<li id='" + room + "' class='collection-item avatar waves-effect'><i id='chat-main' class='material-icons circle chat-list-photo grey lighten-2 z-depth-1'>person</i><img id='chat-img-main' class='circle chat-list-photo hide z-depth-1'><span class='title truncate'>" + name + "</span><p><div class='last_message truncate'>" + message + "</div><div class='last_message_time'>" + getTime() + "</div></p></li>" );
								}
								
							} else if ( doit == 2 && rtarget === username ) {
								$( "#msg-content .chat-msgs" ).remove();
								$( ".send-msg" ).attr( "contenteditable", "true" );
								$( "#send-btn" ).removeClass( "disabled" );
								$( "#voice-btn" ).removeClass( "disabled" );
								$( ".click-to-toggle" ).removeClass( "hide" );
								$( "#open-emoji" ).removeClass( "disabled" );
								$( "ul.msgs" ).find( "li#" + room ).prependTo( "ul.msgs" );	// Move chat to the top
								$( "#clear-chat" ).parent().removeClass( "hide" );
								$( "#users" ).parent().removeClass( "hide" );
								$.post( "./action.php?act=chat-details", { userid: userid, username: username, token: token, chat: chat }, function( data ) {
									var response = jQuery.parseJSON( data );
									if(response.error != "") {
										Materialize.toast( response.error, 4000 );
										setTimeout(function() {
											location.reload();
										},3000);
									} else {
										$( "#modal1 p" ).html( response.user_list );
									}
								});									
								$( "#" + room + " .last_message_time" ).text( getTime() );
								$( "#" + room + " .last_message" ).html( message );
							}
						}
			}
		}
	};
	
	///////////////////////////////////////
	//////////// AJAX LOGIN ///////////////
	///////////////////////////////////////
	
	$( "body" ).on( "click", ".login", function( ev ) {
		ev.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			var email = $( "#login_input_email" ).val();
			var password = $( "#login_input_password" ).val();
			
			if ( email.length == 0 ) {
				Materialize.toast( "Please enter your Username or Email address.", 4000 );
				$(".login").removeClass("disabled");
			} else if ( password.length == 0 ) {
				Materialize.toast( "Please enter your password.", 4000 );
				$(".login").removeClass("disabled");
			} else {
				jQuery.ajax( {
					type: "POST",
					url: "./action.php?act=login",
					data:{ email: email, password: password },
					cache: false,
					dataType: "json",
					success: function( response ) {
						if( response.stat == 0 ) {
							  Materialize.toast( response.error, 4000 );
							  $(".login").removeClass("disabled");
						} else if( response.stat == 1 ) {
							window.location.href = "./?v=" + v.getTime();
						} else {
							Materialize.toast( "An unknown error has occured.", 4000 );
							$(".login").removeClass("disabled");
						}
					},
					error: function( e ){
						Materialize.toast( "An unknown error has occured.", 4000 );
						$(".login").removeClass("disabled");
					}
				});
			}
		}
	});	
	
	/////////////////////////////////////////////
	//////////// AJAX GUEST LOGIN ///////////////
	/////////////////////////////////////////////
	
	$( "body" ).on( "click", ".guest-btn", function( ev ) {
		ev.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			jQuery.ajax( {
				type: "POST",
				url: "./action.php?act=guest-login",
				cache: false,
				dataType: "json",
				success: function( response ) {
					if( response.stat == 0 ) {
						  Materialize.toast( response.error, 4000 ); // 4000 is the duration of the toast
						  $(".guest-btn").removeClass("disabled");
					} else if( response.stat == 1 ) {
						window.location.href = "./?confirm&v=" + v.getTime();
					} else {
						Materialize.toast( "An unknown error has occured.", 4000 );
						$(".guest-btn").removeClass("disabled");
					}
				},
				error: function( e ){
					Materialize.toast( "An unknown error has occured.", 4000 );
					$(".guest-btn").removeClass("disabled");
				}
			});
		}
	});	
	
	///////////////////////////////////////
	//////////// AJAX REGISTER ////////////
	///////////////////////////////////////
	
	$("body").on("click", ".register", function(ev){
		ev.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			var username = $("#reg_username").val();
			var email = $("#reg_email").val();
			var pass = $("#reg_password").val();
			var repass = $("#reg_password2").val();
			
			if( !isValidEmailAddress( email ) ) {
				Materialize.toast( "Invalid Email Address.", 4000 );
				$( "#reg_email" ).addClass( "invalid" );
				$(".register").removeClass("disabled");
			} else if( email.length == 0 ) {
				Materialize.toast( "Please enter your Email address.", 4000 );
				$( "#reg_email" ).addClass( "invalid" );
				$(".register").removeClass("disabled");
			} else if ( pass !== repass ) {
				Materialize.toast( "Your passwords are not matched.", 4000 );
				$( "#reg_password" ).addClass( "invalid" );
				$( "#reg_password2" ).addClass( "invalid" );
				$(".register").removeClass("disabled");
			} else if ( repass.length == 0 ) {
				Materialize.toast( "Please enter your Password (Repeat).", 4000 );
				$( "#reg_password2" ).addClass( "invalid" );
				$(".register").removeClass("disabled");
			} else if ( username.length == 0 ) {
				Materialize.toast( "Please enter your Username.", 4000 );
				$( "#reg_username" ).addClass( "invalid" );
				$(".register").removeClass("disabled");
			} else {
				jQuery.ajax({
					type: "POST",
					url: "./action.php?act=register",
					data:{ username: username, email: email, pass: pass, repass: repass },
					dataType: "json",
					success: function( response ) {
						if( response.stat == 0 ) {
							  Materialize.toast( response.error, 4000 ); // 4000 is the duration of the toast
							  $(".register").removeClass("disabled");
						} else if( response.stat == 1 ) {
							window.location.href = "./?v=" + v.getTime();
						} else {
							Materialize.toast( "An unknown error has occured.", 4000 );
							$(".register").removeClass("disabled");
						}
					},
					error: function(){
						Materialize.toast( "An unknown error has occured.", 4000 );
						$(".register").removeClass("disabled");
					}
				});
			}
		}
	});	
	
	////////////////////////////////////////////
	//////////// ACCOUNT ACTIVATION ////////////
	////////////////////////////////////////////
	
	$("body").on("click", "#activate-account-btn", function(ev){
		ev.preventDefault();
		var activation_code = $("#activation_code").val();
		window.location = "./action.php?act=activate&key="+activation_code+"&name="+username+"&id="+userid;
	});

	$("body").on("click", "#get_new_activation_code", function(ev){
		ev.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");			
			jQuery.ajax({
				type: "POST",
				url: "./action.php?act=new-activation-code",
				data:{ username: username, userid: userid, token: token },
				dataType: "json",
				success: function( response ) {
					if( response.stat == 0 ) {
						Materialize.toast( response.error, 4000 );
						$("#get_new_activation_code").removeClass("disabled");
					} else if( response.stat == 1 ) {
						Materialize.toast( "We sent a new activation code to your email.", 10000 );
						$("#get_new_activation_code").removeClass("disabled");
					} else {
						Materialize.toast( "An unknown error has occured.", 4000 );
						$("#get_new_activation_code").removeClass("disabled");
					}
				},
				error: function(){
					Materialize.toast( "An unknown error has occured.", 4000 );
					$("#get_new_activation_code").removeClass("disabled");
				}
			});
		}
	});	
	
	/////////////////////////////////////////
	//////////// FORGOT PASSWORD ////////////
	/////////////////////////////////////////
	
	// On "Register" Button Click
	$( "body" ).on( "click", ".forgot-password", function( ev ){
		ev.preventDefault();
		$("#registration-reveal").addClass("hide");
		$("#login-reveal").addClass("hide");
		$("#password-reveal").removeClass("hide").css({ display: "block", opacity: 0 }).velocity( "stop", !1 ).velocity({ opacity: 1 },{ duration: 300, queue: !1, easing: "easeInOutQuad"});
	});
	
	// On "X" Button Click in "Registration" Page
	$( "body" ).on( "click", ".close-password", function( ev ) {
		ev.preventDefault();
		$("#password-reveal").css({ display: "block", opacity: 1 }).velocity( "stop", !1 ).velocity({ opacity: 0 },{ duration: 300, queue: !1, easing: "easeInOutQuad", complete: function() {$(this).css("display", "none");$("#login-reveal").removeClass("hide");}});
	});
	
	$("body").on("click", "#reset-password-btn", function(ev){
		ev.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");			
			jQuery.ajax({
				type: "POST",
				url: "./action.php?act=reset-password-action",
				data:{ email: $("#pass_email").val() },
				dataType: "json",
				success: function( response ) {
					if( response.stat == 0 ) {
						Materialize.toast( response.error, 4000 );
						$("#reset-password-btn").removeClass("disabled");
					} else if( response.stat == 1 ) {
						Materialize.toast( "We sent an email to reset your password. Please check your email.", 10000 );
						$("#reset-password-btn").removeClass("disabled");
					} else {
						Materialize.toast( "An unknown error has occured.", 4000 );
						$("#reset-password-btn").removeClass("disabled");
					}
				},
				error: function(){
					Materialize.toast( "An unknown error has occured.", 4000 );
					$("#reset-password-btn").removeClass("disabled");
				}
			});
		}
	});
	
	$("body").on("click", "#save-forgotten-password", function(ev){
		ev.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");			
			jQuery.ajax({
				type: "POST",
				url: "./action.php?act=save-forgotten-password",
				data:{ pass1: $("#new_password").val(), pass2: $("#new_password2").val(), username: $("#new_pass_username").val(), user_id: $("#new_pass_user_id").val() },
				dataType: "json",
				success: function( response ) {
					if( response.stat == 0 ) {
						Materialize.toast( response.error, 4000 );
						$("#save-forgotten-password").removeClass("disabled");
					} else if( response.stat == 1 ) {
						Materialize.toast( "Your password has been successfully changed.", 3000 );
						setTimeout(function() {
							window.location = "./";
						}, 2000);
					} else {
						Materialize.toast( "An unknown error has occured.", 4000 );
						$("#save-forgotten-password").removeClass("disabled");
					}
				},
				error: function(){
					Materialize.toast( "An unknown error has occured.", 4000 );
					$("#save-forgotten-password").removeClass("disabled");
				}
			});
		}
	});
	
	////////////////////////////////////////////////
	//////////////// VOICE NOTES ///////////////////
	////////////////////////////////////////////////
	
	if(voice_notes == 1) {
		$(window).resize(function(){
			var window_width = $(window).width();
			if(window_width <= 640) {
				if(recording == true) {
					$("#send-msg").addClass("hide");
					$(".send-msg-bg").addClass("hide");
					$("#open-emoji").addClass("hide");
					$("#close-emoji").addClass("hide");
				}
			} else {
				if(recording == true) {
					$("#send-msg").removeClass("hide");
					$(".send-msg-bg").removeClass("hide");
					$("#open-emoji").removeClass("hide");
					$("#close-emoji").removeClass("hide");
				}
			}
		});
		$("body").on("click", "#voice-btn", function(){
			if( websocket.readyState == 1 ) {
				$("#voice-confirm-btn").removeClass("hide");
				$("#voice-reset-btn").removeClass("hide");
				$("#voice-recording").removeClass("hide");
				$("#send-msg").css("width", "calc( 100% - 200px )");
				$("#voice-recording-text").html("00:00:00");
				var window_width = $(window).width();
				if(window_width <= 640) {
					$("#send-msg").addClass("hide");
					$(".send-msg-bg").addClass("hide");
					$("#open-emoji").addClass("hide");
					$("#close-emoji").addClass("hide");
				}
				recording = true;
				var voice_now = Math.round(+new Date()/1000);
				voice_timer_interval = setInterval(function() {
					recorderTimer(voice_now);
				}, 1000);
				Voice.voice.record();
			} else {
				Materialize.toast( "Could not connect to WebSocket server.", 4000 );
			}
		});

		$("body").on("click", "#voice-reset-btn", function(){
			var window_width = $(window).width();
			if(window_width <= 640) {
				$("#send-msg").removeClass("hide");
				$(".send-msg-bg").removeClass("hide");
				$("#open-emoji").removeClass("hide");
				$("#close-emoji").removeClass("hide");
			}
			Voice.voice.stop();
			$("#voice-confirm-btn").addClass("hide");
			$("#voice-reset-btn").addClass("hide");
			$("#voice-recording").addClass("hide");
			$("#send-msg").css("width", "calc( 100% - 30px )");
			recording = false;
			clearInterval(voice_timer_interval);
		});
	  
		$("body").on("click", "#voice-confirm-btn", function(){
			if( websocket.readyState == 1 ) {
				var time = getTime();
				Voice.voice.export(function(url){
					$("#msg-content").append("<div class='chat-msgs'><div class='my-msgs'><div class='my-usr-msg voice-note' num='"+m+"'><p class='shared-music'><audio src='"+url+"' type='audio/wav' controls></audio></p><div class='msg-time left'>" + time + "</div><i class='material-icons right msg-icon tiny' num='"+k+"'>schedule</i></div></div></div>");
					scrollToBottom("#msg-content");
					var window_width = $(window).width();
					if(window_width <= 640) {
						$("#send-msg").removeClass("hide");
						$(".send-msg-bg").removeClass("hide");
						$("#open-emoji").removeClass("hide");
						$("#close-emoji").removeClass("hide");
					}
					
					$("#voice-confirm-btn").addClass("hide");
					$("#voice-reset-btn").addClass("hide");
					$("#voice-recording").addClass("hide");
					$("#send-msg").css("width", "calc( 100% - 30px )");
					recording = false;
					clearInterval(voice_timer_interval);
				}, "URL");
				Voice.voice.export(function(blob){
					var formData = new FormData();
					formData.append('voice-recorder-file', blob);
					formData.append('voice-recorder-token', token);
					formData.append('voice-recorder-userid', userid);
					formData.append('voice-recorder-username', username);
					formData.append('voice-recorder-room', chat);				

					$.ajax({
						url: "./action.php?act=voice-note",
						processData: false,
						contentType: false,
						dataType: "json",
						type: "POST",
						data: formData,
						success:function(data){
							if(data.stat == 0) {
								Materialize.toast( data.error, 4000 );
								$(".voice-note[num='" + m + "'] i.msg-icon").html("priority_high").css( "color", "red" );
							} else {
								var send_file = {
										name: username,	// Username
										iduser: userid,	// User ID
										msgtype: "8",	// Send Photo
										token: token,	// Token (For Security)
										share_media: data.file,	// Chat Picture
										room: chat,
										rmtype: rmtyp,
										k: k,
										file: "voice_note",
										time: time
									};
								var json_send_file = JSON.stringify( send_file );							
								websocket.send( json_send_file );
							}
						},
						error:function(e){
							$(".voice-note[num='" + m + "'] i.msg-icon").text( "priority_high" ).css( "color", "red" );
						}
					});
				}, "blob");
			} else {
				Materialize.toast( "Could not connect to WebSocket server.", 4000 );
			}
			m++;
			k++;
			Voice.voice.stop();
		});
	}
	
	////////////////////////////////////////////////////
	//////////////// CHANGE PASSWORD ///////////////////
	////////////////////////////////////////////////////
	
	$("#modal11").modal({
		complete: function() {
			$("#current_pass").val("");
			$("#new_pass_1").val("");
			$("#new_pass_2").val("");
		}
	});
	
	$("body").on("click", "#change-password", function(e) {
		e.preventDefault();
		$("#modal11").modal("open");
	});
	
	$("body").on("click", "#change-password-save", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			var current_pass = $("#current_pass").val();
			var new_pass_1 = $("#new_pass_1").val();
			var new_pass_2 = $("#new_pass_2").val();
			if(new_pass_1 == new_pass_2) {
				$.post( "./action.php?act=change-password", { userid: userid, token: token, username: username, current_pass: current_pass, new_pass_1: new_pass_1, new_pass_2: new_pass_2 }).done(function( stat ) {
					var res = jQuery.parseJSON(stat);
					if( res.stat == 1 ) {
						Materialize.toast( "Your password is successfully saved.", 4000 );
						$("#modal11").modal("close");
					} else {
						Materialize.toast( res.error, 4000 );
					}
					$("#change-password-save").removeClass("disabled");
				});
			} else {
				Materialize.toast( "New passwords are not the same.", 4000 );
				$("#change-password-save").removeClass("disabled");
			}
		}
	});
	
	///////////////////////////////////////
	//////////// OTHERS ///////////////////
	///////////////////////////////////////

	$('#modal1').modal();
	$('.carousel.carousel-slider').carousel({full_width: true});
	$('.photo-link').simpleLightbox();
	$('.tooltipped').tooltip({delay: 50});
	
	$( "body" ).on( "click", "#cdropdown1", function( ev ) {
		ev.preventDefault();
		
		if( open1 == false ) {
			if($(".online-users").hasClass("opened")) {
				closeSideCard( ".online-users" );
				$(".online-users").removeClass("opened");
			}
			if($(".friends").hasClass("opened")) {
				closeSideCard( ".friends" );
				$(".friends").removeClass("opened");
			}
			$( "#dropdown1" ).stop(true, true).css('opacity', 0).slideDown({
				queue: false,
				duration: 300,
				easing: 'easeOutCubic'
			}).animate( {opacity: 1}, {queue: false, duration: 300, easing: 'easeOutSine'});
			open1 = true;
		}
	});
	$( "body" ).on( "click", "#add-btn", function( ev ) {
		ev.preventDefault();
		if( open2 == false ) {
			if($(".online-users").hasClass("opened")) {
				$(".online-users").removeClass("opened");
				closeSideCard( ".online-users" );
			}
			if($(".friends").hasClass("opened")) {
				$(".friends").removeClass("opened");
				closeSideCard( ".friends" );
			}
			$( "#dropdown2" ).stop(true, true).css({'opacity': 0, 'width': '112px'}).slideDown({
				queue: false,
				duration: 300,
				easing: 'easeOutCubic'
			}).animate( {opacity: 1}, {queue: false, duration: 300, easing: 'easeOutSine'});
			open2 = true;
		}
	});
	$( "body" ).on( "click", "#cdropdown3", function( ev ) {
		ev.preventDefault();
		if( open3 == false ) {
			$( "#dropdown3" ).stop(true, true).css({'opacity': 0, 'width': '140px'}).slideDown({
				queue: false,
				duration: 300,
				easing: 'easeOutCubic'
			}).animate( {opacity: 1}, {queue: false, duration: 300, easing: 'easeOutSine'});
			open3 = true;
		}
	});
	$( "body" ).on( "click", "#cdropdown4", function( ev ) {
		ev.preventDefault();
		if( open4 == false ) {
			$( "#dropdown4" ).stop(true, true).css('opacity', 0).slideDown({
				queue: false,
				duration: 300,
				easing: 'easeOutCubic'
			}).animate( {opacity: 1}, {queue: false, duration: 300, easing: 'easeOutSine'});
			open4 = true;
		}
	});
	$( "body" ).on( "click", "#cdropdown5", function( ev ) {
		ev.preventDefault();
		if( open5 == false ) {
			$( "#dropdown5" ).stop(true, true).css('opacity', 0).slideDown({
				queue: false,
				duration: 300,
				easing: 'easeOutCubic'
			}).animate( {opacity: 1}, {queue: false, duration: 300, easing: 'easeOutSine'});
			open5 = true;
			$( ".chat-change-pp" ).addClass( "active-pp" );
		}
	});
	$( "body" ).on( "click", "#cdropdown6", function( ev ) {
		ev.preventDefault();
		if( open6 == false ) {
			$( "#dropdown6" ).stop(true, true).css('opacity', 0).slideDown({
				queue: false,
				duration: 300,
				easing: 'easeOutCubic'
			}).animate( {opacity: 1}, {queue: false, duration: 300, easing: 'easeOutSine'});
			open6 = true;
		}
	});

	$(document).mousedown(function (e) {
		if ($("#dropdown1").has(e.target).length === 0) {
			if( open1 == true ) {				
				$("#dropdown1").stop(true, true).css('opacity', 1).slideUp({
					queue: false,
					duration: 225,
					easing: 'easeOutCubic',
					complete: function() {
						$(this).css('height', '');
						open1 = false;
					}
				}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
			}
		}
		if ($("#dropdown2").has(e.target).length === 0) {
			if( open2 == true ) {				
				$("#dropdown2").stop(true, true).css('opacity', 1).slideUp({
					queue: false,
					duration: 225,
					easing: 'easeOutCubic',
					complete: function() {
						$(this).css('height', '');
						open2 = false;
					}
				}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
			}
		}
		if ($("#dropdown3").has(e.target).length === 0) {
			if( open3 == true ) {				
				$("#dropdown3").stop(true, true).css('opacity', 1).slideUp({
					queue: false,
					duration: 225,
					easing: 'easeOutCubic',
					complete: function() {
						$(this).css('height', '');
						open3 = false;
					}
				}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
			}
		}
		if ($("#dropdown4").has(e.target).length === 0) {
			if( open4 == true ) {				
				$("#dropdown4").stop(true, true).css('opacity', 1).slideUp({
					queue: false,
					duration: 225,
					easing: 'easeOutCubic',
					complete: function() {
						$(this).css('height', '');
						open4 = false;
					}
				}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
			}
		}
		if ($("#dropdown5").has(e.target).length === 0) {
			if( open5 == true ) {				
				$("#dropdown5").stop(true, true).css('opacity', 1).slideUp({
					queue: false,
					duration: 225,
					easing: 'easeOutCubic',
					complete: function() {
						$(this).css('height', '');
						open5 = false;
						$( ".chat-change-pp" ).removeClass( "active-pp" );
					}
				}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
			}
		}
		if ($("#dropdown6").has(e.target).length === 0) {
			if( open6 == true ) {				
				$("#dropdown6").stop(true, true).css('opacity', 1).slideUp({
					queue: false,
					duration: 225,
					easing: 'easeOutCubic',
					complete: function() {
						$(this).css('height', '');
						open6 = false;
					}
				}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
			}
		}
		if ($(".fixed-action-btn ul").has(e.target).length === 0) {
			if ($(".fixed-action-btn").hasClass('active') === true) {
				$('.fixed-action-btn').closeFAB();
			}
		}
	});
	
	$("body").on("click", ".fixed-action-btn", function() {
		if( chat_side == true ) {
			closeSideCard( ".chat-settings" );
			
			if( !jQuery.isEmptyObject( pic_prev_chat )) {
				$( "#chat-img" ).attr( "src", pic_prev_chat );
			} else {
				$( "#chat-img" ).attr( "src", "" ).addClass( "hide" );
				$( "i#chat" ).removeClass( "hide" );
			}
			$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});
			$( "#msg-content" ).velocity({ width: "100%" }, { duration: 300, easing: "easeInOutQuad"} );
			$( ".msg-box" ).velocity({ width: "100%" }, { duration: 300, easing: "easeInOutQuad"} );
			
			pic_prev_chat = null;
			chat_side = false;
		}
	});
	
	$("body").on("click","#dropdown1 a", function() {
		if( open1 == true ) {				
			$("#dropdown1").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					open1 = false;
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
	});
	
	$("body").on("click","#dropdown2 a", function() {
		if( open2 == true ) {				
			$("#dropdown2").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					open2 = false;
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
	});
	
	$("body").on("click","#dropdown3 a", function() {
		if( open3 == true ) {				
			$("#dropdown3").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					open3 = false;
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
	});
	
	$("body").on("click","#dropdown4 a", function() {
		if( open4 == true ) {				
			$("#dropdown4").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					open4 = false;
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
	});
	
	$("body").on("click","#dropdown5 a", function() {
		if( open5 == true ) {				
			$("#dropdown5").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					open5 = false;
					$( ".chat-change-pp" ).removeClass( "active-pp" );
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
	});
	
	$("body").on("click","#dropdown6 a", function() {
		if( open6 == true ) {				
			$("#dropdown6").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					open6 = false;
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
	});
	
	$('.send-msg').perfectScrollbar();
	$('#msg-content').perfectScrollbar();
	$(".emoji-tab").perfectScrollbar();
	$(".card-reveal").perfectScrollbar();
	$(".invite-reveal").perfectScrollbar();
	$(".share-img-list").perfectScrollbar();
	$(".share-video-list").perfectScrollbar();
	$(".share-file-list").perfectScrollbar();
	$(".share-music-list").perfectScrollbar();

	$( window ).resize(function() {
		$('.send-msg').perfectScrollbar("update");
		$('#msg-content').perfectScrollbar("update");
		$(".emoji-tab").perfectScrollbar("update");
		$(".card-reveal").perfectScrollbar("update");
		$(".invite-reveal").perfectScrollbar("update");
		$(".share-img-list").perfectScrollbar("update");
		$(".share-video-list").perfectScrollbar("update");
		$(".share-file-list").perfectScrollbar("update");
		$(".share-music-list").perfectScrollbar("update");
		if( emoji == true ) {
			$("#msg-content").css("height", "calc(100% - 425px)");
		}
		var vid_disp_height = $(window).height() - 20;
		var vid_disp_width = $(window).width() - 20;
		$(".slbContent #vid-disp").css({"height": vid_disp_height, "width": vid_disp_width});
	});	
	
	$('#send-msg').on('paste', function(e) {
		e.preventDefault();
		var text = '';
		if (e.clipboardData || e.originalEvent.clipboardData) {
		  text = (e.originalEvent || e).clipboardData.getData('text/plain');
		} else if (window.clipboardData) {
		  text = window.clipboardData.getData('Text');
		}
		if (document.queryCommandSupported('insertText')) {
		  document.execCommand('insertText', false, text);
		} else {
		  document.execCommand('paste', false, text);
		}
	});
});


