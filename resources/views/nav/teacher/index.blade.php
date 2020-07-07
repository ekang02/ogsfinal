<li class="nav-item {{ Request::is('teacher') ? 'active' : '' }}">
	<a class="nav-link" href="{{url('/teacher')}}">
	  <i class="fas fa-fw fa-home"></i>
	  <span>Dashboard</span>
	</a>
</li>

<!-- Divider -->
<hr class="sidebar-divider m-0">
<!-- <li class="nav-item {{ Request::is('teacher/score-sheet') ? 'active' : '' }}">
	<a class="nav-link" href="{{url('/teacher/score-sheet')}}">
	  <i class="fas fa-fw fa-star"></i>
	  <span>Score Sheet</span>
	</a>
</li> -->

<li class="nav-item {{ Request::is('teacher/score-sheet','teacher/score-sheet-list', 'teacher/score-sheet-list-print') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSheet" aria-expanded="true" aria-controls="collapseSheet">
	  <i class="fas fa-fw fa-star"></i>
	  <span>Score Sheet</span>
	</a>
	<div id="collapseSheet" class="collapse {{ Request::is('teacher/add-score-sheet','teacher/score-sheet-list', 'teacher/score-sheet-list-print') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <a class="collapse-item {{ Request::is('teacher/add-score-sheet') ? 'active' : '' }}" href="{{url('teacher/add-score-sheet')}}">Score Sheet Add</a>
	    <a class="collapse-item {{ Request::is('teacher/score-sheet-list') ? 'active' : '' }}" href="{{url('teacher/score-sheet-list')}}">Score Sheet List</a>
	    <a class="collapse-item {{ Request::is('teacher/score-sheet-list-print') ? 'active' : '' }}" href="{{url('teacher/score-sheet-list-print')}}">Score Sheet List Print</a>
	  </div>
	</div>
</li>

<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('teacher/subjects') ? 'active' : '' }}">
	<a class="nav-link" href="{{url('/teacher/subjects')}}">
	  <i class="fas fa-fw fa-book"></i>
	  <span>Subjects</span>
	</a>
</li>

<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('teacher/class-advisory') ? 'active' : '' }}">
	<a class="nav-link" href="{{url('/teacher/class-advisory')}}">
	  <i class="fas fa-fw fa-book"></i>
	  <span>Class Advisory</span>
	</a>
</li>
<!-- Divider -->
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('teacher/reports-computation-per-period','teacher/reports-computation-per-quarter','teacher/progress-report') ? 'active' : 'collapsed' }}">
	<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReport" aria-expanded="true" aria-controls="collapseReport">
	  <i class="fa fa-chart-bar fa-fw"></i>
	  <span>Reports</span>
	</a>
	<div id="collapseReport" class="collapse {{ Request::is('teacher/reports-computation-per-period','teacher/reports-computation-per-quarter','teacher/progress-report') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
	  <div class="bg-white py-2 collapse-inner rounded">
	    <a class="collapse-item {{ Request::is('teacher/reports-computation-per-period') ? 'active' : '' }}" href="{{url('/teacher/reports-computation-per-period')}}">Computation per Period</a>
	    <a class="collapse-item {{ Request::is('teacher/reports-computation-per-quarter') ? 'active' : '' }}" href="{{url('/teacher/reports-computation-per-quarter')}}">Computation per Quarter</a>
	    <a class="collapse-item {{ Request::is('teacher/progress-report') ? 'active' : '' }}" href="{{url('/teacher/progress-report')}}">Progress Report</a>
	  </div>
	</div>
</li>
<!-- Divider -->
<!--
<hr class="sidebar-divider m-0">
<li class="nav-item {{ Request::is('teacher/profile') ? 'active' : '' }}">
	<a class="nav-link" href="{{url('teacher/profile')}}">
	  <i class="fas fa-fw fa-user-cog"></i>
	  <span>My Profile</span>
	</a>
</li>
-->