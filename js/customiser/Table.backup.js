const SITE_BASE_URL = window.location.protocol + "//www.zespoke.com/";

function Table(model, mtl, textures, id, scale, rot, x, y) {

	this.id = id;
	this.table;
	this.model = model;
	this.textures = textures;
	this.mtl = mtl;
	this.position = new THREE.Vector3(0,0,0);
	this.scale = new THREE.Vector3(scale,scale,scale);
	this.rotation = rot;
	this.translation = new THREE.Vector3(x, y, 0);

	this.manipulation = function() {

		this.table.position.set(this.position.x, this.position.y, this.position.z);

	};
	
	this.setUpMaterial = function(object, loading) {
	
		object.material.map.wrapT = THREE.RepeatWrapping;
		object.material.map.wrapS = THREE.RepeatWrapping;
		object.material.map.repeat.set(2,4); /** 2, 4 **/
		//console.log(object.material);
		
		//console.log("The Object");
		//console.log(object);
		loading.unload()
	
	};

	this.load = function(table) {


		var tableObject = JSON.stringify(this) //Keep the Object.
		var changeYRotation = 0;

		table.position.set(this.position.x + parseFloat(this.translation.x), this.position.y + parseFloat(this.translation.y), this.position.z);// = this.position;
		table.rotation.y = this.rotation;
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
					object.material.transparent = true;
					object.material.opacity = 0.1;

				}

			}

		});
	
		var host = SITE_BASE_URL; /**window.location.host + window.location.pathname;**/

		var url = "http://" + host + "?route=module/customiser/gettextures&table_id=" + this.id;
		var textLoc = "http://" + window.location.host + "/assets/files/textures/";
	
		var that = this;
	
		var loadTextures = function(json) {
	
			console.log("Load Textures Object");
			console.log(json);

			//console.log("Loading Textures");
			table.traverse(function (object) {
		
				if (object instanceof THREE.Mesh) {
		
					//for (var i = 0; i < json.length; i++) {
			
					json.forEach(function(jsonItem, i, json) {
						
						if (object.material.name == json[i].Ref) {
						
							var bMap = new THREE.ImageUtils.loadTexture(SITE_BASE_URL + "/image/data/a_textures/bmp_map.jpg");

							if (json[i].name.indexOf("Yes") >= 0) {
			
								//console.log(object.material);
								object.material.visible = true;
					
							} else if (json[i].name.indexOf("No") >= 0) {
				
								object.material.visible = false;
				
							} else  {
				
								var imgUrl = SITE_BASE_URL + "/image/" + json[i].image;
								//console.log("Loading Texture");
								
								var loader = new THREE.TextureLoader();
								
								//First load the texture
								Loading.load();
								
								loader.load(imgUrl, function(texture) {
								
									//console.log("Setting Up Material");
									
									//Setup the Material
									
									//Check if the material is Gloss or Matt.
									if (json[i].matt === "0") {
									
										object.material = new THREE.MeshPhongMaterial({
										
											name: json[i].Ref,
											map: texture,
											shininess: 60,
											specular: 0x111111,
											color:0xFFFFFF,
											refractionRation: 0.11,
											reflectivity: 0.3,
											visible: object.material.visible,
											
										});
										
										//Check if the material is reflective.
										if (json[i].reflection == "1") {
										
											loader.load( SITE_BASE_URL + "/image/data/a_textures/serveimage.jpg", function(texture) {
											
												texture.mapping = THREE.SphericalReflectionMaping;
												
												object.material.envMap = texture;
												object.material.needsUpdate = true;	
											
												if (json[i].loc_intensity != null && json[i].loc_intensity > 0) {
												
													object.material.reflectivity = json[i].loc_intensity;
												
												} else {
												
													object.material.reflectivity = json[i].intensity;
												
												}
												
												
												that.setUpMaterial(object, Loading);
											
											});
										
										} else {
										
											that.setUpMaterial(object, Loading);
										
										}
									
									} else {
									
										object.material = new THREE.MeshLambertMaterial({
										
											name: json[i].Ref,
											map: texture,
											shininess: 100,
											specular: 0x0a0a0a,
											visible: object.material.visible,
										
										});
										
										that.setUpMaterial(object, Loading);
									
									}
								
								});
					
							}
			
						}
		
					});
				
				}
				
			});

		}
		
		jQuery('body').on('changetexture', function(e, a) {
		
			var url = SITE_BASE_URL + "?route=module/customiser/getproductoption&option_id=" + a;
			jsonHttpRequest(url, loadTextures);
		
		});
		
		jQuery('.dd-option').on('click', function(e) {

			var value = jQuery(this).children('input.dd-option-value').val();
			var url = SITE_BASE_URL + "?route=module/customiser/getproductoption&option_id=" + value;
			jsonHttpRequest(url, loadTextures);
	
		});

		var glassShelf = false;
		var backPlate = false;
		var feet = false;

		

		table.traverse(function(object) {

				//console.log(object);

			if (object instanceof THREE.Mesh) {

				if (object.material.name == "Glass") {

					glassShelf = true;

				} else if (object.material.name == "Backplate") {

					backPlate = true;

				} else if (object.material.name == "Metal") {

					feet = true;

				}

			}

		})

			if (glassShelf) {

				jQuery('body').append('<input type="checkbox" name="Glass" checked="checked" /><label for="Glass">Glass Shelf</label>');

			}

			if (backPlate) {

				jQuery('body').append('<input type="checkbox" name="Backplate" checked="checked" /><label for="Backplate">Back Plate</label>');

			}

			if (feet) {

				jQuery('body').append('<input type="checkbox" name="Metal" checked="checked" /><label for="Metal">Feet</label>');

			}

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
		
		jQuery('.value.selected').each(function(index) {
		
			var value = jQuery(this).attr('id');
			
			if (value > 0) {
		
				var url = SITE_BASE_URL + "?route=module/customiser/getproductoption&option_id=" + value;
				jsonHttpRequest(url, loadTextures);
				
			}
		
		
		});

	}

	this.init = function() {

		var loader;

		if (this.mtl) {

			loader = new THREE.OBJMTLLoader(new THREE.LoadingManager());
			loader.load(this.model, this.mtl, this.load, this.onProgress, this.onError, this);

		} else {

			loader = new THREE.OBJLoader(new THREE.LoadingManager());
			loader.load(this.model, this.load, this.onProgress, this.onError, this);

		}

	}

}
