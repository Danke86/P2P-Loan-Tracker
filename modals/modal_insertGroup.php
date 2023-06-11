
<form method="post" action="../backend/create_group.php">
  <div class="modal-header">
    <h5 class="modal-title" id="groupModalLabel">Create Group</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>

  <div class="modal-body">
      <div class="mb-3">
        <label for="groupName" class="form-label">Group Name:</label>
        <input type="text" class="form-control" id="groupName" name="groupName" required>
      </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-success">Create</button>
  </div>
</form>

