	function update_stats()
	{
		$(document).ready(function(){
			$("#stats").load("update_stats.php");

		});
	}

	function active_items()
	{
		var item = $(".item");
		var size = $(".item").length;
		
		for( var i=0; i<size; i++)
		{
			if(item.eq(i).data('active') == 1) put_on(item.eq(i));
		}
	}

	function put_on(that)
	{
		var type = that.data('type');
		var active = that.data('active');
		var equipment = $("#"+type+"_eq");
		var place = $("#"+type);
		var childrenLength = place.children().length;
		var id = that.data('id');

		console.log(type);

		if(childrenLength > 0)
		{
			if(active == 1)
			{
				// ściągam do ekwipunku
				that.appendTo(equipment);
				that.css({"width" : "98px", "height" : "98px", "border" : "solid 1px #0AE2EE", "border-radius" : "3px"});
				that.data('active', 0);	
				insert(id, 0);
			}
			else if(id != place.children().data('id'))
			{
				// podmieniam przedmioty
				insert(place.children().data('id'), 0);
				place.children().data('active', 0);
				place.children().css({"width" : "98px", "height" : "98px", "border" : "solid 1px #0AE2EE", "border-radius" : "3px"});
				place.children().appendTo(equipment);

				that.appendTo(place);
				that.css({"width" : "100%", "height" : "100%", "border" : "none"});
				that.data('active', 1);	
				insert(id, 1);
			}
		}
		else
		{
			// zakładam na puste miejsce
			that.appendTo(place);
			that.css({"width" : "100%", "height" : "100%", "border" : "none"});
			that.data('active', 1);
			insert(id, 1);
		}
	}

	function insert(firstId, active)
	{
		$(document).ready(function() {
			
			$(document).load("items.php", {
				firstItemId: firstId,
				itemActive: active
			}, function(data){
				if(data) update_stats();
			});
		});
	}

	function check(tool)
	{
		var a = tool.lastChild;
		var offset = $(tool).offset();

			$(tool).mousemove(function(e){
				var windowHeight = $(window).height();
				var toolHeight = $(tool).children().last().height();

				mouseX = e.pageX - offset.left;
				mouseY = e.pageY - offset.top;

				if(e.clientY + toolHeight + 20 >= windowHeight)
				{
					$(a).css({
	      				left:  mouseX+10,
	    				top: mouseY - toolHeight - 10
	    			});	
				}
				else
				{
					$(a).css({
		      			left:  mouseX+10,
		    			top:   mouseY+10
		    		});
				}
			});
			
		$(tool).mouseout(function(){
			mouseX = 0;
			mouseY = 0;
		});				
	}

	function showMessage (that)
	{
		var message = $(that).siblings(".message_content");
		var readed = $(that).data('readed');
		var msgId = $(that).data('mid');
		
		if( $(message).css("display") == "block") $(message).css("display", "none");
		else $(message).css("display", "block");

		if(readed == 0)
		{
			$(that).css("font-weight", "normal");
			$(that).data('readed', 1);

			$(document).ready(function() {			
				$("#blad").load("messageoperate.php", {
					msgId : msgId
				});
			});
		}
	}

	function showAllMessages(messages)
	{
		if(messages == 'sent')
		{
			$('.messages_received').css("display", "none");
			$('.messages_sent').css("display", "block");
		}
		else
		{
			$('.messages_sent').css("display", "none");
			$('.messages_received').css("display", "block");
		}
	}

	function deleteMessage(that, removeId)
	{
		$(that).parent().parent().remove();

		$(document).ready(function() {			
			$("#blad").load("messageoperate.php", {
				removeId : removeId
			});
		});
	}

	function viewQuest(that)
	{
		var message = $(that).siblings(".quest_content");
			
		if( $(message).css("display") == "block") $(message).css("display", "none");
		else $(message).css("display", "block");
	}

