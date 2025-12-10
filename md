Create an admin dashboard page for managing certificate requests. 
Requirements:

1. Display a table of certificate requests with the following columns:
   - ID
   - Resident Name
   - Certificate Type
   - Request Date
   - Status (Pending, Accepted, Rejected)
   - Action

2. In the Action column:
   - Show a button or link "Take Action" for requests with status "Pending".
   - When clicked, open a modal popup or inline form with two buttons:
     a) Accept → Updates the request status to "Accepted"
     b) Reject → Updates the request status to "Rejected"
   - Include a cancel option to close the modal without action.

3. Table should highlight current status:
   - Pending → default
   - Accepted → green text or badge
   - Rejected → red text or badge

4. Backend requirements (PHP & MySQL):
   - Fetch certificate requests from the database table `certificate_requests`.
   - Update request status when admin clicks Accept/Reject.
   - Reload the page or update the table dynamically after action.

5. UI requirements:
   - Clean, readable table.
   - Modal popup should be centered with clear Accept/Reject buttons.
   - Buttons should be color-coded: green for Accept, red for Reject.

Output the full HTML, PHP, and JavaScript code to implement this functionality.