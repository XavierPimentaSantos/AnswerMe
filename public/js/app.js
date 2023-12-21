  Pusher.logToConsole = true;

  var pusher = new Pusher('4fc151dc2ba70eed2c92', {
    cluster: 'eu'
  });

  var channel = pusher.subscribe('answerme-channel');
  channel.bind('user-register', function(data) {
    // Log the entire data object to the console
    console.log(data);

    // Access the username attribute from the JSON data
    var username = data.username;

    Swal.fire({
      title: username + ' has joined our website :)',
      icon: 'info',
      confirmButtonText: 'OK'
    });
  });

  
  const edit_profile_btn = document.getElementById('edit-profile-btn');
  if(edit_profile_btn) {
    edit_profile_btn.addEventListener('click', function() {
      toggleProfileSections(true);
    });
  }

  document.addEventListener('click', function(event) {
    if (event.target.classList.contains('button')) {
        const answerId = event.target.dataset.id;
        toggleAnswerSections(answerId, true);
    }
});


  const edit_question_btn = document.getElementById('edit-question-btn');
  if(edit_question_btn) {
    edit_question_btn.addEventListener('click', function () {
      toggleQuestionSections(true);
    });
  }

  function toggleQuestionSections(editMode) {
    document.getElementById('question-edit').style.display = editMode ? 'block' : 'none';
    document.getElementById('question-view').style.display = editMode ? 'none' : 'block';
  }

  function toggleAnswerSections(answerId, editMode) {
    console.log(answerId);
    const viewSection = document.getElementById(`answer-view-${answerId}`);
    const editSection = document.getElementById(`answer-edit-${answerId}`);

    console.log(viewSection);
    console.log(editSection);

    viewSection.style.display = editMode ? 'none' : 'block';
    editSection.style.display = editMode ? 'block' : 'none';
}

  function toggleProfileSections(editMode) {
    document.getElementById('profile-view').style.display = editMode ? 'none' : 'block';
    //document.getElementById('profile-picture').style.display = editMode ? 'none' : 'block';
    document.getElementById('profile-edit').style.display = editMode ? 'block' : 'none';
    document.getElementById('edit-profile-btn').style.display = editMode ? 'none' : 'inline-block';
    document.getElementById('profile-header').style.display = editMode ? 'none' : 'block';
    document.getElementById('edit-profile-header').style.display = editMode ? 'block' : 'none';
  }

function toggleQuestionSections(editMode) {
  document.getElementById('question-edit').style.display = editMode ? 'block' : 'none';
  document.getElementById('question-view').style.display = editMode ? 'none' : 'block';
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

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}

// START SECTION: FUNCTIONS RELATED TO EDITING A POST'S TAGS

const add_tag = document.getElementById('add_tag');
const tag_input = document.getElementById('tag_input');

if(add_tag) {
  add_tag.addEventListener('click', function() {
    tag_val = tag_input.value.trim();
    tag_checkbox = document.getElementById('tag_'+tag_val);
    if(tag_checkbox.checked == true) {
      tag_checkbox.checked = false;
    }
    else {
      tag_checkbox.checked = true;
    }
    updateTags();
  });
}

const checkboxes = document.querySelectorAll('.tag-checkbox');

checkboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        updateTags(); // when any checkbox is ticked/unticked, we want to update the tags that are shown
    });
}); 

function updateTags() {
  const checkedTags = document.querySelectorAll('.tag-checkbox:checked');
  const selectedTags = Array.from(checkedTags).map(checkbox => checkbox.value);

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

// END SECTION

// START SECTION: FUNCTIONS RELATED TO VOTING

const score_container = document.getElementById('question_score');

if(score_container) {
  score_container.addEventListener('click', function(event) {
    if(event.target.classList.contains('increase-question-score-btn')) {
      increaseScore(event.target.getAttribute('data-id'));
    }
    else if(event.target.classList.contains('decrease-question-score-btn')) {
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
    return response.text();
  })
  .then(data => {
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
    return response.text();
  })
  .then(data => {
    document.getElementById('question_score').innerHTML = data;
  })
  .catch(error => console.error('Error updating score:', error));
}

const question_answers = document.getElementById('question_answers');
if(question_answers) {
  question_answers.addEventListener('click', function(event) {
    if(event.target.classList.contains('increase-answer-score-btn')) {
      increaseScoreAns(event.target.getAttribute('data-id'));
    }
    else if(event.target.classList.contains('decrease-answer-score-btn')) {
      decreaseScoreAns(event.target.getAttribute('data-id'));
    }
    else if(event.target.classList.contains('validate_answer_btn')) {
      console.log('piriquito' + event.target.getAttribute('data-id'));
      validateAnswer(event.target.getAttribute('data-id'));
    }
  });
}

function increaseScoreAns(answer_id) {
  fetch('/increase_score_ans', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({ answer_id : answer_id }),
  })
  .then(response => {
    return response.text();
  })
  .then(data => {
    document.getElementById('answer_score_' + answer_id).innerHTML = data;
  })
  .catch(error => console.error('Error updating score:', error));
}

function decreaseScoreAns(answer_id) {
  fetch('/decrease_score_ans', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({ answer_id : answer_id }),
  })
  .then(response => {
    return response.text();
  })
  .then(data => {
    document.getElementById('answer_score_' + answer_id).innerHTML = data;
  })
  .catch(error => console.error('Error updating score:', error));
}

// END SECTION

// START SECTION: FUNCTIONS RELATED TO MARKING AN ANSWER AS CORRECT

function validateAnswer(answer_id) {
  fetch('/validate_answer', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({ answer_id: answer_id }),
  })
  .then(response => {
    if(response.ok) {
      document.getElementById('validate-answer-btn-' + answer_id).classList.add('hidden');
      document.getElementById('valid_answer_' + answer_id).classList.remove('hidden');
    }
  })
  .catch(error => console.error('Error updating tags:', error));
}

// END SECTION

// START SECTION: FUNCTIONS RELATED TO COMMENTING UNDER A QUESTION

const question_comment_post_btn = document.getElementById('question-comment-post-btn');

if(question_comment_post_btn) {
  question_comment_post_btn.addEventListener('click', function() {
    question_comment_post();
  });
}

function question_comment_post() {
  const question_id = question_comment_post_btn.getAttribute('data-question-id');
  const question_comment_body = document.getElementById('question_comment_body').value;
  fetch('/questions/' + question_id + '/comment', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({
      question_comment_body : question_comment_body
    }),
  })
  .then(response => {
    return response.text();
  })
  .then(data => {
    document.getElementById('comment-section').innerHTML = data;
    document.getElementById('question_comment_body').value = '';
  })
  .catch(error => console.error('Error posting comment:', error));
}

const comment_section = document.getElementById('comment-section');

if(comment_section) {
  comment_section.addEventListener('click', function(event) {
    if(event.target.classList.contains('question-comment-edit-btn')) {
      const question_id = event.target.getAttribute('data-question-id');
      const comment_id = event.target.getAttribute('data-comment-id');

      document.getElementById('question_comment_card_' +  comment_id).classList.add('hidden');
      document.getElementById('question_comment_edit_form_' +  comment_id).classList.remove('hidden');

      document.getElementById('question_comment_edit_btn_' + comment_id).addEventListener('click', function() {
        question_comment_edit(comment_id, question_id);
      });

      document.getElementById('question_comment_cancel_btn_' + comment_id).addEventListener('click', function() {
        retract_question_comment_edit(comment_id);
      });
    }

    else if(event.target.classList.contains('question-comment-delete-btn')) {
      const comment_id = event.target.getAttribute('data-comment-id');
      const question_id = event.target.getAttribute('data-question-id');

      document.getElementById('question_comment_card_' + comment_id).remove();

      question_comment_delete(question_id, comment_id);
    }
  });
}

function question_comment_edit(comment_id, question_id) {
  const question_comment_body = document.getElementById('question_comment_body_edit_input_' + comment_id).value;
  fetch('/questions/' + question_id + '/comment/' + comment_id + '/edit', {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({
      question_comment_body : question_comment_body
    }),
  })
  .then(response => {
    return response.text();
  })
  .then(data => {
    document.getElementById('question_comment_card_' +  comment_id).innerHTML = data;
  })
  .catch(error => console.error('Error editing comment:', error));
}

function retract_question_comment_edit(comment_id) {
  document.getElementById('question_comment_body_edit_input_' + comment_id).value = document.getElementById('question_comment_body_' + comment_id).textContent;
  document.getElementById('question_comment_card_' +  comment_id).classList.remove('hidden');
  document.getElementById('question_comment_edit_form_' +  comment_id).classList.add('hidden');
}

function question_comment_delete(question_id, comment_id) {
  fetch('/questions/' + question_id + '/comment/' + comment_id + '/delete', {
    method: 'DELETE',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({
    }),
  })
  .then(response => {
    if(response.ok) {
      document.getElementById('question_comment_card_' + comment_id).remove();
      document.getElementById('question_comment_edit_form_' + comment_id).remove();
    }
    return response.text();
  })
  .catch(error => console.error('Error deleting comment:', error));
}

// END SECTION

// START SECTION: FUNCTIONS RELATED TO ANSWERING A QUESTION

const answer_post_btn = document.getElementById('answer-post-btn');

if(answer_post_btn) {
  answer_post_btn.addEventListener('click', function() {
    answer_post();
  });
}

function answer_post() {
  const title = document.getElementById('answer-title-input').value;
  const content = document.getElementById('answer-content-input').value;
  if(title=="" || content=="") {
    throw new error("ERROR: Title and Content form fields cannot be empty.");
  }
  const question_id = answer_post_btn.getAttribute('data-question-id');
  fetch('/questions/' + question_id + '/answer', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({ 
      title : title,
      content: content
    }),
  })
  .then(response => {
    return response.text();
  })
  .then(data => {
    document.getElementById('answer-section').innerHTML = data;
    document.getElementById('answer-title-input').value = '';
    document.getElementById('answer-content-input').value = '';
    document.getElementById('no_answers').classList.add('hidden');
    document.getElementById('has_answers').classList.remove('hidden');
  })
  .catch(error => console.error('Error posting question:', error));
}

// END SECTION

// START SECTION: FUNCTIONS RELATED TO COMMENTING UNDER AN ANSWER

const answer_section = document.getElementById('answer-section');

if(answer_section) {
  answer_section.addEventListener('click', function(event) {
    if(event.target.classList.contains('answer-comment-post-btn')) {
      answer_comment_post(event.target.getAttribute('data-id'));
    }
    else if(event.target.classList.contains('answer-comment-edit-btn')) {
      document.getElementById('answer_comment_edit_form_' + event.target.getAttribute('data-comment-id')).classList.remove('hidden');
      
      document.getElementById('answer_comment_edit_btn_' + event.target.getAttribute('data-comment-id')).addEventListener('click', function() {
        answer_comment_edit(event.target.getAttribute('data-answer-id'), event.target.getAttribute('data-comment-id'));
      });

      document.getElementById('answer_comment_cancel_btn_' + event.target.getAttribute('data-comment-id')).addEventListener('click', function() {
        retract_answer_comment_edit(event.target.getAttribute('data-comment-id'));
      });
    }
    else if(event.target.classList.contains('answer-comment-delete-btn')) {
      answer_comment_delete(event.target.getAttribute('data-answer-id'), event.target.getAttribute('data-comment-id'));
    }
  });
}

function answer_comment_post(answer_id) {
  const answer_comment_body = document.getElementById('answer_comment_body_input_' + answer_id).value;
  fetch('/answers/' + answer_id + '/comment', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({
      answer_comment_body : answer_comment_body
    }),
  })
  .then(response => {
    return response.text();
  })
  .then(data => {
    document.getElementById('comment-section-' + answer_id).innerHTML = data;
    document.getElementById('answer_comment_body_input' + answer_id).value = '';
  })
  .catch(error => console.error('Error posting comment:', error)); 
}

function answer_comment_edit(answer_id, comment_id) {
  const answer_comment_body = document.getElementById('answer_comment_body_edit_input_' + comment_id).value;
  fetch('/answers/' + answer_id + '/comment/' + comment_id + '/edit', {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({
      answer_comment_body : answer_comment_body
    }),
  })
  .then(response => {
    return response.text();
  })
  .then(data => {
    document.getElementById('answer_comment_card_' + comment_id).innerHTML = data;
  })
  .catch(error => console.error('Error editing comment:', error));
}

function retract_answer_comment_edit(comment_id) {
  document.getElementById('answer_comment_body_edit_input_' + comment_id).value = document.getElementById('answer_comment_body_' + comment_id).textContent;
  document.getElementById('answer_comment_card_' +  comment_id).classList.remove('hidden');
  document.getElementById('answer_comment_edit_form_' +  comment_id).classList.add('hidden');
}

function answer_comment_delete(answer_id, comment_id) {
  fetch('/answers/' + answer_id + '/comment/' + comment_id + '/delete', {
    method: 'DELETE',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({
    }),
  })
  .then(response => {
    if(response.ok) {
      document.getElementById('answer_comment_card_' + comment_id).remove();
      document.getElementById('answer_comment_edit_form_' + comment_id).remove();
    }
    return response.text();
  })
  .catch(error => console.error('Error deleting comment:', error));
}

// END SECTION

// multiple pictures in question
document.addEventListener("DOMContentLoaded", function() {
  const imageInput1 = document.getElementById('image1');
  const imageInput2 = document.getElementById('image2');
  const imageInput3 = document.getElementById('image3');
  const imagePreviewContainer1 = document.getElementById('image-1');
  const imagePreviewContainer2 = document.getElementById('image-2');
  const imagePreviewContainer3 = document.getElementById('image-3');
  const MAX_IMAGES = 3;

  function previewImage1(input) {

      imagePreviewContainer1.innerHTML = '';

      Array.from(input.files).slice(0, MAX_IMAGES).forEach(function(file) {
          const reader = new FileReader();

          reader.onload = function(e) {
              const preview = document.createElement('img');
              preview.src = e.target.result;
              preview.style.width = '100px';
              preview.style.marginRight = '5px';
              imagePreviewContainer1.appendChild(preview);
          };

          reader.readAsDataURL(file);
      });
  }

  function previewImage2(input) {

    imagePreviewContainer2.innerHTML = '';

    Array.from(input.files).slice(0, MAX_IMAGES).forEach(function(file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            const preview = document.createElement('img');
            preview.src = e.target.result;
            preview.style.width = '100px';
            preview.style.marginRight = '5px';
            imagePreviewContainer2.appendChild(preview);
        };

        reader.readAsDataURL(file);
    });
}

function previewImage3(input) {

  imagePreviewContainer3.innerHTML = '';

  Array.from(input.files).slice(0, MAX_IMAGES).forEach(function(file) {
      const reader = new FileReader();

      reader.onload = function(e) {
          const preview = document.createElement('img');
          preview.src = e.target.result;
          preview.style.width = '100px';
          preview.style.marginRight = '5px';
          imagePreviewContainer3.appendChild(preview);
      };

      reader.readAsDataURL(file);
  });
}


  // Initial setup for existing images and trigger preview
  imageInput1.addEventListener('change', function() {
      previewImage1(imageInput1);
  });
  imageInput2.addEventListener('change', function() {
    previewImage2(imageInput2);
  });
  imageInput3.addEventListener('change', function() {
    previewImage3(imageInput3);
  });
});

/*

function updateProfilePicture() {
  let newProfilePicture = document.getElementById('newProfilePicture').files[0];

  let data = {'newProfilePicture': newProfilePicture};

  fetch('/profile/update-picture', {
    method: 'POST',
    body: data
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(data => {
    // Update the profile picture on success
    document.getElementById('profilePicture').src = data.profile_picture;
    document.getElementById('message').innerHTML = '<div class="alert alert-success">Profile picture updated successfully</div>';
  })
  .catch(error => {
    console.error('Error:', error);
    document.getElementById('message').innerHTML = '<div class="alert alert-danger">Error updating profile picture</div>';
  });
}*/



// START SECTION: FUNCTIONS RELATED TO FOLLOWING A QUESTION

const question_follow_btn = document.getElementById('question_follow_btn');
if(question_follow_btn) {
  question_follow_btn.addEventListener('click', function() {
    console.log('stupid button pressed');
    toggle_question_follow(question_follow_btn.getAttribute('data-question-id'));
  })
}

function toggle_question_follow(question_id) {
  fetch('/questions/' + question_id + '/toggle_follow' , {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
  })
  .then(response => {
    return response.json();
  })
  .then(data => {
    document.getElementById('question_follow_btn').style.color = data.color;
    console.log('changed color');
  })
  .catch(error => console.error('Error following question:', error));
}

// END SECTION 