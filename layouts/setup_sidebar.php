<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link" class="text-center">
      <img src="resources/panabologo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">HRIS CONFIG</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="AdminLTE_new/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo($_SESSION["hris"]["fullname"]); ?></a>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
  <li class="nav-header">ADMIN</li>
  <li class="nav-item">
      <a href="users" class="nav-link <?php echo ($lastWord == 'users') ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-users"></i>
        <p>
          Users
          <span class="right badge badge-danger"></span>
        </p>
      </a>
  </li>

  <li class="nav-item">
      <a href="holidays" class="nav-link <?php echo ($lastWord == 'holidays') ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-calendar"></i>
        <p>
          Holidays
          <span class="right badge badge-danger"></span>
        </p>
      </a>
  </li>

  <li class="nav-item">
      <a href="siteoptions" class="nav-link <?php echo ($lastWord == 'siteoptions') ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-globe"></i>
        <p>
          Site Options
          <span class="right badge badge-danger"></span>
        </p>
      </a>
  </li>
<!-- 

  <li class="nav-item">
      <a href="mira_database" class="nav-link <?php echo ($lastWord == 'mira_database') ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-globe"></i>
        <p>
          MIRA DATABASE
          <span class="right badge badge-danger"></span>
        </p>
      </a>
  </li> -->
        </ul>
      </nav>
    </div>
  </aside>
  <div class="modal fade" id="changePassword">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Change Password</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <form class="generic_form_trigger" data-url="profile">
                <input type="hidden" name="action" value="changePassword">
                <input type="hidden" name="user_id" value="<?php echo($_SESSION["mariphil"]["userid"]) ?>">
                <div class="form-group">
                  <label>Current Password</label>
                  <input name="current_password" required type="password" class="form-control"  placeholder="---">
                </div>

                <div class="form-group">
                  <label>New Password</label>
                  <input name="new_password" required type="password" class="form-control"  placeholder="---">
                </div>

                <div class="form-group">
                  <label>Repeat New Password</label>
                  <input name="repeat_password" required type="password" class="form-control"  placeholder="---">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
          </div>
        </div>
      </div>