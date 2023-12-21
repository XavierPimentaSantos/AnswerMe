Pusher.logToConsole = true;

var pusher = new Pusher('4fc151dc2ba70eed2c92', {
  cluster: 'eu'
});

let notifications = [];

let userId = document.getElementById('notifications_btn').dataset.userId;
console.log(userId);



const authorChannel = pusher.subscribe('user-' + userId);

authorChannel.bind('user-register', function(data) {
  // Add the notification to the array
  notifications.push({
    title: data.username + ' has joined AnswerMe :)',
    text: '',
    icon: 'success'
  });

  // Update the UI to display notifications
  updateNotificationsUI();
});

authorChannel.bind('delete-question', function(data) {
  notifications.push({
    title:'Your question has been removed by a moderator',
    text: data.question_title,
    icon: 'error'
  });
  // Update the UI to display notifications
  updateNotificationsUI();
});

authorChannel.bind('edit-question', function(data) {
  notifications.push({
    title:'Your question has been edited by a moderator',
    text: data.question_title,
    icon: 'error'
  });

  // Update the UI to display notifications
  updateNotificationsUI();
});

authorChannel.bind('upvote-question', function(data) {
    // Add the upvote notification to the array
    notifications.push({
        title: 'Your question has been upvoted!',
        text: data.question_title,
        icon: 'success'
    });

    // Update the UI to display notifications
    updateNotificationsUI();
});

authorChannel.bind('downvote-question', function(data) {
  // Add the downvote notification to the array
  notifications.push({
    title:'Your question has been downvoted!',
    text: data.question_title,
    icon: 'error'
  });

  // Update the UI to display notifications
  updateNotificationsUI();
});




authorChannel.bind('upvote-answer', function(data) {
  // Add the upvote notification to the array
  notifications.push({
    title: 'Your answer has been upvoted!',
    text: data.answer_title,
    icon: 'success'
  });

  // Update the UI to display notifications
  updateNotificationsUI();
});

authorChannel.bind('downvote-answer', function(data) {
  // Add the downvote notification to the array
  notifications.push({
    title:'Your answer has been downvoted!',
    text: data.answer_title,
    icon: 'error'
  });

  // Update the UI to display notifications
  updateNotificationsUI();
});

authorChannel.bind('validate-answer', function(data) {
  // Add the validate notification to the array
  notifications.push({
    title:'Your answer has been validated!',
    text: data.answer_title,
    icon: 'success'
  });

  // Update the UI to display notifications
  updateNotificationsUI();
});

authorChannel.bind('answer-question', function(data) {
  notifications.push({
    title:'Your question has been answered by: ' + data.answer_author,
    text: data.question_title,
    icon: 'success'
  });

  // Update the UI to display notifications
  updateNotificationsUI();
});

authorChannel.bind('delete-question', function(data) {
  notifications.push({
    title:'Your answer has been removed by a moderator',
    text: data.answer_title,
    icon: 'error'
  });
  // Update the UI to display notifications
  updateNotificationsUI();
});

authorChannel.bind('edit-answer', function(data) {
  notifications.push({
    title:'Your answer has been edited by a moderator',
    text: data.answer_title,
    icon: 'error'
  });

  // Update the UI to display notifications
  updateNotificationsUI();
});



function updateNotificationsUI() {
  let notificationsDropdown = document.getElementById('notifications-dropdown');
  notificationsDropdown.innerHTML = '';
  notifications.forEach(function(notification, index) {
    let notificationElement = document.createElement('div');
    notificationElement.innerHTML = `
      <div class="notification">
        <strong>${notification.title}</strong>
        <p>${notification.text}</p>
        <button class="close-btn" onclick="closeNotification(${index})">OK</button>  
      </div>
    `;
    notificationsDropdown.appendChild(notificationElement);
  });
  }

  function closeNotification(index) {
    notifications.splice(index, 1);
    updateNotificationsUI();
  }


  document.getElementById('notifications_btn').addEventListener('click', function() {
    let notificationsDropdown = document.getElementById('notifications-dropdown');
    notificationsDropdown.classList.toggle('hidden');
  });



  document.addEventListener('click', function(event) {
    if (event.target.classList.contains('button')) {
        const answerId = event.target.dataset.id;
        toggleAnswerSections(answerId, true);
    }
});

const edit_answer_btn = document.getElementById('edit-answer-btn');
if(edit_answer_btn) {
  edit_answer_btn.addEventListener('click', function () {
    toggleAnswerSections(true);
  });
}


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
      document.getElementById('validate-answer-btn-' + answer_id).style.display = 'none';
      document.getElementById('valid_answer_' + answer_id).style.display = 'block';
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
  const profilePictureInput = document.getElementById('profile-picture-input');
  const imagePreviewContainer1 = document.getElementById('image-1');
  const imagePreviewContainer2 = document.getElementById('image-2');
  const imagePreviewContainer3 = document.getElementById('image-3');
  const MAX_IMAGES = 3;

  function previewProfilePicture(input) {
    const profilePicture = document.getElementById('profile-picture').getElementsByTagName('img')[0];

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            profilePicture.src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);
    }
  }

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

  if(profilePictureInput){
    profilePictureInput.addEventListener('change', function() {
      previewProfilePicture(profilePictureInput);
    });
  }
  

  if(imageInput1){
    imageInput1.addEventListener('change', function() {
        previewImage1(imageInput1);
    });
  }

  if(imageInput2){
    imageInput2.addEventListener('change', function() {
      previewImage2(imageInput2);
    });
  }

  if(imageInput3){
    imageInput3.addEventListener('change', function() {
      previewImage3(imageInput3);
    });
  }
});


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
    document.getElementById('follow_btn_tooltip').innerText = data.tip;
    console.log('changed color');
  })
  .catch(error => console.error('Error following question:', error));
}

// END SECTION 

// FUNCTIONS RELATED TO EDITING THE PROFILE

const edit_profile_btn = document.getElementById('edit-profile-btn');
if(edit_profile_btn) {
  edit_profile_btn.addEventListener('click', function() {
    toggleProfileSections();
  });
}

const cancel_update_profile_btn = document.getElementById('cancel-update-profile-btn');
if(cancel_update_profile_btn) {
  cancel_update_profile_btn.addEventListener('click', function() {
    document.getElementById('profile-view').style.display = 'grid';
    document.getElementById('profile-edit').style.display = 'none';
    document.getElementById('top-posts').style.display = 'block';
  });
}

function toggleProfileSections() {
  document.getElementById('profile-view').style.display = 'none';
  document.getElementById('profile-edit').style.display = 'grid';
  document.getElementById('top-posts').style.display = 'none';
}

const update_profile_btn = document.getElementById('update-profile-btn');
if(update_profile_btn) {
  update_profile_btn.addEventListener('click', function() {
    console.log('pressed btn');
    updateProfile(update_profile_btn.getAttribute('data-user'));
  })
}

function updateProfile(username) {
  const new_name = document.getElementById('name_input').value;
  const new_username = document.getElementById('username_input').value;
  const new_email = document.getElementById('email_input').value;
  const new_bio = document.getElementById('bio_input').value;
  
  var profile_picture = document.getElementById('profile_picture_input').files[0];

   /*  // Use FileReader to read the file
    var reader = new FileReader();

    reader.onload = function(e) {
      // e.target.result contains the data URL representing the file
      var imageDataUrl = e.target.result;

      // You can use imageDataUrl as needed (e.g., display in an image tag)
      console.log('Data URL:', imageDataUrl);
    };

    // Read the file as a data URL
    reader.readAsDataURL(selectedFile); */

  let show_birthdate, show_name, show_email, show_nation;
  if(document.getElementById('name_opt').checked==true) {
    show_name = true;
  }
  else {
    show_name = false;
  }
  if(document.getElementById('email_opt').checked==true) {
    show_email = true;
  }
  else {
    show_email = false;
  }
  if(document.getElementById('birthdate_opt').checked==true) {
    show_birthdate = true;
  }
  else {
    show_birthdate = false;
  }
  if(document.getElementById('nationality_opt').checked==true) {
    show_nation = true;
  }
  else {
    show_nation = false;
  }

  fetch('/profile/' + username + '/edit' , {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({
      new_name : new_name,
      new_username : new_username,
      new_email : new_email,
      new_bio : new_bio,
      show_birthdate : show_birthdate,
      show_name : show_name,
      show_email : show_email,
      show_nation : show_nation,
      profile_picture : profile_picture
    }),
  })
  .then(response => {
    return response.text();
  })
  .then(data => {
    window.location.href = "/profile/" + data;
  })
  .catch(error => console.error('Error following question:', error));
}

// END SECTION