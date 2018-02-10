var $lmdigloss;

function showEntry(e)
{
	var id = this.className.substr(2);

	// There should always be only one popup window
	$("#lexiconEntry").remove();

	if (id) 
	{
	$lmdigloss = this;
	$('<div id="lexiconEntry"><br><br><br></div>').insertAfter($lmdigloss);
	source = lmdi_gloss_url;
	$.get(source, {id: id}, function(txt)
		{
			$('#lexiconEntry').html(txt);
			var n = txt.indexOf ('elinks');
			if (n == -1)
			{
				$('#lexiconEntry').click(function() 
				{ 
					$("#lexiconEntry").remove();
					return false;
				}
				);
			}
			else
			{
				$('#lexiconClose').click(function() 
				{ 
					$("#lexiconEntry").remove();
					return false; 
				}
				);
			}
		}
		);
	}
}

function displayEntry(entry) 
{
	if (!entry)
	{
		return;
	}
	$results.html(entry).show();
}

$(document).ready(function()
	{
		$("lmdigloss").each(function(e)
		{
			if (this.className)
			{
				$(this).bind("click",showEntry);
			}
		});
	});
