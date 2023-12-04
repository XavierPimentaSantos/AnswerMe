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






  const profileForm = document.getElementById("edit-profile-form");
  const profileView = document.getElementById("profile-view");
  const editButton = document.getElementById("edit-profile-btn");
  const submitEdit = document.getElementById("update-profile-btn");
  const fullnameInput = document.getElementById("name-input");
  const emailInput = document.getElementById("email-input");
  const fullname = document.getElementById("name");
  const email = document.getElementById("email");

  const titleInput = document.getElementById("title-input");
  const contentInput = document.getElementById("content-input");

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
  