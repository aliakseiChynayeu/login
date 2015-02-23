function onBodyLoad() 
{
	clearErrors();
	showErrors(errors);
	defineLanguage();
}

/*
 * Function to set style for selected language
 */
function defineLanguage()
{
	var lang = readCookie("lang");
	var selectedLang = document.getElementById(lang);
	if (selectedLang)
	{
		selectedLang.className = "ln_selected";
	}
}

/*
 * Read cookie for particular name
 */
function readCookie(cookieName){
  var results = document.cookie.match(cookieName + '=(.*?)(;|$)')
  if(results){
    return(results[1])
    }
  else{
    return null
    }
  }
/*
 * Change language function
 */
function changeLanguage(lang) 
{
	var url = "?lang="+lang;
	window.location.replace(url);
}


/*-----------Validation---------------*/
/*
 *	Function to validate registration form
 */
function validateLoginForm() {
	var isLoginValid = checkTextField("login");
	var isPasswordValid = checkTextField("password");
	if (!(isLoginValid && isPasswordValid)) 
	{
		var errorElement = document.getElementById("general_error");
		var inputElement = null;
		showMessage(false, inputElement, messages['error.login.general'], errorElement);
	}
	return isLoginValid && isPasswordValid;
}

/*
 *	Function to validate registration form
 */
function validateRegistrationForm() {
	var isLoginValid = checkTextField("login");
	var isNameValid = checkTextField("name");
	var isSurnameValid = checkTextField("surname");
	var isPasswordValid = checkTextField("password");
	if (!(isLoginValid && isNameValid && isSurnameValid && isPasswordValid)) 
	{
		var errorElement = document.getElementById("general_error");
		var inputElement = null;
		showMessage(false, inputElement, messages['error.registrate.general'], errorElement);
	}
	return isLoginValid && isNameValid && isSurnameValid && isPasswordValid;
}

/*
 *	Function to check text field on empty, length and letters
 */
function checkTextField(inputId) 
{
	if (isNotEmpty(inputId, messages["error."+inputId+".empty"])) 
	{
		if (isLengthMinMax(inputId, messages["error."+inputId+".length"], 3, 30))
		{
			return isLettersAndNumbers(inputId,  messages["error."+inputId+".letters"]);
		}
		else return false;
	}
	else return false;
}

/*
 * Return true if the input value is not empty
 */
function isNotEmpty(inputId, errorMsg) 
{
   var inputElement = document.getElementById(inputId);
   var errorElement = document.getElementById(inputId + "_error");
   var inputValue = inputElement.value.trim();
   var isValid = (inputValue.length !== 0);  // boolean
   showMessage(isValid, inputElement, errorMsg, errorElement);
   return isValid;
}

/*
 * Return true if the input length is between minLength and maxLength
 */
function isLengthMinMax(inputId, errorMsg, minLength, maxLength) {
   var inputElement = document.getElementById(inputId);
   var errorElement = document.getElementById(inputId + "_error");
   var inputValue = inputElement.value.trim();
   var isValid = (inputValue.length >= minLength) && (inputValue.length <= maxLength);
   showMessage(isValid, inputElement, errorMsg, errorElement);
   return isValid;
}

/*
 *  Return true if input value match pattern 
 */
function isLettersAndNumbers(inputId, errorMsg) 
{
	var inputElement = document.getElementById(inputId);
   	var errorElement = document.getElementById(inputId + "_error");
   	var inputValue = inputElement.value.trim();
   	var pattern = new RegExp("^[a-zA-Z0-9]+$");
   	var isValid = (pattern.test(inputValue));
   	showMessage(isValid, inputElement, errorMsg, errorElement);
   	return isValid;
}

/*----------- End Validation---------------*/

/*------------Message Processing--------------*/
/* 
 * If "isValid" is false, print the errorMsg; else, reset to normal display.
 * The errorMsg shall be displayed on errorElement if it exists;
 * otherwise via an alert().
 */
function showMessage(isValid, inputElement, errorMsg, errorElement) {
   if (!isValid) {
      // Put up error message on errorElement or via alert()
      if (errorElement !== null) {
         errorElement.innerHTML = errorMsg;
      } else {
         alert(errorMsg);
      }
      // Change "class" of inputElement, so that CSS displays differently
      if (inputElement !== null) {
         inputElement.className = "invalid";
      }
   } else {
      // Reset to normal display
      if (errorElement !== null) {
         errorElement.innerHTML = "";
      }
      if (inputElement !== null) {
         inputElement.className = "";
      }
   }
}

/*
 * Function to show errors on particular element
 */
function showErrors(errorsToShow) {
	for (var elementId in errorsToShow) 
	{
  		if (errors.hasOwnProperty(elementId)) 
  		{
   			var inputElement = document.getElementById(elementId);
   			var errorElement = document.getElementById(elementId + "_error"); 
   			showMessage(false, inputElement, errorsToShow[elementId], errorElement);
  		}
	}
}

/*
	Functions to clear all errors 
*/
function clearErrors() {
	var error_elements = document.getElementsByClassName('error');
	var input_elements = document.getElementsByTagName("input");
	for (var i = 0; i < error_elements.length; ++i) 
	{
    	var item = error_elements[i];  
    	item.innerHTML = ""; 
	}

	for (var i = 0; i < input_elements.length; ++i) 
	{
    	var item = input_elements[i];  
    	item.className = ""; 
	}
	var general_error = document.getElementById('general_error');
	if (general_error) 
	{
		general_error.innerHTML = "";
	}
}

/*------------End Message Processing--------------*/