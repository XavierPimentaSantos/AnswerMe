function addEventListeners() {
    let itemCheckers = document.querySelectorAll('article.card li.item input[type=checkbox]');
    [].forEach.call(itemCheckers, function(checker) {
      checker.addEventListener('change', sendItemUpdateRequest);
    });
  
    let itemCreators = document.querySelectorAll('article.card form.new_item');
    [].forEach.call(itemCreators, function(creator) {
      creator.addEventListener('submit', sendCreateItemRequest);
    });
  
    let itemDeleters = document.querySelectorAll('article.card li a.delete');
    [].forEach.call(itemDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeleteItemRequest);
    });
  
    let cardDeleters = document.querySelectorAll('article.card header a.delete');
    [].forEach.call(cardDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeleteCardRequest);
    });
  
    let cardCreator = document.querySelector('article.card form.new_card');
    if (cardCreator != null)
      cardCreator.addEventListener('submit', sendCreateCardRequest);

    document.getElementById('edit-profile-btn').addEventListener('click', function () {
      toggleProfileSections(true);
    });
/*
    document.getElementById('edit-answer-btn').addEventListener('click', function () {
      toggleAnswerSections(true);
    });

    document.getElementById('edit-question-btn').addEventListener('click', function () {
      toggleQuestionSections(true);
    });
*/
    document.getElementById('update-profile-btn').addEventListener('click', function () {
      updateProfile();
    });
/*
    document.getElementById('update-question-btn').addEventListener('click', function () {
      updateQuestion();
    });

    document.getElementById('update-answer-btn').addEventListener('click', function () {
      updateAnswer();
    });
*/

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
  
  //
  document.addEventListener('DOMContentLoaded', function () {
    // Listen for change event on checkboxes with class 'tag-checkbox'
    var checkboxes = document.querySelectorAll('.tag-checkbox');
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            updateSelectedTags();
        });
    });

    // Function to update the content of 'selected_tags' div using AJAX
    function updateSelectedTags() {
        var selectedTags = [];

        // Iterate through checked checkboxes and collect values
        checkboxes.forEach(function (checkbox) {
            if (checkbox.checked) {
                selectedTags.push(checkbox.value);
            }
        });

        // Send AJAX request to update-selected-tags route
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/update-selected-tags', true);
        xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Update the content of 'selected_tags' div with the returned HTML
                document.getElementById('selected_tags').innerHTML = xhr.responseText;
            } else if (xhr.status !== 200) {
                console.error('Error updating selected tags:', xhr.statusText);
            }
        };
        
        // Convert the selectedTags array to JSON and send it in the request body
        xhr.send(JSON.stringify({ selectedTags: selectedTags }));
    }
  });
  //

  addEventListeners();
  