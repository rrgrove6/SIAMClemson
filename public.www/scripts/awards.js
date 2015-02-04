function display_award_panel()
{
	get_award_years();
	update_award_panel(2014);
}

function update_award_panel(year_date)
{
	get_awards(year_date);

	// set all tabs to be unhighlighted
	var year_list = $("award_years").childNodes;
	
	for(var i = 0; i < year_list.length; ++i)
	{
		var cur_year = year_list[i];
		cur_year.style.background = "white";
		cur_year.style.color = "black";
	}
	
	// set selected tab to be highligted
	var cur_year_id = "award_y" + year_date;
	$(cur_year_id).style.background = "#ff6206";
	$(cur_year_id).style.color = "white";
}

function get_awards(year_date)
{
	var url = "http://people.clemson.edu/~siam/common/SIAM_AJAX.php";
	var myAjax = new Ajax.Request(url, {
		parameters: {
			year: year_date,
			func: 'get_awards'
		},
		onSuccess: displayAwards,
		onFailure: showError
	});
}

function displayAwards(transport)
{
	var list = transport.responseText.split(";");
	
	var award_table = $("awards");
	while(award_table.hasChildNodes())
	{
		award_table.removeChild(award_table.firstChild);
	}
	
	// create tbody so IE will render our table
	var tbody = document.createElement("tbody");
	award_table.appendChild(tbody);
	var thead = document.createElement("thead");
	thead.style.height = "0px";
	award_table.appendChild(thead);
	var tfoot = document.createElement("tfoot");
	tfoot.style.height = "0px";
	award_table.appendChild(tfoot);
	
	award_table = tbody; // we cheat and reassign the object reference
	
	// create header
	var row = document.createElement("tr");
	var name = document.createElement("td");
	name.className = "award_header";
	name.appendChild(document.createTextNode("Name"))
	row.appendChild(name);
	var desc = document.createElement("td");
	desc.className = "award_header";
	desc.appendChild(document.createTextNode("Award"));
	row.appendChild(desc);
	award_table.appendChild(row);

	for(var i = 0; i < list.length; ++i)
	{
		var cur_award = list[i].split(",");
		var row = document.createElement("tr");
		var name = document.createElement("td");
		name.className = "award_name";
		name.appendChild(document.createTextNode(cur_award[0] + " " + cur_award[1]))
		row.appendChild(name);
		var desc = document.createElement("td");
		desc.className = "award_desc";
		desc.appendChild(document.createTextNode(cur_award[2]));
		row.appendChild(desc);
		award_table.appendChild(row);
	}
}

function get_award_years()
{
	var url = "http://people.clemson.edu/~siam/common/SIAM_AJAX.php";
	var myAjax = new Ajax.Request(url, {
		asynchronous: false,
		parameters: {
			func: 'get_award_years'
		},
		onSuccess: displayAwardYears,
		onFailure: showError
	});
}

function displayAwardYears(transport)
{
	var list = transport.responseText.split(",");
	var year_list = $("award_years");

	for(var i = 0; i < list.length; ++i)
	{
		var cur_year = document.createElement('li');
		cur_year.id = "award_y" + list[i];
		cur_year.innerHTML = list[i];
		cur_year.onclick = new Function("javascript:update_award_panel('" + list[i] + "')");
		year_list.appendChild(cur_year);
	}
}

function showError()
{
	alert("An error has occured");
}