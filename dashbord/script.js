// Function to check if any field is empty
function checkEmptyFields() {
    var courseName = document.getElementById('courseName').value;
    var courseTeacher = document.getElementById('courseTeacher').value;
    var videoOption = document.querySelector('input[name="videoOption"]:checked').value;
    var videoLectureLink = document.getElementById('videoLectureLink').value;
    var videoLectureFile = document.getElementById('videoLectureFile').value;

    if (courseName.trim() === '' || courseTeacher.trim() === '') {
        return true; // Return true if any required field is empty
    }

    if (videoOption === 'link' && videoLectureLink.trim() === '') {
        return true; // Return true if video link option is selected but link field is empty
    }

    if (videoOption === 'upload' && videoLectureFile.trim() === '') {
        return true; // Return true if video upload option is selected but file field is empty
    }

    return false; // Return false if all required fields are filled
}

// Event listener for video option selection
var videoOptions = document.querySelectorAll('input[name="videoOption"]');
videoOptions.forEach(function(option) {
    option.addEventListener('change', function() {
        var videoLinkField = document.getElementById('videoLinkField');
        var videoUploadField = document.getElementById('videoUploadField');

        if (this.value === 'link') {
            videoLinkField.style.display = 'block';
            videoUploadField.style.display = 'none';
        } else {
            videoLinkField.style.display = 'none';
            videoUploadField.style.display = 'block';
        }
    });
});

// The rest of your existing JavaScript code remains unchanged
document.getElementById('isPaid').addEventListener('change', function () {
    var priceField = document.getElementById('priceField');
    if (this.checked) {
        priceField.style.display = 'block';
    } else {
        priceField.style.display = 'none';
    }
});

function displayErrorMessage(message) {
    var errorMessageDiv = document.getElementById('errorMessage');
    errorMessageDiv.innerHTML = "<p>" + message + "</p>";
}

function displaySuccessMessage() {
    var successMessageDiv = document.getElementById('successMessage');
    successMessageDiv.style.display = 'block';
    setTimeout(function() {
        successMessageDiv.style.display = 'none';
        location.reload(); // Refresh the page after 4 seconds
    }, 2000);
}

var urlParams = new URLSearchParams(window.location.search);
var message = urlParams.get('message');
if (message) {
    displayErrorMessage(message);
}

document.getElementById('addCourseForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form submission

    if (checkEmptyFields()) {
        displayErrorMessage('Please fill in all required fields.');
        return; // Stop form submission if any field is empty
    }

    var formData = new FormData(this);
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            displaySuccessMessage(); // Display success message if course is added successfully
        } else {
            throw new Error('Failed to add course.'); // Throw an error if course addition fails
        }
    })
    .catch(error => {
        displayErrorMessage(error.message); // Display error message if an error occurs during course addition
    });
});
