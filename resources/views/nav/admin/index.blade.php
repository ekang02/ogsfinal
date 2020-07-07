<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('admin/principal','admin/teacher','admin/student') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsUsers" aria-expanded="true" aria-controls="collapsUsers">
	  <i class="fas fa-fw fa-users"></i>
	  <span>User Management</span>
	</a>
	<div id="collapsUsers" class="collapse {{ Request::is('admin/principal','admin/teacher','admin/student') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <a class="collapse-item {{ Request::is('admin/principal') ? 'active' : '' }}" href="{{url('principal/staff')}}"> Principal Management</a>
	    <a class="collapse-item {{ Request::is('admin/teacher') ? 'active' : '' }}" href="{{url('admin/teacher')}}">Teacher Management</a>
	    <a class="collapse-item {{ Request::is('admin/student') ? 'active' : '' }}" href="{{url('admin/student')}}">Student Management</a>
	  </div>
	</div>
</li>
<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('admin/add-year', 'admin/add-section') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseYearSection" aria-expanded="true" aria-controls="collapseYearSection">
	  <i class="fas fa-fw fa-wrench"></i>
	  <span>Grade & Section Mngt</span>
	</a>
	<div id="collapseYearSection" class="collapse {{ Request::is('admin/add-section','admin/add-year', 'admin/advisory-list') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <a class="collapse-item {{ Request::is('admin/add-year') ? 'active' : '' }}" href="{{url('admin/add-year')}}">Add Grade</a>
	    <a class="collapse-item {{ Request::is('admin/add-section') ? 'active' : '' }}" href="{{url('admin/add-section')}}">Add Section</a>
	  </div>
	</div>
</li>
<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('admin/subject','admin/subject-teacher','admin/subject-section') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSubject" aria-expanded="true" aria-controls="collapseSubject">
	  <i class="fas fa-fw fa-cog"></i>
	  <span>Subject Management</span>
	</a>
	<div id="collapseSubject" class="collapse {{ Request::is('admin/subject','admin/subject-teacher','admin/subject-section') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <a class="collapse-item {{ Request::is('admin/subject') ? 'active' : '' }}" href="{{url('admin/subject')}}">Add Subject</a>
	    <a class="collapse-item {{ Request::is('admin/subject-section') ? 'active' : '' }}" href="{{url('admin/subject-section')}}">Add Subject Section</a>
	  </div>
	</div>
</li>
<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('admin/academic-year', 'admin/assign-principal', 'admin/images') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSettings" aria-expanded="true" aria-controls="collapseSubject">
	  <i class="fas fa-fw fa-gears"></i>
	  <span>System Management</span>
	</a>
	<div id="collapseSettings" class="collapse {{ Request::is('admin/academic-year', 'admin/assign-principal', 'admin/images') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <a class="collapse-item {{ Request::is('admin/academic-year') ? 'active' : '' }}" href="{{url('admin/academic-year')}}">Academic Year</a>
	    <a class="collapse-item {{ Request::is('admin/assign-principal') ? 'active' : '' }}" href="{{url('admin/assign-principal')}}"> Assign Principal</a>
	    <a class="collapse-item {{ Request::is('admin/images') ? 'active' : '' }}" href="{{url('admin/images')}}"> Assign Images</a>

	  </div>
	</div>
</li>
<!-- Divider -->
<!--
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('admin/profile') ? 'active' : '' }}">
	<a class="nav-link" href="{{url('admin/profile')}}">
	  <i class="fas fa-fw fa-user-cog"></i>
	  <span>My Profile</span>
	</a>
</li>
-->