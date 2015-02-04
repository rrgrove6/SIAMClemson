function display_officer_panel()
{
	get_officer_years();
	update_officer_panel(2014);
}

function update_officer_panel(year_date)
{
	get_officers(year_date);

	// set all tabs to be unhighlighted
	var year_list = $("officer_years").childNodes;
	
	for(var i = 0; i < year_list.length; ++i)
	{
		var cur_year = year_list[i];
		cur_year.style.background = "white";
		cur_year.style.color = "black";
	}
	
	// set selected tab to be highligted
	var cur_year_id = "officer_y" + year_date;
	$(cur_year_id).style.background = "#ff6206";
	$(cur_year_id).style.color = "white";
}

function get_officers(year_date)
{
	var url = "http://people.clemson.edu/~siam/common/SIAM_AJAX.php";
	var myAjax = new Ajax.Request(url, {
		parameters: {
			year: year_date,
			func: 'get_officers'
		},
		onSuccess: displayOfficers,
		onFailure: showError
	});
}

function displayOfficers(transport)
{
	var list = transport.responseText.split(";");
	
	var officer_table = $("officers");
	while(officer_table.hasChildNodes())
	{
		officer_table.removeChild(officer_table.firstChild);
	}
	
	// create tbody so IE will render our table
	var tbody = document.createElement("tbody");
	officer_table.appendChild(tbody);
	var thead = document.createElement("thead");
	thead.style.height = "0px";
	officer_table.appendChild(thead);
	var tfoot = document.createElement("tfoot");
	tfoot.style.height = "0px";
	officer_table.appendChild(tfoot);
	
	officer_table = tbody; // we cheat and reassign the object reference
	
	// create header
	var row = document.createElement("tr");
	var name = document.createElement("td");
	name.className = "officer_header";
	name.appendChild(document.createTextNode("Office"))
	row.appendChild(name);
	var desc = document.createElement("td");
	desc.className = "officer_header";
	desc.appendChild(document.createTextNode("Name"));
	row.appendChild(desc);
	officer_table.appendChild(row);

	for(var i = 0; i < list.length; ++i)
	{
		var cur_officer = list[i].split(",");
		var row = document.createElement("tr");
		var office = document.createElement("td");
		office.className = "officer_office";
		office.appendChild(document.createTextNode(cur_officer[0]));
		row.appendChild(office);
		var name = document.createElement("td");
		name.className = "officer_name";
		name.appendChild(document.createTextNode(cur_officer[1] + " " + cur_officer[2]))
		row.appendChild(name);
		officer_table.appendChild(row);
	}
}

function get_officer_years()
{
	var url = "http://people.clemson.edu/~siam/common/SIAM_AJAX.php";
	var myAjax = new Ajax.Request(url, {
		asynchronous: false,
		parameters: {
			func: 'get_officer_years'
		},
		onSuccess: displayOfficerYears,
		onFailure: showError
	});
}

function displayOfficerYears(transport)
{
	var list = transport.responseText.split(",");
	var year_list = $("officer_years");

	for(var i = 0; i < list.length; ++i)
	{
		var cur_year = document.createElement('li');
		cur_year.id = "officer_y" + list[i];
		cur_year.innerHTML = "'" + list[i].substring(2);
		cur_year.onclick = new Function("javascript:update_officer_panel('" + list[i] + "')");
		year_list.appendChild(cur_year);
	}
}

function showError()
{
	alert("An error has occured");
}