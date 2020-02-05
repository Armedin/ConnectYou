var openChangepp = false;

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

function convertMonth( num ) {		
	switch(num) {
		case 1:
			month = "Jan";
			break;
		case 2:
			month = "Feb";
			break;
		case 3:
			month = "Mar";
			break;
		case 4:
			month = "Apr";
			break;
		case 5:
			month = "May";
			break;
		case 6:
			month = "June";
			break;
		case 7:
			month = "July";
			break;
		case 8:
			month = "Aug";
			break;
		case 9:
			month = "Sept";
			break;
		case 10:
			month = "Oct";
			break;
		case 11:
			month = "Nov";
			break;
		case 12:
			month = "Dec";
			break;
	}
	return month;
}

function scrollToBottom( element_id ) {
	$( element_id ).scrollTop( $( element_id ).prop( "scrollHeight" ) );
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

$( document ).ready( function() {
	
	websocket = new ReconnectingWebSocket( wsUri );
	
	$(".user-submit").on("click", function(e) {
		e.preventDefault();
		var search = $("#user-search").val();
		if(search.length > 0) {
			window.location = "./index.php?search=" + search;
		} else {
			Materialize.toast('Please type a user to the search box.', 4000);
		}
	});
	
	$(".user-submit-chat").on("click", function(e) {
		e.preventDefault();
		var search = $("#user-search").val();
		if(search.length > 0) {
			window.location = "./index.php?chat-search=" + search;
		} else {
			Materialize.toast('Please type a chat room to the search box.', 4000);
		}
	});
	
	$(".user-submit-msg").on("click", function(e) {
		e.preventDefault();
		var search = $("#user-search").val();
		if(search.length > 0) {
			window.location = "./index.php?message=" + search;
		} else {
			Materialize.toast('Please type a message to the search box.', 4000);
		}
	});
	
	$(".pagination li.disabled").on("click", function(e) {
		e.preventDefault();
	});
	$(".button-collapse").sideNav();
	//$(".left-nav").sideNav().sideNav("show");
	
	$( "#cchange-pp-dropdown" ).on( "click", function( ev ) {
		ev.preventDefault();
		$( ".change-pp" ).addClass( "active-pp" );
	});

	$( "#cchange-pp-create-dropdown" ).on( "click", function( ev ) {
		ev.preventDefault();
		$( ".change-pp-create" ).addClass( "active-pp" );
	});
	 
	$(document).click( function( e ) {
		if ($(e.target).closest( "#cchange-pp-dropdown" ).length === 0) {
			$( ".change-pp" ).removeClass( "active-pp" );
		}
		if ($(e.target).closest( "#cchange-pp-create-dropdown" ).length === 0) {
			$( ".change-pp-create" ).removeClass( "active-pp" );
		}
	});
	
	$( "body" ).on( "click", "#cchange-pp-dropdown", function( ev ) {
		ev.preventDefault();
		$( ".change-pp" ).addClass( "active-pp" );
		if( openChangepp == false ) {
			$( "#change-pp-dropdown" ).stop(true, true).css('opacity', 0).slideDown({
				queue: false,
				duration: 300,
				easing: 'easeOutCubic'
			}).animate( {opacity: 1}, {queue: false, duration: 300, easing: 'easeOutSine'});
			openChangepp = true;
		}
	}).on( "click", "#cchange-pp-create-dropdown", function( ev ) {
		ev.preventDefault();
		$( ".change-pp-create" ).addClass( "active-pp" );
		if( openChangepp == false ) {
			$( "#change-pp-create-dropdown" ).stop(true, true).css('opacity', 0).slideDown({
				queue: false,
				duration: 300,
				easing: 'easeOutCubic'
			}).animate( {opacity: 1}, {queue: false, duration: 300, easing: 'easeOutSine'});
			openChangepp = true;
		}
	}).on( "click", "#cchange-chat-dropdown", function( ev ) {
		ev.preventDefault();
		$( ".change-chat" ).addClass( "active-pp" );
		if( openChangepp == false ) {
			$( "#change-chat-dropdown" ).stop(true, true).css('opacity', 0).slideDown({
				queue: false,
				duration: 300,
				easing: 'easeOutCubic'
			}).animate( {opacity: 1}, {queue: false, duration: 300, easing: 'easeOutSine'});
			openChangepp = true;
		}
	});
	
	$(document).mousedown(function (e) {
		if ($("#change-pp-dropdown").has(e.target).length === 0) {
			$( ".change-pp" ).removeClass( "active-pp" );
			if( openChangepp == true ) {				
				$("#change-pp-dropdown").stop(true, true).css('opacity', 1).slideUp({
					queue: false,
					duration: 225,
					easing: 'easeOutCubic',
					complete: function() {
						$(this).css('height', '');
						openChangepp = false;
					}
				}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
			}
		}
		if ($("#change-chat-dropdown").has(e.target).length === 0) {
			$( ".change-chat" ).removeClass( "active-pp" );
			if( openChangepp == true ) {				
				$("#change-chat-dropdown").stop(true, true).css('opacity', 1).slideUp({
					queue: false,
					duration: 225,
					easing: 'easeOutCubic',
					complete: function() {
						$(this).css('height', '');
						openChangepp = false;
					}
				}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
			}
		}
		if ($("#change-pp-create-dropdown").has(e.target).length === 0) {
			$( ".change-pp-create" ).removeClass( "active-pp" );
			if( openChangepp == true ) {				
				$("#change-pp-create-dropdown").stop(true, true).css('opacity', 1).slideUp({
					queue: false,
					duration: 225,
					easing: 'easeOutCubic',
					complete: function() {
						$(this).css('height', '');
						openChangepp = false;
					}
				}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
			}
		}
	});
	
	var old_username = null, old_email = null, old_owner = null, old_owner_id = null, old_groupname = null, old_status = null;
	
	$("body").on( "click", "#edit-username", function( ev ) {
		ev.preventDefault();
		old_username = $("#username").val();
		$("#username").attr("disabled", false);
		$(this).addClass("hide");
		$("#reset-username").removeClass("hide");
		$("#confirm-username").removeClass("hide");
		Materialize.updateTextFields();
	}).on( "click", "#edit-email", function( ev ) {
		ev.preventDefault();
		old_email = $("#user-email").val();
		$("#user-email").attr("disabled", false);
		$(this).addClass("hide");
		$("#reset-email").removeClass("hide");
		$("#confirm-email").removeClass("hide");
		Materialize.updateTextFields();
	}).on( "click", "#edit-status", function( ev ) {
		ev.preventDefault();
		old_status = $("#user-status").val();
		$("#user-status").attr("disabled", false);
		$(this).addClass("hide");
		$("#reset-status").removeClass("hide");
		$("#confirm-status").removeClass("hide");
		Materialize.updateTextFields();
	}).on( "click", "#edit-owner", function( ev ) {
		ev.preventDefault();
		old_owner = $("#owner-name").val();
		old_owner_id = $("#owner-name-id").val();
		$("#owner-name").attr("disabled", false);
		$(this).addClass("hide");
		$("#reset-owner").removeClass("hide");
		$("#confirm-owner").removeClass("hide");
		Materialize.updateTextFields();
	}).on( "click", "#edit-groupname", function( ev ) {
		ev.preventDefault();
		old_groupname = $("#groupname").val();
		$("#groupname").attr("disabled", false);
		$(this).addClass("hide");
		$("#reset-groupname").removeClass("hide");
		$("#confirm-groupname").removeClass("hide");
		Materialize.updateTextFields();
	});	
	
	$("body").on( "click", "#reset-username", function( ev ) {
		ev.preventDefault();
		$("#username").attr("disabled", true).val(old_username);
		$("#edit-username").removeClass("hide");
		$("#reset-username").addClass("hide");
		$("#confirm-username").addClass("hide");
		Materialize.updateTextFields();
	}).on( "click", "#reset-status", function( ev ) {
		ev.preventDefault();
		$("#user-status").attr("disabled", true).val(old_status);
		$("#edit-status").removeClass("hide");
		$("#reset-status").addClass("hide");
		$("#confirm-status").addClass("hide");
		Materialize.updateTextFields();
	}).on( "click", "#reset-email", function( ev ) {
		ev.preventDefault();
		$("#user-email").attr("disabled", true).val(old_email);
		$("#edit-email").removeClass("hide");
		$("#reset-email").addClass("hide");
		$("#confirm-email").addClass("hide");
		Materialize.updateTextFields();
	}).on( "click", "#reset-owner", function( ev ) {
		ev.preventDefault();
		$("#owner-name").attr("disabled", true).val(old_owner);
		$("#owner-name-id").val(old_owner_id);
		$("#edit-owner").removeClass("hide");
		$("#reset-owner").addClass("hide");
		$("#confirm-owner").addClass("hide");
		$( "#admin_user_search_results" ).addClass("hide");
		$( "#admin_user_search_results .search_result" ).html( "" );
		Materialize.updateTextFields();
	}).on( "click", "#reset-groupname", function( ev ) {
		ev.preventDefault();
		$("#groupname").attr("disabled", true).val(old_groupname);
		$("#edit-groupname").removeClass("hide");
		$("#reset-groupname").addClass("hide");
		$("#confirm-groupname").addClass("hide");
		Materialize.updateTextFields();
	});	
	
	$("body").on( "click", "#confirm-username", function( ev ) {
		ev.preventDefault();
		var new_username = $("#username").val();
		if (new_username.length > 0) {
			if( websocket.readyState == 1 ) {	// Check the connection
				var change_group_name = {
					name: username,	// Username
					iduser: userid,	// User ID
					token: token,	// Token (For Security)
					new_un: new_username,
					target: getParameterByName("manage-user"),
					msgtype: 10
				};
				var json_change_group_name = JSON.stringify( change_group_name );							
				websocket.send( json_change_group_name );
			} else {
				Materialize.toast( "Could not connect to the server.", 4000 );
				$("#username").attr("disabled", true).val(old_username);
				$("#edit-username").removeClass("hide");
				$("#reset-username").addClass("hide");
				$("#confirm-username").addClass("hide");
				Materialize.updateTextFields();
			}
		} else {
			Materialize.toast( "Please type a username.", 4000 );
			Materialize.updateTextFields();
		}
	}).on( "click", "#confirm-status", function( ev ) {
		ev.preventDefault();
		var new_status = $("#user-status").val();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			$.ajax({
				url: "./admin-action.php?act=change-user-status",
				data: {username: username, userid: userid, token: token, new_status: new_status, target_id: getParameterByName("manage-user")},
				type: "POST",
				success: function( data ) {
					var response = jQuery.parseJSON( data );
					if(response.stat == 1) {
						Materialize.toast( "Successfully saved.", 4000 );
						$("#user-status").attr("disabled", true).val(response.user_status);
						Materialize.updateTextFields();
						$("#confirm-status").removeClass("disabled").addClass("hide");
						$("#edit-status").removeClass("hide");
						$("#reset-status").addClass("hide");
					} else {
						Materialize.toast( response.error, 4000 );
						$("#confirm-status").removeClass("disabled");
					}
				},
				error: function() {
					Materialize.toast( "An error occured.", 4000 );
					$("#confirm-status").removeClass("disabled");
				}
			});
		}
	}).on( "click", "#confirm-groupname", function( ev ) {
		ev.preventDefault();
		var groupname = $("#groupname").val();
		if (groupname.length > 0) {
			if( websocket.readyState == 1 ) {	// Check the connection
				var change_group_name = {
					name: username,	// Username
					iduser: userid,	// User ID
					token: token,	// Token (For Security)
					edit_grp_name: groupname,
					msgtype: 18,
					non_room: getParameterByName("manage-chat")
					
				};
				var json_change_group_name = JSON.stringify( change_group_name );							
				websocket.send( json_change_group_name );
			} else {
				Materialize.toast( "Could not connect to the server.", 4000 );
				$("#groupname").attr("disabled", true).val(old_groupname);
				$("#edit-groupname").removeClass("hide");
				$("#reset-groupname").addClass("hide");
				$("#confirm-groupname").addClass("hide");
				Materialize.updateTextFields();
			}
		} else {
			
		}
	}).on( "click", "#confirm-email", function( ev ) {
		ev.preventDefault();
		var email = $("#user-email").val();
		if (email.length > 0) {
			if( websocket.readyState == 1 ) {
				var change_group_name = {
					name: username,	// Username
					iduser: userid,	// User ID
					token: token,	// Token (For Security)
					new_email: email,
					target: getParameterByName("manage-user"),
					msgtype: 11
				};
				var json_change_group_name = JSON.stringify( change_group_name );							
				websocket.send( json_change_group_name );
			} else {
				Materialize.toast( "Could not connect to the server.", 4000 );
				$("#user-email").attr("disabled", true).val(old_email);
				$("#edit-email").removeClass("hide");
				$("#reset-email").addClass("hide");
				$("#confirm-email").addClass("hide");
				Materialize.updateTextFields();
			}
		} else {
			Materialize.toast( "Please type an email address.", 4000 );
			Materialize.updateTextFields();
		}
	}).on( "click", "#confirm-owner", function( ev ) {
		ev.preventDefault();
		var owner_name = $("#owner-name").val();
		var owner_id = $("#owner-name-id").val();
		if (owner_name.length > 0) {
			$.ajax({
				url: "./admin-action.php?act=change-owner",
				data: {username: username, userid: userid, token: token, owner_name: owner_name, owner_id: owner_id, chat_id: getParameterByName("manage-chat")},
				type: "POST",
				success: function( data ) {
					if(data == 1) {
						Materialize.toast( "Successfully saved.", 4000 );
						$("#owner-name").attr("disabled", true).val(owner_name);
						$("#owner-name-id").val(owner_id);
						$("#edit-owner").removeClass("hide");
						$("#reset-owner").addClass("hide");
						$("#confirm-owner").addClass("hide");
						$( "#admin_user_search_results" ).addClass("hide");
						$( "#admin_user_search_results .search_result" ).html( "" );
						Materialize.updateTextFields();
					} else {
						Materialize.toast( "An error occured.", 4000 );
					}
				},
				error: function() {
					Materialize.toast( "An error occured.", 4000 );
				}
			});
		} else {
			Materialize.toast( "Please type a username.", 4000 );
			Materialize.updateTextFields();
		}
	});
	
	$("body").on( "click", "#add-ip-address", function( ev ) {
		ev.preventDefault();
		var ip_address = $("#ban_ip_input").val();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if (ip_address.length > 0) {
				$.ajax({
					url: "./admin-action.php?act=ban-ip-address",
					data: {username: username, userid: userid, token: token, ip_address: ip_address},
					type: "POST",
					success: function( data ) {
						$("#add-ip-address").removeClass("disabled");
						if(data > 0) {
							Materialize.toast( "Successfully added.", 4000 );
							$("#ban_ip_input").val("");
							if($("#banned_ip_list tr").length > 2) {
								$("#banned_ip_list #banned_ip_list_tbody").prepend("<tr><td>" + ip_address + "</td><td><p><i attr-id='" + data + "' id='delete-ip' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></p></td></tr>");
							} else {
								$("#banned_ip_list").removeClass("hide");
								$("#banned_ip_list #banned_ip_list_tbody").prepend("<tr><td>" + ip_address + "</td><td><p><i attr-id='" + data + "' id='delete-ip' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></p></td></tr>");
							}
							Materialize.updateTextFields();
						} else if (data == -1) {
							Materialize.toast( "You cannot ban your own IP address.", 4000 );
						} else {
							Materialize.toast( "That IP address is already exist.", 4000 );
						}
					},
					error: function() {
						Materialize.toast( "An error occured.", 4000 );
						$("#add-ip-address").removeClass("disabled");
					}
				});
			} else {
				Materialize.toast( "Please type an ip address.", 4000 );
				$("#add-ip-address").removeClass("disabled");
				Materialize.updateTextFields();
			}
		}
	});
	
	$("body").on( "click", "#add-word-filter", function( ev ) {
		ev.preventDefault();
		var ban_words_1 = $("#ban_words_1").val();
		var ban_words_2 = $("#ban_words_2").val();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if (ban_words_1.length > 0) {
				$.ajax({
					url: "./admin-action.php?act=ban-words",
					data: {username: username, userid: userid, token: token, ban_words_1: ban_words_1, ban_words_2: ban_words_2},
					type: "POST",
					success: function( data ) {
						$("#add-word-filter").removeClass("disabled");
						if(data > 0) {
							Materialize.toast( "Successfully added.", 4000 );
							$("#ban_words_1").val("");
							$("#ban_words_2").val("");
							if($("#banned_word_list tr").length > 2) {
								$("#banned_word_list #banned_words_tbody").prepend("<tr><td>" + ban_words_1 + "</td><td>" + ban_words_2 + "</td><td><p><i attr-id='" + data + "' id='delete-word' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></p></td></tr>");
							} else {
								$("#banned_word_list").removeClass("hide");
								$("#banned_word_list #banned_words_tbody").prepend("<tr><td>" + ban_words_1 + "</td><td>" + ban_words_2 + "</td><td><p><i attr-id='" + data + "' id='delete-word' class='material-icons small clickable red-text text-darken-3'>delete_forever</i></p></td></tr>");
							}
							Materialize.updateTextFields();
						} else {
							Materialize.toast( "That word is already exist.", 4000 );
						}
					},
					error: function() {
						Materialize.toast( "An error occured.", 4000 );
						$("#add-word-filter").removeClass("disabled");
					}
				});
			} else {
				Materialize.toast( "Please type a word to filter.", 4000 );
				$("#add-word-filter").removeClass("disabled");
				Materialize.updateTextFields();
			}
		}
	});
	
	$('select').material_select();
	$('.modal').modal();
	$("body").on("click", "#save-status", function(e) {
		e.preventDefault();
		if($("#off").is(":checked")) {
			var status = 0;
		} else {
			var status = 1;
		}
		$.ajax({
			url: "./admin-action.php?act=save-status",
			data: {username: username, userid: userid, token: token, status: status, target_id: getParameterByName("manage-user")},
			type: "POST",
			success: function( data ) {
				if(data == 1) {
					Materialize.toast( "Successfully saved.", 4000 );
					if(status == 1) {
						$("#title-status").removeClass("red-text").addClass("green-text").text("Online");
					} else {
						$("#title-status").removeClass("green-text").addClass("red-text").text("Offline");
					}
				} else {
					Materialize.toast( "An error occured.", 4000 );
				}
				
			},
			error: function() {
				Materialize.toast( "An error occured.", 4000 );
			}
		});
	});
	
	$("body").on("click", "#delete-message", function(e) {
		e.preventDefault();
		var msg_id = $(this).attr("attr-id");
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			$.ajax({
				url: "./admin-action.php?act=delete-message",
				data: {username: username, userid: userid, token: token, msg_id: msg_id},
				type: "POST",
				success: function( data ) {
					if(data == 1) {
						if(getParameterByName("manage-chat") == null || getParameterByName("manage-chat") == "" || getParameterByName("manage-chat") == undefined) {
							$("table td i[attr-id='"+msg_id+"']").parent().parent().parent().css({"background-color": "#26a69a", "opacity": 1}).velocity( "stop", !1 ).velocity( { opacity: 0}, { duration: 850, queue: !1, easing: "easeInOutQuad", complete: function() {if($("table tr").length > 2) {$(this).remove();} else {$(this).parent().parent().parent().parent().parent().parent().remove();}} } )
						} else {
							$("#delete-message[attr-id='"+msg_id+"']").parent().parent().css("background-color", "#26a69a").parent().css({"opacity": 1}).velocity( "stop", !1 ).velocity( { opacity: 0}, { duration: 850, queue: !1, easing: "easeInOutQuad", complete: function() {$(this).remove();} } )
						}
					} else {
						Materialize.toast( "An error occured.", 4000 );
						$("#delete-message").removeClass("disabled");
					}
					
				},
				error: function() {
					Materialize.toast( "An error occured.", 4000 );
					$("#delete-message").removeClass("disabled");
				}
			});
		}
	});
	
	$("body").on("click", "#delete-ip", function(e) {
		e.preventDefault();
		var ip_id = $(this).attr("attr-id");
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			$.ajax({
				url: "./admin-action.php?act=delete-ip",
				data: {username: username, userid: userid, token: token, ip_id: ip_id},
				type: "POST",
				success: function( data ) {
					if(data == 1) {
						$("table td i[attr-id='"+ip_id+"']").parent().parent().parent().css({"background-color": "#26a69a", "opacity": 1}).velocity( "stop", !1 ).velocity( { opacity: 0}, { duration: 850, queue: !1, easing: "easeInOutQuad", complete: function() {$(this).remove();if($("#banned_ip_list table tr").length < 3) {$("#banned_ip_list").addClass("hide");}} } );
					} else {
						Materialize.toast( "An error occured.", 4000 );
						$("#delete-ip").removeClass("disabled");
					}
				},
				error: function() {
					Materialize.toast( "An error occured.", 4000 );
					$("#delete-ip").removeClass("disabled");
				}
			});
		}
	});
	
	$("body").on("click", "#delete-word", function(e) {
		e.preventDefault();
		var word_id = $(this).attr("attr-id");
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			$.ajax({
				url: "./admin-action.php?act=delete-word",
				data: {username: username, userid: userid, token: token, word_id: word_id},
				type: "POST",
				success: function( data ) {
					if(data == 1) {
						$("table td i[attr-id='"+word_id+"']").parent().parent().parent().css({"background-color": "#26a69a", "opacity": 1}).velocity( "stop", !1 ).velocity( { opacity: 0}, { duration: 850, queue: !1, easing: "easeInOutQuad", complete: function() {$(this).remove();if($("#banned_word_list table tr").length < 3) {$("#banned_word_list").addClass("hide");}} } );
					} else {
						Materialize.toast( "An error occured.", 4000 );
						$("#delete-word_id").removeClass("disabled");
					}
				},
				error: function() {
					Materialize.toast( "An error occured.", 4000 );
					$("#delete-word_id").removeClass("disabled");
				}
			});
		}
	});

	$("body").on("click", "#save-type", function(e) {
		e.preventDefault();
		switch($("#select_user_type").val()) {
			case "Admin":
				var user_type = 2;
				break;
			case "Member":
				var user_type = 1;
				break;
			default:
				var user_type = 0;
				break;
		}
		if($("#on-ac").is(":checked")) {
			var activated = 1;
		} else {
			var activated = 2;
		}

		$.ajax({
			url: "./admin-action.php?act=save-type",
			data: {username: username, userid: userid, token: token, user_type: user_type, target_id: getParameterByName("manage-user"), activated: activated},
			type: "POST",
			success: function( data ) {
				if(data == 1) {
					Materialize.toast( "Successfully saved.", 4000 );
					if( websocket.readyState == 1 ) {
						var msg = {
							name: username,	// Username
							iduser: userid,	// User ID
							token: token,	// Token (For Security)
							target: getParameterByName("manage-user"),
							msgtype: 9
						};
						var json_msg = JSON.stringify( msg );							
						websocket.send( json_msg );
					}
				} else {
					Materialize.toast( "An error occured.", 4000 );
				}
			},
			error: function() {
				Materialize.toast( "An error occured.", 4000 );
			}
		});
	});

	$("body").on("click", "#save-password", function(e) {
		e.preventDefault();
		var new_pass = $("#new_pass").val();
		var new_pass2 = $("#new_pass_2").val();
		
		if(new_pass !== new_pass2) {
			Materialize.toast( "Passwords are not matched.", 4000 );
		} else if(new_pass.length < 3) {
			Materialize.toast( "Password must be at least 3 characters.", 4000 );
		} else if(new_pass.length > 128) {
			Materialize.toast( "Password must be shorter than 128 characters.", 4000 );
		} else {
			$.ajax({
				url: "./admin-action.php?act=save-password",
				data: {username: username, userid: userid, token: token, password: new_pass, password2: new_pass2, target_id: getParameterByName("manage-user")},
				type: "POST",
				success: function( data ) {
					if(data == 1) {
						Materialize.toast( "Successfully saved.", 4000 );
					} else if(data == -3) {
						Materialize.toast( "Passwords are not matched.", 4000 );
					} else if(data == -1) {
						Materialize.toast( "Password must be shorter than 128 characters.", 4000 );
					} else if(data == -2) {
						Materialize.toast( "Password must be at least 3 characters.", 4000 );
					} else {
						Materialize.toast( "An error occured.", 4000 );
					}
				},
				error: function() {
					Materialize.toast( "An error occured.", 4000 );
				}
			});
		}
	});

	var pic_prev = null;
	$("body").on("click", "#upload-photo", function(e) {
		e.preventDefault();
		$( "#upload-pp-admin" ).trigger( "click" );
	}).on("click", "#upload-photo-create", function(e) {
		e.preventDefault();
		$( "#upload-pp-create" ).trigger( "click" );
	}).on("change", "#upload-pp-admin", function() {
		pic_prev = $( "#pp-img-main" ).attr( "src" );
		if ( this.files && this.files[0] ) {
            var reader = new FileReader();
            reader.onload = function ( e ) {
                $( "#pp-img-main" ).attr( "src", e.target.result ).removeClass( "hide" );
				$( "i#pp-main" ).addClass( "hide" );
				$( "#save-pp-ul" ).css("display", "block");
				Materialize.fadeInImage("#save-pp-ul");
				$( "#pp-act-div" ).removeClass( "hide" );
            }
            reader.readAsDataURL(this.files[0]);
        }
		$( ".change-pp" ).removeClass( "active-pp" );
		if( openChangepp == true ) {				
			$("#change-pp-dropdown").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					openChangepp = false;
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
	}).on("change", "#upload-pp-create", function() {
		pic_prev = $( "#pp-img-main-create" ).attr( "src" );
		if ( this.files && this.files[0] ) {
            var reader = new FileReader();
            reader.onload = function ( e ) {
                $( "#pp-img-main-create" ).attr( "src", e.target.result ).removeClass( "hide" );
				$( "i#pp-main-create" ).addClass( "hide" );
				$( "#pp-act-div-create" ).removeClass( "hide" );
            }
            reader.readAsDataURL(this.files[0]);
        }
		$( ".change-pp-create" ).removeClass( "active-pp" );
		if( openChangepp == true ) {				
			$("#change-pp-create-dropdown").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					openChangepp = false;
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
	}).on( "click", "#save-pp", function( ev ) {
		ev.preventDefault();
		var photo = $( "#upload-pp-admin" ).prop( "files" )[0];
		var form_data = new FormData();                  
		var inputs = ["file", "token", "userid", "previous", "username", "target_id"];
		
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
				case "username":
					input = username;
				break;
				case "target_id":
					input = getParameterByName("manage-user");
				break;
			}
			form_data.append(val, input);
		});
		
		$.ajax({
			url: "../action.php?act=admin-upload-pp",
			data: form_data,
			processData: false,
			contentType: false,
			type: "POST",
			dataType: "json",
			success: function( data ) {
				if( data.stat == 0 ) {
					Materialize.toast( data.error, 4000 );
				} else {
					pic_prev = null;
					if( websocket.readyState == 1 ) {	// Check the connection

						var update_pp = {
							name: username,	// Username
							iduser: userid,	// User ID
							msgtype: 12,	// Change Photo
							token: token,	// Token (For Security)
							chat_img: data.file,	// Chat Picture
							target: getParameterByName("manage-user")
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
				
	}).on( "click", "#discard-pp", function( ev ) {
		ev.preventDefault();
		
		if( !jQuery.isEmptyObject( pic_prev )) {
			$( "#pp-img-main" ).attr( "src", pic_prev );
			$( "#upload-pp" ).val("");
		} else {
			$( "#pp-img-main" ).attr( "src", "" ).addClass( "hide" );
			$( "i#pp-main" ).removeClass( "hide" );
			$( "#upload-pp" ).val("");
		}
		$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});
		
		pic_prev = null;
	}).on( "click", "#remove-photo", function( e ) {
		e.preventDefault();
		$( ".change-pp" ).removeClass( "active-pp" );
		if( openChangepp == true ) {				
			$("#change-pp-dropdown").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					openChangepp = false;
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
		$.post( "../action.php?act=admin-remove-pp", { token: token, userid: userid, username: username, target_id: getParameterByName("manage-user")}).done( function( data ) {
			if( data != "error" ) {	
				pic_prev = null;
				if( websocket.readyState == 1 ) {	// Check the connection

					var update_pp = {
						name: username,	// Username
						iduser: userid,	// User ID
						msgtype: 12,	// Change Photo
						token: token,	// Token (For Security)
						chat_img: "",	// Chat Picture
						target: getParameterByName("manage-user")
					};

					var json_update_pp = JSON.stringify( update_pp );							
					websocket.send( json_update_pp );
				} else {
					Materialize.toast( "Could not connect to WebSocket server.", 4000 );
				}
			}
		});
	}).on( "click", "#remove-photo-create", function( e ) {
		e.preventDefault();
		$( ".change-pp-create" ).removeClass( "active-pp" );
		if( openChangepp == true ) {				
			$("#change-pp-create-dropdown").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					openChangepp = false;
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
		$( "#pp-img-main-create" ).attr( "src", "" ).addClass( "hide" );
		$( "i#pp-main-create" ).removeClass( "hide" );
		$( "#upload-pp-create" ).val( "" );
		$( "#pp-act-div-create" ).addClass("hide");
		$( "#upload-pp-create" ).val("");
	});

	$("body").on("click", "#upload-photo-chat", function(e) {
		e.preventDefault();
		$( "#upload-chat-admin" ).trigger( "click" );
	}).on("change", "#upload-chat-admin", function() {
		pic_prev = $( "#chat-img-main" ).attr( "src" );
		if ( this.files && this.files[0] ) {
            var reader = new FileReader();
            reader.onload = function ( e ) {
                $( "#chat-img-main" ).attr( "src", e.target.result ).removeClass( "hide" );
				$( "i#chat-main" ).addClass( "hide" );
				$( "#save-chat-ul" ).css("display", "block");
				Materialize.fadeInImage("#save-chat-ul");
				$( "#chat-act-div" ).removeClass( "hide" );
            }
            reader.readAsDataURL(this.files[0]);
        }
		$( ".change-chat" ).removeClass( "active-pp" );
		if( openChangepp == true ) {				
			$("#change-chat-dropdown").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					openChangepp = false;
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
	}).on( "click", "#save-chat", function( ev ) {
		ev.preventDefault();
		var photo = $( "#upload-chat-admin" ).prop( "files" )[0];
		var form_data = new FormData();                  
		var inputs = ["file", "token", "userid", "previous", "username", "non_chatid"];
		
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
				case "non_chatid":
					input = getParameterByName("manage-chat");
				break;
				case "username":
					input = username;
				break;
			}
			form_data.append(val, input);
		});

		$.ajax({
			url: "../action.php?act=upload-chat-pp",
			data: form_data,
			processData: false,
			contentType: false,
			type: "POST",
			dataType: "json",
			success: function( data ) {
				if( data.stat == 0 ) {
					Materialize.toast( data.error, 4000 );
				} else {			
					if( websocket.readyState == 1 ) {	// Check the connection

						var update_group_pp = {
							name: username,	// Username
							iduser: userid,	// User ID
							msgtype: 6,	// Change Photo
							token: token,	// Token (For Security)
							chat_img: data.file,	// Chat Picture
							non_room: getParameterByName("manage-chat")
						};
									
						var json_update_group_pp = JSON.stringify( update_group_pp );							
						websocket.send( json_update_group_pp );
					}
				}
			}
		});
		$( "#upload-chat" ).val("");
				
	}).on( "click", "#discard-chat", function( ev ) {
		ev.preventDefault();
		
		if( !jQuery.isEmptyObject( pic_prev )) {
			$( "#chat-img-main" ).attr( "src", pic_prev );
			$( "#upload-chat" ).val("");
		} else {
			$( "#chat-img-main" ).attr( "src", "" ).addClass( "hide" );
			$( "i#chat-main" ).removeClass( "hide" );
			$( "#upload-chat" ).val("");
		}
		$( "#save-chat-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});
		
		pic_prev = null;
	}).on( "click", "#remove-photo-chat", function( e ) {
		e.preventDefault();
		$( ".change-chat" ).removeClass( "active-pp" );
		if( openChangepp == true ) {				
			$("#change-chat-dropdown").stop(true, true).css('opacity', 1).slideUp({
				queue: false,
				duration: 225,
				easing: 'easeOutCubic',
				complete: function() {
					$(this).css('height', '');
					openChangepp = false;
				}
			}).animate( {opacity: 0}, {queue: false, duration: 225, easing: 'easeOutSine'});					
		}
		$.post( "../action.php?act=remove-chat", { token: token, userid: userid, non_chatid: getParameterByName("manage-chat"), username: username } ).done( function( data ) {
			if( data != "error" ) {
				pic_prev = null;
				$( "#upload-chat" ).val( "" );
				
				if( websocket.readyState == 1 ) {	// Check the connection

					var update_group_pp = {
						name: username,	// Username
						iduser: userid,	// User ID
						msgtype: 6,	// Change Photo
						token: token,	// Token (For Security)
						chat_img: "",	// Chat Picture
						non_room: getParameterByName("manage-chat")
					};
								
					var json_update_group_pp = JSON.stringify( update_group_pp );							
					websocket.send( json_update_group_pp );
				}
			} else {
				Materialize.toast( "An error occured.", 4000 );
			}
		});
	});
	
	var select_ban = null;
	$("body").on("click", "#delete-user", function() {
		$('#delete-modal').modal("open");
	}).on("click", "#delete-chatroom", function() {
		$('#delete-chat-modal').modal("open");
	}).on("click", "#block-user", function() {
		$('#block-modal').modal("open");
	}).on("click", "#perm-user", function() {
		$('#perm-modal').modal("open");
	}).on("click", "#logout-user", function() {
		$('#logout-modal').modal("open");
	}).on("click", "#invite-users", function() {
		$('#invite-modal').modal("open");
	}).on("click", "#show-messages", function() {
		$('#messages-modal').modal("open");
		$.post( "./admin-action.php?act=load-messages", {username: username, token: token, userid: userid, chatid: getParameterByName("manage-chat")}).done(function( response ) {
			$( ".loading" ).addClass( "hide" );	// Hide loading element
			$( "#msg-content" ).html( response );	// Load Messages to Chat Page
			scrollToBottom("#msg-content");
			$("#msg-content img").on("load", function() {
				scrollToBottom("#msg-content");
			});
		});
	}).on("click", "#delete_ban", function() {
		$('#remove-ban-modal').modal("open");
		select_ban = $(this).parent().parent().attr("attr-id");
	});	
	var array_inv = "";
	$( "body" ).on( "click", ".inv-add", function( ev ) {
		ev.preventDefault();
		if( t < max_capacity ) {
			var un = $( this ).attr( "attr-un" );
			var uid = $( this ).attr( "attr-id" );
			$.post( "../action.php?act=inv-add-chip", { userid: userid, type: "admin", uid: uid, array: array_inv, chatid: getParameterByName("manage-chat"), username: username } ).done(function( response1 ) {
				if( response1 != 0 && response1 != 1 ) {	// If there is not any errors
					array_inv = response1;
					$( ".inv-users" ).append( "<div class='c-chip'>" + un + "<i class='material-icons remove-chip-inv' attr-id='" + uid + "'>close</i></div>" );
					t = array_inv.split( "," ).length;	// Number of People in the Array
					$( ".inv-capacity" ).html( t + "/" + max_capacity );	// Update the Free Space
				}
			});
		}
	});	
	
	// Remove User Chip (Invite)
	$( "body" ).on( "click", ".remove-chip-inv", function() {
		var uid = $( this ).attr( "attr-id" );	// Get Username From the Chip
		$( this ).parent().remove();	// Remove the Chip
							
		// Make an Ajax Call for Removing the User from the Array
		$.post( "../action.php?act=remove-chip", { uid: uid, array: array_inv }, function( response2 ) {
			if( response2 != 0 ) {	// If there is not any errors
				array_inv = response2; // JQuery Array
				t = array_inv.split( "," ).length; // Number of People in the Array
				$( ".inv-capacity" ).html( t + "/" + max_capacity ); // Update the Free Space
			}
		});
	});
	
	$( "body" ).on( "click", "#invite-btn", function( ev ) {
		ev.preventDefault();
			if( websocket.readyState == 1 ) {	// Check the connection
				$.post( "../action.php?act=invite-group", { type: "admin", chatid: getParameterByName("manage-chat"), array: array_inv, token: token, username: username, userid: userid}).done( function( data ){
					if( data != "error" ){
						var response = $.parseJSON( data );
						var time = getTime();	// Get the Time						

							var invite_msg = {
								name: username,	// Username
								room: response.chat,	// Hashed Chat ID
								iduser: userid,	// User ID
								msgtype: 4,	// Invite to Group
								rmtype: 0,	// Group
								users: response.users, // Only for inviting people
								chatname: response.grp_name,	// Chat Name
								time: time,	// Current Time
								token: token,
								chat_img_inv: response.pics
								
							};				
							var json_invite = JSON.stringify( invite_msg );	// Convert msg into JSON
							websocket.send( json_invite );
							Materialize.toast( "Successfully invited. Please wait...", 4000 );
							setTimeout(function() {
								location.reload();
							},3000);
					} else {
						Materialize.toast( "An error occured.", 4000 );
					}
				});
			} else {
				Materialize.toast( "Could not connect to the server.", 4000 );
			}
	});
	
	$( "#inv-s" ).on( "input", function(){
        var search = $( this ).val();
		
		if( search.length >= 0 ) {
			$.post( "../action.php?act=inv-search", { search: search, page:1, type: "admin" }, function( response ) {
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
			$.post( "../action.php?act=inv-search", {search: search, page: page, type: "admin"}).done(function( response ) {
				$(".inv-search-content" ).html( response ); // Show Search Results
			});

		 }
	 });
	
	$("body").on("click", "#add_admin", function(e) {
		e.preventDefault();
		var target_name = $(this).parent().attr("attr-name");
		var target_id = $(this).parent().attr("attr-id");
		$("#owner-name").val(target_name);
		$("#owner-name-id").val(target_id);
	});
	
	$( "#owner-name" ).on( "input", function() {
        var search = $( this ).val();
		if( search.length > 0 ) {
			$.post( "../action.php?act=search", {search: search, page:1, type: "all"}, function( response ) {
				$( "#admin_user_search_results" ).removeClass("hide");
				$( "#admin_user_search_results .search_result" ).html( response );
			});
		} else {
			$( "#admin_user_search_results" ).addClass("hide");
			$( "#admin_user_search_results .search_result" ).html( "" );
		}
    });
	 
	$("body").on("click", ".search-pagination li", function(e){
		e.preventDefault();
		var page = $("a", this).attr("attr-page");
		if(page > 0) {
			var search = $( "#search" ).val();
			$.post( "../action.php?act=search", {search: search, page: page, type: "all"}).done(function( response ) {
				$( "#admin_user_search_results" ).removeClass("hide");
				$( "#admin_user_search_results .search_result" ).html( response );
			});
		}
	});
	
	$("body").on("click", "#delete-user-confirm", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if( websocket.readyState == 1 ) {	// Check the connection
				var ws_msg = {
					name: username,	// Username
					iduser: userid,	// User ID
					token: token,	// Token (For Security)
					target: getParameterByName("manage-user"),
					msgtype: 13
				};
				var json_ws_msg = JSON.stringify( ws_msg );							
				websocket.send( json_ws_msg );
			} else {
				Materialize.toast( "Could not connect to the server.", 4000 );
				$('#delete-modal').closeModal();
				$(this).removeClass("disabled");
			}
		}
	}).on("click", "#delete-chatroom-confirm", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if( websocket.readyState == 1 ) {	// Check the connection
				var ws_msg = {
					name: username,	// Username
					iduser: userid,	// User ID
					token: token,	// Token (For Security)
					non_room: getParameterByName("manage-chat"),
					msgtype: 17
				};
				var json_ws_msg = JSON.stringify( ws_msg );							
				websocket.send( json_ws_msg );
			} else {
				Materialize.toast( "Could not connect to the server.", 4000 );
				$('#delete-chat-modal').closeModal();
				$(this).removeClass("disabled");
			}
		}
	}).on("click", "#block-user-confirm", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if( websocket.readyState == 1 ) {	// Check the connection
				var ws_msg = {
					name: username,	// Username
					iduser: userid,	// User ID
					token: token,	// Token (For Security)
					target: getParameterByName("manage-user"),
					msgtype: 14,
					block_year: $("#temp_year").val(),
					block_month: $("#temp_month").val(),
					block_day: $("#temp_day").val(),
					block_hour: $("#temp_hour").val(),
					block_minute: $("#temp_min").val(),
					block_reason: $("#temp_reason").val(),
					bantype: "temp"
				};
				var json_ws_msg = JSON.stringify( ws_msg );							
				websocket.send( json_ws_msg );
			} else {
				Materialize.toast( "Could not connect to the server.", 4000 );
				$('#block-modal').closeModal();
				$(this).removeClass("disabled");
			}
		}
	}).on("click", "#perm-user-confirm", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if( websocket.readyState == 1 ) {	// Check the connection
				var ws_msg = {
					name: username,	// Username
					iduser: userid,	// User ID
					token: token,	// Token (For Security)
					target: getParameterByName("manage-user"),
					msgtype: 14,
					block_reason: $("#temp_reason2").val(),
					bantype: "perm"
				};
				var json_ws_msg = JSON.stringify( ws_msg );							
				websocket.send( json_ws_msg );
			} else {
				Materialize.toast( "Could not connect to the server.", 4000 );
				$('#perm-modal').closeModal();
				$(this).removeClass("disabled");
			}
		}
	}).on("click", "#remove-ban-confirm", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if( websocket.readyState == 1 ) {	// Check the connection
				var ws_msg = {
					name: username,	// Username
					iduser: userid,	// User ID
					token: token,	// Token (For Security)
					target: getParameterByName("manage-user"),
					msgtype: 16,
					select_ban: select_ban
				};
				var json_ws_msg = JSON.stringify( ws_msg );							
				websocket.send( json_ws_msg );
			} else {
				Materialize.toast( "Could not connect to the server.", 4000 );
				$('#remove-ban-modal').closeModal();
				$(this).removeClass("disabled");
			}
		}
	}).on("click", "#logout-user-confirm", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if( websocket.readyState == 1 ) {	// Check the connection
				var ws_msg = {
					name: username,	// Username
					iduser: userid,	// User ID
					token: token,	// Token (For Security)
					target: getParameterByName("manage-user"),
					msgtype: 15
				};
				var json_ws_msg = JSON.stringify( ws_msg );							
				websocket.send( json_ws_msg );
			} else {
				Materialize.toast( "Could not connect to the server.", 4000 );
				$('#logout-modal').closeModal();
				$(this).removeClass("disabled");
			}
		}
	}).on("click", "#kick_admin", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if( websocket.readyState == 1 ) {	// Check the connection
				var ws_msg = {
					name: username,	// Username
					iduser: userid,	// User ID
					token: token,	// Token (For Security)
					non_room: getParameterByName("manage-chat"),
					msgtype: 19,
					int: $(this).attr("attr-i"),
					target_id: $(this).attr("attr-id")
				};
				var json_ws_msg = JSON.stringify( ws_msg );							
				websocket.send( json_ws_msg );
			} else {
				Materialize.toast( "Could not connect to the server.", 4000 );
				$(this).removeClass("disabled");
			}
		}
	});	
	
	//$('.collapsible').collapsible({accordion: true});
	$('.collapsible').collapsible();
	
	$("body").on("click", "#save-general-settings", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			$.post( "./admin-action.php?act=save-general-settings", {username: username, userid: userid, token: token, websocket_url: $("#websocket_url").val(), full_url: $("#full_url").val(), timezone: $("#timezone").val()}).done( function( data ) {
				if(data == 1) {
					Materialize.toast( "Successfully saved.", 4000 );
					$("#save-general-settings").removeClass("disabled");
				} else if(data == 2) {
					Materialize.toast( "Invalid timezone.", 4000 );
					$("#save-general-settings").removeClass("disabled");
				} else {
					Materialize.toast( "A problem occured.", 4000 );
					$("#save-general-settings").removeClass("disabled");
				}
			});
		}
	}).on("click", "#new-secretkey", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			$.post( "./admin-action.php?act=new-secretkey", {username: username, userid: userid, token: token}).done( function( data ) {
				if(data == 1) {
					Materialize.toast( "Successfully created a new secret key.", 4000 );
					$("#new-secretkey").removeClass("disabled");
				} else {
					Materialize.toast( "A problem occured.", 4000 );
					$("#new-secretkey").removeClass("disabled");
				}
			});
		}
	}).on("click", "#save-user-settings", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if($("#user_status").is(":checked")) {
				var user_status = 1;
			} else {
				var user_status = 0;
			}
			if($("#guest_login").is(":checked")) {
				var guest_login = 1;
			} else {
				var guest_login = 0;
			}
			if($("#guest_create_groups").is(":checked")) {
				var guest_create_groups = 1;
			} else {
				var guest_create_groups = 0;
			}
			if($("#guest_friends").is(":checked")) {
				var guest_friends = 1;
			} else {
				var guest_friends = 0;
			}
			if($("#guest_send_pm").is(":checked")) {
				var guest_send_pm = 1;
			} else {
				var guest_send_pm = 0;
			}
			if($("#guest_be_invited").is(":checked")) {
				var guest_be_invited = 1;
			} else {
				var guest_be_invited = 0;
			}
			if($("#guest_online_list").is(":checked")) {
				var guest_online_list = 1;
			} else {
				var guest_online_list = 0;
			}
			if($("#member_group").is(":checked")) {
				var member_group = 1;
			} else {
				var member_group = 0;
			}
			if($("#member_pm").is(":checked")) {
				var member_pm = 1;
			} else {
				var member_pm = 0;
			}
			if($("#user_activation").is(":checked")) {
				var user_activation = 1;
			} else {
				var user_activation = 0;
			}
			if($("#forgot_password").is(":checked")) {
				var forgot_password = 1;
			} else {
				var forgot_password = 0;
			}
			
			$.post( "./admin-action.php?act=save-user-settings", {username: username, userid: userid, token: token, min_username: $("#min_username").val(), max_username: $("#max_username").val(), min_password: $("#min_password").val(), max_password: $("#max_password").val(), max_email: $("#max_email").val(), guest_login: guest_login, guest_prefix: $("#guest_prefix").val(), guest_password: $("#guest_password").val(), guest_create_groups: guest_create_groups, guest_send_pm: guest_send_pm, guest_be_invited: guest_be_invited, guest_online_list: guest_online_list, member_group: member_group, member_pm: member_pm, guest_friends: guest_friends, user_status: user_status, default_user_status: $("#default_user_status").val(), user_status_lenght: $("#user_status_lenght").val(), user_activation: user_activation, forgot_password: forgot_password }).done( function( data ) {
				if(data == 1) {
					Materialize.toast( "Successfully saved.", 4000 );
					$("#save-user-settings").removeClass("disabled");
				} else if (data == 2) {
					Materialize.toast( "A problem occured.", 4000 );
					$("#save-user-settings").removeClass("disabled");
				} else if (data == 3) {
					Materialize.toast( "Invalid maximum email length.", 4000 );
					$("#save-user-settings").removeClass("disabled");
				} else if (data == 4) {
					Materialize.toast( "Invalid guest password length.", 4000 );
					$("#save-user-settings").removeClass("disabled");
				} else if (data == 5) {
					Materialize.toast( "Invalid minimum username length.", 4000 );
					$("#save-user-settings").removeClass("disabled");
				} else if (data == 6) {
					Materialize.toast( "Invalid maximum username length.", 4000 );
					$("#save-user-settings").removeClass("disabled");
				} else if (data == 7) {
					Materialize.toast( "Guest prefix is too long or too short.", 4000 );
					$("#save-user-settings").removeClass("disabled");
				} else if (data == 8) {
					Materialize.toast( "Invalid minimum password length.", 4000 );
					$("#save-user-settings").removeClass("disabled");
				} else if (data == 9) {
					Materialize.toast( "Maximum status length is too long.", 4000 );
					$("#save-user-settings").removeClass("disabled");
				} else if (data == 10) {
					Materialize.toast( "Default status is longer than maximum status length.", 4000 );
					$("#save-user-settings").removeClass("disabled");
				} else {
					Materialize.toast( "A problem occured.", 4000 );
					$("#save-user-settings").removeClass("disabled");
				}
			});
		}
	}).on("change", "#share_location", function() {
		if($(this).is(":checked")) {
			$("#more_share_location").removeClass("hide");
		} else {
			$("#more_share_location").addClass("hide");
		}
	}).on("change", "#share_voice", function() {
		if($(this).is(":checked")) {
			$("#more_share_voice").removeClass("hide");
		} else {
			$("#more_share_voice").addClass("hide");
		}
	}).on("change", "#user_status", function() {
		if($(this).is(":checked")) {
			$("#more_user_status").removeClass("hide");
		} else {
			$("#more_user_status").addClass("hide");
		}
	}).on("change", "#guest_login", function() {
		if($(this).is(":checked")) {
			$("#more_guest").removeClass("hide");
		} else {
			$("#more_guest").addClass("hide");
		}
	}).on("change", "#ban_ip", function() {
		if($(this).is(":checked")) {
			$("#more_ban_ip").removeClass("hide");
		} else {
			$("#more_ban_ip").addClass("hide");
		}
	}).on("change", "#share_photo", function() {
		if($(this).is(":checked")) {
			$("#more_share_photo").removeClass("hide");
		} else {
			$("#more_share_photo").addClass("hide");
		}
	}).on("change", "#share_video", function() {
		if($(this).is(":checked")) {
			$("#more_share_video").removeClass("hide");
		} else {
			$("#more_share_video").addClass("hide");
		}
	}).on("change", "#share_file", function() {
		if($(this).is(":checked")) {
			$("#more_share_file").removeClass("hide");
		} else {
			$("#more_share_file").addClass("hide");
		}
	}).on("change", "#share_music", function() {
		if($(this).is(":checked")) {
			$("#more_share_music").removeClass("hide");
		} else {
			$("#more_share_music").addClass("hide");
		}
	}).on("click", "#save-chat-settings", function(e) {
		e.preventDefault();
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if($("#share_archive").is(":checked")) {
				var share_archive = 1;
			} else {
				var share_archive = 0;
			}
			if($("#share_voice").is(":checked")) {
				var share_voice = 1;
			} else {
				var share_voice = 0;
			}
			if($("#share_location").is(":checked")) {
				var share_location = 1;
			} else {
				var share_location = 0;
			}
			if($("#share_photo").is(":checked")) {
				var share_photo = 1;
			} else {
				var share_photo = 0;
			}
			if($("#share_video").is(":checked")) {
				var share_video = 1;
			} else {
				var share_video = 0;
			}
			if($("#share_file").is(":checked")) {
				var share_file = 1;
			} else {
				var share_file = 0;
			}
			if($("#share_music").is(":checked")) {
				var share_music = 1;
			} else {
				var share_music = 0;
			}
			if($("#save_messages").is(":checked")) {
				var save_messages = 1;
			} else {
				var save_messages = 0;
			}
			if($("#emoticons").is(":checked")) {
				var emoticons = 1;
			} else {
				var emoticons = 0;
			}
			if($("#online_users").is(":checked")) {
				var online_users = 1;
			} else {
				var online_users = 0;
			}
			if($("#friend_system").is(":checked")) {
				var friends = 1;
			} else {
				var friends = 0;
			}
			
			$.post( "./admin-action.php?act=save-chat-settings", {
				username: username,
				userid: userid, 
				token: token, 
				photo_extensions: $("#photo_extensions").val(), 
				photo_mimes: $("#photo_mimes").val(),
				video_extensions: $("#video_extensions").val(), 
				video_mimes: $("#video_mimes").val(), 
				file_extensions: $("#file_extensions").val(),
				music_extensions: $("#music_extensions").val(),
				music_mimes: $("#music_mimes").val(),
				max_photo: $("#max_photo").val(),
				max_video: $("#max_video").val(),
				max_file: $("#max_file").val(),
				max_photo_size: $("#max_photo_size").val(),
				max_file_size: $("#max_file_size").val(),
				max_video_size: $("#max_video_size").val(),
				max_music_size: $("#max_music_size").val(),
				img_extensions: $("#img_extensions").val(),
				max_img_size: $("#max_img_size").val(),
				group_capacity: $("#group_capacity").val(),
				max_group_name: $("#max_group_name").val(),
				min_search: $("#min_search").val(),
				share_archive: share_archive,
				share_photo: share_photo,
				share_video: share_video,
				share_file: share_file,
				share_music: share_music,
				save_messages: save_messages,
				emoticons: emoticons,
				online_users: online_users,
				friends: friends,
				share_location: share_location,
				share_voice: share_voice,
				google_maps_api: $("#google_maps_api").val(),
				max_voice_note: $("#max_voice_note").val()
			}).done( function( data ) {
				if(data == 1) {
					Materialize.toast( "Successfully saved.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 2) {
					Materialize.toast( "A problem occured.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 3) {
					Materialize.toast( "Maximum photo size is too large. Please check \"post_max_size\" and \"upload_max_filesize\" settings in \"php.ini\".", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 4) {
					Materialize.toast( "Invalid maximum photo size.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 5) {
					Materialize.toast( "Maximum video size is too large. Please check \"post_max_size\" and \"upload_max_filesize\" settings in \"php.ini\".", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 6) {
					Materialize.toast( "Invalid maximum video size.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 7) {
					Materialize.toast( "Maximum file size is too large. Please check \"post_max_size\" and \"upload_max_filesize\" settings in \"php.ini\".", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 8) {
					Materialize.toast( "Invalid maximum file size.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 9) {
					Materialize.toast( "Maximum audio size is too large. Please check \"post_max_size\" and \"upload_max_filesize\" settings in \"php.ini\".", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 10) {
					Materialize.toast( "Invalid maximum audio size.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 11) {
					Materialize.toast( "Maximum profile / group photo size is too large. Please check \"post_max_size\" and \"upload_max_filesize\" settings in \"php.ini\".", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 12) {
					Materialize.toast( "Invalid maximum profile / group photo size.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 13) {
					Materialize.toast( "Maximum profile / group photo size is too large. Please check \"post_max_size\" and \"upload_max_filesize\" settings in \"php.ini\".", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 14) {
					Materialize.toast( "Maximum uploadable photo number is too large. Please check \"max_file_uploads\" setting in \"php.ini\".", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 15) {
					Materialize.toast( "Maximum uploadable photo number is too small.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 16) {
					Materialize.toast( "Maximum uploadable video number is too large. Please check \"max_file_uploads\" setting in \"php.ini\".", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 17) {
					Materialize.toast( "Maximum uploadable video number is too small.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 18) {
					Materialize.toast( "Maximum uploadable file number is too large. Please check \"max_file_uploads\" setting in \"php.ini\".", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 19) {
					Materialize.toast( "Maximum group name length is too large or too small.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 20) {
					Materialize.toast( "Group capacity is too small.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 21) {
					Materialize.toast( "Minimum required character number to search a user is too small.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else if (data == 22) {
					Materialize.toast( "Invalid voice note size.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				} else {
					Materialize.toast( "A problem occured.", 4000 );
					$("#save-chat-settings").removeClass("disabled");
				}
			});
		}
	});
	
	$("body").on("click", "#create-user", function(e) {
		e.preventDefault()
		if(!$(this).hasClass("disabled")) {
			$(this).addClass("disabled");
			if( $("#user-email").val().length == 0 ) {
				Materialize.toast( "Please enter Email address.", 4000 );
				$(this).removeClass("disabled");
				$( "#reg_email" ).addClass( "invalid" );
			} else if ( $("#pass").val() !== $("#pass_repeat").val() ) {
				Materialize.toast( "Passwords are not matched.", 4000 );
				$(this).removeClass("disabled");
			} else if ( $("#pass_repeat").val().length == 0 ) {
				Materialize.toast( "Please enter Repeat Password.", 4000 );
				$(this).removeClass("disabled");
			} else if ( $("#username").val().length == 0 ) {
				Materialize.toast( "Please enter Username.", 4000 );
				$(this).removeClass("disabled");
			} else {

				var photo = $( "#upload-pp-create" ).prop( "files" )[0];
				var form_data = new FormData();                  
				var inputs = ["status", "activation", "username", "token", "userid", "target_username", "file", "target_email", "target_password", "target_password_repeat", "target_type"];

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
						case "username":
							input = username;
							break;
						case "target_username":
							input = $("#username").val();
							break;
						case "target_email":
							input = $("#user-email").val();
							break;
						case "target_password":
							input = $("#pass").val();
							break;
						case "target_password_repeat":
							input = $("#pass_repeat").val();
							break;
						case "target_type":
							input = $("#select_user_type").val();
							break;
						case "activation":
							if($("#on-ac-new").is(":checked")) {
								input = 1;
							} else {
								input = 2;
							}
							break;
						case "status":
							input = $("#user-status").val();
							break;
					}
					form_data.append(val, input);
				});
				
				$.ajax({
					url: "../action.php?act=admin-new-user",
					data: form_data,
					processData: false,
					contentType: false,
					type: "POST",
					dataType: "json",
					success: function( data ) {
						if( data.stat == 0 ) {
							Materialize.toast( data.error, 4000 );
							$("#create-user").removeClass("disabled");
						} else {
							Materialize.toast( "Successfully created. You are redirecting...", 3000 );
							setTimeout(function(){
								window.location = "./index.php?manage-user="+data.uid;
							},3000);
						}
					},
					error: function() {
						Materialize.toast( "An error occured.", 4000 );
						$("#create-user").removeClass("disabled");
					}
				});
			}
		}
	});
	
	websocket.onmessage = function( ev ) {
		var msg = JSON.parse( ev.data );
		if( !$.isEmptyObject( msg.admin_type ) ) {
			var type = msg.admin_type;
			if(type == "error") {
				Materialize.toast( msg.content, 4000 );
			}
			if(type == "update_username") {
				Materialize.toast( "Successfully saved.", 4000 );
				$("#username").attr("disabled", true);
				$("#edit-username").removeClass("hide");
				$("#reset-username").addClass("hide");
				$("#confirm-username").addClass("hide");
				$("#username-title").text($("#username").val());
				Materialize.updateTextFields();
			}
			if(type == "update_email") {
				Materialize.toast( "Successfully saved.", 4000 );
				$("#user-email").attr("disabled", true);
				$("#edit-email").removeClass("hide");
				$("#reset-email").addClass("hide");
				$("#confirm-email").addClass("hide");
				Materialize.updateTextFields();
			}
			if(type == "update_groupname") {
				Materialize.toast( "Successfully saved.", 4000 );
				$("#groupname").attr("disabled", true);
				$("#edit-groupname").removeClass("hide");
				$("#reset-groupname").addClass("hide");
				$("#confirm-groupname").addClass("hide");
				Materialize.updateTextFields();
			}
			if(type == "update_profile_photo") {
				Materialize.toast( "Successfully saved.", 4000 );
				if(msg.content == null || msg.content == undefined || msg.content == "") {		
					$( "#pp-img-main" ).attr( "src", "" ).addClass( "hide" );
					$( "i#pp-main" ).removeClass( "hide" );
					$( "#upload-pp" ).val( "" );
					$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});				
					$( "#pp-act-div" ).addClass("hide");
				} else {
					$( "#pp-img-main" ).attr( "src", "."+msg.content ).removeClass( "hide" );
					$( "i#pp-main" ).addClass( "hide" );
					$( "#upload-pp" ).val( "" );
					$( "#save-pp-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});				
					$( "#pp-act-div" ).removeClass("hide");
				}
			}
			if(type == "change_group_photo") {
				Materialize.toast( "Successfully saved.", 4000 );
				if(msg.content == null || msg.content == undefined || msg.content == "") {		
					$( "#chat-img-main" ).attr( "src", "" ).addClass( "hide" );
					$( "i#chat-main" ).removeClass( "hide" );
					$( "#upload-chat" ).val( "" );
					$( "#save-chat-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});				
					$( "#chat-act-div" ).addClass("hide");
				} else {
					$( "#chat-img-main" ).attr( "src", "."+msg.content ).removeClass( "hide" );
					$( "i#chat-main" ).addClass( "hide" );
					$( "#upload-chat" ).val( "" );
					$( "#save-chat-ul" ).velocity({opacity:0},{duration:650,queue:!1,easing:"easeOutSine", complete:function(){$(this).css("display","none");}});				
					$( "#chat-act-div" ).removeClass("hide");
				}
			}
			if(type == "delete_user") {
				Materialize.toast( "Successfully deleted. You are redirecting...", 3000 );
				setTimeout(function(){
					window.location = "./index.php?action=manage-users";
				},3000);
			}
			if(type == "delete_chatroom") {
				Materialize.toast( "Successfully deleted. You are redirecting...", 3000 );
				setTimeout(function(){
					window.location = "./index.php?action=manage-chats";
				},3000);
			}
			if(type == "remove_ban") {
				Materialize.toast( "Successfully removed.", 3000 );
				location.reload();
			}
			if(type == "block_user" || type == "perm_user") {
				Materialize.toast( "Successfully banned.", 3000 );
				location.reload();
			}
			if(type == "logout_user") {
				$("#logout-user-confirm").removeClass("disabled");
				Materialize.toast( "Successfully logged off the user.", 3000 );
				$('#logout-modal').closeModal();
			}
			if(type == "admin_kick") {
				$("#kick_admin[attr-i='"+msg.content+"']").parent().css({"background-color": "#26a69a", "opacity": 1}).velocity( "stop", !1 ).velocity( { opacity: 0}, { duration: 850, queue: !1, easing: "easeInOutQuad", complete: function() {if($(".collection .admin-user-list").length > 1) {$(this).remove();} else {$(this).parent().parent().parent().remove();}} } )
				$(".inv-users .b-chip[attr-id='"+msg.user+"']").remove();
				var capa = $(".inv-users .inv-capacity").text().split("/");
				var nnum = capa[0] - 1;
				$(".inv-users .inv-capacity").html(nnum+"/"+max_capacity);
				Materialize.toast( "Successfully kicked.", 3000 );
			}
		}
	}
});
 