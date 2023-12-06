function addEventListeners() {
  document.getElementById('edit-profile-btn').addEventListener('click', function () {
    toggleProfileSections(true);
  });

  document.getElementById('edit-question-btn').addEventListener('click', function () {
    console.log("edit answer");
    toggleQuestionSections(true);
  });

  document.getElementById('edit-answer-btn').addEventListener('click', function () {
    toggleAnswerSections(true);
  });

  document.getElementById('update-answer-btn').addEventListener('click', function () {
    console.log("update answer");
    updateAnswer();
  });

  document.getElementById('update-profile-btn').addEventListener('click', function () {
    updateProfile();
  });

  document.getElementById('update-question-btn').addEventListener('click', function () {
    updateQuestion();
  });
}


function updateProfileData(newName, newEmail) {
  fullname.textContent = newName;
  email.textContent = newEmail;
}

function updateAnswerData(newTitle, newContent) {
  title.textContent = newTitle;
  content.textContent = newContent;
}

function updateProfile() {
  /*const newName = fullnameInput.value;
  const newEmail = emailInput.value;
  const data = {name: newName, email: newEmail};
  sendAjaxRequest('put', `api/profile/${userId}`, data, function () {*/
    updateProfileData(newName, newEmail);
    toggleProfileSections(false);
  // });
}

function updateAnswer() {
  //sendAjaxRequest('put', `api/answer/${userId}`, data, function () {
    updateAnswerData(newAnswer);
    toggleAnswerSections(false);
 // });
}



function toggleProfileSections(editMode) {
  document.getElementById('profile-view').style.display = editMode ? 'none' : 'block';
  document.getElementById('profile-edit').style.display = editMode ? 'block' : 'none';
  document.getElementById('edit-profile-btn').style.display = editMode ? 'none' : 'inline-block';
  document.getElementById('profile-header').style.display = editMode ? 'none' : 'block';
}

function toggleQuestionSections(editMode) {
  document.getElementById('question-edit').style.display = editMode ? 'block' : 'none';
  document.getElementById('question-view').style.display = editMode ? 'none' : 'block';
}

function toggleAnswerSections(editMode) {
  document.getElementById('answer-edit').style.display = editMode ? 'block' : 'none';
  document.getElementById('answer-view').style.display = editMode ? 'none' : 'block';
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}

//  START SECTION: FUNCTIONS RELATED TO EDITING A POST'S TAGS

let add_tag = document.getElementById('add_tag');
let tag_input = document.getElementById('tag_input');
add_tag.addEventListener('click', function() {
  tag_val = tag_input.value.trim();
  tag_checkbox = document.getElementById('tag_'+tag_val);
  if(tag_checkbox.checked == true) {
    tag_checkbox.checked = false;
  }
  else {
    tag_checkbox.checked = true;
  }
  console.log('that\'s crazy...');
  updateTags();
});

function updateTags() {
  let checkedTags = document.querySelectorAll('.tag-checkbox:checked');
  let selectedTags = Array.from(checkedTags).map(checkbox => checkbox.value);

  console.log('mensagem iai');

  // Make an AJAX request using the fetch API
  fetch('/update_tags', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
      },
      body: JSON.stringify({ selectedTags: selectedTags }),
  })
  .then(response => response.text())
  .then(data => {
      document.getElementById('tag-section').innerHTML = data;
  })
  .catch(error => console.error('Error updating tags:', error));
}

let checkboxes = document.querySelectorAll('.tag-checkbox');

checkboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        console.log('checkbox ticked');
        updateTags(); // when any checkbox is ticked/unticked, we want to update the tags that are shown
    });
}); 

//  END SECTION

// addEventListeners();
