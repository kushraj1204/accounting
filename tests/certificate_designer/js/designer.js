(function(jQuery) {
	"use strict";

	function CertificateDesigner(instance, options) {
		let defaultOptions = {
			canvas: ""
		};
		this.instance = instance;
		this.options = jQuery.extend(true, {}, defaultOptions, options);
		this.$el = jQuery(this.instance);
		this.init();
	}

	CertificateDesigner.prototype = {
		init: function() {
			let self = this;
			self.$canvas = new fabric.Canvas(self.options.canvas.attr("id"));
			self.setCanvasDimension(800, 600);
			//fabric.Object.prototype.objectCaching = false;
			self.setupActions();
		},
		setupActions: function() {
			let self = this;
			self.$btnDimension = self.$el.find("#btnDimension");
			self.$txtWidth = self.$el.find("#txtWidth");
			self.$txtHeight = self.$el.find("#txtHeight");
			self.$btnDimension.on("click", function() {
				let width = self.$txtWidth.val();
				let height = self.$txtHeight.val();
				self.setCanvasDimension(width, height);
			});
			self.$btnBGImage = self.$el.find("#btnBGImage");
			self.$btnBGImage.on("click", function() {
				self.setCanvasBGImage("images/bg.jpg");
			});
			self.$btnImage = self.$el.find("#btnImage");
			self.$btnImage.on("click", function() {
				self.addImage("images/logo.png");
			});
			jQuery(document).on("keyup", function(e) {
				if (e.keyCode == 46) {
					self.deleteSelectedObjectsFromCanvas();
				}
			});
			self.$btnText = self.$el.find("#btnText");
			self.$btnText.on("click", function() {
				self.addText();
			});
			self.$btnAdjustText = self.$el.find("#btnAdjustText");
			self.$btnAdjustText.on("click", function() {
				self.loadTextFont();
			});
			self.$textColor = self.$el.find("#textColor");
			self.$textColor.spectrum({
				color: "#000"
			});
			self.$el.find("#btnSave").on("click", function() {
				let data = self.$canvas.toJSON(["height", "width"]);
				console.log("data", data);
			});
			self.$el.find("#btnLoad").on("click", function() {
				self.loadFromJSON("test.json");
			});
			self.$el.find("#btnGenerateCerts").on("click", function() {
				let tmp = self.$el.find("#json_value").val();
				let json_values = null;
				try {
					json_values = JSON.parse(tmp);
				} catch (e) {
					alert("Invalid json value");
					return;
				}
				jQuery.ajax({
					url: "certificate.json",
					method: "GET",
					success: function(res) {
						let promises = [];
						//fonts
						let fonts = ["Anton", "Saira Stencil One", "Lobster", "Roboto", "Times New Roman"];
						var fontsObservers = [];
						fonts.forEach(function(family) {
							var obs = new FontFaceObserver(family);
							fontsObservers.push(obs.load());
						});
						Promise.all(fontsObservers)
							.then(function(fonts) {
								json_values.forEach(function(json_value) {
									promises.push(self.generateDataUrlForEachObject(res, json_value));
								});
								Promise.all(promises).then(function(certificates) {
									//console.log(certificates);
									jQuery.ajax({
										url: "generate_certs.php",
										data: { certificates: certificates },
										type: "POST",
										dataType: "json",
										success: function(res) {
											if (res.success) {
												window.location.href = res.file;
											}
										}
									});
								});
							})
							.catch(function(err) {
								console.log("Some fonts are not available:", err);
							});
					}
				});
			});
		},
		generateDataUrlForEachObject: function(canvasJSON, dataJSON) {
			return new Promise(function(resolve, reject) {
				let canvas = new fabric.Canvas(null);
				canvas.clear();
				canvas.loadFromJSON(
					canvasJSON,
					function() {
						//canvas.renderAll();
						resolve([canvas.toDataURL(), dataJSON.id]);
					},
					function(o, object) {
						if (o.type == "textbox") {
							//replace variables
							for (var key in dataJSON) {
								if (dataJSON.hasOwnProperty(key)) {
									let regex = new RegExp("%%" + key + "%%");
									//console.log("before", object.text, regex, dataJSON[key]);
									object.text = object.text.replace(regex, dataJSON[key]);
									//console.log("after", object.text);
								}
							}
						}
					}
				);
			});
		},
		loadFromJSON: function(url) {
			let self = this;
			jQuery.ajax({
				url,
				method: "GET",
				success: function(res) {
					self.$canvas.clear();
					//fonts
					let fonts = ["Anton", "Saira Stencil One", "Lobster", "Roboto", "Times New Roman"];
					var fontsObservers = [];
					fonts.forEach(function(family) {
						var obs = new FontFaceObserver(family);
						fontsObservers.push(obs.load());
					});
					Promise.all(fontsObservers)
						.then(function(fonts) {
							self.$canvas.loadFromJSON(res, self.$canvas.renderAll.bind(self.$canvas), function(o, object) {
								//console.log(o, object);
							});
						})
						.catch(function(err) {
							console.log("Some fonts are not available:", err);
						});
				}
			});
		},
		loadTextFont: function() {
			let self = this;
			let ao = self.$canvas.getActiveObject();
			if (!ao) {
				alert("Text not selected");
				return;
			}
			let textFont = self.$el.find("#ddFont").val();
			if (textFont.length == 0) {
				textFont = "Times New Roman";
			}
			let fo = new FontFaceObserver(textFont);
			fo.load()
				.then(function() {
					self.setTextProperties(textFont);
				})
				.catch(function(e) {
					console.log(e);
					alert("font loading failed " + textFont);
				});
		},
		setTextProperties: function(textFont) {
			let self = this;
			let fontSize = self.$el.find("#txtFontSize").val();
			fontSize = parseInt(fontSize);
			if (isNaN(fontSize) || fontSize < 8) {
				fontSize = 8;
			}
			let fontWeight = self.$el.find("#ddFontWeight").val();
			fontWeight = parseInt(fontWeight);
			if (isNaN(fontWeight) || fontWeight < 100) {
				fontWeight = 100;
			}
			let textAlign = self.$el.find("#ddTextAlign").val();
			if (textAlign.length == 0) {
				textAlign = "left";
			}
			if (typeof textFont !== "undefined") {
				self.$canvas.getActiveObject().set("fontFamily", textFont);
			}
			let textColor = self.$textColor.spectrum("get").toHexString();
			self.$canvas.getActiveObject().set("fontWeight", fontWeight);
			self.$canvas.getActiveObject().set("fontSize", fontSize);
			self.$canvas.getActiveObject().set("textAlign", textAlign);
			self.$canvas.getActiveObject().set("fill", textColor);
			self.$canvas.requestRenderAll();
		},
		deleteSelectedObjectsFromCanvas: function() {
			let self = this;
			self.$canvas.getActiveObjects().forEach(obj => {
				self.$canvas.remove(obj);
			});
			self.$canvas.discardActiveObject().renderAll();
		},
		setCanvasDimension(width, height) {
			let self = this;
			width = parseInt(width);
			height = parseInt(height);
			if (isNaN(width)) {
				width = 800;
			}
			if (isNaN(height)) {
				height = 600;
			}
			self.$canvas.setWidth(width);
			self.$canvas.setHeight(height);
			//self.$canvas.renderAll();
		},
		setCanvasBGImage(path) {
			let self = this;
			self.$canvas.setBackgroundImage(
				path,
				function() {
					self.$canvas.renderAll.bind(self.$canvas),
						{
							originX: "left",
							originY: "top"
						};
					self.$canvas.renderAll();
				},
				{ crossOrigin: "anonymous" }
			);
		},
		addImage: function(path) {
			let self = this;
			fabric.Image.fromURL(
				path,
				function(img) {
					self.$canvas.add(img.set({ left: 0, top: 0, angle: 0 }).scale(1));
					self.$canvas.renderAll();
				},
				{ crossOrigin: "anonymous" }
			);
		},
		addText: function() {
			let self = this;
			let text = new fabric.Textbox("Hello world", {
				left: 0,
				top: 0,
				width: 100,
				fontSize: 16,
				fontWeight: "normal",
				fill: "#000000",
				textAlign: "center"
			});
			self.$canvas.add(text);
		}
	};

	jQuery.fn.CertificateDesigner = function(options) {
		let args = Array.prototype.slice.call(arguments, 1);
		let plgName = "CertificateDesigner";
		return this.each(function() {
			let inst = jQuery.data(this, plgName);
			if (typeof inst === "undefined") {
				if (typeof options === "undefined" || typeof options == "string" || options instanceof String) {
					throw "invalid options passed while creating new instance.";
				}
				let p = new CertificateDesigner(this, options);
				jQuery.data(this, plgName, p);
			} else if (typeof options !== "undefined") {
				if (typeof inst[options] === "function") {
					inst[options].apply(inst, args);
				}
			}
		});
	};
})(jQuery);
