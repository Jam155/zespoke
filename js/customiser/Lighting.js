var Lighting = function() {
	
	this.lights = new Array();

}

/* Add an Ambient Light to the lighting array
 * Input - Colour in Hex
 * Output - Position (Position in array);
 */
Lighting.prototype.addAmbientLight = function(color, intensity) {

	var position = this.lights.length;

	this.lights.push(new THREE.AmbientLight(color, intensity));

	return position;

}

/* Add a SpotLight to the lighting array
 * Inputs - Colour (Hex Value)
 * 	  - Intensity (Float)
 * 	  - Distance (Float)
 * 	  - Angle (Float)
 * Outputs - Position (Position in array)
 */
Lighting.prototype.addSpotLight = function(colour, intensity, distance, angle) {

	var position = this.lights.length;

	this.lights.push(new THREE.SpotLight(colour, intensity, distance, angle));

	return position;

}

/* Add a PointLight to the lighting array
 * Inputs - Colour (Hex Value)
 * 	  - Intensity (Float)
 * Outputs - Position (Position in array)
 */
Lighting.prototype.addPointLight = function(colour, intensity) {

	var position = this.lights.length;

	this.lights.push(new THREE.PointLight(colour, intensity));

	return position;

}
/* Adds all the lights to the scene
 * Inputs - Scene (ThreeJS Scene)
 * Outputs - Scene (ThreeJS Scene)
 */
Lighting.prototype.addLightsToScene = function(scene) {

	this.lights.forEach(function(light) {

		scene.add(light);

	});

	return scene;

}

/* Moves light to a new position
 * Inputs - index (Position of Light in array)
 * 	  - x (x coordinate)
 * 	  - y (y coordinate)
 * 	  - z (z coordinate)
 * Outputs - index (Position of Light in array)
 */
Lighting.prototype.positionLight = function(index, x, y, z) {

	this.lights[index].position.set(x, y, z);

	return index;

}

Lighting.prototype.changeXPosition = function(index, x) {

	this.lights[index].position.x = x;

	return index;

}

Lighting.prototype.changeYPosition = function(index, y) {

	this.lights[index].position.y = y;

	return index;

}

Lighting.prototype.changeZPosition = function(index, z) {

	this.lights[index].position.z = z;

	return index;

}

Lighting.prototype.changeIntensity = function(index, intensity) {

	this.lights[index].intensity = intensity;

	return index;

}

Lighting.prototype.changeColour = function(index, colour) {

	this.lights[index].color = new THREE.Color(colour);

	return index;

}

Lighting.prototype.forEach = function(each) {

	this.lights.forEach(each);

}


