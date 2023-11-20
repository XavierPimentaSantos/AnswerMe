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

    document.getElementById('update-profile-btn').addEventListener('click', function () {
      updateProfile();
    });

  }


  function toggleProfileSections(editMode) {
    document.getElementById('profile-view').style.display = editMode ? 'none' : 'block';
    document.getElementById('profile-edit').style.display = editMode ? 'block' : 'none';
    document.getElementById('edit-profile-btn').style.display = editMode ? 'none' : 'inline-block';
    document.getElementById('profile-header').style.display = editMode ? 'none' : 'block';
  }
  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }

  function updateProfile() {
    // Gather form data
    let formData = new FormData(document.getElementById('edit-profile-form'));

    // Send AJAX request to update profile
    console.log('AJAX request to update profile');
    sendAjaxRequest('post', '/update-profile', formData, function () {
        // Handle the response, update success or error messages
        if (this.status === 200) {
            document.getElementById('success-message').classList.remove('hidden');
            document.getElementById('error-message').classList.add('hidden');

            // Update profile view with new data
            let name = formData.get('name');
            let email = formData.get('email');

            document.getElementById('profile-view').innerHTML = `
                <p><strong>Name:</strong> ${name}</p>
                <p><strong>Email:</strong> ${email}</p>
            `;

            toggleProfileSections(false);  // Hide the edit section
        } else {
            document.getElementById('success-message').classList.add('hidden');
            document.getElementById('error-message').classList.remove('hidden');
        }
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
  
  addEventListeners();
  