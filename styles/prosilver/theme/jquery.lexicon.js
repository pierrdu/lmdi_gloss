var $lmdigloss;

function showEntry(e) 
{
	var id = this.className.substr(2);
	var source = "app.php/gloss?mode=lexicon&amp;id=";

	// There is always only one popup window
	$("#lexiconEntry").remove();

	if (id) 
	{
	$lmdigloss = this;
	$('<div class="loading" id="lexiconEntry"><br><br><br></div>').insertAfter($lmdigloss);
	$.get(source, {id: id}, function(txt) {
		$('#lexiconEntry').html(txt).removeClass('loading');
		// $('#lexiconEntry').click(function() { $("#lexiconEntry").remove(); return false; } );
		$('#lexiconClose').click(function() { $("#lexiconEntry").remove(); return false; } );
	});
	}
}

function displayEntry(entry) 
{
	if (!entry)
		return;
	$results.html(entry).show();
}

$(document).ready(function() {
	$("lmdigloss").each(function(e){
		if (this.className) $(this).bind("click",showEntry);
		});
	});