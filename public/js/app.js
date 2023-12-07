/* function addEventListeners() {
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

  

  document.getElementById('update-profile-btn').addEventListener('click', function () {
    updateProfile();
  });

  document.getElementById('update-question-btn').addEventListener('click', function () {
    updateQuestion();
  });

  document.getElementById('update-answer-btn').addEventListener('click', function () {
    updateAnswer();
  });
} */

/* document.getElementById('edit-profile-btn').addEventListener('click', function () {
  toggleProfileSections(true);
}); */

let edit_profile_btn = document.getElementById('edit-profile-btn');
if(edit_profile_btn) {
  edit_profile_btn.addEventListener('click', function() {
    toggleProfileSections(true);
  });
}

let edit_answer_btn = document.getElementById('edit-answer-btn');
if(edit_answer_btn) {
  edit_answer_btn.addEventListener('click', function () {
    toggleAnswerSections(true);
  });  
}

let edit_question_btn = document.getElementById('edit-question-btn');
if(edit_question_btn) {
  edit_question_btn.addEventListener('click', function () {
    toggleQuestionSections(true);
  });
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

// START SECTION: FUNCTIONS RELATED TO VOTING

let score_container = document.getElementById('question_score');

if(score_container) {
  score_container.addEventListener('click', function(event) {
    if(event.target.classList.contains('increase-question-score-btn')) {
      console.log('inc btn pressed');
      increaseScore(event.target.getAttribute('data-id'));
    }
    else if(event.target.classList.contains('decrease-question-score-btn')) {
      console.log('dec btn pressed');
      decreaseScore(event.target.getAttribute('data-id'));
    }
  });  
}

function increaseScore(question_id) {
  fetch('/increase_score', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({ question_id : question_id }),
  })
  .then(response => {
    if(response.ok) {
      console.log('succesfully updated score');
      return response.text();
    }
    else {
      console.log('could not update the score');
    }
  })
  .then(data => {
    console.log(question_id);
    document.getElementById('question_score').innerHTML = data;
  })
  .catch(error => console.error('Error updating score:', error));
}

function decreaseScore(question_id) {
  fetch('/decrease_score', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({ question_id : question_id }),
  })
  .then(response => {
    if(response.ok) {
      console.log('succesfully updated score');
      return response.text();
    }
    else {
      console.log('could not update the score');
    }
  })
  .then(data => {
    console.log(question_id);
    document.getElementById('question_score').innerHTML = data;
  })
  .catch(error => console.error('Error updating score:', error));
}

// END SECTION

// addEventListeners();
