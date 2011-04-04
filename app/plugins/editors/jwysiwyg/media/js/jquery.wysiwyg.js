/**
 * WYSIWYG - jQuery plugin 0.94
 * (phase 2)
 *
 * Copyright (c) 2008-2009 Juan M Martinez, 2010 Akzhan Abdulin and all contrbutors
 * http://plugins.jquery.com/project/jWYSIWYG
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * $Id: jquery.wysiwyg.js 121 2011-02-24 20:51:43Z bost56 $
 */

/*jslint browser: true, forin: true */

/*
:TODO:
1) documentSelection || getSelection || window.getSelection ???
 */

(function($) {
	/* Wysiwyg namespace: private properties and methods */
	var Wysiwyg = {
		controls: {
			bold: {
				visible: true,
				tags: ["b", "strong"],
				css: {
					fontWeight: "bold"
				},
				tooltip: "Bold"
			},

			italic: {
				visible: true,
				tags: ["i", "em"],
				css: {
					fontStyle: "italic"
				},
				tooltip: "Italic"
			},

			copy: {
				visible: false,
				tooltip: "Copy"
			},

			createLink: {
				groupIndex: 6,
				visible: true,
				exec: function() {
					var selection = this.documentSelection.call($(this.editor));

					if (selection && selection.length > 0) {
						if ($.browser.msie) {
							this.focus();
							this.editorDoc.execCommand("createLink", true, null);
						}
						else {
							var szURL = prompt("URL", "http://");
							if (szURL && szURL.length > 0) {
								this.editorDoc.execCommand("unlink", false, null);
								this.editorDoc.execCommand("createLink", false, szURL);
							}
						}
					}
					else if (this.options.messages.nonSelection) {
						alert(this.options.messages.nonSelection);
					}
				},
				tags: ["a"],
				tooltip: "Create link"
			},

			cut: {
				groupIndex: 8,
				visible: false,
				tooltip: "Cut"
			},

			decreaseFontSize: {
				visible: false && !($.browser.msie),
				tags: ["small"],
				tooltip: "Decrease font size"
			},

			h1: {
				visible: true,
				groupIndex: 7,
				className: "h1",
				command: ($.browser.msie || $.browser.safari) ? "FormatBlock" : "heading",
				"arguments": ($.browser.msie || $.browser.safari) ? "<h1>" : "h1",
				tags: ["h1"],
				tooltip: "Header 1"
			},

			h2: {
				visible: true,
				className: "h2",
				command: ($.browser.msie || $.browser.safari)	? "FormatBlock" : "heading",
				"arguments": ($.browser.msie || $.browser.safari) ? "<h2>" : "h2",
				tags: ["h2"],
				tooltip: "Header 2"
			},

			h3: {
				visible: true,
				className: "h3",
				command: ($.browser.msie || $.browser.safari) ? "FormatBlock" : "heading",
				"arguments": ($.browser.msie || $.browser.safari) ? "<h3>" : "h3",
				tags: ["h3"],
				tooltip: "Header 3"
			},

			html: {
				groupIndex: 10,
				visible: false,
				exec: function() {
					if (this.viewHTML) {
						this.setContent($(this.original).val());
						$(this.original).hide();
						$(this.editor).show();
					}
					else {
						var $ed = $(this.editor);
						this.saveContent();
						$(this.original).css({
							width:	$(this.element).outerWidth() - 6,
							height: $(this.element).height() - $(this.panel).height() - 6,
							resize: "none"
						}).show();
						$ed.hide();
					}

					this.viewHTML = !(this.viewHTML);
				},
				tooltip: "View source code"
			},

			increaseFontSize: {
				groupIndex: 9,
				visible: false && !($.browser.msie),
				tags: ["big"],
				tooltip: "Increase font size"
			},

			indent: {
				groupIndex: 2,
				visible: true,
				tooltip: "Indent"
			},

			insertHorizontalRule: {
				visible: true,
				tags: ["hr"],
				tooltip: "Insert Horizontal Rule"
			},

			insertImage: {
				visible: true,
				exec: function() {
					var self = this;
					if ($.modal) {
						$.modal(this.defaults.formImageHtml, {
							onShow: function(dialog) {
								$("input:submit", dialog.data).click(function(e) {
									e.preventDefault();
									var szURL = $('input[name="url"]', dialog.data).val();
									var title = $('input[name="imagetitle"]', dialog.data).val();
									var description = $('input[name="description"]', dialog.data).val();
									var img = '<img src="' + szURL + '" title="' + title + '" alt="' + description + '"/>';
									self.insertHtml(img);
									$.modal.close();
								});
								$("input:reset", dialog.data).click(function(e) {
									e.preventDefault();
									$.modal.close();
								});
							},
							maxWidth: this.defaults.formWidth,
							maxHeight: this.defaults.formHeight,
							overlayClose: true
						});
					}
					else {
						if ($.fn.dialog) {
							var dialog = $(this.defaults.formImageHtml).appendTo("body");
							dialog.dialog({
								modal: true,
								width: this.defaults.formWidth,
								height: this.defaults.formHeight,
								open: function(ev, ui) {
									$("input:submit", $(this)).click(function(e) {
										e.preventDefault();
										var szURL = $('input[name="url"]', dialog).val();
										var title = $('input[name="imagetitle"]', dialog).val();
										var description = $('input[name="description"]', dialog).val();
										var img="<img src='" + szURL + "' title='" + title + "' alt='" + description + "' />";
										self.insertHtml(img);
										$(dialog).dialog("close");
									});
									$("input:reset", $(this)).click(function(e) {
										e.preventDefault();
										$(dialog).dialog("close");
									});
								},
								close: function(ev, ui){
									$(this).dialog("destroy");
								}
							});
						}
						else {
							if ($.browser.msie) {
								this.focus();
								this.editorDoc.execCommand("insertImage", true, null);
							}
							else {
								var szURL = prompt("URL", "http://");
								if (szURL && szURL.length > 0) {
									this.editorDoc.execCommand("insertImage", false, szURL);
								}
							}
						}
					}
				},
				tags: ["img"],
				tooltip: "Insert image"
			},

			insertOrderedList: {
				groupIndex: 5,
				visible: true,
				tags: ["ol"],
				tooltip: "Insert Ordered List"
			},

			insertTable: {
				visible: true,
				exec: function() {
					var self = this;
					if ($.fn.modal) {
						$.modal(this.defaults.formTableHtml, {
							onShow: function(dialog) {
								$("input:submit", dialog.data).click(function(e) {
									e.preventDefault();
									var rowCount = $('input[name="rowCount"]', dialog.data).val();
									var colCount = $('input[name="colCount"]', dialog.data).val();
									self.insertTable(colCount, rowCount, this.defaults.tableFiller);
									$.modal.close();
								});
								$("input:reset", dialog.data).click(function(e) {
									e.preventDefault();
									$.modal.close();
								});
							},
							maxWidth: this.defaults.formWidth,
							maxHeight: this.defaults.formHeight,
							overlayClose: true
						});
					}
					else {
						if ($.fn.dialog) {
							var dialog = $(this.defaults.formTableHtml).appendTo("body");
							dialog.dialog({
								modal: true,
								width: this.defaults.formWidth,
								height: this.defaults.formHeight,
								open: function(event, ui) {
									$("input:submit", $(this)).click(function(e) {
										e.preventDefault();
										var rowCount = $('input[name="rowCount"]', dialog).val();
										var colCount = $('input[name="colCount"]', dialog).val();
										self.insertTable(colCount, rowCount, this.defaults.tableFiller);
										$(dialog).dialog("close");
									});
									$("input:reset", $(this)).click(function(e) {
										e.preventDefault();
										$(dialog).dialog("close");
									});
								},
								close: function(event, ui){
									$(this).dialog("destroy");
								}
							});
						}
						else {
							var colCount = prompt("Count of columns", "3");
							var rowCount = prompt("Count of rows", "3");
							this.insertTable(colCount, rowCount, this.defaults.tableFiller);
						}
					}
				},
				tags: ["table"],
				tooltip: "Insert table"
			},

			insertUnorderedList: {
				visible: true,
				tags: ["ul"],
				tooltip: "Insert Unordered List"
			},

			justifyCenter: {
				visible: true,
				tags: ["center"],
				css: {
					textAlign: "center"
				},
				tooltip: "Justify Center"
			},

			justifyFull: {
				visible: true,
				css: {
					textAlign: "justify"
				},
				tooltip: "Justify Full"
			},

			justifyLeft: {
				visible: true,
				groupIndex: 1,
				css: {
					textAlign: "left"
				},
				tooltip: "Justify Left"
			},

			justifyRight: {
				visible: true,
				css: {
					textAlign: "right"
				},
				tooltip: "Justify Right"
			},

			ltr: {
				visible: false,
				exec: function() {
					var selection = this.documentSelection();
					if ($("<div/>").append(selection).children().length > 0) {
						selection = $(selection).attr("dir", "ltr");
					}
					else {
						selection = $("<div/>").attr("dir", "ltr").append(selection);
					}
					this.editorDoc.execCommand("inserthtml", false, $("<div/>").append(selection).html());
				},
				tooltip : "Left to Right"
			},

			outdent: {
				visible: true,
				tooltip: "Outdent"
			},

			paste: {
				visible: false,
				tooltip: "Paste"
			},

			redo: {
				visible: true,
				tooltip: "Redo"
			},

			removeFormat: {
				visible: true,
				exec: function() {
					this.removeFormat();
				},
				tooltip: "Remove formatting"
			},

			rtl: {
				visible: false,
				exec: function() {
					var selection = this.documentSelection();
					if ($("<div/>").append(selection).children().length > 0) {
						selection = $(selection).attr("dir", "rtl");
					}
					else {
						selection = $("<div/>").attr("dir", "rtl").append(selection);
					}
					this.editorDoc.execCommand("inserthtml", false, $("<div/>").append(selection).html());
				},
				tooltip : "Right to Left"
			},

			strikeThrough: {
				visible: true,
				tags: ["s", "strike"],
				css: {
					textDecoration: "line-through"
				},
				tooltip: "Strike-through"
			},

			subscript: {
				groupIndex: 3,
				visible: true,
				tags: ["sub"],
				tooltip: "Subscript"
			},

			superscript: {
				visible: true,
				tags: ["sup"],
				tooltip: "Superscript"
			},

			underline: {
				visible: true,
				tags: ["u"],
				css: {
					textDecoration: "underline"
				},
				tooltip: "Underline"
			},

			undo: {
				groupIndex: 4,
				visible: true,
				tooltip: "Undo"
			}
		},

		defaults: {
			html: '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">STYLE_SHEET</head><body style="margin: 0px;">INITIAL_CONTENT</body></html>',
			formTableHtml: '<form class="wysiwyg"><fieldset><legend>Insert table</legend><label>Count of columns: <input type="text" name="colCount" value="3" /></label><label><br />Count of rows: <input type="text" name="rowCount" value="3" /></label><input type="submit" class="button" value="Insert table" /> <input type="reset" value="Cancel" /></fieldset></form>',
			formImageHtml: '<form class="wysiwyg"><fieldset><legend>Insert Image</legend><label>Image URL: <input type="text" name="url" value="http://" /></label><label>Image Title: <input type="text" name="imagetitle" value="" /></label><label>Image Description: <input type="text" name="description" value="" /></label><input type="submit" class="button" value="Insert Image" /> <input type="reset" value="Cancel" /></fieldset></form>',
			formWidth: 440,
			formHeight: 270,
			debug: false,
			events: {},
			controls: {},
			css: {},
			autoGrow: true,
			autoSave: true,
			// http://code.google.com/p/jwysiwyg/issues/detail?id=15
			brIE: true,
			iFrameClass: null,
			initialContent: "",
			loadCss: ["jquery.wysiwyg.css", "jquery.wysiwyg.modal.css"],
			maxHeight: 10000, /* see autoGrow */
			messages: {
				nonSelection: "Select the text you wish to link"
			},
			resizeOptions: false,
			// http://code.google.com/p/jwysiwyg/issues/detail?id=11
			rmUnwantedBr: true,
			tableFiller: "Lorem ipsum"
		},

		editor: null,
		editorDoc: null,
		element: null,
		options: {},
		original: null,
		rangeSaver: null,
		timers: [],

		addHoverClass: function() {
			$(this).addClass("wysiwyg-button-hover");
		},

		appendControls: function() {
			var controls = this.parseControls();
			var currentGroupIndex	= 0;
			var hasVisibleControls = true; // to prevent separator before first item

			for (var name in controls) {
				var control = controls[name];
				if (control.groupIndex && currentGroupIndex != control.groupIndex) {
					currentGroupIndex = control.groupIndex;
					hasVisibleControls = false;
				}

				if (!control.visible) {
					continue;
				}
				if (!hasVisibleControls) {
					this.appendMenuSeparator();
					hasVisibleControls = true;
				}
				if (control.custom) {
					this.appendMenuCustom(name, control.options);
				}
				else {
					this.appendMenu(
						control.command || name,
						control["arguments"] || "",
						control.className || control.command || name || "empty",
						control.exec,
						control.tooltip || control.command || name || ""
						);
				}
			}
		},

		appendMenu: function(cmd, args, className, fn, tooltip) {
			var self = this;
			args = args || [];

			return $('<li role="menuitem" unselectable="on">' + (className || cmd) + "</li>")
			.addClass(className || cmd)
			.attr("title", tooltip)
			.hover(this.addHoverClass, this.removeHoverClass)
			.click(function() {
				if (fn) {
					fn.apply(self);
				}
				else {
					self.focus();
					self.withoutCss();
					// when click <Cut>, <Copy> or <Paste> got "Access to XPConnect service denied" code: "1011"
					// in Firefox untrusted JavaScript is not allowed to access the clipboard
					try {
						self.editorDoc.execCommand(cmd, false, args);
					}
					catch(e) {
						console.error(e);
					}
				}
				if (self.options.autoSave) {
					self.saveContent();
				}
				this.blur();
				self.focusEditor();
			})
			.appendTo(this.panel);
		},

		appendMenuCustom: function(name, options) {
			var self = this;

			if ("undefined" !== typeof options.callback) {
				$(window).bind("trigger-" + name + ".wysiwyg", options.callback);
			}

			return $('<li role="menuitem" unselectable="on"><img src="' + options.icon + '" class="jwysiwyg-custom-icon"/>' + name + "</li>")
			.addClass("custom-command-" + name)
			.addClass("wysiwyg-custom-command")
			.addClass(name)
			.attr("title", options.tooltip)
			.hover(this.addHoverClass, this.removeHoverClass)
			.click(function() {
				if ("undefined" !== typeof options.exec) {
					options.exec.apply(self);
				}
				else {
					self.focus();
					self.withoutCss();
					// when click <Cut>, <Copy> or <Paste> got "Access to XPConnect service denied" code: "1011"
					// in Firefox untrusted JavaScript is not allowed to access the clipboard
					try {
						if ("undefined" !== typeof options.command) {
							self.editorDoc.execCommand(options.command, false, args);
						}
					}
					catch(e) {
						console.error(e);
					}
				}
				if (self.options.autoSave) {
					self.saveContent();
				}
				this.blur();
				self.focusEditor();
			//					self.triggerCallback(name);
			})
			.appendTo(this.panel);
		},

		appendMenuSeparator: function() {
			return $('<li role="separator" class="separator"></li>').appendTo(this.panel);
		},

		checkTargets: function(element) {
			for (var name in this.options.controls) {
				var control = this.options.controls[name];
				var className = control.className || control.command || name || "empty";

				$("." + className, this.panel).removeClass("active");

				if (control.tags || (control.options && control.options.tags)) {
					var tags = control.tags || (control.options && control.options.tags);
					var elm = element;
					do {
						if (elm.nodeType != 1) {
							break;
						}

						if ($.inArray(elm.tagName.toLowerCase(), tags) != -1) {
							$("." + className, this.panel).addClass("active");
						}
					} while ((elm = elm.parentNode));
				}

				if (control.css || (control.options && control.options.css)) {
					var css = control.css || (control.options && control.options.css);
					var el = $(element);
					do {
						if (el[0].nodeType != 1) {
							break;
						}

						for (var cssProperty in css) {
							if (el.css(cssProperty).toString().toLowerCase() == css[cssProperty]) {
								$("." + className, this.panel).addClass("active");
							}
						}
					} while ((el = el.parent()));
				}
			}
		},

		designMode: function() {
			var attempts = 3;
			var runner;
			var self = this;
			var doc	= this.editorDoc;
			runner = function() {
				if (self.innerDocument() !== doc) {
					self.initFrame();
					return;
				}
				try {
					doc.designMode = "on";
				}
				catch(e) {
				}
				attempts--;
				if (attempts > 0 && $.browser.mozilla) {
					self.timers.designMode = setTimeout(runner, 100);
				}
			};
			runner();
			this.editorDoc_designMode = true;
		},

		destroy: function() {
			for (var i in this.timers) {
				clearTimeout(this.timers[i]);
			}
			
			// Remove bindings
			var $form = $(this.element).closest("form");
			$form.unbind("submit.wysiwyg", this.autoSaveFunction)
			.unbind("reset.wysiwyg", this.resetFunction);
			$(this.element).remove();
			$.removeData(this.original, "wysiwyg");
			$(this.original).show();
			return this;
		},

		documentSelection: function() {
			if (this.editor.get(0).contentWindow.document.selection) {
				return this.editor.get(0).contentWindow.document.selection.createRange().text;
			}
			else {
				return this.editor.get(0).contentWindow.getSelection().toString();
			}
		},
		//not used?
		execute: function(command, arg) {
			if (typeof(arg) == "undefined") {
				arg = null;
			}
			this.editorDoc.execCommand(command, false, arg);
		},

		extendOptions: function(options) {
			var controls = {};

			/**
			 * If the user set custom controls, we catch it, and merge with the
			 * defaults controls later.
			 */
			if (options && options.controls) {
				controls = options.controls;
				delete options.controls;
			}

			options = $.extend(true, {}, this.defaults, options);
			options.controls = $.extend(true, options.controls, this.controls);

			for (var control in controls) {
				if (control in options.controls) {
					$.extend(options.controls[control], controls[control]);
				}
				else {
					options.controls[control] = controls[control];
				}
			}
			return options;
		},

		/**
		 * Search path to this js file
		 */
		findPath: function() {
			var collection = $("script");
			var reg = /^(.*)jquery\.wysiwyg\.js$/;

			var path = null;
			for (i = 0; i < collection.length; i++) {
				if (null === path) {
					var p = reg.exec(collection[i].src);
					if (null !== p) {
						return p[1];
					}
				}
			}
			return path;
		},

		focus: function() {
			this.editor.get(0).contentWindow.focus();
			return this;
		},

		focusEditor: function() {
			if (this.rangeSaver !== null) {
				if (window.getSelection) { //non IE and there is already a selection
					var s = window.getSelection();
					if (s.rangeCount > 0) {
						s.removeAllRanges();
					}
					s.addRange(savedRange);
				}
				else if (document.createRange) { //non IE and no selection
					window.getSelection().addRange(savedRange);
				}
				else if (document.selection) { //IE
					savedRange.select();
				}
			}
		},

		getContent: function() {
			return $(this.innerDocument()).find("body").html();
		},

		getElementByAttributeValue: function(tagName, attributeName, attributeValue) {
			var elements = this.editorDoc.getElementsByTagName(tagName);

			for (var i = 0; i < elements.length; i++) {
				var value = elements[i].getAttribute(attributeName);

				if ($.browser.msie) {
					/** IE add full path, so I check by the last chars. */
					value = value.substr(value.length - attributeValue.length);
				}

				if (value == attributeValue) {
					return elements[i];
				}
			}

			return false;
		},

		//2 times
		getInternalRange: function() {
			var selection = this.getInternalSelection();

			if (!selection) {
				return null;
			}

			return (selection.rangeCount > 0) ? selection.getRangeAt(0) : (selection.createRange ? selection.createRange() : null);
		},
		// 2 times
		getInternalSelection: function() {
			return (this.editor.get(0).contentWindow.getSelection) ? this.editor.get(0).contentWindow.getSelection() : this.editor.get(0).contentDocument.selection;
		},
		// used once in initFrame
		getRange: function() {
			var selection = this.getSelection();

			if (!selection) {
				return null;
			}

			return (selection.rangeCount > 0) ? selection.getRangeAt(0) : (selection.createRange ? selection.createRange() : null);
		},
		//used once in getRange
		getSelection: function() {
			return (window.getSelection) ? window.getSelection() : document.selection;
		},

		// :TODO: you can type long string and letters will be hidden because of overflow
		grow: function() {
			var innerBody = $(this.innerDocument().body);
			var innerHeight = $.browser.msie ? innerBody[0].scrollHeight : innerBody.height() + 2 + 20; // 2 - borders, 20 - to prevent content jumping on grow

			var minHeight = this.initialHeight;
			var height = Math.max(innerHeight, minHeight);
			height = Math.min(height, this.options.maxHeight);

			this.editor.attr("scrolling", height < this.options.maxHeight ? "no" : "auto"); // hide scrollbar firefox
			innerBody.css("overflow", height < this.options.maxHeight ? "hidden" : ""); // hide scrollbar chrome

			this.editor.get(0).height = height;
			return this;
		},

		init: function(element, options) {
			var self = this;

			this.editor = element;
			this.options = this.extendOptions(options);

			if (false !== this.options.loadCss) {
				for (var i in this.options.loadCss) {
					this.loadCss(this.options.loadCss[i]);
				}
			}

			if ($.browser.msie && parseInt($.browser.version, 10) < 8) {
				this.options.autoGrow = false;
			}

			$.data(element, "wysiwyg", this);

			var newX = element.width || element.clientWidth || 0;
			var newY = element.height || element.clientHeight || 0;

			if (element.nodeName.toLowerCase() == "textarea") {
				this.original = element;

				if (newX === 0 && element.cols) {
					newX = (element.cols * 8) + 21;

					// fix for issue 30 ( http://github.com/akzhan/jwysiwyg/issues/issue/30 )
					element.cols = 1;
				}
				if (newY === 0 && element.rows) {
					newY = (element.rows * 16) + 16;

					// fix for issue 30 ( http://github.com/akzhan/jwysiwyg/issues/issue/30 )
					element.rows = 1;
				}

				this.editor = $(location.protocol == "https:" ? '<iframe src="javascript:false;"></iframe>' : "<iframe></iframe>").attr("frameborder", "0");

				if (this.options.iFrameClass) {
					this.editor.addClass(this.options.iFrameClass);
				}
				else {
					this.editor.css({
						minHeight: (newY - 6).toString() + "px",
						// fix for issue 12 ( http://github.com/akzhan/jwysiwyg/issues/issue/12 )
						width: (newX > 50) ? (newX - 8).toString() + "px" : ""
					});
					if ($.browser.msie && parseInt($.browser.version, 10) < 7) {
						this.editor.css("height", newY.toString() + "px");
					}
				}
				/**
				 * http://code.google.com/p/jwysiwyg/issues/detail?id=96
				 */
				this.editor.attr("tabindex", $(element).attr("tabindex"));
			}

			var panel = this.panel = $('<ul role="menu" class="panel"></ul>');

			this.appendControls();
			this.element = $("<div/>").addClass("wysiwyg").append(panel)
			.append($("<div><!-- --></div>")
				.css({
					clear: "both"
				}))
			.append(this.editor);

			if (!this.options.iFrameClass) {
				this.element.css({
					width: (newX > 0) ? newX.toString() + "px" : "100%"
				});
			}

			$(element).hide().before(this.element);

			this.viewHTML = false;

			/**
			 * @link http://code.google.com/p/jwysiwyg/issues/detail?id=52
			 */
			this.initialContent = $(element).val();
			this.initFrame();

			this.autoSaveFunction = function() {
				self.saveContent();
			};

			this.resetFunction = function() {
				self.setContent(self.initialContent);
				self.saveContent();
			};

			if (this.options.resizeOptions && $.fn.resizable) {
				this.element.resizable($.extend(true, {
					alsoResize: this.editor
				}, this.options.resizeOptions));
			}

			var $form = $(element).closest("form");

			if (this.options.autoSave) {
				$form.submit(self.autoSaveFunction);
			}

			$form.bind("reset.wysiwyg", self.resetFunction);
		},

		initFrame: function() {
			var self = this;
			var style = "";

			/**
			 * @link http://code.google.com/p/jwysiwyg/issues/detail?id=14
			 */
			if (this.options.css && this.options.css.constructor == String) {
				style = '<link rel="stylesheet" type="text/css" media="screen" href="' + this.options.css + '"/>';
			}

			this.editorDoc = this.innerDocument();
			this.editorDoc_designMode = false;

			this.designMode();
			this.editorDoc.open();
			this.editorDoc.write(
				this.options.html
				/**
				 * @link http://code.google.com/p/jwysiwyg/issues/detail?id=144
				 */
				.replace(/INITIAL_CONTENT/, function() {
					return self.initialContent;
				})
				.replace(/STYLE_SHEET/, function() {
					return style;
				}));
			this.editorDoc.close();

			if ($.browser.msie) {
				/**
				 * Remove the horrible border it has on IE.
				 */
				this.timers.initFrame_IeBorder = setTimeout(function() {
					$(self.editorDoc.body).css("border", "none");
				}, 0);
			}

			$(this.editorDoc).click(function(event) {
				self.checkTargets(event.target ? event.target : event.srcElement);
			});

			/**
			 * @link http://code.google.com/p/jwysiwyg/issues/detail?id=20
			 */
			$(this.original).focus(function() {
				if ($(this).filter(":visible")) {
					return;
				}
				self.focus();
			});

			this.emptyContentRegex = /^<([\w]+)[^>]*>(<br\/?>)?<\/\1>/;
			$(this.editorDoc).keydown(function(event) {
				if (event.keyCode == 8) { // backspace
					var content = self.getContent();
					if (self.emptyContentRegex.test(content)) { // if content is empty
						event.stopPropagation(); // prevent remove single empty tag
						return false;
					}
				}
				return true;
			});

			if (!$.browser.msie) {
				$(this.editorDoc).keydown(function(event) {
					/* Meta for Macs. tom@punkave.com */
					if (event.ctrlKey || event.metaKey) {
						switch (event.keyCode) {
							case 66:
								// Ctrl + B
								this.execCommand("Bold", false, false);
								return false;
							case 73:
								// Ctrl + I
								this.execCommand("Italic", false, false);
								return false;
							case 85:
								// Ctrl + U
								this.execCommand("Underline", false, false);
								return false;
						}
					}
					return true;
				});
			}
			else if (this.options.brIE) {
				$(this.editorDoc).keydown(function(event) {
					if (event.keyCode == 13) {
						var rng = self.getRange();
						rng.pasteHTML("<br/>");
						rng.collapse(false);
						rng.select();
						return false;
					}
					return true;
				});
			}

			if (this.options.autoSave) {
				/**
				 * @link http://code.google.com/p/jwysiwyg/issues/detail?id=11
				 */
				var handler = function() {
					self.saveContent();
				};
				$(this.editorDoc).keydown(handler).keyup(handler).mousedown(handler).bind($.support.noCloneEvent ? "input.wysiwyg" : "paste.wysiwyg", handler);
			}

			if (this.options.autoGrow) {
				this.initialHeight = $(this.editorDoc).height();
				$(this.editorDoc).find("body").css("border", "1px solid white"); // cancel margin collapsing
				var growHandler = function() {
					self.grow();
				};
				$(this.editorDoc).keyup(growHandler);
				// fix when content height > textarea height
				this.grow();
			}

			if (this.options.css) {
				this.timers.initFrame_Css = setTimeout(function() {
					if (self.options.css.constructor == String) {
					/**
						 * $(self.editorDoc)
						 * .find("head")
						 * .append(
						 *	 $('<link rel="stylesheet" type="text/css" media="screen"/>')
						 *	 .attr("href", self.options.css)
						 * );
						 */
					}
					else {
						$(self.editorDoc).find("body").css(self.options.css);
					}
				}, 0);
			}

			if (this.initialContent.length === 0) {
				this.setContent(this.options.initialContent);
			}

			$.each(this.options.events, function(key, handler) {
				$(self.editorDoc).bind(key + ".wysiwyg", handler);
			});

			// restores selection properly on focus
			$(self.editor).blur(function() {
				self.rangeSaver = self.getInternalRange();
			});

			$(this.editorDoc.body).addClass("wysiwyg");
			if (this.options.events && this.options.events.save) {
				var saveHandler = this.options.events.save;
				$(self.editorDoc).bind("keyup.wysiwyg", saveHandler);
				$(self.editorDoc).bind("change.wysiwyg",saveHandler);
				if ($.support.noCloneEvent) {
					$(self.editorDoc).bind("input.wysiwyg", saveHandler);
				}
				else {
					$(self.editorDoc).bind("paste.wysiwyg", saveHandler);
					$(self.editorDoc).bind("cut.wysiwyg", saveHandler);
				}
			}
		},

		innerDocument: function() {
			var element = this.editor.get(0);

			if (element.nodeName.toLowerCase() == "iframe") {
				if (element.contentWindow) {
					return element.contentWindow.document;
				}
				else {
					return element;
				}
			/*
				 return ( $.browser.msie )
				 ? document.frames[element.id].document
				 : element.contentWindow.document // contentDocument;
				 */
			}
			return element;
		},

		insertHtml: function(szHTML) {
			if (!szHTML || szHTML.length === 0) {
				return this;
			}
			
			if ($.browser.msie) {
				this.focus();
				this.editorDoc.execCommand("insertImage", false, "#jwysiwyg#");
				var img = this.getElementByAttributeValue("img", "src", "#jwysiwyg#");
				if (img) {
					$(img).replaceWith(szHTML);
				}
			}
			else {
				this.editorDoc.execCommand("insertHTML", false, szHTML);
			}
			return this;
		},

		insertTable: function(colCount, rowCount, filler) {
			if (isNaN(rowCount) || isNaN(colCount) || rowCount === null || colCount === null) {
				return;
			}
			colCount = parseInt(colCount, 10);
			rowCount = parseInt(rowCount, 10);
			if (filler === null) {
				filler = "&nbsp;";
			}
			filler = "<td>" + filler + "</td>";
			var html = ['<table border="1" style="width: 100%;"><tbody>'];
			for (var i = rowCount; i > 0; i--) {
				html.push("<tr>");
				for (var j = colCount; j > 0; j--) {
					html.push(filler);
				}
				html.push("</tr>");
			}
			html.push("</tbody></table>");
			return this.insertHtml(html.join(""));
		},

		/**
		 * Include necessary CSS file
		 */
		loadCss: function(file, options) {
			options = options || {
				"basePath": this.findPath(), 
				"cssPath": "css/"
			};

			var collection = $("link[rel=stylesheet]");
			var path = options.basePath + options.cssPath + file;

			for (i = 0; i < collection.length; i++) {
				if (path == collection[i].href) {
					// is loaded
					return true;
				}
			}

			var l = $("<link/>");
			l.attr({
				"href":		path,
				"media":	"all",
				"rel":		"stylesheet",
				"type":		"text/css"
			});
			$("head").append(l);
			return true;
		},

		parseControls: function() {
			if (this.options.parseControls) {
				return this.options.parseControls.call(this);
			}
			return this.options.controls;
		},

		removeFormat: function() {
			if ($.browser.msie) {
				this.focus();
			}

			//			this.editorDoc.execCommand("formatBlock", false, "<p>"); // remove headings
			this.editorDoc.execCommand("removeFormat", false, null);
			this.editorDoc.execCommand("unlink", false, null);
			return this;
		},

		removeHoverClass: function() {
			$(this).removeClass("wysiwyg-button-hover");
		},

		saveContent: function() {
			if (this.original) {
				if (this.viewHTML) {
					this.setContent($(this.original).val());
				}
				var content = this.getContent();

				if (this.options.rmUnwantedBr) {
					content = (content.substr(-4) == "<br/>") ? content.substr(0, content.length - 4) : content;
				}

				$(this.original).val(content);
				if (this.options.events && this.options.events.save) {
					this.options.events.save.call(this);
				}
			}
			return this;
		},

		setContent: function(newContent) {
			$(this.innerDocument()).find("body").html(newContent);
			return this;
		},

		triggerCallback: function(name) {
			$(window).trigger("wysiwyg-trigger-" + name, [
				this.getInternalRange(),
				this,
				this.getInternalSelection()
				]);
			$(".custom-command-" + name, this.panel).blur();
			this.focusEditor();
		},

		withoutCss: function() {
			if ($.browser.mozilla) {
				try {
					this.editorDoc.execCommand("styleWithCSS", false, false);
				}
				catch(e) {
					try {
						this.editorDoc.execCommand("useCSS", false, true);
					}
					catch(e2) {
					}
				}
			}
			return this;
		}
	};

	/*
	 * Wysiwyg namespace: public properties and methods
	 */
	$.wysiwyg = {
		/**
		 * Custom control support by Alec Gorge ( http://github.com/alecgorge )
		 */
		addControl: function(name, settings) {
			// sample settings
			/*
			var example = {
				icon: "/path/to/icon",
				tooltip: "my custom item",
				callback: function(selectedText, wysiwygInstance) {
					//Do whatever you want to do in here.
				}
			};
			*/

			var custom = {};
			custom[name] = {
				visible: true, 
				custom: true, 
				options: settings
			};

			self = this.data("wysiwyg");
			self.panel = $('<ul role="menu" class="panel"></ul>');
			self.controls = $.extend(true, self.controls, self.controls, custom);
			self.options.controls = self.controls;
			self.appendControls();
		},

		clear: function() {
			var self = this.data("wysiwyg");
			self.setContent("");
			self.saveContent();
			return this;
		},

		createLink: function(szURL) {
			var self = this.data("wysiwyg");

			if (!szURL || szURL.length === 0) {
				return this;
			}

			var selection = self.documentSelection.call($(self.editor));

			if (selection && selection.length > 0) {
				if ($.browser.msie) {
					self.focus();
				}
				self.editorDoc.execCommand("unlink", false, null);
				self.editorDoc.execCommand("createLink", false, szURL);
			}
			else if (self.options.messages.nonSelection) {
				alert(self.options.messages.nonSelection);
			}
			return this;
		},

		destroy: function() {
			var self = this.data("wysiwyg");

			if ("undefined" === typeof self) {
				return this;
			}

			self.destroy();
			return this;
		},

		"document": function() {
			var self = this.data("wysiwyg");
			return $(self.editorDoc);
		},

		getContent: function() {
			var self = this.data("wysiwyg");
			return self.getContent();
		},

		init: function(options) {
			return this.each(function() {
				$this = $(this);

				if ($this.data("wysiwyg")) {
					return this;
				}

				/*
				 * :TODO:
				 * this code do nothing, remove it? 
				 */
				if (false) {
					if (arguments.length > 0 && arguments[0].constructor == String) {
						var action = arguments[0].toString();
						var params = [];
	
						if (action == "enabled") {
							return this.data("wysiwyg") !== null;
						}
						for (var i = 1; i < arguments.length; i++) {
							params[i - 1] = arguments[i];
						}
						var retValue = null;
	
						// .filter('textarea') is a fix for bug 29 ( http://github.com/akzhan/jwysiwyg/issues/issue/29 )
						this.filter("textarea").each(function() {
							$.data(this, "wysiwyg").designMode();
							retValue = Wysiwyg[action].apply(this, params);
						});
						return retValue;
					}
				}
				/* end */

				Wysiwyg.init(this, options);
			});
		},

		insertHtml: function(szHTML) {
			var self = this.data("wysiwyg");
			self.insertHtml(szHTML);
			return this;
		},

		insertImage: function(szURL, attributes) {
			var self = this.data("wysiwyg");

			if (!szURL || szURL.length === 0) {
				return this;
			}
			
			if ($.browser.msie) {
				self.focus();
			}
			if (attributes) {
				self.editorDoc.execCommand("insertImage", false, "#jwysiwyg#");
				var img = self.getElementByAttributeValue("img", "src", "#jwysiwyg#");

				if (img) {
					img.src = szURL;

					for (var attribute in attributes) {
						img.setAttribute(attribute, attributes[attribute]);
					}
				}
			}
			else {
				self.editorDoc.execCommand("insertImage", false, szURL);
			}
			return this;
		},

		insertTable: function(colCount, rowCount, filler) {
			var self = this.data("wysiwyg");
			self.insertTable(colCount, rowCount, filler);
			return this;
		},

		removeFormat: function() {
			var self = this.data("wysiwyg");
			self.removeFormat();
			return this;
		},

		save: function() {
			var self = this.data("wysiwyg");
			self.saveContent();
			return this;
		},

		setContent: function(newContent) {
			var self = this.data("wysiwyg");
			self.setContent(newContent);
			self.saveContent();
			return this;
		}
	};

	$.fn.wysiwyg = function(method) {
		if ("undefined" !== typeof $.wysiwyg[method]) {
			return $.wysiwyg[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if ("object" === typeof method || !method) {
			return $.wysiwyg.init.apply(this, arguments);
		}
		else {
			try {
				$.error("Method " +  method + " does not exist on jQuery.wysiwyg");
			}
			catch(e) {
				console.error(error);
			}
		}
	};
})(jQuery);
