
  document.getElementById('edit-profile-btn').addEventListener('click', function () {
    toggleProfileSections(true);
  });

  document.getElementById('edit-question-btn').addEventListener('click', function () {
    toggleQuestionSections(true);
  });

  document.getElementById('edit-answer-btn').addEventListener('click', function () {
    console.log('edit answer button clicked');
    toggleAnswerSections(true);
  });

  function toggleQuestionSections(editMode) {
    document.getElementById('question-edit').style.display = editMode ? 'block' : 'none';
    document.getElementById('question-view').style.display = editMode ? 'none' : 'block';
  }

  function toggleAnswerSections(editMode) {
    document.getElementById('answer-edit').style.display = editMode ? 'block' : 'none';
    document.getElementById('answer-view').style.display = editMode ? 'none' : 'block';
  }

  function toggleProfileSections(editMode) {
    document.getElementById('profile-view').style.display = editMode ? 'none' : 'block';
    document.getElementById('profile-picture').style.display = editMode ? 'none' : 'block';
    document.getElementById('profile-edit').style.display = editMode ? 'block' : 'none';
    document.getElementById('edit-profile-btn').style.display = editMode ? 'none' : 'inline-block';
    document.getElementById('profile-header').style.display = editMode ? 'none' : 'block';
    document.getElementById('edit-profile-header').style.display = editMode ? 'block' : 'none';
  }

  function toggleQuestionSections(editMode) {
    document.getElementById('question-edit').style.display = editMode ? 'block' : 'none';
    document.getElementById('question-view').style.display = editMode ? 'none' : 'block';
  }

  function toggleAnswerSections(editMode) {
    document.getElementById('answer-edit').style.display = editMode ? 'block' : 'none';
    document.getElementById('answer-view').style.display = editMode ? 'none' : 'block';
  }
  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }

  const fname = document.getElementById("name");
  const email = document.getElementById("email");

  function updateProfileData(newName, newEmail) {
    fname.textContent = newName;
    email.textContent = newEmail;
  }

  function updateProfile() {
    console.log('update profile');
    const data = {
      name: document.getElementById('name-input').value,
      email: document.getElementById('email-input').value,
    };
    sendAjaxRequest('post', '/profile/edit', data, function () {
      updateProfileData(data.name, data.email);
      toggleProfileSections(false);
    });
  }
  function updateQuestion() {
    titleInput = document.getElementById('title-input');
    contentInput = document.getElementById('content-input');
    const data = {
      question_id: document.getElementById('title-input').getAttribute('data-id'),
      title: titleInput.textContent,
      content: contentInput.textContent,
    };
    sendAjaxRequest('post', 'questions/update', data, function () {
      toggleQuestionSections(false);
    });
  }

  function updateAnswer() {

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


  