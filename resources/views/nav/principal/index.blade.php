<li class="nav-item {{ Request::is('principal') ? 'active' : '' }}">
	<a class="nav-link" href="{{url('/principal')}}">
	  <i class="fas fa-fw fa-home"></i>
	  <span>Dashboard</span>
	</a>
</li>
<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('principal/staff','principal/teacher','principal/student') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsUsers" aria-expanded="true" aria-controls="collapsUsers">
	  <i class="fas fa-fw fa-users"></i>
	  <span>User Management</span>
	</a>
	<div id="collapsUsers" class="collapse {{ Request::is('principal/staff','principal/teacher','principal/student') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <!-- <a class="collapse-item {{ Request::is('principal/staff') ? 'active' : '' }}" href="{{url('principal/staff')}}"> Staff Management</a> -->
	    <a class="collapse-item {{ Request::is('principal/teacher') ? 'active' : '' }}" href="{{url('principal/teacher')}}">Teacher Management</a>
	    <a class="collapse-item {{ Request::is('principal/student') ? 'active' : '' }}" href="{{url('principal/student')}}">Student Management</a>
	  </div>
	</div>
</li>
<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('principal/students','principal/subjects','principal/scoresheets') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsSearch" aria-expanded="true" aria-controls="collapsSearch">
	  <i class="fas fa-fw fa-search"></i>
	  <span>Search</span>
	</a>
	<div id="collapsSearch" class="collapse {{ Request::is('principal/scoresheets','principal/subjects','principal/students') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <a class="collapse-item {{ Request::is('principal/students') ? 'active' : '' }}" href="{{url('principal/students')}}">Students</a>
	    <a class="collapse-item {{ Request::is('principal/subjects') ? 'active' : '' }}" href="{{url('principal/subjects')}}">Subjects</a>
	    <a class="collapse-item {{ Request::is('principal/scoresheets') ? 'active' : '' }}" href="{{url('principal/scoresheets')}}">Score Sheets</a>
	  </div>
	</div>
</li>
<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('principal/add-year', 'principal/add-section') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseYearSection" aria-expanded="true" aria-controls="collapseYearSection">
	  <i class="fas fa-fw fa-wrench"></i>
	  <span>Grade & Section</span>
	</a>
	<div id="collapseYearSection" class="collapse {{ Request::is('principal/add-section','principal/add-year', 'principal/advisory-list') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <a class="collapse-item {{ Request::is('principal/add-year') ? 'active' : '' }}" href="{{url('principal/add-year')}}">Add Grade</a>
	    <a class="collapse-item {{ Request::is('principal/add-section') ? 'active' : '' }}" href="{{url('principal/add-section')}}">Add Section</a>
	    <a class="collapse-item {{ Request::is('principal/advisory-list') ? 'active' : '' }}" href="{{url('principal/advisory-list')}}">Class Advisory List</a>
	  </div>
	</div>
</li>
<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('principal/subject','principal/subject-teacher','principal/subject-section') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSubject" aria-expanded="true" aria-controls="collapseSubject">
	  <i class="fas fa-fw fa-cogs"></i>
	  <span>Subject</span>
	</a>
	<div id="collapseSubject" class="collapse {{ Request::is('principal/subject','principal/subject-teacher','principal/subject-section') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <a class="collapse-item {{ Request::is('principal/subject') ? 'active' : '' }}" href="{{url('principal/subject')}}">Subject List</a>
	    <a class="collapse-item {{ Request::is('principal/subject-section') ? 'active' : '' }}" href="{{url('principal/subject-section')}}">Subject Section</a>
	    <a class="collapse-item {{ Request::is('principal/subject-teacher') ? 'active' : '' }}" href="{{url('principal/subject-teacher')}}">Subject Teacher</a>
	  </div>
	</div>
</li>
<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('principal/grades-assessment-level', 'principal/grades-criteria', 'principal/grades','principal/grades-per-quarter','principal/grades-period', 'principalcademic-year') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGrades" aria-expanded="true" aria-controls="collapseGrades">
	  <i class="fas fa-fw fa-cog"></i>
	  <span>Grades</span>
	</a>
	<div id="collapseGrades" class="collapse {{ Request::is('principal/grades-assessment-level', 'principal/grades','principal/grades-quarter','principal/grades-period', 'principal/grades-criteria', 'principalcademic-year') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <a class="collapse-item {{ Request::is('principal/academic-year') ? 'active' : '' }}" href="{{url('principal/academic-year')}}">Academic Year</a>
	    <a class="collapse-item {{ Request::is('principal/grades-assessment-level') ? 'active' : '' }}" href="{{url('principal/grades-assessment-level')}}">Assessment Level</a>
	    <a class="collapse-item {{ Request::is('principal/grades-criteria') ? 'active' : '' }}" href="{{url('principal/grades-criteria')}}">Criteria</a>
	    <a class="collapse-item {{ Request::is('principal/grades') ? 'active' : '' }}" href="{{url('principal/grades')}}">Add Grades</a>
	    <a class="collapse-item {{ Request::is('principal/grades-period') ? 'active' : '' }}" href="{{url('principal/grades-period')}}">Grades Per Period</a>
	    <a class="collapse-item {{ Request::is('principal/grades-quarter') ? 'active' : '' }}" href="{{url('principal/grades-quarter')}}">Grades Per Quarter</a>
	  </div>
	</div>
</li>

<!-- Divider -->
<!--
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('principal/profile') ? 'active' : '' }}">
	<a class="nav-link" href="{{url('principal/profile')}}">
	  <i class="fas fa-fw fa-user-cog"></i>
	  <span>My Profile</span>
	</a>
</li>
-->
<!-- Divider -->
<!-- <hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('principal/form-137','principal/student-card','principal/consolidated-grade') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReport" aria-expanded="true" aria-controls="collapseReport">
	  <i class="fa fa-chart-bar fa-fw"></i>
	  <span>Reports</span>
	</a>
	<div id="collapseReport" class="collapse {{ Request::is('principal/form-137','principal/student-card','principal/consolidated-grade') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <a class="collapse-item {{ Request::is('principal/form-137') ? 'active' : '' }}" href="{{url('/principal/form-137')}}">Form 137</a>
	    <a class="collapse-item {{ Request::is('principal/student-card') ? 'active' : '' }}" href="{{url('/principal/student-card')}}">Student Card</a>
	  </div>
	</div>
</li> -->