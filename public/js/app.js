const edit_profile_btn = document.getElementById('edit-profile-btn');
if(edit_profile_btn) {
  edit_profile_btn.addEventListener('click', function() {
    toggleProfileSections(true);
  });
}

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
    console.log('that\'s crazy...');
    updateTags();
  });
}

const checkboxes = document.querySelectorAll('.tag-checkbox');

checkboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        console.log('checkbox ticked');
        updateTags(); // when any checkbox is ticked/unticked, we want to update the tags that are shown
    });
}); 

function updateTags() {
  const checkedTags = document.querySelectorAll('.tag-checkbox:checked');
  const selectedTags = Array.from(checkedTags).map(checkbox => checkbox.value);

  // console.log('mensagem iai');

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

// END SECTION

// START SECTION: FUNCTIONS RELATED TO VOTING

const score_container = document.getElementById('question_score');

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

const question_answers = document.getElementById('question_answers');
if(question_answers) {
  question_answers.addEventListener('click', function(event) {
    if(event.target.classList.contains('increase-answer-score-btn')) {
      console.log('inc btn ans pressed');
      increaseScoreAns(event.target.getAttribute('data-id'));
    }
    else if(event.target.classList.contains('decrease-answer-score-btn')) {
      console.log('dec btn ans pressed');
      decreaseScoreAns(event.target.getAttribute('data-id'));
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
    if(response.ok) {
      console.log('succesfully updated score');
      return response.text();
    }
    else {
      console.log('could not update the score');
    }
  })
  .then(data => {
    console.log(answer_id);
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
    if(response.ok) {
      console.log('succesfully updated score');
      return response.text();
    }
    else {
      console.log('could not update the score');
    }
  })
  .then(data => {
    console.log(answer_id);
    document.getElementById('answer_score_' + answer_id).innerHTML = data;
  })
  .catch(error => console.error('Error updating score:', error));
}

// END SECTION

// START SECTION: FUNCTIONS RELATED TO MARKING AN ANSWER AS CORRECT

const validate_answer_btn_array = Array.from(document.getElementsByClassName('validate_answer_btn'));

validate_answer_btn_array.forEach(function(element) {
  element.addEventListener('click', function() {
    let ans_id = element.getAttribute('data-id');
    console.log('btn pressed, id =' + ans_id);
    validateAnswer(ans_id);
  });
});

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
    console.log('question-comment-post-btn pressed');
    question_comment_post();
  });
}

function question_comment_post() {
  const question_id = question_comment_post_btn.getAttribute('data-question-id');
  const question_comment_body = document.getElementById('question_comment_body').value;
  console.log(question_comment_body);
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
        retract_edit(comment_id);
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
  console.log('/questions/' + question_id + '/comment/' + comment_id + '/edit');
  console.log(question_comment_body);
  fetch('/questions/' + question_id + '/comment/' + comment_id + '/edit', {
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
    document.getElementById('question_comment_body_' + comment_id).textContent = data;
    document.getElementById('question_comment_body_edit_input_' + comment_id).value = data;
    document.getElementById('question_comment_card_' +  comment_id).classList.remove('hidden');
    document.getElementById('question_comment_edit_form_' +  comment_id).classList.add('hidden');
  })
  .catch(error => console.error('Error editing comment:', error));
}

function retract_edit(comment_id) {
  document.getElementById('question_comment_body_edit_input_' + comment_id).value = document.getElementById('question_comment_body_' + comment_id).textContent;
  document.getElementById('question_comment_card_' +  comment_id).classList.remove('hidden');
  document.getElementById('question_comment_edit_form_' +  comment_id).classList.add('hidden');
}

function question_comment_delete(question_id, comment_id) {
  fetch('/questions/' + question_id + '/comment/' + comment_id + '/delete', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({
    }),
  })
  .then(response => {
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
    document.getElementById('no_answers').dataList.add('hidden');
    document.getElementById('has_answers').dataList.remove('hidden');
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
  });
}

function answer_comment_post(answer_id) {
  const answer_comment_body = document.getElementById('answer_comment_body_' + answer_id).value;
  console.log(answer_comment_body);
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
    document.getElementById('answer_comment_body_' + answer_id).value = '';
  })
  .catch(error => console.error('Error posting comment:', error)); 
}

// END SECTION