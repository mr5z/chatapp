<div class="row padding-14 text-right">
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-create-room">
        Create room
        <span class="glyphicon glyphicon-plus"></span>
    </button>
</div>
<div id="modal-create-room" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
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
                            <input type="radio" name="accessibility" id="access-public" />
                            <label class="label label-default" for="access-public">Public</label>
                        </div>
                        <div>
                            <input type="radio" name="accessibility" id="access-private" />
                            <label class="label label-default" for="access-private">Private</label>
                        </div>
                            
                    </div>
                </div>
                <hr />
                <div class="top-7">
                    <div class="button-group dropdown">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-plus"></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" class="small" data-value="option1" tabIndex="-1"><input type="checkbox"/>&nbsp;Option 1</a></li>
                            <li><a href="#" class="small" data-value="option2" tabIndex="-1"><input type="checkbox"/>&nbsp;Option 2</a></li>
                            <li><a href="#" class="small" data-value="option3" tabIndex="-1"><input type="checkbox"/>&nbsp;Option 3</a></li>
                            <li><a href="#" class="small" data-value="option4" tabIndex="-1"><input type="checkbox"/>&nbsp;Option 4</a></li>
                            <li><a href="#" class="small" data-value="option5" tabIndex="-1"><input type="checkbox"/>&nbsp;Option 5</a></li>
                            <li><a href="#" class="small" data-value="option6" tabIndex="-1"><input type="checkbox"/>&nbsp;Option 6</a></li>
                        </ul>
                    </div>
                    <div class="top-7" id="room-members-list">
                        <h3>
                            <span>hehe<a href="#">&times;</a></span>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" data-dismiss="modal">Create</button>
            </div>
        </div>
    </div>
</div>