/*============================================================

 Default.js

============================================================*/

function CallSubmit( action, param, method )
{
	param = param || "";
	method = method || "POST";

	var f = document.forms["main"];
	f["action"] = action;
	f["method"] = method;

	//--- [BEGIN] Brwoser Safari
	if ( navigator.userAgent.indexOf("Safari") != -1  )
	{
		var obj = event.srcElement;
		if ( obj.name.length > 0 )
		{
			f["action"] += "?" + obj.name;
		}
	}
	//--- [END] Brwoser Safari

	f.submit();

	return false;
}

/*------------------------------------------------------------
	END OF FILE
------------------------------------------------------------*/
