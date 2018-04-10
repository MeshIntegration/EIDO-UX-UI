

function bulkaction(formName, actionRequested){
	
	if((formName == undefined || formName.trim().length == 0)
			|| (actionRequested == undefined || actionRequested.trim().length == 0))
	{
		return 3;
	}
	
    var form = document.getElementById(formName);
    var tr_elements = form.getElementsByTagName("tr");
    //Check if the update all box is checked
    var changeall = tr_elements[0].cells[0].firstChild.checked;
     
     var updateusers=new Array();
     for (var i = 1; i < tr_elements.length; i++) 
    {
        row = tr_elements[i];
        ischecked = row.cells[0].firstChild.checked;
        if(changeall || ischecked)
        {
        	// Get the user 'key'
            datacell = row.cells[1].firstChild.innerText.replace("\n",";");
            userentry = datacell + ';true;
            updateusers.push(datacell);
        }
    }
    
     $.ajax({
    	    type: 'POST',
    	    url: 'bulk_actions_a.php',
    	    data: {json: JSON.stringify(updateusers), actionRequested},
    	    dataType: 'json'
    	});

     	// a call back method may be added if desired.
}
