
var customiser;

Customiser = function() {

	this.view_angle = 45;
	this.aspect = this.width/this.height;
	this.near = 0.1;
	this.far = 1000;
	this.focus = new THREE.Vector3(-10,300,0);
	this.position = new THREE.Vector3(0,0,-200);
	this.changed = true;


	this.texture = THREE.ImageUtils.loadTexture('catalog/view/theme/gentle/image/webgl-customiser/background-image.jpg');
	this.backgroundMesh = new THREE.Mesh(
		new THREE.PlaneBufferGeometry(100,100,0),
		new THREE.MeshBasicMaterial({
			map: this.texture

		}));

	this.backgroundMesh.doubleSided = true;
	this.backgroundMesh.material.depthTest = false;
	this.backgroundMesh.material.depthWrite = false;

	//Create the Background Scene.

	this.backgroundScene = new THREE.Scene();
	this.backgroundCamera = new THREE.PerspectiveCamera();

	this.camera = new THREE.PerspectiveCamera(this.view_angle, this.aspect, this.near, this.far);
	this.backgroundCamera.position.set(0,0,100);
	this.backgroundCamera.lookAt(new THREE.Vector3(0,0,0));
	this.backgroundScene.add(this.backgroundCamera);
	this.backgroundScene.add(this.backgroundMesh);

	this.width = jQuery('.customiser').width();
	this.height = 310;

	this.view_angle = 45;
	this.aspect = this.width/this.height;
	this.near = 0.1;
	this.far = 1000;
	this.focus = new THREE.Vector3(-10,300,0);
	this.position = new THREE.Vector3(0,0,-200);

	this.renderer;
	this.camera;
	this.scene;
	this.manager = new THREE.LoadingManager();

	this.spinner;

	this.curTable;

	this.floor;

	this.objects = [];
	this.options = [];

	this.curTextures = {};

	function LoadingObject() {

		this.total = 1;
		this.loading = 1;
	
		this.load = function() {
	
			this.total++;
			this.loading++;
			console.log(this.loading);
	
		}
	
		this.unload = function() {
	
			if (this.loading > 0) {
		
				this.loading--;
			
			}
		
			console.log(this.loading);
	
		}
	
		this.loaded = function() {
	
			return this.loading <= 0;
	
		}
	
		this.getTotal = function() {
	
			return this.total;
	
		}

	}
	
	this.loading = new LoadingObject();
	
	this.table;
	this.geometry;
	this.material;

	this.manager.onProgress = function(item, loaded, total) {

		//console.log(item, loaded, total);

	}	

	this.initLighting = function() {

		var ambiantLight = new THREE.AmbientLight(0x444444);

		var light = new THREE.DirectionalLight(0xFFFFFF, 1.0);
		light.position.set(0.5, 0.4, 1.0);
		light.shadowCameraVisible = true;

		var spotlight3 = new THREE.PointLight(0xFFFFFF, 0.8);
		spotlight3.position.set(20, 100, -80);
		spotlight3.shadowCameraNear = 0.01;
		spotlight3.shadowDarkness = 0.1;
		spotlight3.shadowMapWidth = 2048;
		spotlight3.shadowMapHeight = 2048;
	
		this.scene.add(spotlight3)
		this.scene.add(ambiantLight);
		this.scene.add(light);

	}
	
	this.addFloor = function() {

		var geometry = new THREE.BoxGeometry(500,5,500);
		var material = new THREE.MeshPhongMaterial({
			ambient: 0xFFFFFF,
			color: 0xFFFFFF,
			shininess: 20,
			specular: 0x888888,
		});

		var cube = new THREE.Mesh(geometry, material); 

		cube.traverse(function(object) {

			object.recieveShadow = true;
			object.castShadow = false;

		});
	
		cube.position.y = -0.5/2;
		this.floor = cube;

		//scene.add(cube);
		cube.receiveShadow = true;

	}
	
	this.animate = function() {

		requestAnimationFrame(customiser.animate);

		customiser.renderer.clear();

		if (!customiser.loading.loaded()) {

			if (jQuery('.customiser .spinner-loader').length <= 0) {
			
				jQuery('.customiser').append("<div class='spinner-loader'>Loading</div>");
		
			}	//console.log(spinner);
		

		} else {

			if (jQuery('.customiser .spinner-loader').length > 0) {

				jQuery('.spinner-loader').remove();

			}
		
			for(var i = 0; i < this.objects.length; i++) {

				customiser.objects[i].manipulation();

			}
		
			customiser.renderer.render(customiser.backgroundScene, customiser.backgroundCamera);
			customiser.renderer.render(customiser.scene, customiser.camera);
		
		}

	}
	
	this.initRenderer = function() {
		
				this.renderer = new THREE.WebGLRenderer({preserveDrawingBuffer: true, antialias: true, alpha: true, precision: "highp"});

				this.renderer.shadowMapEnabled = true;
				this.renderer.shadowMapSoft = true;
				this.renderer.shadowMapType = THREE.PCFSoftShadowMap;
				this.renderer.shadowCameraNear = 3;
				this.renderer.shadowCameraFar = this.camera.far;
				this.renderer.shadowCameraFov = 50;
				this.renderer.shadowMabBias = 0.0039;
				this.renderer.shadowMapDarkness = 0.5;
				this.renderer.setSize(this.width, this.height);
				this.renderer.domElement.id = "customiserCanvas";
	
				var dlink = "<a id='customiserImage' download>Picture</a>";
	
				jQuery('div.customiser').append(this.renderer.domElement);
				jQuery('div.customiser').append(dlink);

	}
	
	this.initCamera = function() {

		this.camera = new THREE.PerspectiveCamera(this.view_angle, this.aspect, this.near, this.far);

		this.camera.position.set(50,75,-100);
		this.camera.lookAt(this.focus);

	}
	
	this.setCameraLookAt = function(table) {

		var boundingBox = new THREE.Box3().setFromObject(table);
		var floorBox = new THREE.Box3().setFromObject(floor);
	
		this.focus.y = 10/**(boundingBox.max.y / 2) + floorBox.max.y;**/
		this.focus.z = (boundingBox.max.z - boundingBox.min.z)/2
		this.camera.lookAt(focus);

	}

	this.initScene = function() {

		this.scene = new THREE.Scene();

	}
	
	this.init = function(product) {

		try {
	
			console.log("Initialised Customiser");

			var baseDir = "http://" + window.location.host + window.location.pathname; 
			var url = baseDir + "?route=module/customiser/getproducttable&product_id=";

			this.initCamera();
			console.log("Initialised Camera");
			this.initScene();
			this.initRenderer();
			this.initLighting();
			this.addFloor();
	
			this.jsonHttpRequest(url + product, function(json) {
	
				console.log(json);
				customiser.setTable(json['Table_ID']);
	
			});

			customiser = this;
			this.animate();
		
		} catch (err) {
		
			console.log(err);
			jQuery('.image, .image-additional').removeClass('hidden'); 
		
		}

	}
	
	this.getAJAXObject = function() {

		var xmlhttp;

		if (window.XMLHttpRequest) {

			xmlhttp = new XMLHttpRequest();

		} else {

			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

		}

		return xmlhttp;

	}
	
	this.jsonHttpRequest = function(url, method) {

		var xmlhttp = this.getAJAXObject();

		xmlhttp.open("GET", url, true);
		xmlhttp.send();

		xmlhttp.onreadystatechange = function() {

			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

				console.log(url);
				var response = JSON.parse(xmlhttp.responseText)
				method(response);
			

			}

		}

	}
	
	this.setTable = function getModel(tableID) {

		var host = window.location.host + window.location.pathname;
	
		var url = "http://" + host + "?route=module/customiser/gettable&table_id=" + tableID

		console.log("URL: " + url);

		//loadJSONTextures(tableID);
		this.jsonHttpRequest(url, function(json) {
	
			var baseURL = "/work/zespoke.kinodev.co.uk/assets/files/";

			this.table = new Table(baseURL + json.Object_File, baseURL + json.Material_File, [], tableID, this.floor);
			console.log(this.table);

			/**if (typeof curTable != 'undefined') {

				scene.remove(curTable);

			}**/

			table.init(this.options);

		});

	}

}
