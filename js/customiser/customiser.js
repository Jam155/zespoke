
	var aspect = 1.33334;

	var view_angle = 45;
	var near = 0.1;
	var far = 1000;
	var changed = true;
	var composer;
	var bgComposer;
	var fxaaShader;
	var resized = false;
	var productID = 0;
	var isLoading = true;
	var Lighting = new Lighting();

	var getWidth = function() {

		return jQuery('#customiser').width();

	}

	var getHeight = function() {

		return getWidth() / aspect;

	}

	var resizeCustomiser = function() {

		width = getWidth();
		height = getHeight();
		resized = true;
		renderer.setSize(width, height);
		fxaaShader.uniforms['resolution'].value = new THREE.Vector2(1/width, 1/height);

	}

	jQuery(window).on('resize', resizeCustomiser);

	var texture = THREE.ImageUtils.loadTexture(URLS.BG_IMAGE);
	var backgroundMesh = new THREE.Mesh(
		new THREE.BoxGeometry(2,2,100),
		new THREE.MeshBasicMaterial({
			map: texture

		}));


	backgroundMesh.doubleSided = true;
	backgroundMesh.material.depthTest = false;
	backgroundMesh.material.depthWrite = false;
	backgroundMesh.position.set(0, 0, -50)

	//Create the Background Scene.

	var backgroundScene = new THREE.Scene();
	var backgroundCamera = new THREE.OrthographicCamera(-1,1,1,-1,1, 1000);

	camera = new THREE.PerspectiveCamera(view_angle, aspect, near, far);
	backgroundCamera.position.set(0,0,100);
	backgroundCamera.lookAt(new THREE.Vector3(0,0,0));
	backgroundScene.add(backgroundCamera);
	backgroundScene.add(backgroundMesh);

	foregroundScene = new THREE.Scene();
	foregroundCamera = new THREE.PerspectiveCamera();

	backgroundCamera.position.set(0,0,100);
	backgroundCamera.lookAt(new THREE.Vector3(0,0,0));

	foregroundScene.add(foregroundCamera);

	var view_angle = 45;
	var near = 0.1;
	var far = 1000;
	var focus = new THREE.Vector3(-10,300,0);
	focus = new THREE.Vector3(0, 0, 0);
	var position = new THREE.Vector3(0,0,-200);

	var renderer;
	var camera;
	var camera2;
	var scene;
	var manager = new THREE.LoadingManager();

	var textures = {};
	var textureMap = {};

	var spinner;

	var curTable;

	var floor;

	var objects = [];
	var options = [];

	var curTextures = {};
	var LoadingObject = function() {

		this.total = 0;
		this.loading = 0;
	
		this.load = function() {
	
			this.total++;
			this.loading++;
	
		}
	
		this.unload = function() {
	
			if (this.loading > 0) {
		
				this.loading--;
			
			}
		
		}
	
		this.loaded = function() {
	
			return this.loading <= 0;
	
		}
	
		this.getTotal = function() {
	
			return this.total;
	
		}

	}



	var Loading = new LoadingObject();

	var onProgress = function(xhr) {

		if (xhr.lengthComputable) {

			var percentComplete = xhr.loaded / xhr.total * 100;

		}

	};

	var onError = function(xhr) {

		//console.log('Error loading file.');

	}

	var table;
	var geometry;
	var material;

	manager.onProgress = function(item, loaded, total) {

		//console.log(item, loaded, total);

	}	

	var log_error = function(title, message) {

		jQuery.ajax({

			url: '/wp-admin/admin-ajax.php',
			type: 'GET',
			dataType: 'json',
			data: {

				'action': 'log_error',
				'error_title': title,
				'error_error': message,

			},
			success: function(data) {

				console.log(title + ' successfully logged.');

			}

		});

	}
	
	var animate = function() {

		requestAnimationFrame(animate);
		renderer.clear();

		if (!Loading.loaded()) {

			if (jQuery('.customiser .spinner-loader').length <= 0) {
			
				jQuery('.customiser').append("<div class='spinner-loader'></div><div class='spinner-loader-text'>Loading..</div>");
		
			}	
		

		} else {

			if (jQuery('.customiser .spinner-loader').length > 0) {

				jQuery('.spinner-loader, .spinner-loader-text').remove();
				console.log("Overlay");
				console.log(jQuery("#customiser .overlay"));
				//jQuery('#customiser .overlay').removeClass('hidden');

			}


		
			for(var i = 0; i < objects.length; i++) {

				objects[i].manipulation();

			}

			renderer.autoClear = false;
			renderer.clear();
			
			composer.render();			
		
		}

	}

	var initRenderer = function() {
		
				try {
				
				var params = { minFilter: THREE.LinearFilter, magFilter: THREE.LinearFilter, format: THREE.RGBAFormat, stencilBuffer: false };
				renderer = new THREE.WebGLRenderer({preserveDrawingBuffer: true, antialias: false, alpha: true, precision: "highp"});
				
				if (renderer.getContext()) {

					log_error("Init Renderer", "Renderer Successfully Initialized");

					

					var width = getWidth();
					var height = getHeight();

					renderer.shadowMapEnabled = true;
					renderer.shadowMapSoft = true;
					renderer.shadowMapType = THREE.PCFSoftShadowMap;
					renderer.shadowCameraNear = 3;
					renderer.shadowCameraFar = camera.far;
					renderer.shadowCameraFov = 50;
					renderer.shadowMabBias = 0.0039;
					renderer.shadowMapDarkness = 0.5;
					renderer.setClearColor(0x000000, 0);
					renderer.setSize(width, height);
					renderer.autoClearColor = false;
					
					drawBuffer = new THREE.WebGLRenderTarget(width * 4, height * 4);
					renderer.domElement.id = "customiserCanvas";
					
					var renderTarget = new THREE.WebGLRenderTarget(width, height, params);
					composer = new THREE.EffectComposer(renderer, renderTarget);
					
					var bgRenderPass = new THREE.RenderPass(backgroundScene, backgroundCamera);
					composer.addPass(bgRenderPass);

					var renderPass = new THREE.RenderPass(scene, camera, null, new THREE.Color(0xFFFFFF), 1);
					renderPass.renderToScreen = true;
					composer.addPass(renderPass);
	
					fxaaShader = new THREE.ShaderPass(THREE.FXAAShader);
					fxaaShader.uniforms['resolution'].value = new THREE.Vector2(1/width, 1/height);					
					fxaaShader.renderToScreen = true;

					composer.addPass(fxaaShader);					

					var dlink = "<a id='customiserImage' download>Picture</a>";
					
					//jQuery('div.customiser').append(renderer.domElement);

					//Listen for loss of context.
					jQuery('div.customiser .overlay').removeClass('hidden');

				} else {

					jQuery('div.main-image').removeClass('hidden');
					jQuery('div#customiser').addClass('hidden');

				}

				} catch (exception) {

					console.log("Error with WebGL");
					jQuery('div.main-image').removeClass('hidden');
					jQuery('div#customiser').addClass('hidden');
					console.log(exception);
					//throw exception;

				}

	}

	var initCamera = function() {

		camera = new THREE.PerspectiveCamera(view_angle, aspect, near, far);

		jQuery.ajax({

			url: '/wp-admin/admin-ajax.php',
			type: 'GET',
			dataType: 'json',
			data: {

				'action': 'get_camera',
			},
			success: function(data) {

				jQuery.ajax({
					url: '/wp-admin/admin-ajax.php',
					type: 'GET',
					dataType: 'json',
					data: {
						'action': 'camera_offset',
						'product': getProductID(),
					},
					success: function(offset) {

						camera_pos = data[0];

						camera.position.set(camera_pos.x_position, camera_pos.y_position, camera_pos.z_position);

						focus.x = 0;
						focus.y = offset;
						focus.z = 0;

						camera.lookAt(focus);

					}
				});

			}

		});

	}

	var initScene = function() {

		scene = new THREE.Scene();

	}

	var getProductID = function() {

		var product = jQuery("input[name=product]").val();
		return product;

	}

	var getObjectFile = function() {

		var objfile = jQuery("input[name=object_file]").val();
		return objfile;

	}

	var getMaterialFile = function() {

		var matfile = jQuery("input[name=materials_file]").val();
		return matfile;

	}

	var getScale = function() {

		var scale = jQuery("input[name=scale]").val();
		return scale;

	}

	var getRotation = function() {

		var rotation = jQuery("input[name=rotation]").val();

	}

	var init = function(product) {

		log_error("Customiser Request", navigator.userAgent);

		try {

			if (!Modernizr.webgl) {

				log_error("WebGL Not Supported", "No WebGl Support in browser");
				console.log("No WebGL Support");
				throw "WebGL Not Supported";

			}


			THREE.TextureLoader.prototype.crossOrigin = '';

			var product = getProductID();
			var objfile = getObjectFile();
			var matfile = getMaterialFile();
			var scale = jQuery("input[name=scale]").val();
			var rotation = jQuery("input[name=rotation]").val();
			var xtrans = jQuery("input[name=translation_x]").val();
			var ytrans = jQuery("input[name=translation_y]").val();			

			jQuery.ajax({

				url: '/wp-admin/admin-ajax.php',
				type: 'GET',
				dataType: 'json',
				data: {

					'action': 'customiser_get_table_request',
					'product': product

				},
				success: function(data) {

					initCamera();
					initScene();
					initRenderer();
					Lighting.initLighting(scene);
					loadTableObject(data.Object_File, data.Material_File, product, scale, rotation, xtrans, ytrans);

				},
				error: function(err) {

					log_error("Failed to Load Table", err);
					jQuery('.main-image').removeClass('hidden');
					jQuery('div#customiser').remove();

				}

			});

		
		} catch (err) {
	
			log_error("Customiser Failed", err);
			console.log("An Error occurred");
			console.log(err.stack);
			jQuery('.main-image').removeClass('hidden');
			jQuery('div#customiser').remove();
					
		}

	}

	var getAJAXObject = function() {

		var xmlhttp;

		if (window.XMLHttpRequest) {

			xmlhttp = new XMLHttpRequest();

		} else {

			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

		}

		return xmlhttp;

	}

	var jsonHttpRequest = function(url, method) {

		var xmlhttp = getAJAXObject();

		xmlhttp.open("GET", url, true);
		xmlhttp.timeout = 4000;
		xmlhttp.send();

		xmlhttp.onreadystatechange = function() {

			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

				log_error("AJAX Success", xmlhttp.response);
				var response = JSON.parse(xmlhttp.responseText)
				method(response);
			

			} else if (xmlhttp.readyState == 4) {

				log_error("AJAX Error", "Status: " + xmlhttp.status);
				log_error("AJAX Error", "Response: " + xmlhttp.response);

			}

		}
	

	}

	var addInterface = function() {

		jQuery("body").append('<button id="export" value="Export">Export</button>')

	}

	var changeTexture = function(sides, texture_id) {

			console.log(sides);

                        jQuery.ajax({

                                url: '/wp-admin/admin-ajax.php',
                                type: 'GET',
                                dataType: 'json',
                                data: {

                                        'action': 'customiser_texture_request',
                                        'texture': texture_id,

                                },
                                success: function(data) {

                                        THREE.TextureLoader.prototype.crossOrigin = '';
                                        var loader = new THREE.TextureLoader();
                                        loader.crossOrigin = '';

                                        loader.load(data.texture, function(theTexture) {

                                                var sides_array = sides.split(',');
                                                data.texture = theTexture;

                                                sides_array.forEach(function(side) {

                                                        data.Ref = side;
							console.log(side);
                                                        table.useTexture(data);

                                                });
                                        });

                                },
                                error: function(err) {

                                        console.log(err);

                                }

                        });

                }
	
	jQuery(document).ready(function() {

		//Pinch to Zoom Functionality.
		var prevDiff = -1;

		jQuery("button[name=featured_image]").on('click', function(e) {

			var customiser = document.getElementById("customiserCanvas");
			var fullQuality = customiser.toDataURL("image/jpeg", 1.0);

			jQuery.post(

				'/wp-admin/admin-ajax.php',
				{
					action: 'save_featured_image',
					image: fullQuality,
					product_id: getProductID()

				},
				function(data) {

					//alert(data);
					console.log(data);

				}
			)


			console.log(fullQuality);

		});

		jQuery(".ac-row.option article input[type=radio]").on('click', function(e) {

			$input = jQuery(this);
			side = $input.closest('.ac-row').data('side');
			val = $input.val();

			if (val == 'Yes') {

				table.showTexture(side);

			} else if (val == 'No') {

				table.hideTexture(side);

			}

		});

		var rotatingLeft = false;

		var rotateLeft = function() {

			if (rotatingLeft) {

				table.rotateLeft();
				window.requestAnimationFrame(rotateLeft);

			}

		}

		var rotatingRight = false;

		var rotateRight = function() {

			if (rotatingRight) {

				table.rotateRight();
				window.requestAnimationFrame(rotateRight);

			}

		}


		var mousedown = false;
		var mouseY = 0;
		var pinching = false;

		jQuery('#customiser').on('touchstart', function(e) {

			jQuery("#customiser").on('touchmove', function(e) {

				if (e.targetTouches.length == 2) {

					e.preventDefault();

					var curDiff = Math.abs(e.targetTouches[0].clientX - e.targetTouches[1].clientX);
					console.log(e.targetTouches);
					console.log(curDiff - prevDiff);

					if (Math.abs(curDiff - prevDiff) > 5) {

						if (prevDiff > 0) {

							if (curDiff > prevDiff) {

								console.log("Stretching");
								table.zoomBy(1.1);

							}

							if (curDiff < prevDiff) {

								console.log("Pinching");
								table.zoomBy(0.9);

							}

						}

						prevDiff = curDiff;

					}

				}

			});

			if (e.touches.length === 1) {

				//e.preventDefault();
				mouseX = e.touches[0].pageX;

				//alert(mouseX);

				jQuery("#customiser").on('touchmove', function(e) {

					//e.preventDefault();

					var dx = e.touches[0].pageX - mouseX;
					mouseX = e.touches[0].pageX;
					var mult = Math.PI / 530;
					table.rotateY(dx * mult);


				});

			}

		});

		jQuery("#customiser").on('pointerup', function(e) {

			prevDiff = -1;
			console.log("touch up");

		});

		jQuery("#customiser").on('mousedown', function(e) {
			
			mouseX = e.pageX;
			//alert(e);

			jQuery("#customiser").on('mousemove', function(e) {
		
				var dx = e.pageX - mouseX;
				mouseX = e.pageX;
				var mult = Math.PI / 530;
				table.rotateY(dx * mult);
				console.log(dx);

			});

		});

		jQuery("#customiser").on('mouseup mouseleave', function(e) {

			mousedown = false;

			jQuery("#customiser").off('mousemove');
			console.log(mousedown);

		});

		jQuery("#customiser .top-controls .zoom-in").on('click', function(e) {

			console.log("Zooming In");
			table.zoomIn();	

		});

		jQuery("#customiser .top-controls .zoom-out").on('click', function(e) {

			console.log("Zooming Out");
			table.zoomOut();

		});

		jQuery(".ac-row.texture article input[type=radio]").on('click', function(e) {

			$input = jQuery(this);
			side = $input.closest('.ac-row').data('side');

			textureId = $input.data('texture');	

			changeTexture(side, textureId);

		});	
		
	});

	var loadingTextures = 0;

	var loaded = function() {

		if (loadingTextures > 0) {

			window.requestAnimationFrame(loaded);

		} else {

			animate();
			jQuery('#customiser .loader').remove();
			jQuery('#customiser .main-image').addClass('hidden');

			//var theCanvas = renderer.domElement
			jQuery('div.customiser').append(renderer.domElement);

			var canvas = document.getElementById('customiserCanvas');
			//jQuery(canvas).addClass('hidden');
			//canvas.addClass('hidden');
			canvas.addEventListener("webglcontextlost", function(e) {

				console.log("Context was lost");
				e.preventDefault();
				log_error("Lost Context", e);

				convas.addEventListener("webglcontextrestored", function(e) {

					init(125);

				}, false);

                        }, false);

                        //canvas.addEventListener("webglcontextrestored", init(125), false);

			jQuery('#customiser').removeClass('hidden');
			jQuery('#customiser .overlay').removeClass('hidden');
			setTimeout(function() {
				jQuery('#customiser .overlay').addClass('hidden');
			}, 2000);
			jQuery('#customiser .controls').removeClass("hidden");
			jQuery('#customiser .top-controls').removeClass("hidden");
			//animate();

		}

	}

	var getDefaultTextures = function(table, next) {

		var defaultTextures = jQuery('.ac-row.texture article input[type=radio]').filter(':checked');
		loadingTextures = defaultTextures.length;
		window.requestAnimationFrame(loaded);
		console.log(defaultTextures);

		/** Get the IDs of the Textures **/
		var textureIds = [];
		var sides = [];

		defaultTextures.each(function(i) {

			var $input = jQuery(this);
			sides.push($input.closest('.ac-row').data('side'));
			textureIds.push($input.data('texture'));

		});

		jQuery.ajax({

			url: '/wp-admin/admin-ajax.php',
			type: 'GET',
			dataType: 'json',
			data: {

				'action': 'customiser_texture_requests',
				'textures': textureIds,
				'sides': sides,

			},
			success: function(data) {

				data.forEach(function(texture) {
					
					var loader = new THREE.TextureLoader();
					loader.load(texture.texture, function(theTexture) {

						var sides = texture.Ref.split(',');
						texture.texture = theTexture;

						sides.forEach(function(side) {

							texture.Ref = side;
							table.useTexture(texture);

						});

						--loadingTextures;

					});

				});

			}

		});

	}

	var setupScale = function(table) {

		var html = "<div>";

		html += "Scale<input name='scale' type='number' value='0' step='0.1' />";

		html += "</div>";

		jQuery('#customiser-lighting').append(html);
		jQuery('input[name=scale]').on('change', function(e) {

			var scale = jQuery(this).val();
			table.setScale(scale);

		});

	};

	var loadTableObject = function(model, mtl, id, scale, rot, x, y) {

		THREE.OBJMTLLoader.prototype.crossOrigin = "";
		var loader = new THREE.OBJMTLLoader(new THREE.LoadingManager());
		loader.load(model, mtl, function(object) {

			table = new Table(object, true, scale, rot, x, y);
			table.setup();
			getDefaultTextures(table);
			setupScale(table);

		}, this.onProgress, this.onError, this);

	};

	var setTable = function getModel(tableID, scale, rot, x, y) {
		
		var reflection = false;
		var host = SITE_BASE_URL;
		var url = "/wp-admin/admin-ajax.php?action=customiser_get_table_request&product=" + tableID; 
		
		jsonHttpRequest(url, function(json) {
		
			loadTableObject(json.Object_File, json.Material_File, tableID, scale, rot, x, y);
			
		});

	}
