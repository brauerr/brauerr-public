/**
* Create an object literal with the following key value pairs:
* type: {string} 'Goldfish'
* brand: {string} 'Pepperidge Farm'
* flavor: {string} 'Cheddar'
* count: {number} 2000
* It should be returned direcly by the following function
* @return {object} - the object literal
*/

function returnObjectLiteral() {
  //your code here
  //Modify ONLY this line
  return {type : 'Goldfish', brand : 'Pepperidge Farm',
    flavor : 'Cheddar', count : 2000};
  //end your code
}

/**
* Create a constructor function for a `Cat` object.
* @constructor
* @param {string} name - The name of the cat as a string
* @param {string} color - The color of the cat
* The above values should be stored and be accessable via the name and color
* properties respectively. In addition, the following methods should be
* callable on a Cat object:
* destroyFurniture({string} name, {number} cost) - adds a piece of destroyed
* furnature
* lastDestroyedFurniture() - returns a object with two key value pairs with
* keys 'name' and 'cost' and values corrisponding to the name and cash money
* value of the last item destroyed.
* totalDestroyed() - returns a {number} that is the total value of destroyed
* furnature.
* nthDestroyed( {number} n ) - Returns the nth destroyed furnature. 0 should
* return the first item destroyed, 1 the second and so on. It should return it
* as an object just as lasatDestroyedFurniture does.
*/

//your code here
var Furniture = function(name, cost) {
  this.name = name;
  this.cost = cost;
};

var destroyFurniture = function(name, cost) {
  this.furnitureArray.push(new Furniture(name, cost));
};

var lastDestroyedFurniture = function() {
  var arrayLength = this.furnitureArray.length,
    myName = this.furnitureArray[arrayLength - 1].name,
    myCost = this.furnitureArray[arrayLength - 1].cost;
  return {name : myName, cost : myCost};
};

var totalDestroyed = function() {
  var sum = 0,
    i = 0;
  for (i = 0; i < this.furnitureArray.length; i++) {
    sum += this.furnitureArray[i].cost;
  }
  return sum;
};

var nthDestroyed = function(n) {
  var myName = this.furnitureArray[n].name,
    myCost = this.furnitureArray[n].cost;
  return {name : myName, cost : myCost};
};

var Cat = function(name, color) {
  this.name = name;
  this.color = color;
  this.furnitureArray = [];
  this.destroyFurniture = destroyFurniture;
  this.lastDestroyedFurniture = lastDestroyedFurniture;
  this.totalDestroyed = totalDestroyed;
  this.nthDestroyed = nthDestroyed;
}
//end your code

/**
* Create an instance of a 'Orange' cat with the name 'Maru' stored in a variable
* called myCat. Call the destroyFurniture function on at least $1000 of
* furniture.
*/

//your code here
var myCat = new Cat('Maru', 'Orange');
myCat.destroyFurniture('Sofa', 1000);
//end your code

/**
* Add a method to the cat prototype called 'pet'. This function should accept
* a {number} n as an argument. If that {number} is greater than 2.5 it should
* return the {string} 'CLAW!' if that {number} is less than or equal to 2.5
* it should return the {string} 'Purr.'.
* @param {number} n - The number of pets
* @return {string} - The cats reaction.
*/
//your code here
var pet = function(n) {
  if (n > 2.5) {
    return 'CLAW!';
  }
  return 'Purr.';
}

Cat.prototype.pet = pet;
//end your code