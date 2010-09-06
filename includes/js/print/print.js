/*------------------------------------------------------------------------------
File:           print.js
Developer:      Denys Nosov (a.k.a. Dutch)
Creation Date:  15.11.2007
Version:        1.0
Homepage:       http://www.joomla-ua.org/
License:        Creative Commons Attribution-ShareAlike 2.0 License http://creativecommons.org/licenses/by-sa/2.0/
Note:           Do not change in code!!!

Function:       footnoteLinks()
Author:         Aaron Gustafson (aaron at easy-designs dot net)
Creation Date:  8 May 2005
Version:        1.3
Homepage:       http://www.easy-designs.net/code/footnoteLinks/
License:        Creative Commons Attribution-ShareAlike 2.0 License http://creativecommons.org/licenses/by-sa/2.0/
Note:           If you change or improve on this script, please let us know by emailing the author (above) with a link to your demo page.

Excerpts from the jsUtilities Library
Version:        2.1
Homepage:       http://www.easy-designs.net/code/jsUtilities/
License:        Creative Commons Attribution-ShareAlike 2.0 License http://creativecommons.org/licenses/by-sa/2.0/
Note:           If you change or improve on this script, please let us know.
------------------------------------------------------------------------------*/
function footnoteLinks(containerID,targetID) {
	if (!document.getElementById || !document.getElementsByTagName || !document.createElement)
		return false;
	if (!document.getElementById(containerID) || !document.getElementById(targetID))
		return false;

	var container = document.getElementById(containerID);
	var target = document.getElementById(targetID);
	var h2 = document.createElement('h2');
	addClass.apply(h2,['printOnly']);
	var coll = container.getElementsByTagName('*');
	var ol = document.createElement('ol');
	addClass.apply(ol,['printOnly']);
	var myArr = [];
	var thisLink;
	var num = 1;

	var h2_txt = document.createTextNode('Ссылки :');

	for (var i=0; i<coll.length; i++) {
		var thisClass = coll[i].className;
			if ( (coll[i].getAttribute('href') || coll[i].getAttribute('cite')) && (thisClass == '' || thisClass.indexOf('ignore') == -1) && (thisClass == '' || thisClass.indexOf('lightwindow') == -1) && (thisClass == '' || thisClass.indexOf('comment-link') == -1)&& (thisClass == '' || thisClass.indexOf('highslide') == -1) ) {
				if(coll[i].className!='print_button') {
					thisLink = coll[i].getAttribute('href') ? coll[i].href : coll[i].cite;
					var note = document.createElement('sup');
					addClass.apply(note,['printOnly']);
					var note_txt;
					var j = inArray.apply(myArr,[thisLink]);
						if ( j || j===0 ) {
						note_txt = document.createTextNode(j+1);
					} else {
						var li	 = document.createElement('li');
						var li_txt = document.createTextNode(thisLink);
						h2.appendChild(h2_txt);
						li.appendChild(li_txt);
						ol.appendChild(li);
						myArr.push(thisLink);
						note_txt = document.createTextNode(num);
						num++;
					}
					note.appendChild(note_txt);
					if (coll[i].tagName.toLowerCase() == 'blockquote') {
						var lastChild = lastChildContainingText.apply(coll[i]);
						lastChild.appendChild(note);
					} else {
						coll[i].parentNode.insertBefore(note, coll[i].nextSibling);
					}
				}
			}
		}
	target.appendChild(h2);
	target.appendChild(ol);
	addClass.apply(document.getElementsByTagName('html')[0],['noted']);
	return true;
}
window.onload = function() {
	footnoteLinks('main','main');
}

	if(Array.prototype.push == null) {
		Array.prototype.push = function(item) {
			this[this.length] = item;
			return this.length;
		};
	};
	if (!Function.prototype.apply) {
		Function.prototype.apply = function(oScope, args) {
			var sarg = [];
			var rtrn, call;

			if (!oScope) oScope = window;
			if (!args) args = [];

			for (var i = 0; i < args.length; i++) {
				sarg[i] = "args["+i+"]";
			};

			call = "oScope.__applyTemp__(" + sarg.join(",") + ");";
			oScope.__applyTemp__ = this;
			rtrn = eval(call);
			oScope.__applyTemp__ = null;

			return rtrn;
		};
	};

	function inArray(needle) {

		for (var i=0; i < this.length; i++) {
			if (this[i] === needle) {
				return i;
			}
		}

		return false;
	}

	function addClass(theClass) {
		if (this.className != '') {
			this.className += ' ' + theClass;
		} else {
			this.className = theClass;
		}
	}

	function lastChildContainingText() {
		var testChild = this.lastChild;
		var contentCntnr = ['p','li','dd'];
			while (testChild.nodeType != 1) {
				testChild = testChild.previousSibling;
			}
		var tag = testChild.tagName.toLowerCase();
		var tagInArr = inArray.apply(contentCntnr, [tag]);

		if (!tagInArr && tagInArr!==0) {
			testChild = lastChildContainingText.apply(testChild);
		}

	  return testChild;

}
