
    // Show spinner function
    function showSpinner() {
      document.getElementById('spinnerContainer').style.display = 'flex';
    }

    // Hide spinner function
    function hideSpinner() {
      document.getElementById('spinnerContainer').style.display = 'none';
    }

    // Simulate an asynchronous action (e.g., fetching data)
    function simulateAsyncAction() {
      showSpinner();
      setTimeout(function() {
        // Simulated asynchronous action
        hideSpinner();
      }, 2000); // Change the timeout as needed
    }

    // Example: Trigger the spinner for a simulated asynchronous action
    simulateAsyncAction();
