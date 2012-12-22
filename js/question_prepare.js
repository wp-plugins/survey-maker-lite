function question()
	{
	this.Name = '';
	this.Type = '';	
	this.q = "";
	this.ans = new Array();
	this.ans_string = '';
	
	this.style = "";
	this.id = 0;
	
	this.split_ans = '[split]';
	this.split_qa = '[qa]';
	this.split_q = '[qsplit]';	
	}
	
	
question.prototype.setType = function(strType){this.Type = String(strType);};
question.prototype.setQuestion = function (strQ){this.q = String(sanitize(strQ));}
question.prototype.addAnswer = function(strAns){this.ans.push(String(sanitize(strAns)));}
question.prototype.setId = function(intId){this.id = intId;}

question.prototype.decodeFromString = function(str)
	{
	str = String(str);
	this.Name = str;
	var str = str.split(this.split_qa);
	this.Type = String(sanitize(str[0]));
	if (str.length > 1)
		{
		var _qa = str[1].split('[split]');
		if (_qa.length > 1) // question + answer
			{
			this.q = String(sanitize(_qa[0]));
			var _ans = new Array();
			for (var i = 1; i < _qa.length;i++)
				{
				_ans.push(String(sanitize(_qa[i])));
				}
			this.ans = _ans;
			}
		if (_qa.length == 1) // question
			{
			this.q = String(sanitize(_qa));
			}
		
		}
	}



question.prototype.makeName = function()
	{
	var _temp = this.ans;
	var _split_temp = '';
	
	if (_temp.length >= 1)
		{
		_temp = _temp.join(this.split_ans);
		_split_temp = this.split_ans + _temp;
		}
	this.Name = this.Type + this.split_qa + this.q + _split_temp;
	}

question.prototype.getHTML = function()
	{
	var rStr ='';
	rStr += "<div style='"+this.style+"' id='q"+this.id+"' name='"+this.Name+"' class='questionWrapper'>\n";
	rStr += "\t<div class='question'>";
	rStr += desanitize(this.q);
	rStr += "</div>\n";
	rStr += "\t\t<div class='answer'>";
	rStr += this.ans_string;
	rStr += "\t\t</div>\n";
	rStr += "\t<div style='clear:both'></div>\n";
	rStr += "</div>\n";
	return rStr;
	}

question.prototype.makeAnswer = function()
	{
	var _ans_string = "";
	switch (this.Type)
		{
		case "plainText":
			_ans_string += "\n\t\t\t<input type='hidden' name='sm[q"+this.id+"][]' value='dummy' />\n";
			break;
		case "newPage":
			_ans_string += "<input type='hidden' name='sm[q"+this.id+"][]' value='dummy' />Page Break<br/>\n";
			break;			
		case "text":
			_ans_string += "\n\t\t\t<input type='text' name='sm[q"+this.id+"]'/>";
			break;
		case "radio":
			_ans_string += "\n\t\t\t<input type='hidden' name='sm[q"+this.id+"][]' value='dummy' />\n";
			for ( var i  = 0; i < this.ans.length;i++)
				{
				_ans_string += "\t\t\t<input type='radio' name='sm[q"+this.id+"]' value='"+this.ans[i]+"'/>"+desanitize(this.ans[i])+"<br/>\n";
				}
			break;
		case "select":
			_ans_string += "\n\t\t\t<input type='hidden' name='sm[q"+this.id+"][]' value='dummy' />\n<select name='sm[q"+this.id+"]'>\n";
			for ( var i  = 0; i < this.ans.length;i++)
				{
				//alert(this.ans[i]);
				_ans_string += "\t\t\t<option value='"+this.ans[i]+"'>"+desanitize(this.ans[i])+"</option>\n";
				}
			_ans_string += "</select>";
			break;
		case "checkbox":
			_ans_string += "\n\t\t\t<input type='hidden' name='sm[q"+this.id+"][]' value='dummy' />\n";
			for ( var i  = 0; i < this.ans.length;i++)
				{
				_ans_string += "\t\t\t<input type='checkbox' name='sm[q"+this.id+"]' value='"+this.ans[i]+"' />"+desanitize(this.ans[i])+"<br/>\n";
				}
			break;				
		default : 
			_ans_string += "blank item";
		}
	this.ans_string = _ans_string;	
	}






function sanitize(str)
	{
	var str = String(str);
	str = str.replace(/\</gi,"");
	str = str.replace(/\>/gi,"");
	str = str.replace(/\]/gi,"");
	str = str.replace(/\[/gi,"");
	str = str.replace(/\"/gi,"-doublequote-");
	str = str.replace(/\'/gi,"-singlequote-");
	str = str.replace(/\\/gi,"-backslash-");
	str = str.replace(/\//gi,"-backslash-");
	return str;
	}
	
	
function desanitize(str2)
	{
	var str2 = String(str2);
	str2 = str2.replace("-doublequote-",'"');
	str2 = str2.replace("-singlequote-","'");
	str2 = str2.replace(/-backslash-/gi,"\\");
	str2 = str2.replace(/-slash-/gi,"/");
	return str2;
	}	


function splitQ(qString)
	{
	//question are joined by the keyword "[qsplit]"
	//so, split the keyword and return the array		
	return qString.split("[qsplit]")
	}
	
function splitQA(qArray)
	{
	// each of the array have this format
	// radio[qa]item1[split]item2[split]item3[split]
	// first split the "[qa]" keyword
	// then split the "[split]" keyword
	var returnArray = [];
	for($i = 0;$i < qArray.length ; $i++)
		{
		var cur = qArray[$i];
		//alert(cur);
		var curSplit = cur.split("[qa]");
		var curType = curSplit[0];
		var curAns = curSplit[1];
		
		// now split curAns
		curAns = curAns.split("[split]");
		
		returnArray.push([curType,curAns])
		}
	return returnArray
	}


function makeQuestion(testQ)
	{
	return splitQ(testQ);
	//return splitQA(testQ);		
	}
	
