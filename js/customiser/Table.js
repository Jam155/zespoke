function Table(table, reflection, scale, rot, x, y) {

	this.table = table;
	this.position = new THREE.Vector3(0,0,0);
	this.scale = new THREE.Vector3(scale,scale,scale);
	this.rotation = rot;
	this.translation = new THREE.Vector3(x, y, 0);
	this.reflection = reflection;
	this.rotating = false;
	this.targetRotation = 0 //Rotation Target?


	this.envMap = THREE.ImageUtils.loadTextureCube([

                '/wp-content/uploads/empty_room_white/right.jpg',
                '/wp-content/uploads/empty_room_white/left.jpg',
                '/wp-content/uploads/empty_room_white/top.jpg',
                '/wp-content/uploads/empty_room_white/bottom.jpg',
                '/wp-content/uploads/empty_room_white/front.jpg',
                '/wp-content/uploads/empty_room_white/back.jpg',

        ]);



	this.manipulation = function() {

		this.table.position.set(this.position.x, this.position.y, this.position.z);

	};
	
	this.setUpMaterial = function(object) {
	
		object.material.map.wrapT = THREE.RepeatWrapping;
		object.material.map.wrapS = THREE.RepeatWrapping;
			
		if (object.cus_repeatable) {
		
			object.material.map.repeat.set(2, 4);
		
		} else {
		
			object.material.map.repeat.set(1, 1);
		
		}
	
	};
	
	this.getMaterial = function(ref, name, visible, matt, reflection, repeatable, intensity, texture) {
	
		intensity = 0.35;

		var args = {};
		var material;
		var reflection = this.reflection;
	
		texture.anisotropy = 16;
		texture.minFilter = THREE.NearestMipMapNearestFilter;

		args.name = ref;
		args.map = texture;
		args.visible = visible;
		args.reflectivity = 0;
		args.combine = THREE.MixOperation;
		args.side = THREE.FrontSide;

		console.log("Is matt texture?");
		console.log(matt);
	
		if (!matt) {
			
			if (reflection) {
			
				reflection.mapping = THREE.SphericalReflectionMapping;
				this.envMap.mapping = THREE.CubeReflectionMapping;
				args['envMap'] = this.envMap;
				args['needsUpdate'] = true;
				args['reflectivity'] = intensity;	
			}
		
		} else {
		
			args['shininess'] = 100;
			args['specular'] = 0x0a0a0a;
		
		}
		
		if (!matt) {
		
			material = new THREE.MeshPhongMaterial(args);

			material.name = ref;
			material.map = texture;
			material.visible = visible;
			material.reflectivity = 0.1;
			material.shininess = 10;
			material.combine = THREE.MixOperation;
		
		} else {
			material = new THREE.MeshLambertMaterial(args);
		
		}
		
		return material;
	
	}

	this.hideTexture = function(side) {
	
		that = this;
		this.table.traverse(function(tObject) {

			if (tObject instanceof THREE.Mesh) {
			
				if (tObject.material.name == side) {

					tObject.material.visible = false;

				}

			}

		});

	}

	this.showTexture = function(side) {

		that = this;
		that.table.traverse(function(tObject) {

			if (tObject instanceof THREE.Mesh) {

				if (tObject.material.name == side) {

					tObject.material.visible = true;

				}

			}

		});

	}
	
	this.setUpTexture = function(object, texture) {
		
		that = this;
		
		if (object.material.name == texture.Ref) {
		
			if (texture.name.indexOf("Yes") >= 0) {
			
				object.material.visible = true;
			
			} else if (texture.name.indexOf("No") >= 0) {
			
				object.material.visible = false;
			
			} else {

				console.log("Texture Data:");
				console.log(texture);

				console.log("Other Texture Data:");
				
				var ref = texture.Ref;
				var name = texture.name;
				var reflection = texture.reflection == "1";
				var reflectivity = 0.35;
				var visible = object.material.visible;
				//var matt = texture.matt === "0";
				var map = texture.texture;
				var repeatable = texture.repeatable == "1";

				console.log("Ref: ");
				console.log(texture.Ref);
				console.log("Name: ");
				console.log(texture.name);
				console.log("Reflection: ");
				console.log(texture.reflection);
				
				if (texture.loc_intensity != null && texture.loc_intensity > 0) {
				
					reflectivity = texture.loc_intensity;
				
				} else {
				
					reflectivity = texture.intensity;
				
				}

				//reflectivity = 0.15;
				
				object.material = that.getMaterial(ref, name, visible, texture.matt, texture.reflection, texture.repeatable, reflectivity, map);
				object.cus_repeatable = repeatable;
				
				this.setUpMaterial(object);
			
			}
		
		}
	
	}
	
	this.useTexture = function(texture) {
	
		var that = this;
		
		that.table.traverse(function (tobject) {
			
			if (tobject instanceof THREE.Mesh) {
                                
                                that.setUpTexture(tobject, texture);
                               	
                        }
                        
                });
                                                                                                                        
	}

	this.getDefaultTextures = function() {

		var defaultTextures = jQuery('.ac-row.texture article input[type=radio]').filter(':checked');
		var table = this;

		defaultTextures.each(function(i) {

			var $input = jQuery(this);
			var side = $input.closest('.ac-row').data('side');
			var textureId = $input.data('texture');

			jQuery.ajax({

		        	url: '/wp-admin/admin-ajax.php',
		        	type: 'GET',
		        	dataType: 'json',
		        	data: {

		                	'action': 'customiser_texture_request',
		                	'texture': textureId

		        	},
		        	success: function(data) {

		                	var loader = new THREE.TextureLoader();

		                	loader.load(data.texture, function(theTexture) {

		                        	var sides = side.split(',');

		                        	data.texture = theTexture;

		                        	sides.forEach(function(side) {

		                                	data.Ref = side;
		                                	table.useTexture(data);

		                        	});

		                	});
		                	data.Ref = side;
		        	},
		        	error: function(err) {

		                	console.log(err);

		        	}

			});

		});

	}


	this.getDefaultOptions = function() {

		var defaultOptions = jQuery('.ac-row.option article input[type=radio]').filter(':checked');
		var table = this;

		defaultOptions.each(function(i) {

			$input = jQuery(this);
        		side = $input.closest('.ac-row').data('side');
        		val = $input.val();

        		if (val == 'Yes') {

                		table.showTexture(side);

        		} else if (val == 'No') {

                		table.hideTexture(side);

        		}

		});

	}
	
	this.setup = function() {
		
		this.table.rotation.y = this.rotation;
		this.table.castShadow = true;
		this.table.scale.set(this.scale.x, this.scale.y, this.scale.z);
		this.getDefaultOptions();
	
		console.log(table.position);
		this.table.position.set(0, 0, 0);
		setCameraLookAt(this.table);
		scene.add(this.table);

	
	}

	this.load = function(table) {

		var changeYRotation = 0;

		this.table = table;
		table.position.set(0 /*this.position.x + parseFloat(this.translation.x)*/, 0 /*this.position.y + parseFloat(this.translation.y)*/, 0/*this.position.z*/);// = this.position;
		table.rotation.y = 0; //this.rotation;
		table.castShadow = true;
		table.scale.set(this.scale.x, this.scale.y, this.scale.z);

		table.updatePosition = function(translation) {

			var boundingBox = new THREE.Box3().setFromObject(table);
			var floorBox = new THREE.Box3().setFromObject(floor);

			var yTranslation = (boundingBox.min.y - floorBox.max.y)*-1;
			this.translateY(yTranslation + parseFloat(translation.y));

		}

		table.updatePosition(this.translation);

		table.traverse(function(object) {

			object.castShadow = true;

			if (object instanceof THREE.Mesh) {

				if (object.material.name == "Inside") {
				
					var envMap = THREE.ImageUtils.loadTexture('Tex/envmap2.jpg');

				}
				
				if (object.material.name == "option_shelf") {

					object.material.color.setHex(0x2A8B93);

				}

			}

		});
	
		var host = SITE_BASE_URL;

		var url = "//" + host + "?route=module/customiser/gettextures&table_id=" + this.id;
		var textLoc = "//" + window.location.host + "/assets/files/textures/";
		var that = this;
		var texturesCache = {};
	
		var setUpMaterials = function(object, item, loading) {
		
			var matt = item.matt === "0";
			var ref = item.Ref;
			var name = item.name;
			var reflection = item.reflection == "1";
			var reflectivity = 0.35;
			var visible = object.material.visible;
			
			object.material = that.getMaterial(ref, name, visible, matt, reflection, reflectivity, texturesCache[item.name]);
			that.setUpMaterial(object);
		
		}
	
		var loadTexture = function(object, item) {
		
			if (object.material.name == item.Ref) {
			
				if (item.name.indexOf("Yes") >= 0) {
				
					object.material.visible = true;
				
				} else if (item.name.indexOf("No") >= 0) {
				
					object.material.visible = false;
				
				} else {
				
					var imgUrl = SITE_BASE_URL + "/image/" + item.image;
					THREE.TextureLoader.prototype.crossOrigin = '';
					var loader = new THREE.TextureLoader();

					console.log("Image URL:");
					console.log(imgUrl);
					//loader.crossOrgin = '';
					
					if (!(item.name in texturesCache)) {
					
						loader.load(imgUrl, function(texture) {
						
							texturesCache[item.name] = texture;
							setUpMaterials(object, item);
						
						});
					
					} else {
					
						setUpMaterials(object, item);
					
					}
				
				}
			
			}
		
		}
	
		var loadTextures = function(json) {
	
			table.traverse(function (tobject) {
		
				if (tobject instanceof THREE.Mesh) {
			
					json.forEach(function(jsonItem, i, json) {
					
						loadTexture(tobject, json[i]);
					
					});
				
				}
				
			});

		}

		var glassShelf = false;
		var backPlate = false;
		var feet = false;

			for(var i = 0; i < options.length; i++) {

				var enabled = false;

				if (options[i].Enabled == 1) {

					enabled = true;

				}

				jQuery('input[type=checkbox][name=' + options[i].Table_Option + ']').prop('checked', enabled);

				table.traverse(function(object) {

					if (object instanceof THREE.Mesh) {

						if (object.material.name == options[i].Table_Option) {

							object.material.visible = enabled;
							object.castShadow = enabled;

						}

					}

				})

			}

	
		table.castShadow = true;
		table.receiveShadow = true;
		setCameraLookAt(table);
		scene.add(table);
		curTable = table;
		changed = true;
	
		if (jQuery('.dd-option-selected').length > 0 ) {
	
			var defaults = jQuery('.dd-option-selected');
			//console.log("Default Values");
	
			for (var i = 0; i < defaults.length; i++) {
		
				var value = jQuery(defaults[i]).find('input').val();
				
				if (value > 0) {
				
					var url = SITE_BASE_URL + "?route=module/customiser/getproductoption&option_id=" + value;	
			
					jsonHttpRequest(url, loadTextures);
					
				}
			
		
			}			
	
		}

	}

	this.zoomIn = function() {

		var zoomInc = 1.01;
		var targetScale = this.table.scale.x * 1.5;
		var table = this.table;

		function zoom() {

			table.scale.set(table.scale.x * zoomInc, table.scale.y * zoomInc, table.scale.z * zoomInc);
			zoomInc = zoomInc * 1.1;

			if (table.scale.x < targetScale) {

				console.log("zooming");

				requestAnimationFrame(zoom);

			}

		}

		requestAnimationFrame(zoom);
		//var targetScale = this.table.scale.x * 2;



		//this.table.scale.set(this.table.scale.x * 1.1, this.table.scale.y * 1.1, this.table.scale.z * 1.1);

	}

	this.zoomOut = function() {

		var zoomOut = 1.01;
		var targetScale = this.table.scale.x / 1.5;
		var table = this.table;

		function zoom() {

			table.scale.set(table.scale.x / zoomOut, table.scale.y / zoomOut, table.scale.z / zoomOut);
			zoomOut = zoomOut * 1.1;

			if (table.scale.x > targetScale) {

				requestAnimationFrame(zoom);

			}

		}

		requestAnimationFrame(zoom);

		//this.table.scale.set(this.table.scale.x / 1.1, this.table.scale.y / 1.1, this.table.scale.z / 1.1);

	}

	this.setScale = function(scale) {

		this.table.scale.set(scale, scale, scale);

	}

	this.rotate = function() {

		this.rotating = true;
		var that = this;

		function rotate() {

			//console.log(that.table.rotation);
			that.table.rotateY(that.targetRotation * 0.13);
			that.targetRotation = that.targetRotation * 0.9;
			requestAnimationFrame(rotate);

		}


		//this.table.rotateY(this.targetRotation * 0.05);

		requestAnimationFrame(rotate);

	}

	this.rotateY = function(dy) {

		this.targetRotation = (this.targetRotation + dy);
		console.log(this.targetRotation);

		if (!this.rotating) {
			this.rotate();
		}
		//this.table.rotateY(dy);

	}

	this.rotateLeft = function() {

		this.table.rotateY(0.01);

	}

	this.rotateRight = function() {

		this.table.rotateY(-0.01);

	}

	/*this.init = function() {

		var that = this;
		var loader;

		if (this.mtl) {

			THREE.OBJMTLLoader.prototype.crossOrigin = "";
			loader = new THREE.OBJMTLLoader(new THREE.LoadingManager());
			loader.load(this.model, this.mtl, function(table) {
			
				that.table = table;
				that.setup();
			
			}, this.onProgress, this.onError, this);

		} else {

			loader = new THREE.OBJLoader(new THREE.LoadingManager());
			loader.load(this.model, this.load, this.onProgress, this.onError, this);

		}

	}*/

}
