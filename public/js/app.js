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

    document.getElementById('update-question-btn').addEventListener('click', function () {
      updateQuestion();
    });

    document.getElementById('update-answer-btn').addEventListener('click', function () {
      updateAnswer();
    });


  }


  const profileForm = document.getElementById("edit-profile-form");
  const profileView = document.getElementById("profile-view");
  const editButton = document.getElementById("edit-profile-btn");
  const submitEdit = document.getElementById("update-profile-btn");
  const fullnameInput = document.getElementById("name-input");
  const emailInput = document.getElementById("email-input");
  const fullname = document.getElementById("name");
  const email = document.getElementById("email");

  function updateProfileData(newName, newEmail) {
    fullname.textContent = newName;
    email.textContent = newEmail;
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

  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
  
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
  }
  
  addEventListeners();
  