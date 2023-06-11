<form method="post" action="../backend/edit_group.php">
  <div class="modal fade" id="editModal<?php echo $groupId; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $groupId; ?>" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel<?php echo $groupId; ?>">Edit Group Name</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
              <input type="hidden" name="groupid" value="<?php echo $groupId; ?>" />
              <label for="editGroupName<?php echo $groupId; ?>" class="form-label">New Group Name:</label>
              <input type="text" class="form-control" id="editGroupName<?php echo $groupId; ?>" name="editGroupName" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
</form>
