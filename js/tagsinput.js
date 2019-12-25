
(function($){

  var KeyCodes = {
    BACKSPACE: 8,
    TAB: 9,
    ENTER: 13
  };

  $.taginput = function(element, options){

    var Default$1 = {
      tagSeperator: ',',
      defaultText: 'Add a tag',
      autocomplete: null
      },
    options = $.extend(Default$1, options);

    var originalTagInput = $(element);
    var tagInputContainer= _createTagInput(originalTagInput, options.tagSeperator);
    originalTagInput.replaceWith(tagInputContainer);

    var tagInput = tagInputContainer.find(".taginput");
    var tagHiddenData = tagInputContainer.find(".tag-hiddendata");

    if(options.autocomplete != null && jQuery.ui.autocomplete !== undefined){
      tagInput.autocomplete(options.autocomplete);
      //return false;
    }
    // Handle all key event listeners on tagInput
    tagInput.on("keydown",function(e){
      var input = $(this);
      if(e.keyCode == KeyCodes.ENTER && $.trim(input.val()) !== ''){
        e.preventDefault();
        var tag = $.trim(input.val());
        var tagIndex = $.inArray(tag, tagHiddenData.val().split(options.tagSeperator)); //check if the tag has been entered

        if(tagIndex === -1){
          input.before('<span class="tag tag-primary" tag-data="'+ tag + '">' + tag + ' <span class="remove-tag"><i class="fal fa-times"></i></span></span>');
          if($.trim(tagHiddenData.val()) === '') //if there's no previous data we don't want tag seperator to be infront
            tagHiddenData.val(tag);
          else
            tagHiddenData.val(tagHiddenData.val() + options.tagSeperator + tag);
          input.val('');
        }else{ //flash the existing tag
          var existingTag = tagInputContainer.find('span.tag[tag-data="' + tag + '"]');
          existingTag.fadeOut(150).fadeIn(200);
        }

      }
      if(e.keyCode == KeyCodes.BACKSPACE && $.trim(input.val()) === ''){
        input.prev("span.tag").remove();
        //split data into arrays, remove the last and join by the tag seperator
        tagHiddenData.val(tagHiddenData.val().split(options.tagSeperator).slice(0, -1).join(options.tagSeperator));
      }

    });

    tagInputContainer.on("click", "span.remove-tag", function(e){ //doesn't work ;-;
      e.stopPropagation();
      var tag = $(this).parent();
      var tagText = $.trim(tag.text());
      tagHiddenData.val((tagHiddenData.val() + options.tagSeperator).replace(tagText + options.tagSeperator, '').slice(0, -1));
      tag.remove();
    });



  };

  // Create the tags input
  var _createTagInput = function(input, tagSeperator){
    var tags = input.val().split(tagSeperator);
    var tagLabels = "";
    if($.trim(input.val()) !== ''){
      tagLabels = $.map(tags, function(tag, index) {
              return '<span class="tag tag-primary" tag-data="'+ tag + '">' + tag + ' <span class="remove-tag"><i class="fal fa-times"></i></span></span>';
      }).join('');
    }
    return $('<div class="tags-input">'+tagLabels+'<input type="text" class="taginput" placeholder="Add interest">'+
            '<input class="tag-hiddendata" type="hidden" value="'+input.val()+'"></div>');

  }



	$.fn.taginput = function(options){
    if(typeof(options) == "object" || options == undefined){
		    return new $.taginput(this, options);
		}
	};

})(jQuery);
