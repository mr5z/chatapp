<div class="row padding-14 text-right">
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-create-room">
        Create room
        <span class="glyphicon glyphicon-plus"></span>
    </button>
</div>
<div id="modal-create-room" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Create room</h3>
            </div>
            <div class="modal-body form-group">
                <div class="top-7">
                    <div class="top-7">
                        <label class="label label-default" for="room-name">Room name: </label>
                        <input type="text" class="form-control" placeholder="Enter room name" id="room-name" name="room-name" />
                    </div>
                    <div class="top-7">
                        <label class="label label-default" for="room-password">Room password: </label>
                        <input type="text" class="form-control" placeholder="Room password" id="room-password" name="room-password" />
                    </div>
                    <div class="top-7">
                        <h4>Accessibility</h4>
                        <div>
                            <input type="radio" name="room-accessibility" id="access-public" value="public" />
                            <label class="label label-default" for="access-public">Public</label>
                        </div>
                        <div>
                            <input type="radio" name="room-accessibility" id="access-private" value="private" />
                            <label class="label label-default" for="access-private">Private</label>
                        </div>    
                    </div>
                    <div class="top-7">
                        <h4>Description</h4>
                        <textarea class="form-control" name="room-description" rows="3" style="resize:none" placeholder="Enter room description here(optional)"></textarea>
                    </div>
                </div>
                <hr />
                <div class="top-7">
                    <div class="button-group dropdown">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-plus"></span>
                            <span>Add members</span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" id="add-members">
<?php
                            $contactList = getContactListByUserId($userId);
                            if ($contactList) {
                                if ($contactList->num_rows > 0) {
                                    while($row = $contactList->fetch_object()) {
?>
                                        <li>
                                            <a href="#" class="small" data-contact-id="<?php echo $row->id; ?>" tabIndex="-1"><input type="checkbox"/>
                                                &nbsp;<?php echo "$row->firstName $row->lastName"; ?>
                                            </a>
                                        </li>
<?php
                                    }
                                }
                                else {
?>
                                    <li><a href="#" class="small">No available contacts</a></li>
<?php
                                }
                            }
                            else {
                                
                            }
?>
                        </ul>
                    </div>
                    <div class="top-7" id="room-members-list">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" data-dismiss="modal" id="create-room">Create</button>
            </div>
        </div>
    </div>
</div>